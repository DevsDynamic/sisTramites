@extends('layouts.admin.app')

@section('title', 'Planes')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2 class="page-title">

            Planes

        </h2>

        <a href="{{ route('plans.create') }}" class="btn btn-primary">

            <i class="ti ti-plus"></i>

            Nuevo Plan

        </a>

    </div>

    <div class="row row-cards">

        @foreach ($plans as $plan)
            {{-- <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <div class="card-title">

                        {{ $plan->name }}

                    </div>

                    <div class="text-secondary mb-3">

                        {{ $plan->description }}

                    </div>

                    <div class="display-6 fw-bold mb-3">

                        S/ {{ number_format($plan->price, 2) }}

                    </div>

                    <div class="text-secondary">

                        {{ $plan->duration_days }} días

                    </div>

                </div>

            </div>

        </div> --}}

            <div class="col-md-4">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div class="card-title">

                                {{ $plan->name }}

                            </div>

                            <span class="badge bg-blue-lt">

                                {{ $plan->duration_days }} días

                            </span>

                        </div>

                        <div class="text-secondary mb-3">

                            {{ $plan->description }}

                        </div>

                        <div class="display-6 fw-bold mb-4">

                            S/ {{ number_format($plan->price, 2) }}

                        </div>

                        <div class="d-flex gap-2 flex-wrap">

                            <a href="{{ route('plans.show', $plan) }}" class="btn btn-outline-primary btn-sm">

                                <i class="ti ti-eye"></i>

                                Ver

                            </a>

                            <a href="{{ route('plans.edit', $plan) }}" class="btn btn-outline-warning btn-sm">

                                <i class="ti ti-edit"></i>

                                Editar

                            </a>

                            <form method="POST" action="{{ route('plans.destroy', $plan) }}" class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('¿Eliminar plan?')">

                                    <i class="ti ti-trash"></i>

                                    Eliminar

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>
        @endforeach

    </div>

@endsection
