<div class="topbar">
    <div class="container-fluid px-4 py-3">
        <div class="d-flex justify-content-between align-items-center">
            {{-- LEFT --}}
            <div>
                <h2 class="page-title mb-0">
                    @yield('title')
                </h2>
            </div>
            {{-- RIGHT --}}
            <div class="d-flex align-items-center gap-3">
                {{-- EXPIRACION --}}
                <div class="expiration">
                    Expira:
                    
                </div>
                <div>
                    <span id="notif-count" class="badge bg-danger">
                        0
                    </span>
                </div>
                {{-- DARK MODE --}}
                <button class="btn btn-icon" onclick="toggleDarkMode()">
                    <i class="ti ti-moon"></i>
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
