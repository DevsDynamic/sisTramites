package pe.syvtechnology.signer;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.security.KeyStore;
import java.security.MessageDigest;
import java.security.PrivateKey;
import java.security.Security;
import java.security.cert.Certificate;
import java.security.cert.X509Certificate;
import java.time.ZonedDateTime;
import java.time.ZoneId;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Enumeration;
import java.util.GregorianCalendar;
import java.util.List;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.apache.pdfbox.Loader;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.pdmodel.PDPage;
import org.apache.pdfbox.pdmodel.PDPageContentStream;
import org.apache.pdfbox.pdmodel.PDPageContentStream.AppendMode;
import org.apache.pdfbox.pdmodel.common.PDRectangle;
import org.apache.pdfbox.pdmodel.font.PDType1Font;
import org.apache.pdfbox.pdmodel.font.Standard14Fonts;
import org.apache.pdfbox.pdmodel.interactive.digitalsignature.ExternalSigningSupport;
import org.apache.pdfbox.pdmodel.interactive.digitalsignature.PDSignature;
import org.apache.pdfbox.pdmodel.interactive.digitalsignature.SignatureOptions;
import org.bouncycastle.asn1.cms.CMSObjectIdentifiers;
import org.bouncycastle.asn1.cms.Attribute;
import org.bouncycastle.asn1.cms.AttributeTable;
import org.bouncycastle.asn1.ess.ESSCertIDv2;
import org.bouncycastle.asn1.ess.SigningCertificateV2;
import org.bouncycastle.asn1.nist.NISTObjectIdentifiers;
import org.bouncycastle.asn1.x509.AlgorithmIdentifier;
import org.bouncycastle.asn1.ASN1EncodableVector;
import org.bouncycastle.asn1.DERSet;
import org.bouncycastle.asn1.pkcs.PKCSObjectIdentifiers;
import org.bouncycastle.cert.jcajce.JcaCertStore;
import org.bouncycastle.cms.CMSTypedData;
import org.bouncycastle.cms.CMSSignedData;
import org.bouncycastle.cms.CMSSignedDataGenerator;
import org.bouncycastle.cms.DefaultSignedAttributeTableGenerator;
import org.bouncycastle.cms.SignerInfoGenerator;
import org.bouncycastle.cms.jcajce.JcaSignerInfoGeneratorBuilder;
import org.bouncycastle.jce.provider.BouncyCastleProvider;
import org.bouncycastle.operator.ContentSigner;
import org.bouncycastle.operator.jcajce.JcaContentSignerBuilder;
import org.bouncycastle.operator.jcajce.JcaDigestCalculatorProviderBuilder;
import org.springframework.stereotype.Service;

@Service
public class PadesSigner {
    private static final String PROVIDER = BouncyCastleProvider.PROVIDER_NAME;
    private static final Pattern DOCUMENT_IN_NAME = Pattern.compile("(?i)\\bDNI\\s*:\\s*([A-Z0-9-]+)");
    private static final ZoneId LIMA_ZONE = ZoneId.of("America/Lima");
    private static final DateTimeFormatter SIGNED_AT_FORMAT = DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm:ss z");

    public PadesSigner() {
        if (Security.getProvider(PROVIDER) == null) {
            Security.addProvider(new BouncyCastleProvider());
        }
    }

    public byte[] sign(SignRequest request) throws Exception {
        SigningMaterial material = readCertificate(request.certificate(), request.certificatePassword());
        try (PDDocument document = Loader.loadPDF(request.document());
             ByteArrayOutputStream output = new ByteArrayOutputStream()) {
            int pages = document.getNumberOfPages();
            if (pages < 1) throw new IllegalArgumentException("El PDF no contiene páginas.");

            for (int pageIndex : selectedPages(request, pages)) {
                stamp(document, document.getPage(pageIndex), request);
            }

            PDSignature signature = new PDSignature();
            signature.setFilter(PDSignature.FILTER_ADOBE_PPKLITE);
            signature.setSubFilter(PDSignature.SUBFILTER_ETSI_CADES_DETACHED);
            signature.setName(request.signerName());
            signature.setReason("Firma digital de documento");
            signature.setSignDate(GregorianCalendar.from(ZonedDateTime.now(LIMA_ZONE)));

            SignatureOptions options = new SignatureOptions();
            options.setPreferredSignatureSize(SignatureOptions.DEFAULT_SIGNATURE_SIZE * 2);
            document.addSignature(signature, options);
            ExternalSigningSupport external = document.saveIncrementalForExternalSigning(output);
            external.setSignature(createCms(external.getContent(), material));
            options.close();
            return output.toByteArray();
        }
    }

    private List<Integer> selectedPages(SignRequest request, int pages) {
        return switch (request.placement()) {
            case "first" -> List.of(0);
            case "all" -> {
                List<Integer> result = new ArrayList<>();
                for (int index = 0; index < pages; index++) result.add(index);
                yield result;
            }
            case "specific" -> {
                if (request.pageNumber() == null || request.pageNumber() < 1 || request.pageNumber() > pages) {
                    throw new IllegalArgumentException("La página seleccionada no existe en el documento.");
                }
                yield List.of(request.pageNumber() - 1);
            }
            default -> List.of(pages - 1);
        };
    }

    private void stamp(PDDocument document, PDPage page, SignRequest request) throws IOException {
        PDRectangle box = page.getMediaBox();
        float pageWidth = box.getWidth();
        float pageHeight = box.getHeight();
        boolean vertical = "vertical".equals(request.orientation());
        float width = vertical ? 110 : 188;
        float height = vertical ? 112 : 72;
        int column = Math.floorMod(request.slot(), 2);
        int row = Math.max(0, request.slot() / 2);
        float x = column == 0 ? box.getLowerLeftX() + pageWidth - width - 24 : box.getLowerLeftX() + 24;
        float y = box.getLowerLeftY() + 24 + row * (height + 12);

        if ("manual".equals(request.positionMode()) && request.positionX() != null && request.positionY() != null) {
            width = percentageOrDefault(request.positionWidth(), width / pageWidth) * pageWidth;
            height = percentageOrDefault(request.positionHeight(), height / pageHeight) * pageHeight;
            x = box.getLowerLeftX() + percentageOrDefault(request.positionX(), 0.36f) * pageWidth;
            y = box.getLowerLeftY() + (1 - percentageOrDefault(request.positionY(), 0.44f)) * pageHeight - height;
        }

        x = Math.max(box.getLowerLeftX() + 4, Math.min(x, box.getUpperRightX() - width - 4));
        y = Math.max(box.getLowerLeftY() + 4, Math.min(y, box.getUpperRightY() - height - 4));

        try (PDPageContentStream stream = new PDPageContentStream(document, page, AppendMode.APPEND, true, true)) {
            // Apariencia visible sobria: la validez depende de la firma PAdES,
            // no de este sello visual.
            stream.setNonStrokingColor(248f / 255f, 250f / 255f, 252f / 255f);
            stream.addRect(x, y, width, height);
            stream.fill();
            stream.setLineWidth(0.65f);
            stream.setStrokingColor(148f / 255f, 163f / 255f, 184f / 255f);
            stream.addRect(x, y, width, height);
            stream.stroke();
            stream.beginText();
            stream.setFont(new PDType1Font(Standard14Fonts.FontName.HELVETICA_BOLD), 8);
            stream.setNonStrokingColor(30f / 255f, 41f / 255f, 59f / 255f);
            stream.newLineAtOffset(x + 7, y + height - 14);
            stream.showText(safeText("approval".equals(request.appearanceType()) ? "VISTO BUENO DIGITAL (VB)" : "FIRMADO DIGITALMENTE"));
            stream.setFont(new PDType1Font(Standard14Fonts.FontName.HELVETICA), 7.5f);
            stream.setNonStrokingColor(51f / 255f, 65f / 255f, 85f / 255f);
            stream.newLineAtOffset(0, -13);
            String signerName = safeText(request.signerName());
            String signerDocument = safeText(request.signerDocument()).replaceFirst("(?i)^DNI\\s*:\\s*", "");
            Matcher documentInName = DOCUMENT_IN_NAME.matcher(signerName);
            if (documentInName.find()) {
                if (signerDocument.isBlank()) signerDocument = documentInName.group(1);
                signerName = documentInName.replaceAll("").trim();
            }
            signerName = signerName.replaceFirst("(?i)^CN\\s*=\\s*", "");
            stream.showText(safeText(signerName));
            if (!signerDocument.isBlank()) {
                stream.newLineAtOffset(0, -13);
                stream.showText(safeText("Documento: " + signerDocument));
            }
            stream.newLineAtOffset(0, -13);
            stream.showText(SIGNED_AT_FORMAT.format(ZonedDateTime.now(LIMA_ZONE)));
            stream.endText();
        }
    }

    private float percentageOrDefault(Float value, float fallback) {
        if (value == null) return fallback;
        return Math.max(0f, Math.min(value, 1f));
    }

    private String safeText(String value) {
        return value == null ? "" : value.replaceAll("[^\\p{Print}]", " ");
    }

    private SigningMaterial readCertificate(byte[] certificate, char[] password) throws Exception {
        KeyStore store = KeyStore.getInstance("PKCS12");
        store.load(new ByteArrayInputStream(certificate), password);
        Enumeration<String> aliases = store.aliases();
        while (aliases.hasMoreElements()) {
            String alias = aliases.nextElement();
            if (store.isKeyEntry(alias)) {
                PrivateKey key = (PrivateKey) store.getKey(alias, password);
                Certificate[] certificates = store.getCertificateChain(alias);
                List<X509Certificate> chain = new ArrayList<>();
                for (Certificate item : certificates) chain.add((X509Certificate) item);
                return new SigningMaterial(key, chain);
            }
        }
        throw new IllegalArgumentException("El archivo PFX no contiene una clave privada utilizable.");
    }

    private byte[] createCms(InputStream content, SigningMaterial material) throws Exception {
        String algorithm = material.privateKey().getAlgorithm().equalsIgnoreCase("EC") ? "SHA256withECDSA" : "SHA256withRSA";
        ContentSigner signer = new JcaContentSignerBuilder(algorithm).setProvider(PROVIDER).build(material.privateKey());
        byte[] certificateDigest = MessageDigest.getInstance("SHA-256")
                .digest(material.chain().getFirst().getEncoded());
        ESSCertIDv2 signingCertificate = new ESSCertIDv2(
                new AlgorithmIdentifier(NISTObjectIdentifiers.id_sha256), certificateDigest);
        ASN1EncodableVector attributes = new ASN1EncodableVector();
        attributes.add(new Attribute(
                PKCSObjectIdentifiers.id_aa_signingCertificateV2,
                new DERSet(new SigningCertificateV2(signingCertificate))));
        JcaSignerInfoGeneratorBuilder signerInfoBuilder = new JcaSignerInfoGeneratorBuilder(
                new JcaDigestCalculatorProviderBuilder().setProvider(PROVIDER).build());
        signerInfoBuilder.setSignedAttributeGenerator(
                new DefaultSignedAttributeTableGenerator(new AttributeTable(attributes)));
        SignerInfoGenerator signerInfo = signerInfoBuilder.build(signer, material.chain().getFirst());
        CMSSignedDataGenerator generator = new CMSSignedDataGenerator();
        generator.addSignerInfoGenerator(signerInfo);
        generator.addCertificates(new JcaCertStore(material.chain()));
        CMSSignedData signed = generator.generate(new InputStreamProcessable(content), false);
        return signed.getEncoded();
    }

    record SignRequest(byte[] document, byte[] certificate, char[] certificatePassword, String signerName,
                       String signerDocument, String appearanceType, String placement, Integer pageNumber,
                       String orientation, String positionMode, Float positionX, Float positionY,
                       Float positionWidth, Float positionHeight, Integer slot) {}
    record SigningMaterial(PrivateKey privateKey, List<X509Certificate> chain) {}

    static class InputStreamProcessable implements CMSTypedData {
        private final InputStream input;
        InputStreamProcessable(InputStream input) { this.input = input; }
        public org.bouncycastle.asn1.ASN1ObjectIdentifier getContentType() {
            return CMSObjectIdentifiers.data;
        }
        public Object getContent() { return input; }
        public void write(OutputStream output) throws IOException {
            input.transferTo(output);
            input.close();
        }
    }
}
