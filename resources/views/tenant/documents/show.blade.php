@extends('layouts.tenant.app')

@section('content')
    <div class="container-xl">

        <div class="card mb-3">
            <div class="card-body">
                <h2>{{ $document->subject }}</h2>
                <div>
                    <span class="badge bg-blue">{{ $document->code }}</span>
                    <span class="badge bg-green">{{ $document->status }}</span>
                </div>
            </div>
        </div>

        <div class="row">

            {{-- TIMELINE --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Flujo del Documento</h3>
                    </div>

                    <div class="card-body">
                        <ul class="list list-timeline">

                            @foreach ($document->flows as $flow)
                                <li class="list-timeline-item">
                                    <div class="list-timeline-icon bg-blue"></div>

                                    <div class="list-timeline-content">
                                        <div class="text-muted">
                                            {{ $flow->created_at }}
                                        </div>

                                        <div>
                                            De Área {{ $flow->from_area_id }}
                                            → Área {{ $flow->to_area_id }}
                                        </div>

                                        <div>
                                            Estado: {{ $flow->status }}
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>

            {{-- LOGS --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Auditoría</h3>
                    </div>

                    <div class="card-body">

                        <ul class="list-group">

                            @foreach ($document->logs as $log)
                                <li class="list-group-item">
                                    <strong>{{ $log->action }}</strong>
                                    <br>
                                    {{ $log->description }}
                                    <br>
                                    <small>{{ $log->created_at }}</small>
                                </li>
                            @endforeach

                        </ul>

                    </div>
                </div>
            </div>

        </div>

    </div>


    <div class="tenant-card p-4">

        <h4>Versiones</h4>

        @foreach ($document->versions as $version)
            <div class="border rounded p-3 mb-2">

                <div class="d-flex justify-content-between">

                    <div>
                        <strong>v{{ $version->version }}</strong>
                        <div class="text-secondary">
                            {{ $version->file_name }}
                        </div>
                    </div>

                    <a href="{{ Storage::url($version->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                        Descargar
                    </a>

                </div>

            </div>
        @endforeach

    </div>
@endsection
