<?php

namespace App\Services;

use App\Models\Area;
use App\Models\Document;
use App\Models\DocumentAttachment;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Signature;
use App\Models\User;
use App\Models\WorkflowTemplate;
use Illuminate\Validation\ValidationException;

class PlanLimitService
{
    public function ensureAvailable(string $resource): void
    {
        $plan = Setting::first()?->plan;
        if (! $plan) return;

        $limits = [
            'users' => ['field' => 'max_users', 'count' => fn () => User::active()->count(), 'label' => 'usuarios activos'],
            'areas' => ['field' => 'max_areas', 'count' => fn () => Area::active()->count(), 'label' => 'áreas activas'],
            'signatures' => ['field' => 'max_signatures', 'count' => fn () => Signature::active()->count(), 'label' => 'firmas activas'],
            'documents' => ['field' => 'max_documents', 'count' => fn () => Document::count(), 'label' => 'documentos'],
            'workflows' => ['field' => 'max_workflows', 'count' => fn () => WorkflowTemplate::active()->count(), 'label' => 'flujos activos'],
        ];

        $limit = $limits[$resource] ?? null;
        if (! $limit || is_null($plan->{$limit['field']})) return;

        if (($limit['count'])() >= $plan->{$limit['field']}) {
            throw ValidationException::withMessages([
                'plan' => "El plan {$plan->name} alcanzó su límite de {$limit['label']} ({$plan->{$limit['field']}}). Contacte al propietario técnico para ampliar la licencia.",
            ]);
        }
    }

    public function ensureStorageAvailable(int $incomingBytes): void
    {
        $plan = Setting::first()?->plan;
        if (! $plan || is_null($plan->max_storage_mb)) return;

        $usedBytes = (int) DocumentAttachment::sum('file_size');
        $limitBytes = $plan->max_storage_mb * 1024 * 1024;

        if ($usedBytes + $incomingBytes > $limitBytes) {
            throw ValidationException::withMessages([
                'file' => "El archivo supera el almacenamiento disponible del plan {$plan->name}.",
            ]);
        }
    }

    public function ensureCanAssign(Plan $plan): void
    {
        $violations = collect($this->usage($plan))
            ->filter(fn ($item) => ! is_null($item['limit_raw']) && $item['used_raw'] > $item['limit_raw'])
            ->map(fn ($item) => "{$item['label']}: actualmente {$item['used']} y el plan permite {$item['limit']}{$item['suffix']}")
            ->values();

        if ($violations->isNotEmpty()) {
            throw ValidationException::withMessages([
                'plan_id' => 'No se puede asignar este plan porque supera sus límites. ' . $violations->implode(' · '),
            ]);
        }
    }

    public function usage(?Plan $plan = null): array
    {
        $plan ??= Setting::first()?->plan;
        if (! $plan) return [];

        $users = User::active()->count();
        $areas = Area::active()->count();
        $signatures = Signature::active()->count();
        $documents = Document::count();
        $workflows = WorkflowTemplate::active()->count();
        $storage = (int) DocumentAttachment::sum('file_size');

        return [
            ['label' => 'Usuarios activos', 'used' => $users, 'used_raw' => $users, 'limit' => $plan->max_users ?? 'Ilimitado', 'limit_raw' => $plan->max_users, 'suffix' => ''],
            ['label' => 'Áreas activas', 'used' => $areas, 'used_raw' => $areas, 'limit' => $plan->max_areas ?? 'Ilimitado', 'limit_raw' => $plan->max_areas, 'suffix' => ''],
            ['label' => 'Firmas activas', 'used' => $signatures, 'used_raw' => $signatures, 'limit' => $plan->max_signatures ?? 'Ilimitado', 'limit_raw' => $plan->max_signatures, 'suffix' => ''],
            ['label' => 'Documentos', 'used' => $documents, 'used_raw' => $documents, 'limit' => $plan->max_documents ?? 'Ilimitado', 'limit_raw' => $plan->max_documents, 'suffix' => ''],
            ['label' => 'Flujos activos', 'used' => $workflows, 'used_raw' => $workflows, 'limit' => $plan->max_workflows ?? 'Ilimitado', 'limit_raw' => $plan->max_workflows, 'suffix' => ''],
            ['label' => 'Almacenamiento', 'used' => round($storage / 1024 / 1024, 1), 'used_raw' => $storage, 'limit' => $plan->max_storage_mb ? round($plan->max_storage_mb / 1024, 1) : 'Ilimitado', 'limit_raw' => $plan->max_storage_mb ? $plan->max_storage_mb * 1024 * 1024 : null, 'suffix' => ' GB'],
        ];
    }
}
