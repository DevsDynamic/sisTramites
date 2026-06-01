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
        Signature $signature
    ): string {

        return match ($signature->type) {

            'visual' => $this->signVisual(
                $attachment,
                $signature
            ),

            'official' => $this->signOfficial(
                $attachment,
                $signature
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
        Signature $signature
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

        $lastSize = null;

        for ($page = 1; $page <= $pages; $page++) {

            $tpl = $pdf->importPage($page);

            $size = $pdf->getTemplateSize($tpl);

            $lastSize = $size;

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
        }

        $pdf->setPage($pages);

        $x = $lastSize['width'] - 60;
        $y = $lastSize['height'] - 35;

        $pdf->Image(
            $imagePath,
            $x,
            $y,
            40,
            20
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

    /**
     * ==================================================
     * FIRMA OFICIAL PFX
     * ==================================================
     */
    private function signOfficial(
        DocumentAttachment $attachment,
        Signature $signature
    ): string {

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

        $pages = $pdf->setSourceFile($pdfPath);

        for ($page = 1; $page <= $pages; $page++) {

            $tpl = $pdf->importPage($page);

            $size = $pdf->getTemplateSize($tpl);

            $pdf->AddPage(
                $size['orientation'],
                [
                    $size['width'],
                    $size['height']
                ]
            );

            $pdf->useTemplate($tpl);
        }

        /**
         * Firma digital
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

        /**
         * Apariencia visual
         */
        $pdf->setSignatureAppearance(
            140,
            240,
            50,
            20
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
}
