@extends('layouts.app')

@section('content')
    <div class="container-xl">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="text-secondary">Total Documentos</div>
                    <div class="display-6 fw-bold">{{ $stats['total'] }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-4">
                    <div class="text-secondary">Pendientes</div>
                    <div class="display-6 fw-bold text-warning">{{ $stats['pending'] }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-4">
                    <div class="text-secondary">Aprobados</div>
                    <div class="display-6 fw-bold text-success">{{ $stats['approved'] }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-4">
                    <div class="text-secondary">Rechazados</div>
                    <div class="display-6 fw-bold text-danger">{{ $stats['rejected'] }}</div>
                </div>
            </div>

        </div>

        {{-- MÉTRICAS --}}
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card p-4">
                    <h5>⏱ Tiempo promedio atención</h5>
                    <div class="display-6">{{ round($avgTime, 1) }} hrs</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-4">
                    <h5>📊 Carga por áreas</h5>
                    @foreach ($byArea as $area)
                        <div class="d-flex justify-content-between">
                            <span>Área {{ $area->to_area_id }}</span>
                            <strong>{{ $area->total }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection