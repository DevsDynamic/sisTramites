<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LlamaTimestampService
{
    public function isConfigured(): bool
    {
        return filled(config('services.llama_timestamp.route'))
            && filled(config('services.llama_timestamp.token'));
    }

    public function stamp(string $pdf, string $fileName): string
    {
        if (! $this->isConfigured()) {
            return $pdf;
        }

        $response = Http::timeout((int) config('services.llama_timestamp.timeout', 120))
            ->acceptJson()
            ->withHeaders([
                'Authorization' => config('services.llama_timestamp.token'),
            ])
            ->post(config('services.llama_timestamp.route'), [
                'operacion' => 'sellar_pdf',
                'zip_base64' => $this->zipPdf($pdf, $fileName),
            ]);

        if ($response->failed() || ! $response->json('success')) {
            throw new \RuntimeException(
                $response->json('mensaje')
                    ?: 'No se pudo obtener el sello de tiempo de Llama.pe.'
            );
        }

        $sealed = $this->extractPdf((string) $response->json('zip_base64'));

        if ($sealed === '') {
            throw new \RuntimeException('Llama.pe no devolvió un PDF sellado válido.');
        }

        return $sealed;
    }

    private function zipPdf(string $pdf, string $fileName): string
    {
        $path = tempnam(sys_get_temp_dir(), 'sistramites-tsa-');
        $zip = new \ZipArchive();

        if ($path === false || $zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('No se pudo preparar el documento para el sello de tiempo.');
        }

        $zip->addFromString(basename($fileName ?: 'documento.pdf'), $pdf);
        $zip->close();
        $encoded = base64_encode((string) file_get_contents($path));
        @unlink($path);

        return $encoded;
    }

    private function extractPdf(string $zipBase64): string
    {
        $path = tempnam(sys_get_temp_dir(), 'sistramites-tsa-');

        if ($path === false || file_put_contents($path, base64_decode($zipBase64, true) ?: '') === false) {
            throw new \RuntimeException('La respuesta del sello de tiempo no es válida.');
        }

        $zip = new \ZipArchive();
        $opened = $zip->open($path) === true;
        $pdf = '';

        if ($opened) {
            for ($index = 0; $index < $zip->numFiles; $index++) {
                $name = $zip->getNameIndex($index);

                if ($name && str_ends_with(strtolower($name), '.pdf')) {
                    $stream = $zip->getStream($name);
                    $pdf = $stream ? stream_get_contents($stream) ?: '' : '';

                    if (is_resource($stream)) {
                        fclose($stream);
                    }

                    break;
                }
            }

            $zip->close();
        }

        @unlink($path);

        return $pdf;
    }
}
