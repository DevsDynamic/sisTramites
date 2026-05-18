{{-- DASHBOARD --}}
@can('dashboard.view')
    <a href="{{ route('tenant.dashboard') }}"
        class="nav-link tenant-menu-item
   {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
        <i class="ti ti-dashboard"></i>
        <span>Dashboard</span>
    </a>
@endcan
{{-- DOCUMENTOS --}}
@can('documents.view')
    <a href="#" class="nav-link tenant-menu-item">
        <i class="ti ti-file-text"></i>
        <span>Documentos</span>
    </a>
@endcan
{{-- FLUJOS --}}
@can('flows.view')
    <a href="#" class="nav-link tenant-menu-item">
        <i class="ti ti-git-branch"></i>
        <span>Flujos</span>
    </a>
@endcan
{{-- FIRMA DIGITAL --}}
@can('signature.view')
    <a href="#" class="nav-link tenant-menu-item">
        <i class="ti ti-signature"></i>
        <span>Firma Digital</span>
    </a>
@endcan
{{-- USUARIOS --}}
@can('users.view')
    <a href="{{ route('tenant.users.index') }}"
        class="nav-link tenant-menu-item
   {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <i class="ti ti-users"></i>
        <span>Usuarios</span>
    </a>
@endcan
{{-- ROLES --}}
@can('roles.view')
    <a href="{{ route('tenant.roles.index') }}"
        class="nav-link tenant-menu-item
   {{ request()->routeIs('roles.*') ? 'active' : '' }}">
        <i class="ti ti-shield-lock"></i>
        <span>Roles</span>
    </a>
@endcan
{{-- ÁREAS --}}
@can('areas.view')
    <a href="{{ route('tenant.areas.index') }}"
        class="nav-link tenant-menu-item
   {{ request()->routeIs('areas.*') ? 'active' : '' }}">
        <i class="ti ti-building"></i>
        <span>Áreas</span>
    </a>
@endcan
{{-- CONFIGURACIÓN --}}
@can('settings.view')
    <a href="{{ route('tenant.onboarding.welcome') }}"
        class="nav-link tenant-menu-item
   {{ request()->routeIs('tenant.onboarding.*') ? 'active' : '' }}">
        <i class="ti ti-settings"></i>
        <span>Configuración</span>
    </a>
@endcan
