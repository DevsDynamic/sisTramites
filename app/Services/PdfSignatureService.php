<?php

namespace App\Services;

use App\Models\Signature;
use App\Models\DocumentAttachment;

use Illuminate\Support\Facades\Storage;

use setasign\Fpdi\Tcpdf\Fpdi;

class PdfSignatureService
{
    public function sign(
        DocumentAttachment $attachment,
        Signature $signature,
        array $options = []
    ): string {

        $options = $this->normalizeOptions($options);

        return match ($signature->type) {

            'visual' => $this->signVisual(
                $attachment,
                $signature,
                $options
            ),

            'official' => $this->signOfficial(
                $attachment,
                $signature,
                $options
            ),

            default => throw new \Exception(
                'Tipo de firma no soportado.'
            ),
        };
    }

    /**
     * ==================================================
     * FIRMA VISUAL
     * ==================================================
     */
    private function signVisual(
        DocumentAttachment $attachment,
        Signature $signature,
        array $options
    ): string {

        $pdfPath = storage_path(
            'app/public/' .
                $attachment->file_path
        );

        $imagePath = storage_path(
            'app/public/' .
                $signature->signature_image
        );

        $pdf = new Fpdi();

        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pages = $pdf->setSourceFile($pdfPath);

        for ($page = 1; $page <= $pages; $page++) {

            $tpl = $pdf->importPage($page);

            $size = $pdf->getTemplateSize($tpl);

            $pdf->AddPage(
                $size['orientation'],
                [$size['width'], $size['height']]
            );

            $pdf->useTemplate(
                $tpl,
                0,
                0,
                $size['width'],
                $size['height']
            );

            if ($this->mustShowAppearance($page, $pages, $options['placement'])) {
                $this->drawVisualAppearance($pdf, $imagePath, $size, $options);
            }
        }

        $signedName =
            'SIGNED_' .
            now()->timestamp .
            '.pdf';

        $signedPath =
            'documents/signed/' .
            $signedName;

        $pdf->Output(
            storage_path(
                'app/public/' .
                    $signedPath
            ),
            'F'
        );

        return $signedPath;
    }

    /**
     * ==================================================
     * FIRMA OFICIAL PFX
     * ==================================================
     */
    // private function signOfficial(
    //     DocumentAttachment $attachment,
    //     Signature $signature
    // ): string {

    //     $pdfPath = storage_path(
    //         'app/public/' .
    //             $attachment->file_path
    //     );

    //     $pfxPath = storage_path(
    //         'app/public/' .
    //             $signature->pfx_path
    //     );

    //     $password = decrypt(
    //         $signature->pfx_password
    //     );

    //     $certs = [];

    //     if (
    //         !openssl_pkcs12_read(
    //             file_get_contents($pfxPath),
    //             $certs,
    //             $password
    //         )
    //     ) {
    //         throw new \Exception(
    //             'No se pudo leer el certificado.'
    //         );
    //     }

    //     $certificate = $certs['cert'];

    //     $privateKey = $certs['pkey'];

    //     $pdf = new Fpdi();

    //     $pages = $pdf->setSourceFile($pdfPath);

    //     for ($page = 1; $page <= $pages; $page++) {

    //         $tpl = $pdf->importPage($page);

    //         $size = $pdf->getTemplateSize($tpl);

    //         $pdf->AddPage(
    //             $size['orientation'],
    //             [
    //                 $size['width'],
    //                 $size['height']
    //             ]
    //         );

    //         $pdf->useTemplate($tpl);
    //     }

    //     /**
    //      * Firma digital
    //      */
    //     $pdf->setSignature(
    //         $certificate,
    //         $privateKey,
    //         $password,
    //         '',
    //         2,
    //         [
    //             'Name' => data_get(
    //                 $signature->certificate_data,
    //                 'subject.CN'
    //             ),

    //             'Location' => 'Perú',

    //             'Reason' => 'Documento firmado digitalmente',

    //             'ContactInfo' => data_get(
    //                 $signature->certificate_data,
    //                 'subject.emailAddress'
    //             )
    //         ]
    //     );

    //     /**
    //      * Apariencia visual
    //      */
    //     $pdf->setSignatureAppearance(
    //         140,
    //         240,
    //         50,
    //         20
    //     );

    //     $signedName =
    //         'SIGNED_' .
    //         now()->timestamp .
    //         '.pdf';

    //     $signedPath =
    //         'documents/signed/' .
    //         $signedName;

    //     $pdf->Output(
    //         storage_path(
    //             'app/public/' .
    //                 $signedPath
    //         ),
    //         'F'
    //     );

    //     return $signedPath;
    // }

    private function signOfficial(
        DocumentAttachment $attachment,
        Signature $signature,
        array $options
    ): string {

        $validTo = data_get(
            $signature->certificate_data,
            'validTo_time_t'
        );

        if ($validTo && $validTo < time()) {

            throw new \Exception(
                'El certificado digital está vencido.'
            );
        }

        $pdfPath = storage_path(
            'app/public/' .
                $attachment->file_path
        );

        $pfxPath = storage_path(
            'app/public/' .
                $signature->pfx_path
        );

        $password = decrypt(
            $signature->pfx_password
        );

        $certs = [];

        if (
            !openssl_pkcs12_read(
                file_get_contents($pfxPath),
                $certs,
                $password
            )
        ) {

            throw new \Exception(
                'No se pudo leer el certificado.'
            );
        }

        $certificate = $certs['cert'];

        $privateKey = $certs['pkey'];

        $pdf = new Fpdi();

        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        /**
         * IMPORTANTE:
         * configurar firma antes de generar páginas
         */
        $pdf->setSignature(
            $certificate,
            $privateKey,
            $password,
            '',
            2,
            [
                'Name' => data_get(
                    $signature->certificate_data,
                    'subject.CN'
                ),

                'Location' => 'Perú',

                'Reason' => 'Documento firmado digitalmente',

                'ContactInfo' => data_get(
                    $signature->certificate_data,
                    'subject.emailAddress'
                )
            ]
        );

        $pages = $pdf->setSourceFile(
            $pdfPath
        );

        $signaturePage = $options['placement'] === 'first' ? 1 : $pages;
        $signatureSize = null;

        for (
            $page = 1;
            $page <= $pages;
            $page++
        ) {

            $tpl = $pdf->importPage($page);

            $size = $pdf->getTemplateSize($tpl);

            if ($page === $signaturePage) {
                $signatureSize = $size;
            }

            $pdf->AddPage(
                $size['orientation'],
                [
                    $size['width'],
                    $size['height']
                ]
            );

            $pdf->useTemplate(
                $tpl,
                0,
                0,
                $size['width'],
                $size['height']
            );

            if ($this->mustShowAppearance($page, $pages, $options['placement'])) {
                $this->drawOfficialAppearance($pdf, $size, $signature, $options);
            }
        }

        /**
         * Apariencia visual
         */

        $geometry = $this->appearanceGeometry($signatureSize, $options['orientation']);

        $pdf->setPage($signaturePage);

        $pdf->setSignatureAppearance(
            $geometry['x'],
            $geometry['y'],
            $geometry['width'],
            $geometry['height']
        );

        $signedName =
            'SIGNED_' .
            now()->timestamp .
            '.pdf';

        $signedPath =
            'documents/signed/' .
            $signedName;

        $pdf->Output(
            storage_path(
                'app/public/' .
                    $signedPath
            ),
            'F'
        );

        return $signedPath;
    }

    private function normalizeOptions(array $options): array
    {
        return [
            'appearance_type' => in_array($options['appearance_type'] ?? null, ['signature', 'approval'], true)
                ? $options['appearance_type']
                : 'signature',
            'placement' => in_array($options['placement'] ?? null, ['first', 'last', 'all'], true)
                ? $options['placement']
                : 'last',
            'orientation' => in_array($options['orientation'] ?? null, ['horizontal', 'vertical'], true)
                ? $options['orientation']
                : 'horizontal',
        ];
    }

    private function mustShowAppearance(int $page, int $totalPages, string $placement): bool
    {
        return match ($placement) {
            'first' => $page === 1,
            'all' => true,
            default => $page === $totalPages,
        };
    }

    private function appearanceGeometry(array $size, string $orientation): array
    {
        $width = $orientation === 'vertical' ? 34 : 62;
        $height = $orientation === 'vertical' ? 42 : 28;

        return [
            'x' => $size['width'] - $width - 8,
            'y' => $size['height'] - $height - 8,
            'width' => $width,
            'height' => $height,
        ];
    }

    private function drawVisualAppearance(Fpdi $pdf, string $imagePath, array $size, array $options): void
    {
        if (! is_file($imagePath)) {
            return;
        }

        $geometry = $this->appearanceGeometry($size, $options['orientation']);

        $pdf->Image(
            $imagePath,
            $geometry['x'] + 2,
            $geometry['y'] + 2,
            $geometry['width'] - 4,
            $geometry['height'] - 4
        );
    }

    private function drawOfficialAppearance(Fpdi $pdf, array $size, Signature $signature, array $options): void
    {
        $geometry = $this->appearanceGeometry($size, $options['orientation']);
        $signerName = (string) data_get(
            $signature->certificate_data,
            'subject.CN',
            'Titular del certificado'
        );
        $documentNumber = data_get($signature->certificate_data, 'subject.serialNumber');
        $label = $options['appearance_type'] === 'approval'
            ? 'VISTO BUENO DIGITAL (VB)'
            : 'FIRMADO DIGITALMENTE';

        $pdf->SetFillColor(219, 234, 254);
        $pdf->SetDrawColor(37, 99, 235);
        $pdf->Rect(
            $geometry['x'],
            $geometry['y'],
            $geometry['width'],
            $geometry['height'],
            'DF'
        );
        $pdf->SetTextColor(30, 64, 175);
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetXY($geometry['x'] + 3, $geometry['y'] + 3);
        $pdf->MultiCell(
            $geometry['width'] - 6,
            4,
            $label . "\n" . $signerName .
                ($documentNumber ? "\nDocumento: " . $documentNumber : ''),
            0,
            'L'
        );
        $pdf->SetTextColor(0, 0, 0);
    }
}
