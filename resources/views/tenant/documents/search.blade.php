@extends('layouts.tenant.app')

@section('content')
    <div class="container-xl">

        <div class="tenant-card p-4">

            <form method="GET">

                <div class="row g-3">

                    {{-- 🔎 SEARCH --}}
                    <div class="col-md-4">

                        <input type="text" name="search" class="form-control" placeholder="Buscar documento..."
                            value="{{ request('search') }}">

                    </div>

                    {{-- 📄 TIPO --}}
                    <div class="col-md-2">

                        <select name="document_type_id" class="form-select">

                            <option value="">
                                Tipo documento
                            </option>

                            @foreach ($documentTypes as $type)
                                <option value="{{ $type->id }}" @selected(request('document_type_id') == $type->id)>
                                    {{ $type->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    {{-- 📌 ESTADO --}}
                    <div class="col-md-2">

                        <select name="status" class="form-select">

                            <option value="">
                                Estado
                            </option>

                            <option value="pending">
                                Pendiente
                            </option>

                            <option value="approved">
                                Aprobado
                            </option>

                            <option value="rejected">
                                Rechazado
                            </option>

                        </select>

                    </div>

                    {{-- 📅 FECHA --}}
                    <div class="col-md-2">

                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">

                    </div>

                    <div class="col-md-2">

                        <button class="btn btn-primary w-100">

                            <i class="ti ti-search"></i>
                            Buscar

                        </button>

                    </div>

                </div>

            </form>

        </div>

        {{-- RESULTADOS --}}
        <div class="tenant-card p-0 mt-4">

            <div class="table-responsive">

                <table class="table table-vcenter">

                    <thead>

                        <tr>
                            <th>Código</th>
                            <th>Asunto</th>
                            <th>Estado</th>
                            <th>Área</th>
                            <th>Fecha</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($documents as $document)
                            <tr>

                                <td>
                                    {{ $document->code }}
                                </td>

                                <td>
                                    {{ $document->subject }}
                                </td>

                                <td>

                                    <span class="badge bg-primary">
                                        {{ $document->status }}
                                    </span>

                                </td>

                                <td>
                                    {{ $document->area?->name }}
                                </td>

                                <td>
                                    {{ $document->created_at->format('d/m/Y H:i') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center py-5">

                                    No hay resultados

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="mt-4">

            {{ $documents->withQueryString()->links() }}

        </div>

    </div>
@endsection
