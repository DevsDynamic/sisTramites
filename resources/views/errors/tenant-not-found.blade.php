<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Sitio no encontrado — {{ config('app.name') }}
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="d-flex flex-column align-items-center justify-content-center min-vh-100">

        <div class="text-center p-5">

            <h1 class="display-1 fw-bold text-muted">
                404
            </h1>

            <h4 class="mb-2">
                Sitio no encontrado
            </h4>

            <p class="text-muted mb-1">

                El subdominio

                <strong>
                    {{ $domain ?? request()->getHost() }}
                </strong>

                no está registrado.

            </p>

            <p class="text-muted mb-4">

                Si crees que esto es un error,
                contacta al administrador del sistema.

            </p>

            <a href="{{ config('app.url') }}" class="btn btn-primary">

                Ir a {{ config('app.name') }}

            </a>

        </div>

    </div>

</body>

</html>
