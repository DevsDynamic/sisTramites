@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                Series
            </h1>
            <div class="text-secondary">
                Lista de series por cada tipo de documento
            </div>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="ti ti-plus"></i>
            Nueva serie
        </button>
    </div>

    <form method="GET" class="mb-4">
        <div class="input-icon">
            <span class="input-icon-addon">
                <i class="ti ti-search"></i>
            </span>

            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                placeholder="Buscar serie...">
        </div>
    </form>

    @include('document-series.partials.cards')

    <div class="mt-4">
        {{ $series->links() }}
    </div>

    {{-- MODALS --}}
    @include('document-series.modals.create')
    @include('document-series.modals.edit')
    @include('document-series.modals.delete')
@endsection

@push('module-js')
    @vite('resources/js/modules/document-series.js')
@endpush
