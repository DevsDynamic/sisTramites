@extends('layouts.tenant.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                Roles
            </h1>
            <div class="text-secondary">
                Gestión de permisos
            </div>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="ti ti-plus"></i>
            Nuevo Rol
        </button>
    </div>

    <div class="row row-cards">
        @foreach ($roles as $role)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-bold fs-3">
                                    {{ $role->name }}
                                </div>
                                <div class="text-secondary">
                                    {{ $role->permissions->count() }}
                                    permisos
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            @foreach ($role->permissions as $permission)
                                <span class="badge bg-primary-lt mb-1">
                                    {{ str($permission->name)->replace('.', ' ')->title() }}
                                </span>
                            @endforeach
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            {{-- EDIT --}}
                            <button class="btn btn-outline-primary btn-sm edit-btn" data-id="{{ $role->id }}"
                                data-name="{{ $role->name }}" data-permissions='@json($role->permissions->pluck('name'))'
                                data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="ti ti-edit"></i>
                            </button>
                            {{-- DELETE --}}
                            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $role->id }}"
                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @include('tenant.roles.modals.edit')
        @endforeach
    </div>

    @include('tenant.roles.modals.create')
    @include('tenant.roles.modals.edit')
    @include('tenant.roles.modals.delete')
@endsection

@push('scripts')
    @include('tenant.roles.scripts')
@endpush
