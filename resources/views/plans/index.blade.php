@extends('layouts.app')

@section('content')
    <x-crud.index>
        <x-slot:header>
            <x-crud.header title="Planes" description="Catálogo de capacidades y límites disponibles para esta instalación.">
                <x-slot:toolbar>
                    <a href="{{ route('plans.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>Plan personalizado
                    </a>
                </x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        <div class="alert alert-info mb-4">
            <i class="ti ti-info-circle me-2"></i>
            Solo el propietario técnico puede administrar este catálogo. Los límites quedan definidos aquí y el plan elegido se asigna desde Licencia y plan.
        </div>

        <div class="row row-cards">
            @forelse($plans as $plan)
                <x-crud.card>
                    <x-slot:header>
                        <div class="d-flex align-items-start justify-content-between gap-3 w-100">
                            <div>
                                <div class="fw-bold fs-3">{{ $plan->name }}</div>
                                <div class="text-secondary small">{{ $plan->description ?: 'Sin descripción.' }}</div>
                            </div>
                            <span class="badge {{ $plan->active ? 'bg-green-lt' : 'bg-secondary-lt' }}">{{ $plan->active ? 'Activo' : 'Inactivo' }}</span>
                        </div>
                    </x-slot:header>

                    <div class="row g-3 text-center small">
                        <div class="col-4"><div class="text-secondary">Usuarios</div><div class="fw-semibold">{{ $plan->max_users ?? 'Ilimitado' }}</div></div>
                        <div class="col-4"><div class="text-secondary">Documentos</div><div class="fw-semibold">{{ $plan->max_documents ?? 'Ilimitado' }}</div></div>
                        <div class="col-4"><div class="text-secondary">Almacenamiento</div><div class="fw-semibold">{{ $plan->max_storage_mb ? number_format($plan->max_storage_mb / 1024, 0) . ' GB' : 'Ilimitado' }}</div></div>
                        <div class="col-4"><div class="text-secondary">Áreas</div><div class="fw-semibold">{{ $plan->max_areas ?? 'Ilimitado' }}</div></div>
                        <div class="col-4"><div class="text-secondary">Firmas</div><div class="fw-semibold">{{ $plan->max_signatures ?? 'Ilimitado' }}</div></div>
                        <div class="col-4"><div class="text-secondary">Flujos</div><div class="fw-semibold">{{ $plan->max_workflows ?? 'Ilimitado' }}</div></div>
                    </div>

                    @if($plan->features)
                        <hr>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($plan->features as $feature)
                                <span class="badge bg-azure-lt">{{ $feature }}</span>
                            @endforeach
                        </div>
                    @endif

                    <x-slot:footer><x-crud.actions><x-crud.button-action color="warning" icon="ti ti-edit" title="Editar plan" size="sm" href="{{ route('plans.edit', $plan) }}" /></x-crud.actions></x-slot:footer>
                </x-crud.card>
            @empty
                <x-crud.empty title="No hay planes configurados" description="Crea el primer plan para poder asignarlo a la licencia." />
            @endforelse
        </div>
    </x-crud.index>
@endsection
