<div class="modal-header">
    <h3 class="modal-title" id="{{ $prefix }}_modalTitle">
        Nueva Área
    </h3>
    <button type="button" class="btn-close" data-bs-dismiss="modal">
    </button>
</div>

<div class="modal-body">
    <div class="row">
        {{-- NOMBRE --}}
        <div class="col-md-8">
            <div class="mb-3">
                <label class="form-label">
                    Nombre del Área
                </label>
                <input type="text" name="name" id="{{ $prefix }}_name" class="form-control"
                    placeholder="Ej: Mesa de Partes" required>
            </div>
        </div>

        {{-- CÓDIGO --}}
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">
                    Código
                </label>
                <input type="text" name="code" id="{{ $prefix }}_code" class="form-control" placeholder="MP">
            </div>
        </div>
    </div>

    {{-- DESCRIPCIÓN --}}
    <div class="mb-3">
        <label class="form-label">
            Descripción
        </label>
        <textarea name="description" id="{{ $prefix }}_description" rows="4" class="form-control"
            placeholder="Descripción interna del área..."></textarea>
    </div>

    {{-- ESTADO --}}
    <label class="form-check form-switch">
        <input type="checkbox" name="active" id="{{ $prefix }}_active" class="form-check-input" checked>
        <span class="form-check-label">
            Área activa
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
