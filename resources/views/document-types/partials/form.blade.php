<x-crud.modal-header :title="$prefix == 'create' ? 'Nuevo tipo de documento' : 'Editar tipo de documento'" />

<div class="modal-body">
    <div class="row">
        {{-- NOMBRE --}}
        <div class="col-md-8">
            <div class="mb-3">
                <label class="form-label">
                    Tipo de documento
                </label>
                <input type="text" name="name" id="{{ $prefix }}_name" class="form-control" placeholder="Ej: PDF"
                    required>
            </div>
        </div>

        {{-- CÓDIGO --}}
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">
                    Código
                    <i class="ti ti-info-circle text-secondary" data-bs-toggle-tooltip="tooltip" data-bs-placement="top"
                        title="Opcional. Si lo dejas vacío, el sistema generará un código automáticamente.">
                    </i>
                </label>
                <input type="text" name="code" id="{{ $prefix }}_code" class="form-control"
                    placeholder="TD0001">
            </div>
        </div>
    </div>

    {{-- ESTADO --}}
    <label class="form-check form-switch">
        <input type="checkbox" name="active" id="{{ $prefix }}_active" class="form-check-input" checked>
        <span class="form-check-label">
            Activo
        </span>
    </label>
</div>

<x-crud.modal-footer :text="$prefix == 'create' ? 'Guardar' : 'Actualizar'" 
                    :icon="$prefix == 'create' ? 'ti ti-device-floppy' : 'ti ti-edit'" 
                    :color="$prefix == 'create' ? 'success' : 'warning'"/>
