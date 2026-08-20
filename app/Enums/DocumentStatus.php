<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case IN_PROCESS = 'in_process';
    case DERIVED = 'derived';
    case APPROVED = 'approved';
    case OBSERVED = 'observed';
    case REJECTED = 'rejected';
    case SIGNED = 'signed';
    case ARCHIVED = 'archived';

    /* LABEL */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Borrador',
            self::PENDING => 'Pendiente',
            self::IN_PROCESS => 'En proceso',
            self::DERIVED => 'Derivado',
            self::APPROVED => 'Aprobado',
            self::OBSERVED => 'Observado',
            self::REJECTED => 'Rechazado',
            self::SIGNED => 'Firmado',
            self::ARCHIVED => 'Archivado',
        };
    }

    /* COLOR */
    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'secondary',
            self::PENDING => 'warning',
            self::IN_PROCESS => 'blue',
            self::DERIVED => 'blue',
            self::APPROVED => 'success',
            self::OBSERVED => 'orange',
            self::REJECTED => 'danger',
            self::SIGNED => 'purple',
            self::ARCHIVED => 'dark',
        };
    }

    /* ICON */
    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'ti ti-file',
            self::PENDING => 'ti ti-clock',
            self::IN_PROCESS => 'ti ti-progress',
            self::DERIVED => 'ti ti-arrow-forward',
            self::APPROVED => 'ti ti-circle-check',
            self::OBSERVED => 'ti ti-alert-triangle',
            self::REJECTED => 'ti ti-x',
            self::SIGNED => 'ti ti-signature',
            self::ARCHIVED => 'ti ti-archive',
        };
    }
}
