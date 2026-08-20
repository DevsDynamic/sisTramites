<div class="row row-cards">
    @forelse ($documents as $document)
        <x-crud.card>
            <x-slot:header>
                <x-crud.card-header :title="$document->code" :subtitle="$document->type?->name">
                    <x-slot:badge>
                        <span class="badge bg-{{ $document->status->color() }}-lt">
                            <i class="{{ $document->status->icon() }} me-1"></i>{{ $document->status->label() }}
                        </span>
                    </x-slot:badge>
                </x-crud.card-header>
            </x-slot:header>

            <div class="fw-semibold mb-1 text-truncate" title="{{ $document->subject }}">{{ $document->subject }}</div>
            <div class="text-secondary small mb-3">
                <i class="ti ti-building me-1"></i>{{ $document->area?->name ?: 'Área no disponible' }}
                <span class="mx-1">·</span><i class="ti ti-calendar me-1"></i>{{ $document->updated_at->format('d/m/Y') }}
            </div>
            <div class="text-secondary small mb-3">
                {{ str($document->content ?: 'Sin descripción registrada.')->limit(100) }}
            </div>
            @php
                $mainFileCount = $document->attachments->where('kind', 'primary')->count();
                $annexCount = $document->attachments->where('kind', 'annex')->count();
                $signedCopyCount = $document->attachments->where('kind', 'signed_copy')->count();
            @endphp
            <div class="d-flex justify-content-between text-secondary small border-top pt-3">
                <span><i class="ti ti-file-description me-1"></i>{{ $mainFileCount }} principal · {{ $annexCount }} anexo(s) · {{ $signedCopyCount }} firma(s)</span>
                <span class="text-truncate ms-2"><i class="ti ti-user me-1"></i>{{ $document->creator?->name }}</span>
            </div>

            @if ($document->pending_signature_requests_count)
                <div class="small text-warning mt-2">
                    <i class="ti ti-signature me-1"></i>{{ $document->pending_signature_requests_count }} firma(s) pendiente(s)
                </div>
            @endif

            <x-slot:footer>
                <x-crud.actions>
                    <a href="{{ route('documents.show', $document) }}" class="btn btn-primary btn-sm" data-bs-toggle-tooltip="tooltip" title="Ver documento"><i class="ti ti-eye"></i></a>
                    @if ($document->canEdit() && ($canManageAll || auth()->id() === $document->created_by))
                        @can('documents.edit')
                            <a href="{{ route('documents.edit', $document) }}" class="btn btn-warning btn-sm" data-bs-toggle-tooltip="tooltip" title="Editar borrador"><i class="ti ti-edit"></i></a>
                        @endcan
                        @can('documents.delete')
                            <form method="POST" action="{{ route('documents.destroy', $document) }}" onsubmit="return confirm('¿Eliminar este borrador de forma permanente?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle-tooltip="tooltip" title="Eliminar borrador"><i class="ti ti-trash"></i></button>
                            </form>
                        @endcan
                    @endif
                </x-crud.actions>
            </x-slot:footer>
        </x-crud.card>
    @empty
        <x-crud.empty icon="ti ti-file-text" title="No hay documentos registrados" description="Crea el primer documento para iniciar la gestión documental." action action-text="Nuevo documento" action-href="{{ route('documents.create') }}" />
    @endforelse
</div>
