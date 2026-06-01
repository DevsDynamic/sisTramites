@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title">
                Firmas Digitales
            </h1>
            <div class="text-secondary">
                Gestión de certificados y firmas visuales
            </div>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="ti ti-plus"></i>
            Nueva Firma
        </button>
    </div>

    @include('signatures.partials.cards')

    <div class="mt-4">
        {{ $signatures->links() }}
    </div>

    {{-- MODALS --}}
    @include('signatures.modals.create')
    @include('signatures.modals.edit')
    @include('signatures.modals.delete')
@endsection

@push('module-js')
    @vite('resources/js/modules/signatures.js')
@endpush