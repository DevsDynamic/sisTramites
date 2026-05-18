@extends('layouts.tenant.app')
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

    <div class="row row-cards">
        @forelse($areas as $area)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-bold fs-3">
                                    {{ $area->name }}
                                </div>
                                <div class="text-secondary">
                                    {{ $area->code }}
                                </div>
                            </div>
                            <span
                                class="badge
                            {{ $area->active ? 'bg-success-lt' : 'bg-danger-lt' }}">
                                {{ $area->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <div class="mt-3 text-secondary">
                            {{ $area->description }}
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            {{-- EDIT --}}
                            <button class="btn btn-outline-primary btn-sm edit-area-btn" data-id="{{ $area->id }}"
                                data-name="{{ $area->name }}" data-code="{{ $area->code }}"
                                data-description="{{ $area->description }}" data-active="{{ $area->active }}"
                                data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="ti ti-edit"></i>
                            </button>

                            {{-- DELETE --}}
                            <button class="btn btn-outline-danger btn-sm delete-area-btn" data-id="{{ $area->id }}"
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
                        <i class="ti ti-building fs-1"></i>
                    </div>
                    <p class="empty-title">
                        No hay áreas
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- MODALS --}}
    @include('tenant.areas.modals.create')
    @include('tenant.areas.modals.edit')
    @include('tenant.areas.modals.delete')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /* RESET CREATE */
            const createModal =
                document.getElementById('createModal');
            createModal.addEventListener(
                'hidden.bs.modal',
                function() {
                    document.getElementById('createForm').reset();
                }
            );

            /* EDIT */
            document
                .querySelectorAll('.edit-area-btn')
                .forEach(button => {
                    button.addEventListener('click', function() {
                        document.getElementById('edit_modalTitle')
                            .innerText = 'Editar Área';
                        document.getElementById('edit_submitButton')
                            .innerText = 'Actualizar';
                        document.getElementById('edit_name')
                            .value = this.dataset.name;
                        document.getElementById('edit_code')
                            .value = this.dataset.code;
                        document.getElementById('edit_description')
                            .value = this.dataset.description;
                        document.getElementById('edit_active')
                            .checked = this.dataset.active == 1;
                        document
                            .getElementById('editForm')
                            .action =
                            `/areas/${this.dataset.id}`;
                    });

                });

            /* DELETE */
            document
                .querySelectorAll('.delete-area-btn')
                .forEach(button => {
                    button.addEventListener('click', function() {
                        document
                            .getElementById('deleteForm')
                            .action =
                            `/areas/${this.dataset.id}`;
                    });
                });
        });
    </script>
@endpush
