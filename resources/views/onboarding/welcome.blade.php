@extends('layouts.app')

@section('content')
    <x-crud.index>
        <x-slot:header>
            <x-crud.header title="Configuración general" description="Administra la información e identidad visual de {{ $settings->company_name ?: config('app.name') }}" />
        </x-slot:header>

        <div class="row row-cards">
            <x-crud.card :stretch="false">
                <x-slot:header>
                    <x-crud.card-header title="Información de la empresa" subtitle="Datos de contacto y presencia institucional">
                        <x-slot:badge>
                            <span class="badge bg-azure-lt"><i class="ti ti-building me-1"></i>Empresa</span>
                        </x-slot:badge>
                    </x-crud.card-header>
                </x-slot:header>

                <div class="d-flex align-items-center gap-3">
                    <span class="avatar avatar-lg bg-azure-lt">
                        <i class="ti ti-building fs-2"></i>
                    </span>
                    <div class="min-w-0">
                        <div class="fw-semibold text-truncate">
                            {{ $settings->company_name ?: 'Empresa sin configurar' }}
                        </div>
                        <div class="text-secondary small text-truncate">
                            {{ $settings->email ?: 'Sin correo institucional registrado' }}
                        </div>
                    </div>
                </div>

                <x-slot:footer>
                    <a href="{{ route('onboarding.company') }}" class="btn btn-primary">
                        <i class="ti ti-pencil me-1"></i>Editar información
                    </a>
                </x-slot:footer>
            </x-crud.card>

            <x-crud.card :stretch="false">
                <x-slot:header>
                    <x-crud.card-header title="Identidad visual" subtitle="Logo, favicon, fondo de acceso y colores">
                        <x-slot:badge>
                            <span class="badge bg-purple-lt"><i class="ti ti-palette me-1"></i>Diseño</span>
                        </x-slot:badge>
                    </x-crud.card-header>
                </x-slot:header>

                <div class="d-flex align-items-center gap-3">
                    @if ($settings->logo)
                        <span class="avatar avatar-lg bg-white border settings-logo-avatar">
                            <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo de la empresa">
                        </span>
                    @else
                        <span class="avatar avatar-lg bg-purple-lt">
                            <i class="ti ti-color-swatch fs-2"></i>
                        </span>
                    @endif
                    <div class="text-secondary">
                        Logo, favicon, fondo de acceso y colores del sistema.
                    </div>
                </div>

                <x-slot:footer>
                    <a href="{{ route('onboarding.branding') }}" class="btn btn-primary">
                        <i class="ti ti-palette me-1"></i>Editar identidad visual
                    </a>
                </x-slot:footer>
            </x-crud.card>

            @if(auth()->user()->isSystemOwner())
                <x-crud.card :stretch="false">
                    <x-slot:header><x-crud.card-header title="Licencia y plan" subtitle="Control de la instalación"><x-slot:badge><span class="badge bg-orange-lt"><i class="ti ti-key me-1"></i>Propietario</span></x-slot:badge></x-crud.card-header></x-slot:header>
                    <div><div class="fw-semibold">{{ $settings->plan?->name ?? $settings->plan_name }}</div><div class="text-secondary small">{{ $settings->license_expires_at ? 'Vence: ' . $settings->license_expires_at->format('d/m/Y') : 'Sin vencimiento configurado' }}</div></div>
                    <x-slot:footer><a href="{{ route('onboarding.license') }}" class="btn btn-primary"><i class="ti ti-pencil me-1"></i>Administrar licencia</a></x-slot:footer>
                </x-crud.card>
            @endif
        </div>
    </x-crud.index>
@endsection
