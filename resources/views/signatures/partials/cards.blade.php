<div id="signaturesContainer" class="row row-cards">
    @forelse($signatures as $signature)
        <div class="col-md-4">
            <div class="card card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <div class="fw-bold">
                                {{ $signature->user->name }}
                            </div>
                            <div class="text-secondary">
                                {{ strtoupper($signature->type) }}
                            </div>
                        </div>

                        <span class="badge bg-success-lt">
                            Activa
                        </span>
                    </div>

                    @if ($signature->type === 'visual')
                        <div class="border rounded-3 p-3 bg-body-tertiary text-center">

                            <div class="mb-2">

                                <span class="badge bg-azure-lt">
                                    Firma Visual
                                </span>

                            </div>

                            <img src="{{ asset('storage/' . $signature->signature_image) }}" class="img-fluid"
                                style="
                                        max-height:120px;
                                        object-fit:contain;
                                    ">

                        </div>
                    @endif

                    @if ($signature->type === 'official')
                        @php
                            $cert = $signature->certificate_data ?? [];
                        @endphp

                        <div class="border rounded-3 p-3 bg-body-tertiary">

                            {{-- HEADER --}}
                            <div class="d-flex align-items-center mb-3">

                                <div class="avatar avatar-lg bg-primary-lt me-3">
                                    <i class="ti ti-certificate fs-1 text-primary"></i>
                                </div>

                                <div>
                                    <div class="fw-bold fs-3">
                                        Certificado Digital
                                    </div>

                                    <div class="text-secondary">
                                        Firma electrónica oficial
                                    </div>
                                </div>

                            </div>

                            {{-- DATOS --}}
                            <div class="row g-2 small">

                                <div class="col-12">
                                    <span class="text-secondary">
                                        Titular
                                    </span>

                                    <div class="fw-semibold">
                                        {{ data_get($cert, 'subject.CN') }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <span class="text-secondary">
                                        Correo
                                    </span>

                                    <div>
                                        {{ data_get($cert, 'subject.emailAddress') }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <span class="text-secondary">
                                        Documento
                                    </span>

                                    <div>
                                        {{ data_get($cert, 'subject.serialNumber') }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <span class="text-secondary">
                                        Entidad Certificadora
                                    </span>

                                    <div>
                                        {{ data_get($cert, 'issuer.O') }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <span class="text-secondary">
                                        Certificadora
                                    </span>

                                    <div>
                                        {{ data_get($cert, 'issuer.CN') }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <span class="text-secondary">
                                        Serie
                                    </span>

                                    <div class="font-monospace">
                                        {{ data_get($cert, 'serialNumberHex') }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <span class="text-secondary">
                                        Algoritmo
                                    </span>

                                    <div>
                                        {{ data_get($cert, 'signatureTypeLN') }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <span class="text-secondary">
                                        Vigente desde
                                    </span>

                                    <div>
                                        @php
                                            $validTo = data_get($cert, 'validFrom_time_t');
                                        @endphp

                                        @if ($validTo)
                                            {{ \Carbon\Carbon::createFromTimestamp($validTo)->format('d/m/Y') }}
                                        @else
                                            No disponible
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <span class="text-secondary">
                                        Vence
                                    </span>

                                    <div>
                                        @php
                                            $validTo = data_get($cert, 'validTo_time_t');
                                        @endphp

                                        @if ($validTo)
                                            {{ \Carbon\Carbon::createFromTimestamp($validTo)->format('d/m/Y') }}
                                        @else
                                            No disponible
                                        @endif
                                    </div>
                                </div>

                            </div>

                            {{-- ESTADO --}}
                            <hr>

                            @php

                                $validTo = data_get($cert, 'validTo_time_t');

                                $expires = $validTo ? \Carbon\Carbon::createFromTimestamp($validTo) : null;

                                $days = $expires ? now()->diffInDays($expires, false) : null;

                            @endphp

                            @if (!$expires)
                                <span class="badge bg-secondary">
                                    Sin información de vigencia
                                </span>
                            @elseif ($expires->isPast())
                                <span class="badge bg-danger">
                                    Certificado vencido
                                </span>
                            @elseif ($days <= 30)
                                <span class="badge bg-warning">
                                    Próximo a vencer
                                </span>
                            @else
                                <span class="badge bg-success">
                                    Certificado vigente
                                </span>
                            @endif

                            @if ($expires)
                                <div class="text-secondary small mt-2">
                                    Válido hasta:
                                    {{ $expires->format('d/m/Y') }}
                                </div>
                            @endif

                        </div>
                    @endif
                </div>
                @if ($signature->type === 'official')
                    <div class="card-footer">
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse"
                            data-bs-target="#cert-{{ $signature->id }}">
                            Ver certificado completo
                        </button>
                        <div class="collapse mt-3" id="cert-{{ $signature->id }}">
                            <pre class="small">
                            {{ json_encode($signature->certificate_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                        </pre>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty">
                <div class="empty-icon">
                    <i class="ti ti-signature fs-1"></i>
                </div>
                <p class="empty-title">
                    No hay tipos de documentos registrados
                </p>
            </div>
        </div>
    @endforelse
</div>
