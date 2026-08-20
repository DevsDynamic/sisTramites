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
                <div class="expiration">
                    Expira:
                </div>

                {{-- NOTIFICACIONES --}}
                <x-crud.badge id="notif-count" text="0" color="danger" />

                {{-- DARK MODE --}}
                <button id="themeToggle" class="btn btn-icon">
                </button>

                {{-- USER --}}
                <div class="topbar-user">
                    {{ auth()->user()->name ?? '' }}
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
