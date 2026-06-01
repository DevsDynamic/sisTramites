@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                Áreas
            </h1>
            <div class="text-secondary">
                Gestión organizacional
            </div>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="ti ti-plus"></i>
            Nueva Área
        </button>
    </div>

    @include('areas.partials.cards')

    <div class="mt-4">
        {{ $areas->links() }}
    </div>

    {{-- MODALS --}}
    @include('areas.modals.create')
    @include('areas.modals.edit')
    @include('areas.modals.delete')
@endsection

@push('module-js')
    @vite('resources/js/modules/areas.js')
@endpush
