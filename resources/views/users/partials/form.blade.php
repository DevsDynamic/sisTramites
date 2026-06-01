<div class="modal-header">
    <h3 class="modal-title" id="{{ $prefix }}_modalTitle">
        Nuevo Usuario
    </h3>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="row">
        {{-- NAME --}}
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">
                    Nombre
                </label>

                <input type="text" name="name" id="{{ $prefix }}_name" class="form-control" required>
            </div>
        </div>

        {{-- EMAIL --}}
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">
                    Correo
                </label>
                <input type="email" name="email" id="{{ $prefix }}_email" class="form-control" required>
            </div>
        </div>
    </div>

    {{-- PASSWORD --}}
    <div class="mb-4">
        <label class="form-label">
            Contraseña
        </label>
        <div class="input-group">
            <input type="password" name="password" id="{{ $prefix }}_password" class="form-control">
            <button type="button" class="btn btn-outline-secondary toggle-password"
                data-target="{{ $prefix }}_password">
                <i class="ti ti-eye"></i>
            </button>
        </div>
        <small class="text-secondary">
            En edición dejar vacío para mantener la contraseña actual.
        </small>
    </div>

    {{-- ROLES --}}
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-1">
                        Roles
                    </h3>
                    <div class="text-secondary small">
                        Permisos del usuario
                    </div>
                </div>
                <label class="form-check form-switch">
                    <input type="checkbox" class="form-check-input select-all" data-target="roles">
                    <span class="form-check-label">
                        Todos
                    </span>
                </label>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                @foreach ($roles as $role)
                    <div class="col-md-4 mb-3">
                        <label class="card card-sm cursor-pointer role-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                        class="form-check-input me-3 roles-checkbox">
                                    <div>
                                        <div class="fw-bold">
                                            {{ $role->name }}
                                        </div>
                                        <div class="text-secondary small">
                                            Rol del sistema
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- AREAS --}}
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0">
                        Áreas
                    </h3>
                </div>
                <label class="form-check form-switch">
                    <input type="checkbox" class="form-check-input select-all" data-target="areas">
                    <span class="form-check-label">
                        Todos
                    </span>
                </label>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                @foreach ($areas as $area)
                    <div class="col-md-4 mb-3">
                        <label class="card card-sm cursor-pointer area-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" name="areas[]" value="{{ $area->id }}"
                                        class="form-check-input me-3 areas-checkbox">
                                    <div>
                                        <div class="fw-bold">
                                            {{ $area->name }}
                                        </div>
                                        <div class="text-secondary small">
                                            Área organizacional
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Cancelar
    </button>
    <button class="btn btn-primary" id="{{ $prefix }}_submitButton">
        <i class="ti ti-device-floppy"></i>
        Guardar
    </button>
</div>
