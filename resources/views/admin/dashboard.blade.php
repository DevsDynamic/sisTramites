@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')

<div class="row row-deck row-cards">

    <div class="col-sm-6 col-lg-3">

        <div class="card">

            <div class="card-body">

                <div class="d-flex align-items-center">

                    <div class="subheader">

                        Clientes

                    </div>

                </div>

                <div class="h1 mb-3">

                    {{ $tenants }}

                </div>

            </div>

        </div>

    </div>

    <div class="col-sm-6 col-lg-3">

        <div class="card">

            <div class="card-body">

                <div class="subheader">

                    Planes

                </div>

                <div class="h1 mb-3">

                    {{ $plans }}

                </div>

            </div>

        </div>

    </div>

    <div class="col-sm-6 col-lg-3">

        <div class="card">

            <div class="card-body">

                <div class="subheader">

                    Activos

                </div>

                <div class="h1 mb-3">

                    {{ $activeTenants }}

                </div>

            </div>

        </div>

    </div>

    <div class="col-sm-6 col-lg-3">

        <div class="card">

            <div class="card-body">

                <div class="subheader">

                    Expirados

                </div>

                <div class="h1 mb-3">

                    {{ $expiredTenants }}

                </div>

            </div>

        </div>

    </div>

</div>

<div class="row mt-4">

    <div class="col-12">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    Crecimiento de Clientes

                </h3>

            </div>

            <div class="card-body">

                <canvas id="tenantsChart"></canvas>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('tenantsChart');

new Chart(ctx, {

    type: 'line',

    data: {

        labels: @json($months),

        datasets: [{

            label: 'Clientes',

            data: @json($tenantCounts),

            tension: 0.4

        }]

    }

});

</script>

@endsection