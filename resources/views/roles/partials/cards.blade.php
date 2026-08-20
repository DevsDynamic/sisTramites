<div id="rolesContainer" class="row row-cards">
    @forelse ($roles as $role)
        @php
            $canManageRole = $canManageSystem || ! $role->isSystem();
        @endphp

        <x-crud.card :stretch="false">
            <x-slot:header>
                <x-crud.card-header
                    :title="$role->name"
                    :subtitle="$role->permissions->count() . ' permiso(s)'"
                >
                    @if ($role->isSystem())
                        <x-slot:badge>
                            <span class="badge bg-purple-lt">
                                <i class="ti ti-lock me-1"></i>
                                Sistema
                            </span>
                        </x-slot:badge>
                    @endif
                </x-crud.card-header>
            </x-slot:header>

            <div class="d-flex flex-wrap gap-2">
                @forelse ($role->permissions as $permission)
                    <span
                        class="badge bg-primary-lt"
                        data-bs-toggle-tooltip="tooltip"
                        title="Código interno: {{ $permission->name }}"
                    >
                        {{ $permission->display_name }}
                    </span>
                @empty
                    <span class="badge bg-secondary-lt">Sin permisos asignados</span>
                @endforelse
            </div>

            <x-slot:footer>
                <x-crud.actions>
                    @if ($canManageRole)
                        <x-crud.button-action
                            permission="roles.edit"
                            color="warning"
                            icon="ti ti-edit"
                            title="Editar"
                            size="sm"
                            modal="editModal"
                            class="edit-btn"
                            :dataset="[
                                'url' => route('roles.update', $role),
                                'id' => $role->id,
                                'name' => $role->name,
                                'permissions' => $role->permissions->pluck('name')->toJson(),
                            ]"
                        />

                        @if ($role->canDelete())
                            <x-crud.button-action
                                permission="roles.delete"
                                color="danger"
                                icon="ti ti-trash"
                                title="Eliminar"
                                modal="deleteModal"
                                size="sm"
                                :dataset="[
                                    'url' => route('roles.destroy', $role),
                                    'entity' => 'Rol',
                                    'name' => $role->name,
                                ]"
                            />
                        @endif
                    @endif
                </x-crud.actions>
            </x-slot:footer>
        </x-crud.card>
    @empty
        <x-crud.empty
            icon="ti ti-shield-lock"
            title="No hay roles registrados"
            description="Empieza creando el primer rol operativo."
            action
            action-text="Nuevo rol"
            action-modal="createModal"
        />
    @endforelse
</div>
