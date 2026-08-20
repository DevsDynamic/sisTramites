<x-crud.modal-header :title="$prefix === 'create' ? 'Nuevo rol' : 'Editar rol'" />

<div class="modal-body">
    <div class="mb-4">
        <label class="form-label">Nombre del rol</label>
        <input type="text" id="{{ $prefix }}_name" name="name" class="form-control" required>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-semibold">Permisos</div>
            <div class="text-secondary small">Define las acciones permitidas para este rol.</div>
        </div>
        <label class="form-check form-switch mb-0">
            <input type="checkbox" class="form-check-input select-all-permissions">
            <span class="form-check-label">Todos</span>
        </label>
    </div>

    @forelse ($permissions as $module => $modulePermissions)
        <div class="card mb-3 bg-body-tertiary permission-module">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="fw-semibold text-uppercase">
                        {{ \App\Models\Permission::moduleLabel($module) }}
                    </div>
                    <label class="form-check form-switch mb-0">
                        <input type="checkbox" class="form-check-input module-checkbox" data-module="{{ $module }}">
                        <span class="form-check-label">Todo</span>
                    </label>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    @foreach ($modulePermissions as $permission)
                        @php
                            $action = explode('.', $permission->name)[1] ?? $permission->name;
                        @endphp
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-check">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->name }}"
                                    class="form-check-input permission-checkbox permission-module-{{ $module }}"
                                >
                                <span class="form-check-label">{{ \App\Models\Permission::actionLabel($action) }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @empty
        <div class="text-secondary">No hay permisos disponibles para asignar.</div>
    @endforelse
</div>

<x-crud.modal-footer
    :text="$prefix === 'create' ? 'Guardar' : 'Actualizar'"
    :icon="$prefix === 'create' ? 'ti ti-device-floppy' : 'ti ti-edit'"
    :color="$prefix === 'create' ? 'primary' : 'warning'"
/>
