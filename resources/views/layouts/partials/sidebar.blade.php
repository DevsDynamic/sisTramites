<aside class="sidebar">
    {{-- BRAND --}}
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
            @include('layouts.partials.brand')
    </a>

    {{-- MENU --}}
    <div class="menu" id="SidebarMenu">
        @include('layouts.partials.menu')
    </div>
</aside>
