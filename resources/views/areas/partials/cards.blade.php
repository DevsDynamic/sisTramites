<div id="areasContainer" class="row row-cards">
    @forelse ($areas as $area)
        <x-crud.card :stretch="false">
            <x-slot:header>
                <x-crud.card-header :title="$area->name" :subtitle="$area->code">
                    <x-slot:badge>
                        <x-crud.badge :active="$area->active" modal="activeModal" :dataset="[
                            'url' => route('areas.active', $area),
                            'name' => $area->name,
                            'entity' => 'Área',
                            'active' => $area->active ? 'deactivate' : 'activate',
                        ]" />
                    </x-slot:badge>
                </x-crud.card-header>
            </x-slot:header>

            @if ($area->description)
                @php
                    $descriptionLimit = 140;
                    $hasLongDescription = \Illuminate\Support\Str::length($area->description) > $descriptionLimit;
                @endphp

                <div class="text-secondary mb-3">
                    <p class="mb-0">
                        {{ $hasLongDescription
                            ? \Illuminate\Support\Str::limit($area->description, $descriptionLimit)
                            : $area->description }}
                    </p>

                    @if ($hasLongDescription)
                        <details class="mt-2">
                            <summary class="text-primary cursor-pointer">
                                Ver más
                            </summary>
                            <p class="mt-2 mb-0 text-break">
                                {{ $area->description }}
                            </p>
                        </details>
                    @endif
                </div>
            @endif

            <div class="d-flex flex-wrap gap-2 small">
                <span class="badge bg-azure-lt">
                    <i class="ti ti-users me-1"></i>{{ $area->users_count }} usuario(s)
                </span>
                <span class="badge bg-indigo-lt">
                    <i class="ti ti-list-numbers me-1"></i>{{ $area->document_series_count }} serie(s)
                </span>
                <span class="badge bg-secondary-lt">
                    <i class="ti ti-file-text me-1"></i>{{ $area->documents_count }} documento(s)
                </span>
            </div>

            <x-slot:footer>
                <x-crud.actions>
                    <x-crud.button-action
                        permission="areas.edit"
                        color="warning"
                        icon="ti ti-edit"
                        title="Editar"
                        size="sm"
                        modal="editModal"
                        class="edit-btn"
                        :dataset="[
                            'url' => route('areas.update', $area),
                            'id' => $area->id,
                            'name' => $area->name,
                            'code' => $area->code,
                            'description' => $area->description,
                            'active' => $area->active,
                        ]"
                    />

                    @if ($area->canDeactivate() || $area->canActivate())
                        <x-crud.button-action
                            permission="areas.edit"
                            :color="$area->active ? 'danger' : 'success'"
                            :icon="$area->active ? 'ti ti-toggle-right' : 'ti ti-toggle-left'"
                            :title="$area->active ? 'Desactivar' : 'Activar'"
                            size="sm"
                            modal="activeModal"
                            :dataset="[
                                'url' => route('areas.active', $area),
                                'name' => $area->name,
                                'entity' => 'Área',
                                'active' => $area->active ? 'deactivate' : 'activate',
                            ]"
                        />
                    @endif

                    @if ($area->canDelete())
                        <x-crud.button-action
                            permission="areas.delete"
                            color="danger"
                            icon="ti ti-trash"
                            title="Eliminar"
                            size="sm"
                            modal="deleteModal"
                            :dataset="[
                                'url' => route('areas.destroy', $area),
                                'entity' => 'Área',
                                'name' => $area->name,
                            ]"
                        />
                    @endif
                </x-crud.actions>
            </x-slot:footer>
        </x-crud.card>
    @empty
        <x-crud.empty
            icon="ti ti-building-community"
            title="No hay áreas registradas"
            description="Empieza creando la primera área organizacional."
            action
            action-text="Nueva área"
            action-modal="createModal"
        />
    @endforelse
</div>
