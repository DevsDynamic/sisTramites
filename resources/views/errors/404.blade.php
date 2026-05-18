@extends('layouts.admin.app')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 60vh">
    <h1 class="display-1 fw-bold text-muted">404</h1>
    <h4 class="mb-3">Página no encontrada</h4>
    <p class="text-muted mb-4">
        La página que buscas no existe o fue movida.
    </p>
    <a href="{{ url('/') }}" class="btn btn-primary">
        Volver al inicio
    </a>
</div>
@endsection