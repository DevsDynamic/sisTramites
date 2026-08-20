<div id="signaturesContainer" class="row row-cards">

    @forelse ($signatures as $signature)
        @php
            $certificate = $signature->certificate_data ?? [];
            $expiresAt = $signature->certificateExpiresAt();
            $daysUntilExpiration = $expiresAt
                ? now()->diffInDays($expiresAt, false)
                : null;
        @endphp

        <x-crud.card :stretch="false">
            <x-slot:header>
                <x-crud.card-header
                    :title="$signature->user->name"
                    :subtitle="$signature->type === 'official'
                        ? 'Certificado digital · ' . $signature->display_code
                        : 'Firma visual · ' . $signature->display_code"
                >
                    <x-slot:badge>
                        <x-crud.badge
                            :active="$signature->active"
                            modal="activeModal"
                            :dataset="[
                                'name' => $signature->display_name,
                                'entity' => 'Firma',
                                'url' => route('signatures.active', $signature),
                                'active' => $signature->active ? 'deactivate' : 'activate',
                            ]"
                        />
                    </x-slot:badge>
                </x-crud.card-header>
            </x-slot:header>

            @if ($signature->type === 'visual')
                <div class="border rounded-3 p-3 bg-body-tertiary text-center">
                    <div class="mb-3">
                        <span class="badge bg-azure-lt">
                            <i class="ti ti-signature me-1"></i>
                            Firma visual
                        </span>
                    </div>

                    <img
                        src="{{ asset('storage/' . $signature->signature_image) }}"
                        alt="Firma visual de {{ $signature->user->name }}"
                        class="img-fluid"
                        style="max-height: 120px; object-fit: contain;"
                    >
                </div>
            @else
                <div class="border rounded-3 p-3 bg-body-tertiary">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-lg bg-primary-lt me-3">
                            <i class="ti ti-certificate fs-1 text-primary"></i>
                        </div>

                        <div class="min-w-0">
                            <div class="fw-bold fs-3">Certificado digital</div>
                            <div class="text-secondary">Firma electrónica oficial</div>
                        </div>
                    </div>

                    <div class="row g-3 small">
                        <div class="col-12">
                            <div class="text-secondary">Titular</div>
                            <div class="fw-semibold">
                                {{ data_get($certificate, 'subject.CN', $signature->user->name) }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-secondary">Correo</div>
                            <div class="text-truncate" title="{{ data_get($certificate, 'subject.emailAddress') }}">
                                {{ data_get($certificate, 'subject.emailAddress', 'No disponible') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-secondary">Documento</div>
                            <div>
                                {{ data_get($certificate, 'subject.serialNumber', 'No disponible') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-secondary">Entidad certificadora</div>
                            <div class="text-truncate" title="{{ data_get($certificate, 'issuer.O') }}">
                                {{ data_get($certificate, 'issuer.O', 'No disponible') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-secondary">Vence</div>
                            <div class="fw-semibold">
                                {{ $expiresAt?->format('d/m/Y') ?? 'No disponible' }}
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    @if (! $expiresAt)
                        <span class="badge bg-secondary-lt">
                            <i class="ti ti-help-circle me-1"></i>
                            Sin información de vigencia
                        </span>
                    @elseif ($expiresAt->isPast())
                        <span class="badge bg-danger-lt">
                            <i class="ti ti-alert-circle me-1"></i>
                            Certificado vencido
                        </span>
                    @elseif ($daysUntilExpiration <= 30)
                        <span class="badge bg-warning-lt">
                            <i class="ti ti-clock-exclamation me-1"></i>
                            Próximo a vencer
                        </span>
                    @else
                        <span class="badge bg-success-lt">
                            <i class="ti ti-circle-check me-1"></i>
                            Certificado vigente
                        </span>
                    @endif
                </div>
            @endif

            <x-slot:footer>
                <x-crud.actions>
                    <x-crud.button-action
                        color="warning"
                        icon="ti ti-edit"
                        title="Editar"
                        size="sm"
                        modal="editModal"
                        class="edit-btn"
                        :dataset="[
                            'url' => route('signatures.update', $signature),
                            'id' => $signature->id,
                            'user_id' => $signature->user_id,
                            'type' => $signature->type,
                            'signature_image_url' => $signature->signature_image
                                ? asset('storage/' . $signature->signature_image)
                                : '',
                            'active' => $signature->active,
                        ]"
                    />

                    @if ($signature->canDeactivate() || $signature->canActivate())
                        <x-crud.button-action
                            :color="$signature->active ? 'danger' : 'success'"
                            :icon="$signature->active ? 'ti ti-toggle-right' : 'ti ti-toggle-left'"
                            :title="$signature->active ? 'Desactivar' : 'Activar'"
                            modal="activeModal"
                            size="sm"
                            class="active-btn"
                            :dataset="[
                                'active' => $signature->active ? 'deactivate' : 'activate',
                                'entity' => 'Firma',
                                'name' => $signature->display_name,
                                'url' => route('signatures.active', $signature),
                            ]"
                        />
                    @endif

                    @if ($signature->canDelete())
                        <x-crud.button-action
                            color="danger"
                            icon="ti ti-trash"
                            title="Eliminar"
                            modal="deleteModal"
                            size="sm"
                            class="delete-btn"
                            :dataset="[
                                'url' => route('signatures.destroy', $signature),
                                'entity' => 'Firma',
                                'name' => $signature->display_name,
                            ]"
                        />
                    @endif
                </x-crud.actions>

                @if ($signature->type === 'official')
                    <button
                        type="button"
                        class="btn btn-outline-primary btn-sm mt-3"
                        data-bs-toggle="modal"
                        data-bs-target="#certificate-{{ $signature->id }}"
                    >
                        <i class="ti ti-file-search me-1"></i>
                        Ver certificado completo
                    </button>
                @endif
            </x-slot:footer>
        </x-crud.card>

        @if ($signature->type === 'official')
            <x-crud.modal
                id="certificate-{{ $signature->id }}"
                size="xl"
                :scrollable="true"
            >
                <x-crud.modal-header
                    :title="'Certificado digital · ' . $signature->display_code"
                    :subtitle="$signature->user->name"
                />

                <div class="modal-body">
                    <pre class="small text-body bg-body-tertiary border rounded-3 p-3 mb-0 overflow-auto">{{ json_encode($certificate, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                </div>
            </x-crud.modal>
        @endif
    @empty
        <x-crud.empty
            icon="ti ti-signature"
            title="No hay firmas registradas"
            description="Empieza registrando la primera firma."
            action
            action-text="Nueva firma"
            action-modal="createModal"
        />
    @endforelse

</div>
