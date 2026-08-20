<div id="documentTypesContainer" class="row row-cards">

    @forelse($types as $type)
        <x-crud.card>

            {{-- HEADER --}}
            <x-slot:header>
                <x-crud.card-header :title="$type->name" :subtitle="$type->code">
                    <x-slot:badge>
                        <x-crud.badge :active="$type->active" modal="activeModal" :dataset="[
                            'id' => $type->id,
                            'name' => $type->name,
                            'entity' => 'Tipo de documento',
                            'url' => route('document-types.active', $type),
                            'active' => $type->active ? 'deactivate' : 'activate',
                        ]" />
                    </x-slot:badge>
                </x-crud.card-header>
            </x-slot:header>

            {{-- Información adicional futura --}}

            {{-- FOOTER --}}
            <x-slot:footer>
                <x-crud.actions>
                    {{-- Editar --}}
                    <x-crud.button-action color="warning" icon="ti ti-edit" title="Editar" size="sm" modal="editModal" class="edit-btn"
                        :dataset="[
                            'url' => route('document-types.update', $type),
                            'id' => $type->id,
                            'name' => $type->name,
                            'code' => $type->code,
                            'active' => $type->active,
                        ]" />

                    {{-- Activar / Desactivar --}}
                    @if ($type->canDeactivate() || $type->canActivate())
                        <x-crud.button-action :color="$type->active ? 'danger' : 'success'" :icon="$type->active ? 'ti ti-toggle-right' : 'ti ti-toggle-left'" :title="$type->active ? 'Desactivar' : 'Activar'" modal="activeModal"
                             size="sm"  class="active-btn" :dataset="[
                                'active' => $type->active ? 'deactivate' : 'activate',
                                'entity' => 'Tipo de documento',
                                'name' => $type->name,
                                'url' => route('document-types.active', $type),
                            ]" />
                    @endif

                    {{-- Eliminar --}}
                    @if ($type->canDelete())
                        <x-crud.button-action color="danger" icon="ti ti-trash" title="Eliminar" modal="deleteModal"
                             size="sm"  class="delete-btn" :dataset="[
                                'url' => route('document-types.destroy', $type),
                                'entity' => 'Tipo de documento',
                                'name' => $type->name,
                            ]" />
                    @endif
                </x-crud.actions>
            </x-slot:footer>
        </x-crud.card>
    @empty
        <x-crud.empty icon="ti ti-file-settings" title="No hay tipos de documentos registrados"
            description="Empieza creando el primer tipo de documento." action action-text="Nuevo tipo de documento"
            action-modal="createModal" />
    @endforelse
</div>
