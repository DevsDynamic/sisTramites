@extends('layouts.app')

@section('content')
    <div class="empty">
        <div class="empty-icon"><i class="ti ti-circle-check fs-1 text-success"></i></div>
        <p class="empty-title">Configuración inicial completada</p>
        <p class="empty-subtitle text-secondary">El sistema está listo para usarse.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">Ir al Dashboard</a>
    </div>
@endsection
