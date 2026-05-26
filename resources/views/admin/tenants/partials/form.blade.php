<div class="row">

    {{-- LEFT --}}
    <div class="col-lg-8">

        {{-- EMPRESA --}}
        <div class="card mb-4">

            <div class="card-header">
                <h3 class="card-title">
                    Empresa
                </h3>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- BUSINESS NAME --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Razón Social
                        </label>

                        <input type="text" name="business_name"
                            value="{{ old('business_name', $tenant->business_name ?? '') }}"
                            class="form-control @error('business_name') is-invalid @enderror" required>

                        @error('business_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- TRADE NAME --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Nombre Comercial
                        </label>

                        <input type="text" name="trade_name"
                            value="{{ old('trade_name', $tenant->trade_name ?? '') }}" class="form-control">
                    </div>

                </div>

            </div>

        </div>

        {{-- CONTACTO --}}
        <div class="card mb-4">

            <div class="card-header">
                <h3 class="card-title">
                    Contacto
                </h3>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            RUC
                        </label>

                        <input type="text" name="ruc" maxlength="11" value="{{ old('ruc', $tenant->ruc ?? '') }}"
                            class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Email
                        </label>

                        <input type="email" name="email" value="{{ old('email', $tenant->email ?? '') }}"
                            class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Teléfono
                        </label>

                        <input type="text" name="phone" value="{{ old('phone', $tenant->phone ?? '') }}"
                            class="form-control">
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-lg-4">

        {{-- SAAS --}}
        <div class="card mb-4">

            <div class="card-header">
                <h3 class="card-title">
                    Configuración SaaS
                </h3>
            </div>

            <div class="card-body">

                {{-- DOMAIN --}}
                <div class="mb-3">
                    <label class="form-label">
                        Subdominio
                    </label>
                    <div class="input-group">
                        <input type="text" name="domain"
                            value="{{ old('domain', isset($tenant) ? explode('.', $tenant->domains->first()?->domain)[0] : '') }}"
                            class="form-control" {{ isset($tenant) ? 'readonly' : '' }} required>
                        <span class="input-group-text">
                            .{{ config('saas.central_domain') }}
                        </span>
                    </div>
                </div>

                {{-- PLAN --}}
                <div class="mb-3">
                    <label class="form-label">
                        Plan
                    </label>
                    <select name="plan_id" class="form-select" required>
                        <option value="">
                            -- Seleccionar --
                        </option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}" @selected(old('plan_id', $tenant->plan_id ?? '') == $plan->id)>
                                {{ $plan->name }} - S/ {{ $plan->price }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ADMIN --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Administrador
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">
                        Usuario Administrador
                    </label>
                    <input type="email" name="admin_email"
                        value="{{ old('admin_email', $tenant->settings['admin_email'] ?? '') }}" class="form-control"
                        {{ isset($tenant) ? 'readonly' : '' }} required>
                    @isset($tenant)
                        <small class="text-secondary">
                            El usuario administrador no puede modificarse.
                        </small>
                    @endisset
                </div>
                {{-- <div class="mb-3">
                    <label class="form-label">
                        {{ isset($tenant) ? 'Nueva Contraseña' : 'Contraseña' }}
                    </label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control"
                            {{ isset($tenant) ? '' : 'required' }}>
                        <button type="button" class="btn btn-icon btn-outline-secondary toggle-password">

                            <i class="ti ti-eye"></i>

                        </button>
                    </div>
                    @isset($tenant)
                        <small class="text-secondary">
                            Dejar vacío para mantener contraseña actual.
                        </small>
                    @endisset
                </div> --}}
                <div class="mb-3">

    <label class="form-label">
        {{ isset($tenant) ? 'Nueva Contraseña' : 'Contraseña' }}
    </label>

    <div class="input-group">

        <input
            type="password"
            name="password"
            class="form-control password-input"
            autocomplete="new-password"
            {{ isset($tenant) ? '' : 'required' }}>

        <span class="input-group-text toggle-password cursor-pointer">
            <i class="ti ti-eye"></i>
        </span>

    </div>

    @isset($tenant)
        <small class="text-secondary">
            Dejar vacío para mantener contraseña actual.
        </small>
    @endisset

</div>
            </div>
        </div>
    </div>
</div>

{{-- FOOTER --}}
<div class="d-flex justify-content-end gap-2 mt-4">

    <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary">

        Cancelar
    </a>

    <button class="btn btn-primary">

        <i class="ti ti-device-floppy"></i>

        {{ $tenant ? 'Actualizar Cliente' : 'Crear Cliente' }}

    </button>

</div>
