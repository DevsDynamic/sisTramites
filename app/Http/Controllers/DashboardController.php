<?php

namespace App\Http\Controllers;

use App\Enums\DocumentStatus;
use App\Models\Area;
use App\Models\Document;
use App\Models\DocumentFlow;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $this->ensurePermission($request, 'dashboard.view');

        $user = $request->user();
        $canViewAll = $user->isSystemOwner() || $user->can('dashboard.view-all');
        $months = in_array((int) $request->input('period', 6), [6, 12], true)
            ? (int) $request->input('period', 6)
            : 6;
        $areaId = $canViewAll && $request->filled('area_id')
            ? (int) $request->input('area_id')
            : null;

        $documents = $this->documentScope($user, $canViewAll, $areaId);
        $flows = $this->flowScope($user, $canViewAll, $areaId);
        $startDate = now()->subMonths($months - 1)->startOfMonth();

        $statusCounts = (clone $documents)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $monthlyCounts = (clone $documents)
            ->whereBetween('created_at', [$startDate, now()])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyDocuments = collect(range($months - 1, 0))
            ->map(function ($offset) use ($monthlyCounts) {
                $date = now()->subMonths($offset)->startOfMonth();

                return [
                    'label' => ucfirst($date->locale('es')->translatedFormat('M')),
                    'total' => (int) ($monthlyCounts[$date->format('Y-m')] ?? 0),
                ];
            })
            ->values();

        $stats = [
            'documents' => (clone $documents)->count(),
            'pending' => (clone $flows)->where('status', 'pending')->count(),
            'signed' => (clone $documents)->where('status', DocumentStatus::SIGNED->value)->count(),
            'overdue' => (clone $flows)
                ->where(function ($query) {
                    $query->where('sla_expired', true)
                        ->orWhere(function ($deadlineQuery) {
                            $deadlineQuery->whereNotNull('sla_deadline')
                                ->where('sla_deadline', '<', now())
                                ->whereNotIn('status', ['approved', 'rejected']);
                        });
                })
                ->count(),
            'active_users' => $canViewAll ? User::active()->count() : null,
        ];

        $statusData = collect(DocumentStatus::cases())
            ->map(fn(DocumentStatus $status) => [
                'label' => $status->label(),
                'color' => $status->color(),
                'total' => (int) ($statusCounts[$status->value] ?? 0),
            ])
            ->filter(fn(array $status) => $status['total'] > 0)
            ->values();

        $recentDocuments = (clone $documents)
            ->with(['type', 'creator', 'area'])
            ->latest('id')
            ->take(5)
            ->get();

        $inbox = (clone $flows)
            ->with(['document.type', 'document.creator'])
            ->whereIn('status', ['pending', 'received', 'observed'])
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', [
            'stats' => $stats,
            'statusData' => $statusData,
            'monthlyDocuments' => $monthlyDocuments,
            'monthlyMax' => max(1, $monthlyDocuments->max('total')),
            'recentDocuments' => $recentDocuments,
            'inbox' => $inbox,
            'canViewAll' => $canViewAll,
            'areas' => $canViewAll ? Area::active()->orderBy('name')->get() : collect(),
            'selectedAreaId' => $areaId,
            'period' => $months,
        ]);
    }

    private function documentScope(User $user, bool $canViewAll, ?int $areaId): Builder
    {
        $query = Document::query();

        if ($canViewAll) {
            return $query->when($areaId, fn(Builder $builder) => $builder->where('area_id', $areaId));
        }

        $areaIds = $user->areas()->pluck('areas.id');

        return $query->where(function (Builder $builder) use ($user, $areaIds) {
            $builder->where('created_by', $user->id)
                ->when($areaIds->isNotEmpty(), function (Builder $documentQuery) use ($areaIds) {
                    $documentQuery->orWhereHas('flows', function (Builder $flowQuery) use ($areaIds) {
                        $flowQuery->whereIn('to_area_id', $areaIds);
                    });
                });
        });
    }

    private function flowScope(User $user, bool $canViewAll, ?int $areaId): Builder
    {
        $query = DocumentFlow::query();

        if ($canViewAll) {
            return $query->when($areaId, function (Builder $builder) use ($areaId) {
                $builder->where(function (Builder $flowQuery) use ($areaId) {
                    $flowQuery->where('from_area_id', $areaId)
                        ->orWhere('to_area_id', $areaId);
                });
            });
        }

        $areaIds = $user->areas()->pluck('areas.id');

        return $query->where(function (Builder $builder) use ($user, $areaIds) {
            $builder->where('sent_by', $user->id)
                ->when($areaIds->isNotEmpty(), fn(Builder $flowQuery) => $flowQuery->orWhereIn('to_area_id', $areaIds));
        });
    }

    private function ensurePermission(Request $request, string $permission): void
    {
        abort_unless(
            $request->user()->isSystemOwner()
                || $request->user()->can($permission),
            403
        );
    }
}
