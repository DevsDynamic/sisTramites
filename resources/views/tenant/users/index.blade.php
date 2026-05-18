@extends('layouts.tenant.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                Usuarios
            </h1>
            <div class="text-secondary">
                Gestión de usuarios de usuarios de: {{ tenant('business_name') }}
            </div>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="ti ti-plus"></i>
            Nuevo Usuario
        </button>
    </div>

    <div class="row row-cards">
        @forelse($users as $user)
            <div class="col-md-4">
                {{-- <div class="card"> --}}
                <div class="card tenant-card">
                    <div class="card-header">
                        <div class="fw-bold fs-3">
                            {{ $user->name }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-fill">
                                <div class="text-secondary mt-1">
                                    <i class="ti ti-mail me-1"></i>
                                    {{ $user->email }}
                                </div>
                            </div>

                            {{-- STATUS --}}
                            <div class="ms-3">
                                @if ($user->isOnline())
                                    <span class="badge bg-green-lt">
                                        <span class="status-dot status-dot-animated bg-green me-1"></span>
                                        Online
                                    </span>
                                @else
                                    <span class="badge bg-secondary-lt">
                                        <i class="ti ti-clock-hour-4 me-1"></i>
                                        {{ $user->last_seen_at?->diffForHumans() }}
                                    </span>
                                @endif
                            </div>

                        </div>

                        {{-- ROLES --}}
                        <div class="border rounded-3 p-3 mb-3 bg-body-tertiary">
                            <div class="d-flex align-items-center mb-2">
                                {{-- <span class="badge bg-primary text-white me-2"> --}}
                                <span class="badge bg-primary-lt text-primary me-2 border border-primary-subtle">
                                    <i class="ti ti-shield-lock"></i>
                                </span>

                                <span class="fw-semibold text-primary">
                                    Roles
                                </span>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                @forelse ($user->roles as $role)
                                    <span class="badge bg-primary-lt text-primary border border-primary-subtle px-3 py-2">
                                        {{ str($role->name)->replace('_', ' ')->title() }}
                                    </span>
                                @empty
                                    <span class="badge bg-secondary-lt">
                                        Sin roles
                                    </span>
                                @endforelse
                            </div>

                        </div>

                        {{-- AREAS --}}
                        <div class="border rounded-3 p-3 bg-body-tertiary">
                            <div class="d-flex align-items-center mb-2">
                                {{-- <span class="badge bg-azure text-white me-2"> --}}
                                <span class="badge bg-azure-lt text-azure me-2 border border-azure-subtle">
                                    <i class="ti ti-building-community"></i>
                                </span>

                                <span class="fw-semibold text-azure">
                                    Áreas
                                </span>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                @forelse ($user->areas as $area)
                                    <span class="badge bg-azure-lt text-azure border border-azure-subtle px-3 py-2">
                                        {{ $area->name }}
                                    </span>
                                @empty
                                    <span class="badge bg-secondary-lt">
                                        Sin áreas
                                    </span>
                                @endforelse
                            </div>

                        </div>

                    </div>
                    {{-- <div class="card-footer"> --}}
                    <div class="card-footer bg-transparent border-0 pt-0">
                        {{-- ACTIONS --}}
                        <div class="mt-0 d-flex gap-2">
                            {{-- EDIT --}}
                            <button class="btn btn-outline-primary btn-sm edit-btn" data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                data-roles='@json($user->roles->pluck('name'))' data-areas='@json($user->areas->pluck('id'))'
                                data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="ti ti-edit"></i>
                            </button>
                            {{-- DELETE --}}
                            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $user->id }}"
                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty">
                    <div class="empty-icon">
                        <i class="ti ti-users fs-1"></i>
                    </div>
                    <p class="empty-title">
                        No hay usuarios registrados
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    @include('tenant.users.modals.create')
    @include('tenant.users.modals.edit')
    @include('tenant.users.modals.delete')

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>
@endsection

@push('scripts')
    @include('tenant.users.scripts')
@endpush
