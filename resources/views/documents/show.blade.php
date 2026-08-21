@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">
                {{ $document->subject }}
            </h1>
            <div class="text-secondary">
                {{ $document->type?->name }}
            </div>
        </div>
        <span class="badge bg-{{ $document->status->color() }}-lt fs-5">
            <i class="{{ $document->status->icon() }}"></i>
            {{ $document->status->label() }}
        </span>
    </div>

    <div class="row">
        {{-- LEFT --}}
        <div class="col-lg-8">
            {{-- INFO --}}
            <div class="card card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        Información
                    </h3>
                </div>

                <div class="card-body">

                    <div class="row mb-4">

                        <div class="col-md-6">

                            <div class="text-secondary small">
                                Código
                            </div>

                            <div class="fw-bold fs-3">
                                {{ $document->code }}
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="text-secondary small">
                                Estado
                            </div>

                            <div class="fw-bold">

                                <span class="badge bg-{{ $document->status->color() }}-lt">
                                    <i class="{{ $document->status->icon() }}"></i>

                                    {{ $document->status->label() }}
                                </span>

                            </div>

                        </div>

                    </div>

                    <div class="mb-4">

                        <div class="text-secondary small mb-2">
                            Contenido
                        </div>

                        <div class="border rounded p-3 bg-body-tertiary">

                            {!! nl2br(e($document->content)) !!}

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="text-secondary small">
                                Creado por
                            </div>

                            <div class="fw-bold">
                                {{ $document->creator?->name }}
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="text-secondary small">
                                Fecha
                            </div>

                            <div class="fw-bold">
                                {{ $document->created_at->format('d/m/Y H:i') }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- ATTACHMENTS --}}
            <div class="card card">
                <div class="card-header">
                    <h3 class="card-title">Archivos adjuntos</h3>
                </div>
                <div class="card-body">
                    @foreach ($document->attachments as $file)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="fw-bold">
                                            {{ $file->original_name ?? $file->file_name }}
                                        </div>
                                        @if ($file->is_signed)
                                            <span class="badge bg-pink-lt">
                                                <i class="ti ti-signature"></i> Firmado
                                            </span>
                                        @else
                                            <span class="badge bg-blue-lt">
                                                Original
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-secondary small">
                                        @if ($file->file_size)
                                            {{ number_format($file->file_size / 1024, 2) }} KB
                                        @else
                                            Tamaño no disponible
                                        @endif
                                        @if ($file->is_signed)
                                            <span class="mx-1">·</span>
                                            Firmado el {{ $file->created_at->format('d/m/Y H:i') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="btn-list">
                                    <a href="{{ route('documents.attachments.file', [$document, $file]) }}" target="_blank" rel="noopener"
                                        class="btn btn-primary btn-sm">
                                        <i class="ti ti-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('documents.attachments.file', [$document, $file, 'download' => 1]) }}"
                                        class="btn btn-outline-secondary btn-sm">
                                        <i class="ti ti-download"></i> Descargar
                                    </a>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">
            {{-- FIRMAR - solo si no está firmado --}}
            <div class="card card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Firmar documento</h3>
                </div>
                <div class="card-body">
                    @if ($document->status === \App\Enums\DocumentStatus::SIGNED)
                        <div class="alert alert-success">
                            <i class="ti ti-check"></i>
                            Este documento ya fue firmado.
                        </div>
                    @elseif($canSignWorkflowStep && $signatures->isEmpty())
                        <div class="alert alert-warning">
                            Esta etapa requiere una firma digital oficial. Registra una firma oficial activa antes de continuar.
                        </div>
                    @elseif($signatures->isEmpty())
                        <div class="alert alert-warning">
                            No tienes una firma pendiente o una etapa de firma asignada.
                        </div>
                    @else
                        @if($canSignWorkflowStep)
                            <div class="alert alert-info small">Estás atendiendo una etapa del flujo. Aplica una firma digital oficial o un visto bueno (VB) para avanzar a la siguiente etapa.</div>
                        @endif
                        @foreach ($signatures as $signature)
                            <div class="border rounded p-3 mb-3">

                                @if ($signature->type === 'official')
                                    <div class="fw-bold text-success">
                                        <i class="ti ti-certificate"></i>
                                        Firma Digital
                                    </div>

                                    <div class="small text-secondary">
                                        {{ data_get($signature->certificate_data, 'subject.CN') }}
                                    </div>

                                    <div class="small">
                                        Vence:
                                        {{ \Carbon\Carbon::createFromTimestamp(data_get($signature->certificate_data, 'validTo_time_t'))->format(
                                            'd/m/Y',
                                        ) }}
                                    </div>
                                @else
                                    <div class="fw-bold text-warning">
                                        <i class="ti ti-writing-sign"></i>
                                        Firma Visual
                                    </div>

                                    <img src="{{ route('signatures.image', $signature) }}" style="height:50px">
                                @endif
                                {{-- <div class="fw-bold">{{ strtoupper($signature->type ?? '') }}</div> --}}
                                <div class="text-secondary small mb-3">
                                    {{ $signature->is_default ? 'Predeterminada' : 'Firma disponible' }}
                                </div>
                                @php($previewAttachment = $document->attachments->whereIn('kind', ['primary', 'signed_copy'])->sortByDesc('id')->first())
                                <form method="POST" action="{{ route('documents.sign', $document) }}" data-signature-placement data-pdf-url="{{ $previewAttachment ? route('documents.attachments.file', [$document, $previewAttachment]) : '' }}">
                                    @csrf
                                    <input type="hidden" name="signature_id" value="{{ $signature->id }}">
                                    <div class="row g-2 mb-3">
                                        @if ($signature->type === 'official')
                                            <div class="col-12">
                                                <label class="form-label small mb-1">Apariencia visible</label>
                                                <select name="appearance_type" class="form-select form-select-sm">
                                                    <option value="signature">Firmado digitalmente</option>
                                                    <option value="approval">Visto bueno digital (VB)</option>
                                                </select>
                                                <div class="form-hint">La firma criptogr&aacute;fica se aplica en ambos casos.</div>
                                            </div>
                                            <div class="col-12" data-certificate-password>
                                                @if ($signature->pfx_password)
                                                    <div class="alert alert-success py-2 mb-0 small">
                                                        <i class="ti ti-lock-check me-1"></i>
                                                        Contraseña recordada de forma cifrada. Para cambiarla o dejar de recordarla, edita esta firma.
                                                    </div>
                                                @else
                                                    <label class="form-label small mb-1">Contraseña del certificado</label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="password" name="certificate_password" class="form-control" autocomplete="current-password" placeholder="Se usa solo para esta firma">
                                                        <button class="btn btn-outline-secondary toggle-password" type="button" aria-label="Mostrar contraseña">
                                                            <i class="ti ti-eye"></i>
                                                        </button>
                                                    </div>
                                                    <div class="form-hint">No se guarda en el historial del documento.</div>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="col-6">
                                            <label class="form-label small mb-1">Mostrar en</label>
                                            <select name="placement" class="form-select form-select-sm" data-placement-select>
                                                <option value="last">&Uacute;ltima hoja</option>
                                                <option value="first">Primera hoja</option>
                                                <option value="all">Todas las hojas</option>
                                                <option value="specific">Página específica</option>
                                            </select>
                                        </div>
                                        <div class="col-6 d-none" data-page-field>
                                            <label class="form-label small mb-1">N.º de página</label>
                                            <input type="number" name="page_number" min="1" class="form-control form-control-sm" placeholder="Solo si eliges página específica">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small mb-1">Formato</label>
                                            <select name="orientation" class="form-select form-select-sm">
                                                <option value="horizontal">Horizontal</option>
                                                <option value="vertical">Vertical</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small mb-1">Ubicación</label>
                                            <select name="position_mode" class="form-select form-select-sm" data-position-mode>
                                                <option value="automatic">Automática</option>
                                                @if($padesConfigured)
                                                    <option value="manual">Elegir sobre la hoja</option>
                                                @endif
                                            </select>
                                        </div>
                                        <input type="hidden" name="position_x" value="0.36" data-position-x>
                                        <input type="hidden" name="position_y" value="0.44" data-position-y>
                                        <input type="hidden" name="position_width" value="0.28" data-position-width>
                                        <input type="hidden" name="position_height" value="0.12" data-position-height>
                                        <div class="col-12 d-none" data-position-editor>
                                            <div class="border rounded-3 p-2 bg-body-tertiary">
                                                <div class="d-flex justify-content-between align-items-center mb-2"><span class="small fw-semibold">Arrastra el recuadro a la posición deseada</span><span class="text-secondary small" data-position-page-label></span></div>
                                                <div class="signature-placement-canvas mx-auto" data-signature-canvas>
                                                    <canvas data-pdf-canvas></canvas>
                                                    <div class="signature-placement-box" data-signature-box>Firma</div>
                                                </div>
                                                <div class="form-hint mt-2">La ubicación se guarda proporcionalmente. En “todas las hojas” se aplicará en la misma posición relativa.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary w-100">
                                        <i class="ti ti-signature"></i>
                                        {{ $signature->type === 'official' ? 'Aplicar firma digital' : 'Aplicar firma visual' }}
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- HISTORIAL --}}
            <div class="card card">
                <div class="card-header">
                    <h3 class="card-title">Historial</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($document->statusLogs as $log)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-blue"></div>
                                <div class="timeline-content">
                                    <div class="fw-bold">{{ $log->description }}</div>
                                    <div class="text-secondary small">
                                        {{ $log->user?->name }} —
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-secondary">Sin historial.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
