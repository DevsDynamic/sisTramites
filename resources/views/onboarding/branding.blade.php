@extends('layouts.app')

@section('content')
    <x-crud.index>
        <x-slot:header>
            <x-crud.header title="Identidad visual" description="Personaliza cómo se presenta el sistema a los usuarios.">
                <x-slot:toolbar>
                    <a href="{{ route('onboarding.welcome') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>Volver
                    </a>
                </x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        <form method="POST" enctype="multipart/form-data" action="{{ route('onboarding.branding.store') }}">
            @csrf

            <div class="row row-cards mb-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <label class="form-label d-block">Logo de la empresa</label>
                            <img id="logo-preview" class="settings-preview settings-preview-logo"
                                src="{{ $settings->logo ? asset('storage/' . $settings->logo) : 'https://placehold.co/240x180?text=LOGO' }}" alt="Vista previa del logo">
                            <input type="file" name="logo" class="form-control mt-3" accept="image/*" data-preview="logo-preview">
                            <div class="form-hint">PNG, JPG o WEBP. Máximo 2 MB.</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <label class="form-label d-block">Favicon</label>
                            <img id="favicon-preview" class="settings-preview settings-preview-favicon"
                                src="{{ $settings->favicon ? asset('storage/' . $settings->favicon) : 'https://placehold.co/96x96?text=ICON' }}" alt="Vista previa del favicon">
                            <input type="file" name="favicon" class="form-control mt-3" accept="image/*" data-preview="favicon-preview">
                            <div class="form-hint">Se recomienda una imagen cuadrada. Máximo 1 MB.</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <label class="form-label d-block">Fondo de inicio de sesión</label>
                            <img id="background-preview" class="settings-preview settings-preview-background"
                                src="{{ $settings->login_background ? asset('storage/' . $settings->login_background) : 'https://placehold.co/480x240?text=FONDO' }}" alt="Vista previa del fondo">
                            <input type="file" name="login_background" class="form-control mt-3" accept="image/*" data-preview="background-preview">
                            <div class="form-hint">Imagen horizontal. Máximo 4 MB.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Colores del sistema</h3>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label">Color principal</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="color" name="primary_color" class="form-control form-control-color"
                                    value="{{ old('primary_color', $settings->primary_color ?? '#206bc4') }}" data-color-value="primary-color-value">
                                <span id="primary-color-value" class="fw-semibold">{{ old('primary_color', $settings->primary_color ?? '#206bc4') }}</span>
                            </div>
                            <div class="form-hint">Botones y acciones principales.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Color del menú lateral</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="color" name="sidebar_color" class="form-control form-control-color"
                                    value="{{ old('sidebar_color', $settings->sidebar_color ?? '#111827') }}" data-color-value="sidebar-color-value">
                                <span id="sidebar-color-value" class="fw-semibold">{{ old('sidebar_color', $settings->sidebar_color ?? '#111827') }}</span>
                            </div>
                            <div class="form-hint">Fondo de la navegación lateral.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Color del texto del menú</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="color" name="sidebar_text_color" class="form-control form-control-color"
                                    value="{{ old('sidebar_text_color', $settings->sidebar_text_color ?? '#ffffff') }}" data-color-value="sidebar-text-color-value">
                                <span id="sidebar-text-color-value" class="fw-semibold">{{ old('sidebar_text_color', $settings->sidebar_text_color ?? '#ffffff') }}</span>
                            </div>
                            <div class="form-hint">Texto e íconos de la navegación lateral.</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>Guardar cambios
                    </button>
                </div>
            </div>
        </form>
    </x-crud.index>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-preview]').forEach(input => {
            input.addEventListener('change', () => {
                const file = input.files?.[0];
                const preview = document.getElementById(input.dataset.preview);

                if (file && preview) preview.src = URL.createObjectURL(file);
            });
        });

        document.querySelectorAll('[data-color-value]').forEach(input => {
            input.addEventListener('input', () => {
                document.getElementById(input.dataset.colorValue).textContent = input.value;
            });
        });
    </script>
@endpush
