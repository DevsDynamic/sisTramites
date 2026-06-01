<div id="areasContainer" class="row row-cards">
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
                            <button class="btn btn-outline-primary btn-sm edit-btn" data-id="{{ $area->id }}"
                                data-name="{{ $area->name }}" data-code="{{ $area->code }}"
                                data-description="{{ $area->description }}" data-active="{{ $area->active }}"
                                data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="ti ti-edit"></i>
                            </button>

                            {{-- DELETE --}}
                            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $area->id }}"
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