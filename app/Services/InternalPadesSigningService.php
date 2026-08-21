<?php

namespace App\Services;

use App\Models\DocumentAttachment;
use App\Models\Signature;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InternalPadesSigningService
{
    public function isConfigured(): bool
    {
        return filled(config('services.internal_signer.url'))
            && filled(config('services.internal_signer.token'));
    }

    public function sign(DocumentAttachment $attachment, Signature $signature, array $options, ?string $certificatePassword): string
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('El servicio interno de firma PAdES aún no está configurado.');
        }

        $pdfDisk = $attachment->storage_disk ?: 'local';
        $pfxDisk = $signature->pfx_disk ?: 'local';
        $pdfPath = Storage::disk($pdfDisk)->path($attachment->file_path);
        $pfxPath = Storage::disk($pfxDisk)->path($signature->pfx_path);

        if (! is_file($pdfPath) || ! is_file($pfxPath)) {
            throw new \RuntimeException('No se encontró el documento o el certificado para firmar.');
        }

        $certificatePassword ??= $signature->pfx_password
            ? decrypt($signature->pfx_password)
            : null;

        if (blank($certificatePassword)) {
            throw new \RuntimeException('Ingrese la contraseña del certificado para firmar.');
        }

        $response = Http::withHeaders([
                'Accept' => 'application/pdf, application/json',
            ])
            ->withToken(config('services.internal_signer.token'))
            ->timeout((int) config('services.internal_signer.timeout', 120))
            ->attach('document', fopen($pdfPath, 'r'), $attachment->original_name ?: 'documento.pdf')
            ->attach('certificate', fopen($pfxPath, 'r'), 'certificate.pfx')
            ->post(rtrim(config('services.internal_signer.url'), '/') . '/api/v1/pades/sign', [
                'certificate_password' => $certificatePassword,
                'signer_name' => (string) data_get($signature->certificate_data, 'subject.CN', $signature->user?->name),
                'signer_document' => (string) data_get($signature->certificate_data, 'subject.serialNumber', ''),
                'appearance_type' => $options['appearance_type'],
                'placement' => $options['placement'],
                'page_number' => $options['page_number'],
                'orientation' => $options['orientation'],
                'position_mode' => $options['position_mode'],
                'position_x' => data_get($options, 'position.x'),
                'position_y' => data_get($options, 'position.y'),
                'position_width' => data_get($options, 'position.width'),
                'position_height' => data_get($options, 'position.height'),
                'slot' => $options['slot'],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException(data_get($response->json(), 'message') ?: 'El servicio de firma no pudo completar la operación.');
        }

        $signedPdf = $response->body();

        if (app(LlamaTimestampService::class)->isConfigured()) {
            $signedPdf = app(LlamaTimestampService::class)->stamp(
                $signedPdf,
                $attachment->original_name ?: 'documento.pdf'
            );
        }

        $signedPath = 'documents/signed/' . Str::uuid() . '.pdf';
        Storage::disk('local')->put($signedPath, $signedPdf);

        return $signedPath;
    }
}
