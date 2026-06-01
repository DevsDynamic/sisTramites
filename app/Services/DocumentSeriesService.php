<?php

namespace App\Services;

use App\Models\DocumentSeries;
use Illuminate\Support\Facades\DB;

class DocumentSeriesService
{
    /**
     * Generar correlativo seguro
     */
    public function generate(
        int $documentTypeId,
        ?int $areaId = null
    ): string {

        return DB::transaction(function () use (
            $documentTypeId,
            $areaId
        ) {

            $series = DocumentSeries::query()
                ->where('document_type_id', $documentTypeId)
                ->where('area_id', $areaId)
                ->where('active', true)
                ->lockForUpdate()
                ->first();

            if (!$series) {

                $series = DocumentSeries::query()
                    ->where('document_type_id', $documentTypeId)
                    ->whereNull('area_id')
                    ->where('active', true)
                    ->lockForUpdate()
                    ->first();
            }

            if (!$series) {
                throw new \Exception(
                    'No existe una serie configurada para este tipo de documento.'
                );
            }

            $series->increment('current_number');

            $series->refresh();

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
