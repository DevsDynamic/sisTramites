@extends('layouts.auth')

@section('content')

<div class="text-center">

    <div class="mb-3">
        <i class="ti ti-alert-circle text-danger"
           style="font-size: 60px;"></i>
    </div>

    <h2>
        Plan expirado
    </h2>

    <p class="text-secondary">
        Tu suscripción ha vencido.
    </p>

    <a href="#" class="btn btn-primary">
        Renovar plan
    </a>

</div>

@endsection