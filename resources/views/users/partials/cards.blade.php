<div id="usersContainer" class="row row-cards">
    @forelse ($users as $user)
        @php
            $canManageUser = $isSystemOwner || ($canManageAdmins && ! $user->isSystemOwner()) || (! $user->hasRole('Administrador') && ! $user->isSystemOwner());
            $canChangeLifecycle = $canManageUser && ! $user->is(auth()->user());
        @endphp

        <x-crud.card>
            <x-slot:header>
                <x-crud.card-header :title="$user->name" :subtitle="$user->email">
                    <x-slot:badge>
                        @if ($canManageUser)
                            <x-crud.badge
                                :active="$user->active"
                                modal="activeModal"
                                :dataset="[
                                    'name' => $user->name,
                                    'entity' => 'Usuario',
                                    'url' => route('users.active', $user),
                                    'active' => $user->active ? 'deactivate' : 'activate',
                                ]"
                            />
                        @else
                            <x-crud.badge :active="$user->active" />
                        @endif
                    </x-slot:badge>
                </x-crud.card-header>
            </x-slot:header>

            <div class="mb-3">
                @if ($user->isSystemOwner())
                    <span class="badge bg-purple-lt me-1">
                        <i class="ti ti-crown me-1"></i>
                        Propietario del sistema
                    </span>
                @endif

                @if ($user->isOnline())
                    <span class="badge bg-green-lt">
                        <span class="status-dot status-dot-animated bg-green me-1"></span>
                        En línea
                    </span>
                @else
                    <span class="badge bg-secondary-lt">
                        <i class="ti ti-clock-hour-4 me-1"></i>
                        {{ $user->last_seen_at?->diffForHumans() ?? 'Nunca conectado' }}
                    </span>
                @endif
            </div>

            <div class="border rounded-3 p-3 mb-3 bg-body-tertiary">
                <div class="text-secondary small mb-2">
                    <i class="ti ti-shield-lock me-1"></i>
                    Roles
                </div>

                <div class="d-flex flex-wrap gap-2">
                    @forelse ($user->roles as $role)
                        <span class="badge bg-primary-lt">{{ $role->name }}</span>
                    @empty
                        <span class="badge bg-secondary-lt">Sin roles</span>
                    @endforelse
                </div>
            </div>

            <div class="border rounded-3 p-3 bg-body-tertiary">
                <div class="text-secondary small mb-2">
                    <i class="ti ti-building-community me-1"></i>
                    Áreas
                </div>

                <div class="d-flex flex-wrap gap-2">
                    @forelse ($user->areas as $area)
                        <span class="badge bg-azure-lt">{{ $area->name }}</span>
                    @empty
                        <span class="badge bg-secondary-lt">Sin áreas</span>
                    @endforelse
                </div>
            </div>

            <x-slot:footer>
                <x-crud.actions>
                    @if ($canManageUser)
                        <x-crud.button-action
                            permission="users.edit"
                            color="warning"
                            icon="ti ti-edit"
                            title="Editar"
                            size="sm"
                            modal="editModal"
                            class="edit-btn"
                            :dataset="[
                                'url' => route('users.update', $user),
                                'id' => $user->id,
                                'name' => $user->name,
                                'email' => $user->email,
                                'roles' => $user->roles->pluck('name')->toJson(),
                                'areas' => $user->areas->pluck('id')->toJson(),
                                'active' => $user->active,
                            ]"
                        />

                        @if ($canChangeLifecycle && ($user->canDeactivate() || $user->canActivate()))
                            <x-crud.button-action
                                permission="users.edit"
                                :color="$user->active ? 'danger' : 'success'"
                                :icon="$user->active ? 'ti ti-toggle-right' : 'ti ti-toggle-left'"
                                :title="$user->active ? 'Desactivar' : 'Activar'"
                                modal="activeModal"
                                size="sm"
                                :dataset="[
                                    'active' => $user->active ? 'deactivate' : 'activate',
                                    'entity' => 'Usuario',
                                    'name' => $user->name,
                                    'url' => route('users.active', $user),
                                ]"
                            />
                        @endif

                        @if ($canChangeLifecycle && $user->canDelete())
                            <x-crud.button-action
                                permission="users.delete"
                                color="danger"
                                icon="ti ti-trash"
                                title="Eliminar"
                                modal="deleteModal"
                                size="sm"
                                :dataset="[
                                    'url' => route('users.destroy', $user),
                                    'entity' => 'Usuario',
                                    'name' => $user->name,
                                ]"
                            />
                        @endif
                    @endif
                </x-crud.actions>
            </x-slot:footer>
        </x-crud.card>
    @empty
        <x-crud.empty
            icon="ti ti-users"
            title="No hay usuarios registrados"
            description="Empieza creando el primer usuario."
            action
            action-text="Nuevo usuario"
            action-modal="createModal"
        />
    @endforelse
</div>
