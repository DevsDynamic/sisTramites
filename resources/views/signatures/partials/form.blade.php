<x-crud.modal-header :title="$prefix === 'create' ? 'Nueva firma' : 'Editar firma'" />

<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Usuario</label>

                @if ($canManageAll)
                    <select name="user_id" id="{{ $prefix }}_user_id" class="form-select" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="hidden" name="user_id" id="{{ $prefix }}_user_id" value="{{ auth()->id() }}">
                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                    <div class="form-hint">La firma se registrará a tu nombre.</div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Tipo de firma</label>

                <select name="type" id="{{ $prefix }}_type" class="form-select" required>
                    <option value="official">Firma oficial (.PFX)</option>
                    <option value="visual">Firma visual</option>
                </select>
            </div>
        </div>
    </div>

    <div id="{{ $prefix }}_officialFields">
        <div class="alert alert-info">
            <i class="ti ti-certificate me-1"></i>
            La firma oficial usa un certificado digital PFX y tiene validez legal.
        </div>

        <div class="mb-3">
            <label class="form-label">Certificado PFX</label>
            <input type="file" name="pfx_file" id="{{ $prefix }}_pfx_file" class="form-control" accept=".pfx">
            @if ($prefix === 'edit')
                <div class="form-hint">Déjalo vacío para conservar el certificado actual.</div>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña del certificado</label>
            <div class="input-group">
                <input type="password" name="pfx_password" id="{{ $prefix }}_pfx_password" class="form-control" autocomplete="new-password">
                <button class="btn btn-outline-secondary" type="button" data-password-toggle="#{{ $prefix }}_pfx_password" aria-label="Mostrar contraseña">
                    <i class="ti ti-eye"></i>
                </button>
            </div>
            <label class="form-check mt-2">
                <input type="checkbox" name="remember_certificate_password" id="{{ $prefix }}_remember_certificate_password" value="1" class="form-check-input">
                <span class="form-check-label">Recordar la contraseña para firmar</span>
            </label>
            <div class="form-hint">
                Es opcional. Se cifra en el servidor y nunca se muestra; desmárcalo al editar para eliminarla.
            </div>
        </div>
    </div>

    <div id="{{ $prefix }}_visualFields">
        <div class="alert alert-warning">
            <i class="ti ti-info-circle me-1"></i>
            Esta firma es visual y no tiene valor legal por sí sola.
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen de firma</label>
            <input type="file" name="signature_image" id="{{ $prefix }}_signature_image" class="form-control" accept="image/png,image/jpeg">
            <div class="form-hint">Formatos permitidos: PNG o JPG. Tamaño máximo: 2 MB.</div>

            <img
                id="{{ $prefix }}_signaturePreview"
                class="img-thumbnail mt-3 d-none"
                alt="Vista previa de la firma"
                style="max-height: 120px; object-fit: contain;"
            >
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
