<x-crud.modal-header :title="$prefix == 'create' ? 'Nueva serie documental' : 'Editar serie documental'" />

<div class="modal-body">
    <div class="row">
        {{-- TIPO DOCUMENTO --}}
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Tipo de documento</label>

                <select name="document_type_id" id="{{ $prefix }}_document_type_id" class="form-select" required>
                    <option value="">Seleccionar</option>

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
                <label class="form-label">Área</label>

                <select name="area_id" id="{{ $prefix }}_area_id" class="form-select">
                    <option value="">Global (todas las áreas)</option>

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
                <label class="form-label">Prefijo</label>

                <input type="text" name="prefix" id="{{ $prefix }}_prefix" class="form-control"
                    placeholder="OFI" value="{{ old('prefix', $series->prefix ?? '') }}" required>
            </div>
        </div>

        {{-- NÚMERO ACTUAL --}}
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">Número actual</label>

                <input type="number" name="current_number" id="{{ $prefix }}_current_number" class="form-control"
                    min="0" value="{{ old('current_number', $series->current_number ?? 0) }}" required>
            </div>
        </div>

        {{-- PADDING --}}
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">Padding</label>

                <input type="number" name="padding" id="{{ $prefix }}_padding" class="form-control"
                    min="1" max="15" value="{{ old('padding', $series->padding ?? 6) }}" required>
            </div>
        </div>

        {{-- VISTA PREVIA --}}
        <div class="col-12">
            <div class="alert alert-info mb-3">
                Vista previa:
                <strong id="{{ $prefix }}_seriesPreview">
                    OFI-000001
                </strong>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- REINICIO ANUAL --}}
        <div class="col-md-6">
            <label class="form-check form-switch">
                <input type="checkbox" name="reset_yearly" id="{{ $prefix }}_reset_yearly"
                    class="form-check-input">
                <span class="form-check-label">Reiniciar cada año</span>
            </label>
        </div>

        {{-- ESTADO --}}
        <div class="col-md-6">
            <label class="form-check form-switch">
                <input type="checkbox" name="active" id="{{ $prefix }}_active" class="form-check-input" checked>
                <span class="form-check-label">Serie activa</span>
            </label>
        </div>
    </div>
</div>

<x-crud.modal-footer :text="$prefix == 'create' ? 'Guardar' : 'Actualizar'" 
                    :icon="$prefix == 'create' ? 'ti ti-device-floppy' : 'ti ti-edit'" 
                    :color="$prefix == 'create' ? 'success' : 'warning'"/>
