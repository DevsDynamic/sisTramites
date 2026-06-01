@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                Tipos de documento
            </h1>
            <div class="text-secondary">
                Áreas disponibles de la empresa
            </div>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="ti ti-plus"></i>
            Nuevo tipo de área
        </button>
    </div>

    @include('document-types.partials.cards')

    <div class="mt-4">
        {{ $types->links() }}
    </div>

    @include('document-types.modals.create')
    @include('document-types.modals.edit')
    @include('document-types.modals.delete')
@endsection

@push('module-js')
    @vite('resources/js/modules/document-types.js')
@endpush
