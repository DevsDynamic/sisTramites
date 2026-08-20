<div id="documentSeriesContainer" class="row row-cards">

    @forelse($series as $serie)
        <x-crud.card>

            {{-- HEADER --}}
            <x-slot:header>
                <x-crud.card-header :title="$serie->documentType->name" :subtitle="$serie->area?->name ?? 'Global'">
                    <x-slot:badge>
                        <x-crud.badge :active="$serie->active" modal="activeModal" :dataset="[
                            'id' => $serie->id,
                            'name' => $serie->prefix,
                            'entity' => 'Serie',
                            'url' => route('document-series.active', $serie),
                            'active' => $serie->active ? 'deactivate' : 'activate',
                        ]" />
                    </x-slot:badge>
                </x-crud.card-header>
            </x-slot:header>


            {{-- BODY --}}
            <div>
                {{-- PREFIJO --}}
                <div class="mb-3">
                    <div class="text-secondary small">
                        Prefijo
                    </div>

                    <div class="fw-bold fs-2">
                        {{ $serie->prefix }}
                    </div>
                </div>


                {{-- INFORMACIÓN --}}
                <div class="row text-center">

                    {{-- ACTUAL --}}
                    <div class="col-4">
                        <div class="text-secondary small">
                            Actual
                        </div>

                        <div class="fw-bold">
                            {{ str_pad($serie->current_number, $serie->padding, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>


                    {{-- PADDING --}}
                    <div class="col-4">
                        <div class="text-secondary small">
                            Padding
                        </div>

                        <div class="fw-bold">
                            {{ $serie->padding }}
                        </div>
                    </div>


                    {{-- RESET --}}
                    <div class="col-4">
                        <div class="text-secondary small">
                            Reset
                        </div>

                        <div class="fw-bold">
                            {{ $serie->reset_yearly ? 'Sí' : 'No' }}
                        </div>
                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <x-slot:footer>
                <x-crud.actions>
                    {{-- EDITAR --}}
                    <x-crud.button-action color="warning" icon="ti ti-edit" title="Editar" size="sm"
                        modal="editModal" class="edit-btn" :dataset="[
                            'url' => route('document-series.update', $serie),
                            'id' => $serie->id,
                            'document_type_id' => $serie->document_type_id,
                            'area_id' => $serie->area_id,
                            'prefix' => $serie->prefix,
                            'current_number' => $serie->current_number,
                            'padding' => $serie->padding,
                            'reset_yearly' => $serie->reset_yearly,
                            'active' => $serie->active,
                        ]" />

                    {{-- ACTIVAR / DESACTIVAR --}}
                    @if ($serie->canDeactivate() || $serie->canActivate())
                        <x-crud.button-action :color="$serie->active ? 'danger' : 'success'" :icon="$serie->active ? 'ti ti-toggle-right' : 'ti ti-toggle-left'" :title="$serie->active ? 'Desactivar' : 'Activar'" modal="activeModal"
                            size="sm" class="active-btn" :dataset="[
                                'active' => $serie->active ? 'deactivate' : 'activate',
                                'entity' => 'Serie',
                                'name' => $serie->prefix,
                                'url' => route('document-series.active', $serie),
                            ]" />
                    @endif

                    {{-- ELIMINAR --}}
                    @if ($serie->canDelete())
                        <x-crud.button-action color="danger" icon="ti ti-trash" title="Eliminar" modal="deleteModal"
                            size="sm" class="delete-btn" :dataset="[
                                'url' => route('document-series.destroy', $serie),
                                'entity' => 'Serie',
                                'name' => $serie->prefix,
                            ]" />
                    @endif
                </x-crud.actions>
            </x-slot:footer>
        </x-crud.card>

    @empty
        <x-crud.empty icon="ti ti-number" title="No hay series configuradas"
            description="Empieza creando la primera serie." action action-text="Nueva serie"
            action-modal="createModal" />
    @endforelse

</div>
