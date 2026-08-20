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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @hasSection('module')
        @vite(View::yieldContent('module'))
    @endif

    @include('layouts.partials.styles')

</head>

<body>
    <div class="layout">
        {{-- SIDEBAR --}}
        @include('layouts.partials.sidebar')

        <div class="sidebar-overlay"></div>

        {{-- MAIN --}}
        <div class="main">

            {{-- TOPBAR --}}
            @include('layouts.partials.topbar')

            {{-- ALERTS --}}
            @include('layouts.partials.alerts')

            {{-- CONTENT --}}
            <main class="content">
                @yield('content')
            </main>

            {{-- FOOTER --}}
            @include('layouts.partials.footer')

        </div>
    </div>
    @include('components.toast')
    {{-- SCRIPTS --}}
    @include('layouts.partials.scripts')
    @stack('scripts')
</body>

</html>
