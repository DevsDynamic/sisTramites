<aside class="sidebar">
    {{-- BRAND --}}
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        @include('layouts.brand')
    </a>

    {{-- MENU --}}
    <div class="menu">
        @include('layouts.onboarding.menu')
    </div>
</aside>
