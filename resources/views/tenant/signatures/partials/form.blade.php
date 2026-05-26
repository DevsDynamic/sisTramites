<div class="modal-header">
    <h3 class="modal-title" id="{{ $prefix }}_modalTitle">
    </h3>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">
                    Usuario
                </label>

                <select name="user_id" id="{{ $prefix }}_user_id" class="form-select">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">
                    Tipo
                </label>

                <select name="type" id="{{ $prefix }}_type"class="form-select">
                    <option value="official">
                        Firma Oficial (.PFX)
                    </option>
                    <option value="visual">
                        Firma Visual
                    </option>
                </select>
            </div>
        </div>

    </div>
    <div id="officialFields">
        <div class="mb-3">
            <label class="form-label">
                Certificado PFX
            </label>
            <input type="file" name="pfx_file" id="{{ $prefix }}_pfx_file"class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">
                Password PFX
            </label>

            <input type="password" name="pfx_password" id="{{ $prefix }}_pfx_password"class="form-control">
        </div>
    </div>

    <div id="visualFields">
        <div class="mb-3">

            <label class="form-label">
                Firma visual
            </label>

            <input type="file" name="signature_image" id="{{ $prefix }}_signature_image" class="form-control">
            <img id="signaturePreview" class="img-thumbnail mt-2 d-none" style="max-height:120px;">
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Cancelar
    </button>
    <button type="submit" class="btn btn-primary">
        Guardar
    </button>
</div>
