<aside class="sidebar">
    {{-- BRAND --}}
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
            @include('layouts.brand')
    </a>

    {{-- MENU --}}
    <div class="menu" id="SidebarMenu">
        @include('layouts.menu')
    </div>
</aside>
