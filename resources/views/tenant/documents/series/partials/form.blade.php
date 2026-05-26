<div class="modal-header">
    <h3 class="modal-title" id="{{ $prefix }}_modalTitle">
    </h3>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="row">
        {{-- TIPO DOCUMENTO --}}
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">
                    Tipo Documento
                </label>

                <select name="document_type_id" id="{{ $prefix }}_document_type_id" class="form-select" required>
                    <option value="">
                        Seleccionar
                    </option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" @selected(old('document_type_id', $series->document_type_id ?? '') == $type->id)>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ÁREA --}}
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">
                    Área
                </label>

                <select name="area_id" id="{{ $prefix }}_area_id" class="form-select">
                    <option value="">
                        Global (todas las áreas)
                    </option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}" @selected(old('area_id', $series->area_id ?? '') == $area->id)>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- PREFIJO --}}
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">
                    Prefijo
                </label>

                <input type="text" name="prefix" id="{{ $prefix }}_prefix" class="form-control"
                    placeholder="OFI" value="{{ old('prefix', $series->prefix ?? '') }}" required>
            </div>
        </div>

        {{-- NÚMERO ACTUAL --}}
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">
                    Número Actual
                </label>

                <input type="number" name="current_number" id="{{ $prefix }}_current_number"class="form-control"
                    min="0" value="{{ old('current_number', $series->current_number ?? 0) }}" required>
            </div>
        </div>

        {{-- PADDING --}}
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">
                    Padding
                </label>

                <input type="number" name="padding" id="{{ $prefix }}_padding" class="form-control"
                    min="1" max="15" value="{{ old('padding', $series->padding ?? 6) }}" required>
            </div>
        </div>

        {{-- PREVIEW --}}
        <div class="col-12">
            <div class="alert alert-info">
                Vista previa:
                <strong id="{{ $prefix }}_seriesPreview">
                    OFI-000001
                </strong>
            </div>
        </div>
    </div>

    {{-- OPTIONS --}}
    <div class="row">
        {{-- RESET YEARLY --}}
        <div class="col-md-6">
            <label class="form-check form-switch">
                <input type="checkbox" name="reset_yearly" id="{{ $prefix }}_reset_yearly"
                    class="form-check-input" checked>
                <span class="form-check-label">
                    Reiniciar cada año
                </span>
            </label>
        </div>

        {{-- ACTIVE --}}
        <div class="col-md-6">
            <label class="form-check form-switch">
                <input type="checkbox" name="active" id="{{ $prefix }}_active" class="form-check-input" checked>
                <span class="form-check-label">
                    Serie activa
                </span>
            </label>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Cancelar
    </button>
    <button class="btn btn-primary" id="{{ $prefix }}_submitButton">
        <i class="ti ti-device-floppy"></i>
    </button>
</div>
