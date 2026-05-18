@extends('layouts.admin.app')

@section('title', 'Clientes')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h2 class="page-title">

                Clientes

            </h2>

        </div>

        <div>

            <a href="{{ route('tenants.create') }}" class="btn btn-primary">

                <i class="ti ti-plus"></i>

                Nuevo Cliente

            </a>

        </div>

    </div>

    <div class="card">

        <div class="table-responsive">

            <table class="table table-vcenter card-table">

                <thead>

                    <tr>

                        <th>Empresa</th>

                        <th>RUC</th>

                        <th>Plan</th>

                        <th>Estado</th>

                        <th>Expira</th>

                        <th></th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($tenants as $tenant)
                        <tr>

                            <td>

                                <div class="fw-bold">

                                    {{ $tenant->business_name }}

                                </div>

                                <div class="text-secondary">

                                    {{ $tenant->email }}

                                </div>

                            </td>

                            <td>

                                {{ $tenant->ruc }}

                            </td>

                            <td>

                                <span class="badge bg-blue-lt">

                                    {{ $tenant->plan?->name }}

                                </span>

                            </td>

                            <td>

                                @if ($tenant->status == 'active')
                                    <span class="badge bg-green">

                                        Activo

                                    </span>
                                @elseif($tenant->status == 'expired')
                                    <span class="badge bg-red">

                                        Expirado

                                    </span>
                                @endif

                            </td>

                            <td>

                                {{ optional($tenant->expires_at)->format('d/m/Y') }}

                            </td>

                            <td>

                                <a href="{{ route('tenants.show', $tenant) }}" class="btn btn-sm btn-outline-primary">

                                    Ver

                                </a>

                                <a href="{{ route('tenants.edit', $tenant) }}" class="btn btn-sm btn-outline-warning">

                                    Editar

                                </a>

                                <form method="POST" action="{{ route('tenants.destroy', $tenant) }}" class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('¿Eliminar cliente?')">

                                        Eliminar

                                    </button>

                                </form>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

@endsection
