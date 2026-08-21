@extends('layouts.app')

@section('content')
    <x-crud.index>
        <x-slot:header>
            <x-crud.header title="Dashboard" :description="$canViewAll ? 'Vista general de la operación del sistema.' : 'Resumen de tus documentos y áreas asignadas.'">
                <x-slot:toolbar>
                    @can('documents.create')
                        <a href="{{ route('documents.create') }}" class="btn btn-success">
                            <i class="ti ti-circle-plus me-1"></i>Nuevo documento
                        </a>
                    @endcan
                </x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        <form method="GET" class="card dashboard-filters mb-4">
            <div class="card-body">
                <div class="row align-items-end g-3">
                    <div class="col-md-4">
                        <label class="form-label">Periodo de análisis</label>
                        <select name="period" class="form-select" onchange="this.form.submit()">
                            <option value="6" @selected($period === 6)>Últimos 6 meses</option>
                            <option value="12" @selected($period === 12)>Últimos 12 meses</option>
                        </select>
                    </div>

                    @if ($canViewAll)
                        <div class="col-md-5">
                            <label class="form-label">Área</label>
                            <select name="area_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Todas las áreas</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}" @selected($selectedAreaId === $area->id)>{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-md-auto ms-md-auto">
                        <span class="badge {{ $canViewAll ? 'bg-purple-lt' : 'bg-azure-lt' }} dashboard-scope">
                            <i class="ti {{ $canViewAll ? 'ti-world' : 'ti-user' }} me-1"></i>
                            {{ $canViewAll ? 'Vista global' : 'Mi alcance' }}
                        </span>
                    </div>
                </div>
            </div>
        </form>

        <div class="row row-cards mb-4">
            <x-crud.card-stat title="Documentos" :value="$stats['documents']" icon="ti ti-file-text" color="primary" />
            <x-crud.card-stat title="Pendientes" :value="$stats['pending']" icon="ti ti-clock-hour-4" color="warning" />
            <x-crud.card-stat title="Firmados" :value="$stats['signed']" icon="ti ti-signature" color="purple" />
            <x-crud.card-stat :title="$canViewAll ? 'Usuarios activos' : 'SLA vencidos'" :value="$canViewAll ? $stats['active_users'] : $stats['overdue']" :icon="$canViewAll ? 'ti ti-users' : 'ti ti-alert-triangle'" :color="$canViewAll ? 'success' : 'danger'" />
        </div>

        <div class="row row-cards mb-4">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title mb-1">Documentos registrados</h3>
                            <div class="text-secondary small">Tendencia mensual del periodo seleccionado.</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-chart" aria-label="Gráfico de documentos por mes">
                            @foreach ($monthlyDocuments as $month)
                                @php($height = max(6, ($month['total'] / $monthlyMax) * 100))
                                <div class="dashboard-chart-column">
                                    <span class="dashboard-chart-value">{{ $month['total'] }}</span>
                                    <div class="dashboard-chart-track">
                                        <div class="dashboard-chart-bar" style="height: {{ $height }}%"></div>
                                    </div>
                                    <span class="dashboard-chart-label">{{ $month['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title mb-1">Estado documental</h3>
                            <div class="text-secondary small">Distribución de documentos en tu alcance.</div>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse ($statusData as $status)
                            @php($percentage = $stats['documents'] ? round(($status['total'] / $stats['documents']) * 100) : 0)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>{{ $status['label'] }}</span>
                                    <span class="fw-semibold">{{ $status['total'] }}</span>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-{{ $status['color'] }}" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-secondary text-center py-4">Aún no hay documentos para mostrar.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cards">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title mb-1">Pendientes por atender</h3>
                            <div class="text-secondary small">Flujos en espera dentro de tu alcance.</div>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse ($inbox as $flow)
                            <a href="{{ route('documents.show', $flow->workflow?->document) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex gap-3 align-items-center">
                                    <span class="avatar bg-warning-lt"><i class="ti ti-clock-hour-4"></i></span>
                                    <div class="min-w-0 flex-fill">
                                <div class="fw-semibold text-truncate">{{ $flow->workflow?->document?->code }}</div>
                                <div class="text-secondary small text-truncate">{{ $flow->workflow?->document?->subject }} · {{ $flow->name }}</div>
                                    </div>
                                    @if ($flow->overdue_at || ($flow->due_at && $flow->due_at->isPast()))
                                        <span class="badge bg-danger-lt">SLA vencido</span>
                                    @else
                                        <span class="badge bg-warning-lt">{{ ucfirst($flow->status) }}</span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="card-body text-secondary text-center py-5">No tienes pendientes por atender.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title mb-1">Actividad reciente</h3>
                            <div class="text-secondary small">Últimos documentos de tu alcance.</div>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse ($recentDocuments as $document)
                            <a href="{{ route('documents.show', $document) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex gap-3 align-items-center">
                                    <span class="avatar bg-primary-lt"><i class="{{ $document->status->icon() }}"></i></span>
                                    <div class="min-w-0 flex-fill">
                                        <div class="fw-semibold text-truncate">{{ $document->code }}</div>
                                        <div class="text-secondary small text-truncate">{{ $document->subject }}</div>
                                    </div>
                                    <span class="badge bg-{{ $document->status->color() }}-lt">{{ $document->status->label() }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="card-body text-secondary text-center py-5">Aún no hay documentos registrados.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </x-crud.index>
@endsection
