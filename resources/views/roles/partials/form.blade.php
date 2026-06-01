<div class="modal-header">
    <h3 class="modal-title">
        {{ isset($role) ? 'Editar Rol' : 'Nuevo Rol' }}
    </h3>
</div>

<div class="modal-body">
    <div class="mb-4">
        <label class="form-label">
            Nombre
        </label>
        <input type="text" id="{{ $prefix }}_name" name="name" class="form-control" required>
    </div>

    {{-- <div class="mb-3">
        <label class="form-label">
            Permisos
        </label>
        <div class="row">
            @foreach ($permissions as $permission)
                <div class="col-md-4 mb-2">
                    <label class="form-check">
                        <input type="checkbox" id="{{ $prefix }}_permission_{{ $permission->id }}"
                            name="permissions[]" value="{{ $permission->name }}" class="form-check-input">
                        <span class="form-check-label">
                            {{ str($permission->name)->replace('.', ' ')->title() }}
                        </span>
                    </label>
                </div>
            @endforeach
        </div>
    </div> --}}

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <label class="form-label mb-0">
                Permisos
            </label>
            <label class="form-check m-0">
                <input type="checkbox" class="form-check-input select-all-permissions">
                <span class="form-check-label fw-bold">
                    Seleccionar todos
                </span>
            </label>
        </div>
        @foreach ($permissions as $module => $modulePermissions)
            <div class="card mb-3 border-0 bg-light">
                <div class="card-body">
                    {{-- HEADER --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0 text-uppercase text-primary">
                            {{ str($module)->replace('_', ' ') }}
                        </h4>
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input module-checkbox"
                                data-module="{{ $module }}">
                            <span class="form-check-label">
                                Todo
                            </span>
                        </label>
                    </div>

                    {{-- PERMISSIONS --}}
                    <div class="row">
                        @foreach ($modulePermissions as $permission)
                            @php
                                $parts = explode('.', $permission->name);
                                $action = $parts[1] ?? $permission->name;
                            @endphp
                            <div class="col-md-4 mb-2">
                                <label class="form-check">
                                    <input type="checkbox" id="{{ $prefix }}_permission_{{ $permission->id }}"
                                        name="permissions[]" value="{{ $permission->name }}"
                                        class="form-check-input permission-checkbox permission-module-{{ $module }}">
                                    <span class="form-check-label">
                                        {{ str($action)->replace('_', ' ')->title() }}
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Cancelar
    </button>
    <button class="btn btn-primary">
        {{ isset($role) ? 'Actualizar' : 'Guardar' }}
    </button>
</div>
