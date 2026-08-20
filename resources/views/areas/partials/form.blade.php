<x-crud.modal-header :title="$prefix === 'create' ? 'Nueva área' : 'Editar área'" />

<div class="modal-body">
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label class="form-label">Nombre del área</label>
                <input type="text" name="name" id="{{ $prefix }}_name" class="form-control"
                    placeholder="Ej.: Mesa de Partes" required>
            </div>
        </div>

        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">Código</label>
                <input type="text" name="code" id="{{ $prefix }}_code" class="form-control"
                    placeholder="AREA-001">
                <div class="form-hint">Opcional. Se generará automáticamente si se deja vacío.</div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="description" id="{{ $prefix }}_description" rows="4" class="form-control"
            placeholder="Descripción interna del área..."></textarea>
    </div>

    <label class="form-check form-switch">
        <input type="checkbox" name="active" value="1" id="{{ $prefix }}_active" class="form-check-input" checked>
        <span class="form-check-label">Área activa</span>
    </label>
</div>

<x-crud.modal-footer
    :text="$prefix === 'create' ? 'Guardar' : 'Actualizar'"
    :icon="$prefix === 'create' ? 'ti ti-device-floppy' : 'ti ti-edit'"
    :color="$prefix === 'create' ? 'success' : 'warning'"
/>
