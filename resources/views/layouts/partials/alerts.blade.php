@if (session('success'))
    <div class="alert alert-success alert-dismissible m-4 mb-0" role="alert">
        <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger m-4 mb-0" role="alert">
        <div class="fw-semibold mb-1">Revisa los datos ingresados.</div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (setting() && ! setting()->onboarding_completed)
    <div class="alert alert-warning m-4 mb-0" role="alert">
        Completa la configuración inicial para activar todas las funciones.
        <a href="{{ route('onboarding.welcome') }}" class="alert-link">Completar ahora</a>
    </div>
@endif
