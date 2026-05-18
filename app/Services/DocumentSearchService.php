<?php

namespace App\Services;

use App\Models\Document;

class DocumentSearchService
{
    public function search(array $filters = [])
    {
        $query = Document::query()

            ->with([
                'documentType',
                'creator',
                'area'
            ]);

        /**
         * 🔎 SEARCH GLOBAL
         */
        if (!empty($filters['search'])) {

            $search = $filters['search'];

            $query->where(function ($q) use ($search) {

                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        /**
         * 📄 TIPO DOCUMENTO
         */
        if (!empty($filters['document_type_id'])) {

            $query->where(
                'document_type_id',
                $filters['document_type_id']
            );
        }

        /**
         * 🏢 ÁREA
         */
        if (!empty($filters['area_id'])) {

            $query->where(
                'area_id',
                $filters['area_id']
            );
        }

        /**
         * 📌 ESTADO
         */
        if (!empty($filters['status'])) {

            $query->where(
                'status',
                $filters['status']
            );
        }

        /**
         * 📅 FECHAS
         */
        if (!empty($filters['date_from'])) {

            $query->whereDate(
                'created_at',
                '>=',
                $filters['date_from']
            );
        }

        if (!empty($filters['date_to'])) {

            $query->whereDate(
                'created_at',
                '<=',
                $filters['date_to']
            );
        }

        /**
         * ⚡ ORDEN
         */
        $query->latest();

        /**
         * 🚀 PAGINACIÓN ENTERPRISE
         */
        return $query->paginate(
            $filters['per_page'] ?? 20
        );
    }
}