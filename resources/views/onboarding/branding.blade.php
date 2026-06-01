@extends('layouts.onboarding.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">

                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach

            </ul>
        </div>
    @endif
    <form method="POST" enctype="multipart/form-data" action="{{ route('onboarding.branding.store') }}">
        @csrf
        <div class="row">
            {{-- LOGO --}}
            <div class="col-md-4">
                <div class="branding-upload-card">
                    <label class="branding-label">
                        Logo Empresa
                    </label>
                    <img id="logo-preview"
                        src="{{ setting()?->logo ? asset('storage/' . setting()->logo) : 'https://placehold.co/200x200?text=LOGO' }}"
                        class="branding-preview branding-preview-logo">
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
                    <img id="favicon-preview"
                        src="{{ setting()?->favicon ? asset('storage/' . setting()->favicon) : 'https://placehold.co/64x64?text=ICON' }}"
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
                    <img id="bg-preview"
                        src="{{ setting()?->login_background ? asset('storage/' . setting()->login_background) : 'https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=1974' }}"
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
                    <div class="col-md-4">
                        <label class="branding-label">
                            Color Principal
                        </label>
                        <div class="branding-color-picker">
                            <input type="color" name="primary_color" id="primaryColor"
                                value="{{ old('primary_color', setting()?->primary_color ?? '#206bc4') }}"
                                class="form-control form-control-color">
                            <div>
                                <div class="fw-bold" id="primaryColorText">
                                    {{ setting()?->primary_color ?? '#206bc4' }}
                                </div>
                                <div class="text-secondary">
                                    Botones y acciones
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SIDEBAR --}}
                    <div class="col-md-4">
                        <label class="branding-label">
                            Color Sidebar
                        </label>
                        <div class="branding-color-picker">
                            <input type="color" name="sidebar_color" id="sidebarColor"
                                value="{{ old('sidebar_color', setting()?->sidebar_color ?? '#111827') }}"
                                class="form-control form-control-color">
                            <div>
                                <div class="fw-bold" id="sidebarColorText">
                                    {{ setting()?->sidebar_color ?? '#111827' }}
                                </div>
                                <div class="text-secondary">
                                    Menú lateral
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SIDEBAR TEXT --}}
                    <div class="col-md-4">
                        <label class="branding-label">
                            Color Texto Sidebar
                        </label>

                        <div class="branding-color-picker">

                            <input type="color" name="sidebar_text_color" id="sidebarTextColor"
                                value="{{ old('sidebar_text_color', setting()?->sidebar_text_color ?? '#ffffff') }}"
                                class="form-control form-control-color">

                            <div>
                                <div class="fw-bold" id="sidebarTextColorText">

                                    {{ old('sidebar_text_color', setting()?->sidebar_text_color ?? '#ffffff') }}

                                </div>

                                <div class="text-secondary">
                                    Texto e íconos del menú
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="d-flex justify-content-between mt-5">
                <a href="{{ route('onboarding.company') }}" class="btn btn-outline-secondary">
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

        /* PRIMARY COLOR */
        document
            .getElementById('primaryColor')
            .addEventListener('input', function() {

                document
                    .getElementById('primaryColorText')
                    .innerText = this.value;
            });

        /* SIDEBAR COLOR */
        document
            .getElementById('sidebarColor')
            .addEventListener('input', function() {

                document
                    .getElementById('sidebarColorText')
                    .innerText = this.value;
            });

        /* SIDEBAR TEXT COLOR */
        document
            .getElementById('sidebarTextColor')
            ?.addEventListener('input', function() {

                document
                    .getElementById('sidebarTextColorText')
                    .innerText = this.value;
            });
    </script>
@endpush
