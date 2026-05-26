<div class="modal-header">
    <h3 class="modal-title" id="{{ $prefix }}_modalTitle">
        Nuevo tipo de documento
    </h3>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="row">
        {{-- NOMBRE --}}
        <div class="col-md-8">
            <div class="mb-3">
                <label class="form-label">
                    Tipo de documento
                </label>
                <input type="text" name="name" id="{{ $prefix }}_name" class="form-control"
                    placeholder="Ej: PDF" required>
            </div>
        </div>

        {{-- CÓDIGO --}}
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">
                    Código
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
            Tipo de documento activa
        </span>
    </label>
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