@php
    $steps = [
        'onboarding.welcome',
        'onboarding.company',
        'onboarding.branding',
        'onboarding.completed',
    ];

    $currentStep = collect($steps)->search(fn($step) => request()->routeIs($step)) + 1;
@endphp

<div class="wizard-steps">
    <div class="wizard-line"></div>
    {{-- STEP 1 --}}
    <a href="{{ route('onboarding.welcome') }}"
        class="wizard-step
       {{ $currentStep >= 1 ? 'completed' : '' }}
       {{ request()->routeIs('onboarding.welcome') ? 'active' : '' }}">
        <div class="wizard-step-number">
            1
        </div>
        <div>
            <div class="wizard-step-title">
                Bienvenida
            </div>
            <div class="wizard-step-subtitle">
                Inicio configuración
            </div>
        </div>
    </a>
    {{-- STEP 2 --}}
    <a href="{{ route('onboarding.company') }}"
        class="wizard-step
       {{ $currentStep >= 2 ? 'completed' : '' }}
       {{ request()->routeIs('onboarding.company') ? 'active' : '' }}">
        <div class="wizard-step-number">
            2
        </div>
        <div>
            <div class="wizard-step-title">
                Empresa
            </div>
            <div class="wizard-step-subtitle">
                Datos empresa
            </div>
        </div>
    </a>
    {{-- STEP 3 --}}
    <a href="{{ route('onboarding.branding') }}"
        class="wizard-step
       {{ $currentStep >= 3 ? 'completed' : '' }}
       {{ request()->routeIs('onboarding.branding') ? 'active' : '' }}">
        <div class="wizard-step-number">
            3
        </div>
        <div>
            <div class="wizard-step-title">
                Branding
            </div>
            <div class="wizard-step-subtitle">
                Logo y colores
            </div>
        </div>
    </a>
    {{-- STEP 4 --}}
    <a href="#"
        class="wizard-step
       {{ $currentStep >= 4 ? 'completed' : '' }}
       {{ request()->routeIs('onboarding.completed') ? 'active' : '' }}">
        <div class="wizard-step-number">
            4
        </div>
        <div>
            <div class="wizard-step-title">
                Finalizado
            </div>
            <div class="wizard-step-subtitle">
                Sistema listo
            </div>
        </div>
    </a>
</div>
