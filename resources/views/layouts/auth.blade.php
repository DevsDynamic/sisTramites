<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ tenant_favicon() }}">
    <title>@yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        body {
            /* margin: 0;
            background: #f5f7fb; */
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background: #f5f7fb;
            font-family: Inter, sans-serif;
        }

        .tenant-login {
            display: flex;
            min-height: 100vh;
        }

        .tenant-login-left {
            /* flex: 1; */
            width: calc(100% - 520px);
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background-repeat: no-repeat;
        }

        .tenant-login-left::before {
            content: '';
            position: absolute;
            inset: 0;

            background:
                radial-gradient(circle at top right,
                    rgba(255, 255, 255, .12),
                    transparent 35%);
        }

        .tenant-overlay-content {
            text-align: center;
            color: white;
            padding: 40px;
        }

        .tenant-login-logo {
            width: 110px;
            height: 110px;
            object-fit: contain;
            margin-bottom: 25px;
            border-radius: 100px;
            background: white;
            padding: 15px;
        }

        .tenant-login-right {
            width: 520px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px;
        }

        .tenant-login-card {
            width: 100%;
            max-width: 380px;
        }

        @media(max-width:992px) {

            .tenant-login-left {
                display: none;
            }

            .tenant-login-right {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>

</body>

</html>
