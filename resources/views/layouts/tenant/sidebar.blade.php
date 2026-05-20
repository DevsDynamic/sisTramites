<aside class="tenant-sidebar">
    {{-- BRAND --}}
    <a href="{{ route('tenant.dashboard') }}" class="tenant-sidebar-brand">
            @include('layouts.tenant.brand')
    </a>

    {{-- MENU --}}
    <div class="tenant-menu" id="tenantSidebarMenu">
        @include('layouts.tenant.menu')
    </div>
</aside>
