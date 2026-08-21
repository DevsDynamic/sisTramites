<div class="topbar">
    <div class="container-fluid px-4 py-3">
        <div class="d-flex justify-content-between align-items-center">
            {{-- LEFT --}}
            <button class="btn btn-icon d-lg-none" id="sidebarToggle">
                <i class="ti ti-menu-2"></i>
            </button>

            <div>
                <h2 class="page-title mb-0">
                    @yield('title')
                </h2>
            </div>
            {{-- RIGHT --}}
            <div class="topbar-actions">
                {{-- EXPIRACION --}}
                <div class="expiration" title="{{ setting()?->license_expires_at ? 'Vence el ' . setting()->license_expires_at->format('d/m/Y') : 'Sin vencimiento configurado' }}">
                    Expira: {{ setting()?->license_expires_at?->format('d/m/Y') ?? '—' }}
                </div>

                {{-- NOTIFICACIONES --}}
                <x-crud.badge id="notif-count" text="0" color="danger" />

                {{-- DARK MODE --}}
                <button id="themeToggle" class="btn btn-icon">
                </button>

                {{-- USUARIO --}}
                <div class="dropdown">
                    <button class="topbar-profile-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(auth()->user()?->avatar_path)
                            <span class="avatar avatar-sm"><img src="{{ route('profile.avatar') }}" alt="Avatar de {{ auth()->user()->name }}"></span>
                        @else
                            <span class="avatar avatar-sm bg-primary-lt text-primary">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                        @endif
                        <span class="topbar-user">{{ auth()->user()->name ?? '' }}</span>
                        <i class="ti ti-chevron-down small"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end topbar-profile-menu">
                        <div class="topbar-profile-identity"><div class="fw-semibold">{{ auth()->user()->name }}</div><div class="text-secondary small">{{ auth()->user()->email }}</div></div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="ti ti-user-circle me-2"></i>Mi perfil y seguridad</a>
                    </div>
                </div>

                {{-- LOGOUT --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button class="btn btn-outline-danger btn-sm">
                        <i class="ti ti-logout"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
