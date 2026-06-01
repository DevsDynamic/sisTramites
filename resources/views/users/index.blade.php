@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                Usuarios
            </h1>
            <div class="text-secondary">
                Gestión de usuarios de usuarios de: 
            </div>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="ti ti-plus"></i>
            Nuevo Usuario
        </button>
    </div>

    @include('users.partials.cards')

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    {{-- MODALS --}}
    @include('users.modals.create')
    @include('users.modals.edit')
    @include('users.modals.delete')
@endsection

@push('module-js')
    @vite('resources/js/modules/users.js')
@endpush
