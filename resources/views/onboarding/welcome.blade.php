@extends('layouts.onboarding.app')

@section('content')

<div class="text-center">
    <div class="mb-4">
        <span class="avatar avatar-xl bg-primary-lt">
            <i class="ti ti-building fs-1"></i>
        </span>
    </div>
    <h1 class="mb-3">
        Bienvenido a {{ config('app.name') }}
    </h1>
    <div class="text-secondary mb-4">
        Configura tu empresa en pocos pasos
    </div>
    <a href="{{ route('onboarding.company') }}"
       class="btn btn btn-lg">
        Comenzar
    </a>
</div>

@endsection