<?php

namespace App\Services;

use App\Models\Tenant\DocumentSeries;
use Illuminate\Support\Facades\DB;

class DocumentSeriesService
{
    /**
     * Generar correlativo seguro
     */
    public function generate(int $documentTypeId, ?int $areaId = null): string
    {
        return DB::transaction(function () use ($documentTypeId, $areaId) {

            // 🔒 LOCK para evitar duplicados en concurrencia
            $series = DocumentSeries::where('document_type_id', $documentTypeId)
                ->where('area_id', $areaId)
                ->lockForUpdate()
                ->first();

            if (!$series) {
                throw new \Exception("Serie no configurada para este tipo de documento");
            }

            // 🔢 incrementar contador
            $series->current_number++;

            // 📅 reset anual (si aplica)
            if ($series->reset_yearly) {
                $year = now()->year;
                if (!str_contains($series->prefix, $year)) {
                    $series->prefix = $series->prefix . '-' . $year;
                }
            }

            $series->save();

            // 🧾 generar código final
            return $this->formatCode($series);
        });
    }

    /**
     * Formato del código final
     */
    private function formatCode(DocumentSeries $series): string
    {
        return $series->prefix . '-' . str_pad(
            $series->current_number,
            $series->padding,
            '0',
            STR_PAD_LEFT
        );
    }
}