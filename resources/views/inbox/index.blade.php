@extends('layouts.app')

@section('content')
    <div class="container-xl">

        <div class="d-flex justify-content-between mb-3">
            <h2>📥 Bandeja de Entrada</h2>
        </div>

        {{-- FILTROS --}}
        <div class="card mb-3">
            <div class="card-body">

                <form method="GET" class="row">

                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Buscar documento..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Todos</option>
                            <option value="pending">Pendiente</option>
                            <option value="received">Recibido</option>
                            <option value="observed">Observado</option>
                            <option value="approved">Aprobado</option>
                            <option value="rejected">Rechazado</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            Filtrar
                        </button>
                    </div>

                </form>

            </div>
        </div>

        {{-- LISTA --}}
        <div class="card">

            <div class="table-responsive">
                <table class="table table-vcenter">

                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Asunto</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($flows as $flow)
                            <tr>

                                <td>
                                    <span class="badge bg-blue">
                                        {{ $flow->document->code }}
                                    </span>
                                </td>

                                <td>
                                    {{ $flow->document->subject }}
                                </td>

                                <td>
                                    @if ($flow->status == 'pending')
                                        <span class="badge bg-yellow">Pendiente</span>
                                    @elseif($flow->status == 'received')
                                        <span class="badge bg-blue">Recibido</span>
                                    @elseif($flow->status == 'approved')
                                        <span class="badge bg-green">Aprobado</span>
                                    @elseif($flow->status == 'rejected')
                                        <span class="badge bg-red">Rechazado</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $flow->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    <a href="{{ route('documents.show', $flow->document_id) }}"
                                        class="btn btn-sm btn-primary">
                                        Ver
                                    </a>
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>

            <div class="card-footer">
                {{ $flows->links() }}
            </div>

        </div>

    </div>
@endsection
