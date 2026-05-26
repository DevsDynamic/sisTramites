@extends('layouts.tenant.app')

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

    <div class="row row-cards">
        @forelse($types as $type)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-bold fs-3">
                                    {{ $type->name }}
                                </div>
                                <div class="text-secondary">
                                    {{ $type->code }}
                                </div>
                            </div>
                            <span
                                class="badge
                            {{ $type->active ? 'bg-success-lt' : 'bg-danger-lt' }}">
                                {{ $type->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            {{-- EDIT --}}
                            <button class="btn btn-outline-primary btn-sm edit-btn" data-id="{{ $type->id }}"
                                data-name="{{ $type->name }}" data-code="{{ $type->code }}"
                                data-active="{{ $type->active }}" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="ti ti-edit"></i>
                            </button>

                            {{-- DELETE --}}
                            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $type->id }}"
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
                        <i class="ti ti-file-settings fs-1"></i>
                    </div>
                    <p class="empty-title">
                        No hay tipos de documentos registrados
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    @include('tenant.document-types.modals.create')
    @include('tenant.document-types.modals.edit')
    @include('tenant.document-types.modals.delete')

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $types->links() }}
    </div>
@endsection

@push('scripts')
    @include('tenant.document-types.scripts')
@endpush
