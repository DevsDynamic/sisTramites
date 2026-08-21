@extends('layouts.app')

@section('content')
    <x-crud.index>
        <x-slot:header>
            <x-crud.header :title="$plan->exists ? 'Editar plan' : 'Nuevo plan personalizado'" description="Define las capacidades disponibles y los límites de uso por instalación.">
                <x-slot:toolbar><a href="{{ route('plans.index') }}" class="btn btn-outline-secondary"><i class="ti ti-arrow-left me-1"></i>Volver</a></x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        <div class="card">
            <form method="POST" action="{{ $plan->exists ? route('plans.update', $plan) : route('plans.store') }}">
                @csrf
                @if($plan->exists) @method('PUT') @endif
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8"><label class="form-label">Nombre</label><input name="name" class="form-control" value="{{ old('name', $plan->name) }}" required><div class="form-hint">Las capacidades del plan no dependen de si la licencia se cobra mensual o anualmente.</div></div>
                        <div class="col-md-4"><label class="form-label">Orden de visualización</label><input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', $plan->sort_order ?? 0) }}"></div>
                        <div class="col-12"><label class="form-label">Descripción</label><textarea name="description" class="form-control" rows="2">{{ old('description', $plan->description) }}</textarea></div>

                        <div class="col-12"><hr class="my-1"><h3 class="card-title mt-3">Límites de uso</h3><div class="text-secondary small mb-2">Deja vacío un límite para tratarlo como ilimitado.</div></div>
                        <div class="col-md-4"><label class="form-label">Usuarios</label><input type="number" min="1" name="max_users" class="form-control" value="{{ old('max_users', $plan->max_users) }}"></div>
                        <div class="col-md-4"><label class="form-label">Áreas</label><input type="number" min="1" name="max_areas" class="form-control" value="{{ old('max_areas', $plan->max_areas) }}"></div>
                        <div class="col-md-4"><label class="form-label">Firmas registradas</label><input type="number" min="1" name="max_signatures" class="form-control" value="{{ old('max_signatures', $plan->max_signatures) }}"></div>
                        <div class="col-md-4"><label class="form-label">Documentos</label><input type="number" min="1" name="max_documents" class="form-control" value="{{ old('max_documents', $plan->max_documents) }}"></div>
                        <div class="col-md-4"><label class="form-label">Flujos de aprobación</label><input type="number" min="1" name="max_workflows" class="form-control" value="{{ old('max_workflows', $plan->max_workflows) }}"></div>
                        <div class="col-md-4"><label class="form-label">Almacenamiento (MB)</label><input type="number" min="1" name="max_storage_mb" class="form-control" value="{{ old('max_storage_mb', $plan->max_storage_mb) }}"></div>

                        <div class="col-12"><label class="form-label">Capacidades incluidas</label><textarea name="features_text" class="form-control" rows="4" placeholder="Una capacidad por línea">{{ old('features_text', implode(PHP_EOL, $plan->features ?? [])) }}</textarea></div>
                        <div class="col-12"><label class="form-check form-switch"><input class="form-check-input" type="checkbox" name="active" value="1" @checked(old('active', $plan->exists ? $plan->active : true))><span class="form-check-label">Plan disponible para asignar</span></label></div>
                    </div>
                </div>
                <div class="card-footer text-end"><button class="btn {{ $plan->exists ? 'btn-warning' : 'btn-success' }}"><i class="ti ti-device-floppy me-1"></i>{{ $plan->exists ? 'Actualizar plan' : 'Crear plan' }}</button></div>
            </form>
        </div>
    </x-crud.index>
@endsection
