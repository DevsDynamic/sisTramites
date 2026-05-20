{{-- @extends('layouts.admin.app')

@section('title', 'Clientes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title">
                Clientes
            </h2>
        </div>
        <div>
            <a href="{{ route('tenants.create') }}" class="btn btn-primary">
                <i class="ti ti-plus"></i>
                Nuevo Cliente
            </a>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>RUC</th>
                        <th>Plan</th>
                        <th>Estado</th>
                        <th>Expira</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tenants as $tenant)
                        <tr>
                            <td>
                                <div class="fw-bold">
                                    {{ $tenant->business_name }}
                                </div>
                                <div class="text-secondary">
                                    {{ $tenant->email }}
                                </div>
                            </td>
                            <td>
                                {{ $tenant->ruc }}
                            </td>
                            <td>
                                <span class="badge bg-blue-lt">
                                    {{ $tenant->plan?->name }}
                                </span>
                            </td>
                            <td>
                                @if ($tenant->status == 'active')
                                    <span class="badge bg-green">
                                        Activo
                                    </span>
                                @elseif($tenant->status == 'expired')
                                    <span class="badge bg-red">
                                        Expirado
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{ optional($tenant->expires_at)->format('d/m/Y') }}
                            </td>
                            <td>
                                <a href="{{ route('tenants.show', $tenant) }}" class="btn btn-sm btn-outline-primary">
                                    Ver
                                </a>
                                <a href="{{ route('tenants.edit', $tenant) }}" class="btn btn-sm btn-outline-warning">
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('tenants.destroy', $tenant) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('¿Eliminar cliente?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection --}}

@extends('layouts.admin.app')

@section('title', 'Clientes')

@section('content')

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">
                Clientes
            </h1>

            <div class="text-secondary">
                Gestión de clientes multi tenant
            </div>
        </div>

        <a href="{{ route('tenants.create') }}" class="btn btn-primary">

            <i class="ti ti-plus"></i>
            Nuevo Cliente
        </a>

    </div>

    {{-- CARD --}}
    <div class="card tenant-card">

        <div class="table-responsive">

            <table class="table table-vcenter align-middle card-table">

                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Dominio</th>
                        <th>Plan</th>
                        <th>Estado</th>
                        <th>Expiración</th>
                        <th class="text-end">
                            Acciones
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($tenants as $tenant)
                        <tr>

                            {{-- CLIENT --}}
                            <td width="320">

                                <div class="d-flex align-items-start gap-3">

                                    {{-- LOGO --}}
                                    <div>

                                        <div class="avatar avatar-lg rounded bg-primary-lt">

                                            <i class="ti ti-building-store fs-2 text-primary"></i>

                                        </div>

                                    </div>

                                    {{-- INFO --}}
                                    <div class="flex-fill">

                                        <div class="fw-bold fs-4">
                                            {{ $tenant->business_name }}
                                        </div>

                                        <div class="text-secondary small mt-1">
                                            <i class="ti ti-id me-1"></i>
                                            {{ $tenant->ruc }}
                                        </div>

                                        <div class="text-secondary small">
                                            <i class="ti ti-mail me-1"></i>
                                            {{ $tenant->email }}
                                        </div>

                                        @if ($tenant->phone)
                                            <div class="text-secondary small">
                                                <i class="ti ti-phone me-1"></i>
                                                {{ $tenant->phone }}
                                            </div>
                                        @endif

                                    </div>

                                </div>

                            </td>

                            {{-- DOMAIN --}}
                            <td>

                                @php
                                    $domain = optional($tenant->domains->first())->domain;
                                @endphp

                                @if ($domain)
                                    <div class="d-flex align-items-center gap-2">

                                    </div>
                                    <a href="http://{{ $tenant->domains->first()?->domain }}/login" target="_blank">
                                        <span class="badge bg-azure-lt text-azure border border-azure-subtle px-3 py-2">
                                            <i class="ti ti-world me-1"></i>
                                            {{ $domain }}
                                        </span>
                                    </a>
                                @else
                                    <span class="badge bg-secondary-lt">
                                        Sin dominio
                                    </span>
                                @endif

                            </td>

                            {{-- PLAN --}}
                            <td>

                                <span class="badge bg-primary-lt text-primary border border-primary-subtle px-3 py-2">

                                    <i class="ti ti-package me-1"></i>

                                    {{ $tenant->plan?->name ?? 'Sin plan' }}

                                </span>

                            </td>

                            {{-- STATUS --}}
                            <td>

                                @if ($tenant->status === 'active')
                                    <span class="badge bg-green-lt text-green border border-green-subtle px-3 py-2">

                                        <span class="status-dot status-dot-animated bg-green me-1"></span>

                                        Activo

                                    </span>
                                @elseif($tenant->status === 'expired')
                                    <span class="badge bg-red-lt text-red border border-red-subtle px-3 py-2">

                                        <i class="ti ti-alert-circle me-1"></i>

                                        Expirado

                                    </span>
                                @else
                                    <span class="badge bg-secondary-lt">

                                        {{ ucfirst($tenant->status) }}

                                    </span>
                                @endif

                            </td>

                            {{-- EXPIRATION --}}
                            <td>

                                @if ($tenant->expires_at)
                                    <div class="fw-semibold">
                                        {{ $tenant->expires_at->format('d/m/Y') }}
                                    </div>

                                    <div class="text-secondary small">
                                        {{ $tenant->expires_at->diffForHumans() }}
                                    </div>
                                @else
                                    <span class="badge bg-secondary-lt">
                                        Sin expiración
                                    </span>
                                @endif

                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-end">

                                <div class="d-flex justify-content-end gap-2">

                                    {{-- VIEW --}}
                                    <a href="{{ route('tenants.show', $tenant) }}"
                                        class="btn btn-icon btn-outline-primary">

                                        <i class="ti ti-eye"></i>

                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('tenants.edit', $tenant) }}"
                                        class="btn btn-icon btn-outline-warning">

                                        <i class="ti ti-edit"></i>

                                    </a>
                                    {{-- DELETE --}}
                                    <form method="POST" action="{{ route('tenants.destroy', $tenant) }}">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-icon btn-outline-danger"
                                            onclick="return confirm('¿Eliminar cliente?')">

                                            <i class="ti ti-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6">

                                <div class="empty py-5">

                                    <div class="empty-icon">
                                        <i class="ti ti-building-store fs-1"></i>
                                    </div>

                                    <p class="empty-title">
                                        No hay clientes registrados
                                    </p>

                                </div>

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection
