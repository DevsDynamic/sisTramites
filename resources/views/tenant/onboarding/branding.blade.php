@extends('layouts.tenant.onboarding.app')

@section('content')
    <form method="POST" enctype="multipart/form-data" action="{{ route('tenant.onboarding.branding.store') }}">
        @csrf
        <div class="row">
            {{-- LOGO --}}
            <div class="col-md-4">
                <div class="branding-upload-card">
                    <label class="branding-label">
                        Logo Empresa
                    </label>
                    <img id="logo-preview" src="{{ tenant_logo() }}" class="branding-preview branding-preview-logo">
                    <input type="file" name="logo" class="form-control" accept="image/*"
                        onchange="previewLogo(event)">
                </div>
            </div>

            {{-- FAVICON --}}
            <div class="col-md-4">
                <div class="branding-upload-card">
                    <label class="branding-label">
                        Favicon
                    </label>
                    <img id="favicon-preview" src="{{ tenant_favicon() }}"
                        class="branding-preview branding-preview-favicon">
                    <input type="file" name="favicon" class="form-control" accept="image/*"
                        onchange="previewFavicon(event)">
                    <div class="branding-help">
                        Recomendado: 64x64 PNG
                    </div>
                </div>
            </div>

            {{-- LOGIN BG --}}
            <div class="col-md-4">
                <div class="branding-upload-card">
                    <label class="branding-label">
                        Fondo Login
                    </label>
                    <img id="bg-preview" src="{{ tenant_login_background() }}"
                        class="branding-preview branding-preview-background">
                    <input type="file" name="login_background" class="form-control" accept="image/*"
                        onchange="previewBackground(event)">
                </div>
            </div>

            {{-- COLORS --}}
            <div class="col-md-12">
                <div class="wizard-section-title mb-4">
                    Personalización Visual
                </div>
                <div class="row g-4">
                    {{-- PRIMARY --}}
                    <div class="col-md-6">
                        <label class="branding-label">
                            Color Principal
                        </label>
                        <div class="branding-color-picker">
                            <input type="color" name="primary_color" id="primaryColor"
                                value="{{ tenant_primary_color() }}" class="form-control form-control-color">
                            <div>
                                <div class="fw-bold" id="primaryColorText">
                                    {{ tenant_primary_color() }}
                                </div>
                                <div class="text-secondary">
                                    Botones y acciones
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SIDEBAR --}}
                    <div class="col-md-6">
                        <label class="branding-label">
                            Color Sidebar
                        </label>
                        <div class="branding-color-picker">
                            <input type="color" name="sidebar_color" id="sidebarColor"
                                value="{{ tenant_sidebar_color() }}" class="form-control form-control-color">
                            <div>
                                <div class="fw-bold" id="sidebarColorText">
                                    {{ tenant_sidebar_color() }}
                                </div>
                                <div class="text-secondary">
                                    Menú lateral
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="d-flex justify-content-between mt-5">
                <a href="{{ route('tenant.onboarding.company') }}" class="btn btn-outline-secondary">
                    Atrás
                </a>
                <button type="submit" class="btn btn-primary px-5">
                    Guardar y Continuar
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    {{-- SCRIPTS --}}
    <script>
        function previewLogo(event) {
            const reader = new FileReader();

            reader.onload = function() {
                document
                    .getElementById('logo-preview')
                    .src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        }

        function previewFavicon(event) {
            const reader = new FileReader();

            reader.onload = function() {
                document
                    .getElementById('favicon-preview')
                    .src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        }

        function previewBackground(event) {
            const reader = new FileReader();

            reader.onload = function() {
                document
                    .getElementById('bg-preview')
                    .src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        }

        /*
        |--------------------------------------------------------------------------
        | PRIMARY COLOR
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('primaryColor')
            .addEventListener('input', function() {

                document
                    .getElementById('primaryColorText')
                    .innerText = this.value;
            });

        /*
        |--------------------------------------------------------------------------
        | SIDEBAR COLOR
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('sidebarColor')
            .addEventListener('input', function() {

                document
                    .getElementById('sidebarColorText')
                    .innerText = this.value;
            });
    </script>
@endpush
