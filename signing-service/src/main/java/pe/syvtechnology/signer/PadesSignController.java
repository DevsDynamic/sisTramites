package pe.syvtechnology.signer;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.ExceptionHandler;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestHeader;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RequestPart;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.multipart.MultipartFile;

@RestController
@RequestMapping("/api/v1/pades")
public class PadesSignController {
    private final PadesSigner signer;
    private final String token;

    public PadesSignController(PadesSigner signer, @Value("${signer.token}") String token) {
        this.signer = signer;
        this.token = token;
    }

    @GetMapping("/health")
    public ResponseEntity<Void> health() {
        return ResponseEntity.noContent().build();
    }

    @PostMapping(value = "/sign", consumes = MediaType.MULTIPART_FORM_DATA_VALUE, produces = MediaType.APPLICATION_PDF_VALUE)
    public ResponseEntity<byte[]> sign(
            @RequestHeader(value = HttpHeaders.AUTHORIZATION, required = false) String authorization,
            @RequestPart("document") MultipartFile document,
            @RequestPart("certificate") MultipartFile certificate,
            @RequestParam("certificate_password") String certificatePassword,
            @RequestParam("signer_name") String signerName,
            @RequestParam(value = "signer_document", required = false) String signerDocument,
            @RequestParam(defaultValue = "signature") String appearanceType,
            @RequestParam(defaultValue = "last") String placement,
            @RequestParam(value = "page_number", required = false) Integer pageNumber,
            @RequestParam(defaultValue = "horizontal") String orientation,
            @RequestParam(value = "position_mode", defaultValue = "automatic") String positionMode,
            @RequestParam(value = "position_x", required = false) Float positionX,
            @RequestParam(value = "position_y", required = false) Float positionY,
            @RequestParam(value = "position_width", required = false) Float positionWidth,
            @RequestParam(value = "position_height", required = false) Float positionHeight
            , @RequestParam(defaultValue = "0") Integer slot
    ) throws Exception {
        requireAuthorized(authorization);
        var request = new PadesSigner.SignRequest(
                document.getBytes(), certificate.getBytes(), certificatePassword.toCharArray(), signerName,
                signerDocument, appearanceType, placement, pageNumber, orientation, positionMode,
                positionX, positionY, positionWidth, positionHeight, slot
        );
        byte[] signed = signer.sign(request);

        return ResponseEntity.ok()
                .header(HttpHeaders.CONTENT_DISPOSITION, "attachment; filename=firmado.pdf")
                .contentType(MediaType.APPLICATION_PDF)
                .body(signed);
    }

    private void requireAuthorized(String authorization) {
        if (token == null || token.isBlank() || !("Bearer " + token).equals(authorization)) {
            throw new UnauthorizedException();
        }
    }

    @ExceptionHandler(UnauthorizedException.class)
    ResponseEntity<ErrorResponse> unauthorized() {
        return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(new ErrorResponse("No autorizado."));
    }

    @ExceptionHandler(Exception.class)
    ResponseEntity<ErrorResponse> error(Exception exception) {
        return ResponseEntity.unprocessableEntity().body(new ErrorResponse(exception.getMessage()));
    }

    record ErrorResponse(String message) {}
    static class UnauthorizedException extends RuntimeException {}
}
