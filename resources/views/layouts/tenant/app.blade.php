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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    {{-- STYLES --}}
    @include('layouts.tenant.styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="tenant-layout">
        {{-- SIDEBAR --}}
        @include('layouts.tenant.sidebar')

        {{-- MAIN --}}
        <div class="tenant-main">

            {{-- TOPBAR --}}
            @include('layouts.tenant.topbar')

            {{-- ALERTS --}}
            @include('layouts.tenant.alerts')

            {{-- CONTENT --}}
            <main class="tenant-content">
                @yield('content')
            </main>

            {{-- FOOTER --}}
            @include('layouts.tenant.footer')

        </div>
    </div>
    {{-- <x-toast /> --}}
    @include('components.toast')

    <script>
        window.AppData = {
            tenantId: @json(tenant_id()),
            userId: @json(auth()->id()),
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('layouts.tenant.scripts')
    {{-- @stack('scripts') --}}
</body>

</html>
