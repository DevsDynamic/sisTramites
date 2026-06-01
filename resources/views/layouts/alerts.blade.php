@if (!('onboarding_completed'))
    <div class="alert alert-warning m-4 mb-0">
        Completa el onboarding para activar todas las funciones.
        <a href="{{ route('onboarding.welcome') }}" class="alert-link">
            Completar ahora
        </a>
    </div>
@endif
