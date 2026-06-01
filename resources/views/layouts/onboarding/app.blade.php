<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title')
    </title>

    {{-- FAVICON --}}
    <link rel="icon" href="">

    {{-- TABLER --}}
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet">

    {{-- ICONS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    {{-- STYLES --}}
    @include('layouts.onboarding.styles')
</head>

<body>
    <div class="layout">
        {{-- SIDEBAR --}}
        @include('layouts.onboarding.sidebar')

        {{-- MAIN --}}
        <div class="main">

            {{-- TOPBAR --}}
            @include('layouts.topbar')

            {{-- ALERTS --}}
            @include('layouts.alerts')

            {{-- CONTENT --}}
            <main class="content">
                <div class="wizard-header">
                    <div class="wizard-header-title">
                        Configuración Inicial
                    </div>
                    <div class="wizard-header-subtitle">
                        Completa la configuración de tu empresa
                    </div>
                </div>

                <div class="card wizard-card">
                    <div class="content">
                        @yield('content')
                    </div>
                </div>
            </main>

            {{-- FOOTER --}}
            @include('layouts.footer')

        </div>
    </div>

    {{-- SCRIPTS --}}
    @include('layouts.scripts')
    @stack('scripts')
</body>

</html>
