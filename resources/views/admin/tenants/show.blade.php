@extends('layouts.admin.app')

@section('title', $tenant->business_name)

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div class="d-flex align-items-center gap-3">

            {{-- LOGO --}}
            <div>

                @if (isset($tenant->settings['branding']['logo']))
                    <img src="{{ asset('storage/' . ($tenant->settings['branding']['logo'] ?? 'default/logo.png')) }}"
                        class="avatar avatar-xl rounded">
                @else
                    <div class="rounded-4 d-flex align-items-center justify-content-center text-white fw-bold"
                        style="
                        width:70px;
                        height:70px;
                        background: {{ data_get($tenant->settings, 'primary_color', '#206bc4') }};
                        font-size:28px;
                    ">

                        {{ strtoupper(substr($tenant->business_name, 0, 1)) }}

                    </div>
                @endif

            </div>

            {{-- INFO --}}
            <div>

                <h1 class="page-title mb-1">
                    {{ $tenant->business_name }}
                </h1>

                <div class="text-secondary">

                    {{ $tenant->domains->first()?->domain }}

                </div>

            </div>

        </div>

        {{-- ACTIONS --}}
        <div class="d-flex gap-2">

            <a href="http://{{ $tenant->domains->first()?->domain }}/login" target="_blank" class="btn btn-success">

                <i class="ti ti-external-link"></i>
                Ingresar
            </a>

            <a href="{{ route('tenants.edit', $tenant) }}" class="btn btn-primary">

                <i class="ti ti-edit"></i>
                Editar
            </a>

        </div>

    </div>

    {{-- STATS --}}
    <div class="row row-cards mb-4">

        {{-- PLAN --}}
        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-secondary mb-2">
                        Plan
                    </div>

                    <div class="fs-2 fw-bold">
                        {{ $tenant->plan?->name }} - S/ {{ $tenant->plan?->price }}
                    </div>

                </div>

            </div>

        </div>

        {{-- STATUS --}}
        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-secondary mb-2">
                        Estado
                    </div>

                    @if ($tenant->status === 'active')
                        <span class="badge bg-green-lt fs-4">

                            <span class="status-dot status-dot-animated bg-green me-1"></span>

                            Activo

                        </span>
                    @elseif($tenant->status === 'expired')
                        <span class="badge bg-red-lt fs-4">

                            Expirado

                        </span>
                    @endif

                </div>

            </div>

        </div>

        {{-- EXPIRATION --}}
        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-secondary mb-2">
                        Expira
                    </div>

                    <div class="fw-bold fs-3">

                        {{ optional($tenant->expires_at)->format('d/m/Y') }}

                    </div>

                </div>

            </div>

        </div>

        {{-- CONTACT --}}
        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <div class="text-secondary mb-2">
                        Contacto
                    </div>

                    <div class="fw-semibold">
                        {{ $tenant->email }}
                    </div>

                    <div class="text-secondary small">
                        {{ $tenant->phone }}
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- BRANDING --}}
    <div class="row">

        {{-- COLORS --}}
        <div class="col-lg-6">

            <div class="card mb-4">

                <div class="card-header">
                    <h3 class="card-title">
                        Branding
                    </h3>
                </div>

                <div class="card-body">

                    {{-- PRIMARY --}}
                    <div class="mb-4">

                        <div class="text-secondary mb-2">
                            Color Primario
                        </div>

                        <div class="d-flex align-items-center gap-3">

                            <div class="rounded-circle border"
                                style="
                                width:40px;
                                height:40px;
                                background:
                                {{ $tenant->settings['branding']['primary_color'] ?? '#206bc4' }};
                            ">
                            </div>

                            <code>
                                {{ $tenant->settings['branding']['primary_color'] ?? '#206bc4' }}
                            </code>
                        </div>

                    </div>

                    {{-- SIDEBAR --}}
                    <div>

                        <div class="text-secondary mb-2">
                            Sidebar
                        </div>

                        <div class="d-flex align-items-center gap-3">

                            <div class="rounded-circle border"
                                style="
                                width:40px;
                                height:40px;
                                background:
                                {{ $tenant->settings['branding']['sidebar_color'] ?? '#111827' }};
                            ">
                            </div>

                            <code>
                                {{ $tenant->settings['branding']['sidebar_color'] ?? '#111827' }}
                            </code>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- COMPANY --}}
        <div class="col-lg-6">

            <div class="card mb-4">

                <div class="card-header">
                    <h3 class="card-title">
                        Empresa
                    </h3>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <div class="text-secondary small">
                            Razón Social
                        </div>
                        <div class="fw-semibold">
                            {{ $tenant->business_name }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-secondary small">
                            Nombre comercial
                        </div>
                        <div class="fw-semibold">
                            {{ $tenant->trade_name }}
                        </div>
                    </div>

                    <div class="mb-3">

                        <div class="text-secondary small">
                            RUC
                        </div>

                        <div class="fw-semibold">
                            {{ $tenant->ruc }}
                        </div>

                    </div>

                    <div>

                        <div class="text-secondary small">
                            Dominio
                        </div>

                        <div class="fw-semibold">
                            {{ $tenant->domains->first()?->domain }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- DELETE --}}
    <div class="card border-danger">

        <div class="card-body d-flex justify-content-between align-items-center">

            <div>

                <div class="fw-bold text-danger">
                    Zona peligrosa
                </div>

                <div class="text-secondary">
                    Esta acción puede eliminar el tenant permanentemente.
                </div>

            </div>

            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">

                <i class="ti ti-trash"></i>
                Eliminar Cliente

            </button>

        </div>

    </div>

    @include('admin.tenants.modals.delete')

@endsection
