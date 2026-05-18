@extends('layouts.tenant.onboarding.app')

@section('content')
    <div class="text-center">
        <div class="mb-4">
            <span class="avatar avatar-xl bg-success-lt">
                <i class="ti ti-check fs-1"></i>
            </span>
        </div>
        <h1 class="mb-3">
            ¡Configuración completada!
        </h1>
        <div class="text-secondary mb-4">
            Tu sistema ya está listo para usarse
        </div>
        <a href="{{ route('tenant.dashboard') }}" class="btn btn-success btn-lg">
            Ir al Dashboard
        </a>
    </div>
@endsection
