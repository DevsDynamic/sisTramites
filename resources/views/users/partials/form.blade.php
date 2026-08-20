<x-crud.modal-header :title="$prefix === 'create' ? 'Nuevo usuario' : 'Editar usuario'" />

<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" id="{{ $prefix }}_name" class="form-control" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Correo</label>
                <input type="email" name="email" id="{{ $prefix }}_email" class="form-control" required>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label">Contraseña</label>
        <div class="input-group">
            <input
                type="password"
                name="password"
                id="{{ $prefix }}_password"
                class="form-control"
                autocomplete="new-password"
                @required($prefix === 'create')
            >
            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="{{ $prefix }}_password">
                <i class="ti ti-eye"></i>
            </button>
        </div>
        @if ($prefix === 'edit')
            <div class="form-hint">Déjala vacía para conservar la contraseña actual.</div>
        @endif
    </div>

    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <div class="fw-semibold">Roles</div>
                <div class="text-secondary small">Permisos asignados al usuario.</div>
            </div>
            <label class="form-check form-switch mb-0">
                <input type="checkbox" class="form-check-input select-all" data-target="roles">
                <span class="form-check-label">Todos</span>
            </label>
        </div>

        <div class="row">
            @foreach ($roles as $role)
                @if ($canManageAdmins || $role->name !== 'Administrador')
                    <div class="col-12 col-md-6 mb-2">
                        <label class="card card-sm cursor-pointer h-100 selectable-card overflow-hidden">
                            <div class="card-body py-2">
                                <div class="d-flex align-items-center" style="min-width: 0;">
                                    <input
                                        type="checkbox"
                                        name="roles[]"
                                        value="{{ $role->name }}"
                                        class="form-check-input me-2 roles-checkbox"
                                    >
                                    <span class="fw-semibold text-truncate" title="{{ $role->name }}">
                                        {{ $role->name }}
                                    </span>
                                </div>
                            </div>
                        </label>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <div class="fw-semibold">Áreas</div>
                <div class="text-secondary small">Áreas organizacionales a las que pertenece.</div>
            </div>
            <label class="form-check form-switch mb-0">
                <input type="checkbox" class="form-check-input select-all" data-target="areas">
                <span class="form-check-label">Todos</span>
            </label>
        </div>

        <div class="row">
            @foreach ($areas as $area)
                <div class="col-12 col-md-6 mb-2">
                    <label class="card card-sm cursor-pointer h-100 selectable-card overflow-hidden">
                        <div class="card-body py-2">
                            <div class="d-flex align-items-center" style="min-width: 0;">
                                <input
                                    type="checkbox"
                                    name="areas[]"
                                    value="{{ $area->id }}"
                                    class="form-check-input me-2 areas-checkbox"
                                >
                                <span class="fw-semibold text-truncate" title="{{ $area->name }}">
                                    {{ $area->name }}
                                </span>
                            </div>
                        </div>
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <label class="form-check form-switch">
        <input type="checkbox" name="active" id="{{ $prefix }}_active" value="1" class="form-check-input" checked>
        <span class="form-check-label">Activo</span>
    </label>
</div>

<x-crud.modal-footer
    :text="$prefix === 'create' ? 'Guardar' : 'Actualizar'"
    :icon="$prefix === 'create' ? 'ti ti-device-floppy' : 'ti ti-edit'"
    :color="$prefix === 'create' ? 'success' : 'warning'"
/>
