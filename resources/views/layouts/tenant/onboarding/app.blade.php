<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title')
    </title>

    {{-- FAVICON --}}
    <link rel="icon" href="{{ tenant_favicon() }}">

    {{-- TABLER --}}
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet">

    {{-- ICONS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    {{-- STYLES --}}
    @include('layouts.tenant.onboarding.styles')
</head>

<body>
    <div class="tenant-layout">
        {{-- SIDEBAR --}}
        @include('layouts.tenant.onboarding.sidebar')

        {{-- MAIN --}}
        <div class="tenant-main">

            {{-- TOPBAR --}}
            @include('layouts.tenant.topbar')

            {{-- ALERTS --}}
            @include('layouts.tenant.alerts')

            {{-- CONTENT --}}
            <main class="tenant-content">
                <div class="wizard-header">
                    <div class="wizard-header-title">
                        Configuración Inicial
                    </div>
                    <div class="wizard-header-subtitle">
                        Completa la configuración de tu empresa
                    </div>
                </div>

                <div class="card wizard-card">
                    <div class="tenant-content">
                        @yield('content')
                    </div>
                </div>
            </main>

            {{-- FOOTER --}}
            @include('layouts.tenant.footer')

        </div>
    </div>

    {{-- SCRIPTS --}}
    @include('layouts.tenant.scripts')
    @stack('scripts')
</body>

</html>
