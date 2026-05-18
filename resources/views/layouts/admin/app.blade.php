<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css"
          rel="stylesheet"/>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

</head>

<body>

<div class="page">

    @include('layouts.admin.sidebar')

    <div class="page-wrapper">

        @include('layouts.admin.topbar')

        <div class="page-body">

            <div class="container-xl">

                @yield('content')

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>

@yield('scripts')

</body>
</html>