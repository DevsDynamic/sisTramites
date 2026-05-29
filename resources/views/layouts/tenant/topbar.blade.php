<div class="tenant-topbar">
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
                <div class="tenant-expiration">
                    Expira:
                    {{ tenant('expires_at') ? \Carbon\Carbon::parse(tenant('expires_at'))->format('d/m/Y') : '--' }}
                </div>
                <div>
                    {{-- @php
                        $count = \App\Models\Tenant\Notification::where('user_id', auth()->id())
                            ->where('read', false)
                            ->count();
                    @endphp

                    <a href="{{ route('tenant.notifications.index') }}" class="btn-icon position-relative">
                        <i class="ti ti-bell"></i>

                        @if ($count > 0)
                            <span class="badge bg-danger position-absolute top-0 end-0">
                                {{ $count }}
                            </span>
                        @endif
                    </a> --}}

                    <span id="notif-count" class="badge bg-danger">
                        0
                    </span>
                </div>
                {{-- DARK MODE --}}
                <button class="btn btn-icon" onclick="toggleDarkMode()">
                    <i class="ti ti-moon"></i>
                </button>
                {{-- USER --}}
                <div class="tenant-topbar-user">
                    {{-- {{ auth('tenant')->user()->name ?? 'Tenant' }} --}}
                    {{ \App\Models\Tenant\TenantUser::first()->name ?? 'Tenant' }}
                </div>
                {{-- LOGOUT --}}
                <form method="POST" action="{{ route('tenant.logout') }}">
                    @csrf

                    <button class="btn btn-outline-danger btn-sm">
                        <i class="ti ti-logout"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
