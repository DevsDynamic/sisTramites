<?php

namespace App\Services;

use App\Models\DocumentSeries;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DocumentSeriesService
{
    /**
     * Generar correlativo seguro
     */
    public function generate(
        int $documentTypeId,
        ?int $areaId = null
    ): string {

        return $this->generateWithSeries($documentTypeId, $areaId)['code'];
    }

    public function preview(int $documentTypeId, ?int $areaId = null): array
    {
        $series = $this->resolve($documentTypeId, $areaId);

        return [
            'series' => $series,
            'code' => $series->previewCode(),
            'is_global' => is_null($series->area_id),
        ];
    }

    public function generateWithSeries(int $documentTypeId, ?int $areaId = null): array
    {
        return DB::transaction(function () use ($documentTypeId, $areaId) {
            $series = $this->resolve($documentTypeId, $areaId, true);
            $series->increment('current_number');
            $series->refresh();

            return [
                'series' => $series,
                'code' => $this->formatCode($series),
            ];
        });
    }

    private function resolve(int $documentTypeId, ?int $areaId, bool $lock = false): DocumentSeries
    {
        $query = DocumentSeries::query()
            ->where('document_type_id', $documentTypeId)
            ->where('area_id', $areaId)
            ->where('active', true);

        if ($lock) $query->lockForUpdate();

        $series = $query->first();

        if (! $series) {
            $globalQuery = DocumentSeries::query()
                ->where('document_type_id', $documentTypeId)
                ->whereNull('area_id')
                ->where('active', true);

            if ($lock) $globalQuery->lockForUpdate();

            $series = $globalQuery->first();
        }

        if (! $series) {
            throw ValidationException::withMessages([
                'document_type_id' => 'No existe una serie activa para el tipo y área seleccionados.',
            ]);
        }

        return $series;
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
