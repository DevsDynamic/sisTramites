@extends('layouts.tenant.app')

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

    <div class="row row-cards">
        @forelse($series as $serie)
            <div class="col-md-4">
                <div class="card tenant-card h-100">
                    {{-- HEADER --}}
                    <div class="card-header">
                        <div>
                            <div class="fw-bold fs-3">
                                {{ $serie->documentType->name }}
                            </div>
                            <div class="text-secondary">
                                {{ $serie->area?->name ?? 'Global' }}
                            </div>
                        </div>
                        <span
                            class="badge
                        {{ $serie->active ? 'bg-success-lt' : 'bg-danger-lt' }}">
                            {{ $serie->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    {{-- BODY --}}
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="text-secondary small">
                                Prefijo
                            </div>
                            <div class="fw-bold fs-2">
                                {{ $serie->prefix }}
                            </div>
                        </div>
                        <div class="row text-center">
                            {{-- CURRENT --}}
                            <div class="col-4">
                                <div class="text-secondary small">
                                    Actual
                                </div>
                                <div class="fw-bold">
                                    {{ str_pad($serie->current_number, $serie->padding, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>
                            {{-- PADDING --}}
                            <div class="col-4">
                                <div class="text-secondary small">
                                    Padding
                                </div>
                                <div class="fw-bold">
                                    {{ $serie->padding }}
                                </div>
                            </div>
                            {{-- RESET --}}
                            <div class="col-4">
                                <div class="text-secondary small">
                                    Reset
                                </div>
                                <div class="fw-bold">
                                    {{ $serie->reset_yearly ? 'Sí' : 'No' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- FOOTER --}}
                    <div class="card-footer bg-transparent border-0">
                        <div class="d-flex gap-2">
                            {{-- EDIT --}}
                            <button class="btn btn-outline-primary btn-sm edit-btn" data-id="{{ $serie->id }}"
                                data-document_type_id="{{ $serie->document_type_id }}"
                                data-area_id="{{ $serie->area_id }}" data-prefix="{{ $serie->prefix }}"
                                data-current_number="{{ $serie->current_number }}" data-padding="{{ $serie->padding }}"
                                data-reset_yearly="{{ $serie->reset_yearly }}" data-active="{{ $serie->active }}"
                                data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="ti ti-edit"></i>
                            </button>
                            {{-- DELETE --}}
                            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $serie->id }}"
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
                        <i class="ti ti-number fs-1"></i>
                    </div>
                    <p class="empty-title">
                        No hay series configuradas
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $series->links() }}
    </div>

    {{-- MODALS --}}
    @include('tenant.documents.series.modals.create')
    @include('tenant.documents.series.modals.edit')
    @include('tenant.documents.series.modals.delete')
@endsection

@push('scripts')
    @include('tenant.documents.series.scripts')
@endpush
