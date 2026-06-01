@extends('layouts.app')
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

    @include('roles.partials.cards')

    @include('roles.modals.create')
    @include('roles.modals.edit')
    @include('roles.modals.delete')
@endsection

@push('module-js')
    @vite('resources/js/modules/roles.js')
@endpush