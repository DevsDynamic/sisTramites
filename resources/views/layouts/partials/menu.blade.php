@php
    $documentsOpen = request()->routeIs('documents.*');
@endphp

@can('dashboard.view')
    <div class="menu-divider" aria-hidden="true"></div>

    <a href="{{ route('dashboard') }}" class="nav-link menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="ti ti-dashboard"></i>
        <span>Dashboard</span>
    </a>
@endcan

<div class="menu-section">
    <small class="text-uppercase menu-label">Gestión documental</small>
</div>

@can('documents.view')
    <div class="menu-dropdown">
        <button type="button" class="menu-item menu-toggle {{ $documentsOpen ? 'active-parent' : '' }}"
            data-bs-toggle="collapse" data-bs-target="#documentMenu"
            aria-expanded="{{ $documentsOpen ? 'true' : 'false' }}" aria-controls="documentMenu">
            <i class="ti ti-file-text"></i>
            <span>Documentos</span>
            <i class="ti ti-chevron-down"></i>
        </button>

        <div class="collapse {{ $documentsOpen ? 'show' : '' }}" id="documentMenu">
            <div class="submenu">
                <a href="{{ route('documents.index') }}" class="nav-link menu-item {{ request()->routeIs('documents.index') ? 'active' : '' }}">
                    <i class="ti ti-folder"></i>
                    <span>Mis documentos</span>
                </a>

                <a href="{{ route('documents.create') }}" class="nav-link menu-item {{ request()->routeIs('documents.create') ? 'active' : '' }}">
                    <i class="ti ti-circle-plus"></i>
                    <span>Crear documento</span>
                </a>
            </div>
        </div>
    </div>
@endcan

@can('flows.view')
    <a href="{{ route('workflow-inbox.index') }}" class="nav-link menu-item {{ request()->routeIs('workflow-inbox.*') ? 'active' : '' }}">
        <i class="ti ti-inbox"></i>
        <span>Mi bandeja</span>
    </a>
@endcan

<div class="menu-section">
    <small class="text-uppercase menu-label">Configuración documental</small>
</div>

@can('document-types.view')
    <a href="{{ route('document-types.index') }}" class="nav-link menu-item {{ request()->routeIs('document-types.*') ? 'active' : '' }}">
        <i class="ti ti-file-settings"></i>
        <span>Tipos de documento</span>
    </a>
@endcan

@can('document-series.view')
    <a href="{{ route('document-series.index') }}" class="nav-link menu-item {{ request()->routeIs('document-series.*') ? 'active' : '' }}">
        <i class="ti ti-number"></i>
        <span>Series documentales</span>
    </a>
@endcan

@can('signature.view')
    <a href="{{ route('signatures.index') }}" class="nav-link menu-item {{ request()->routeIs('signatures.*') ? 'active' : '' }}">
        <i class="ti ti-signature"></i>
        <span>Firmas digitales</span>
    </a>
@endcan

@can('flows.view')
    <a href="{{ route('workflow-templates.index') }}" class="nav-link menu-item {{ request()->routeIs('workflow-templates.*') ? 'active' : '' }}">
        <i class="ti ti-route"></i>
        <span>Flujos de aprobaci&oacute;n</span>
    </a>
@endcan

<div class="menu-section">
    <small class="text-uppercase menu-label">Organización y acceso</small>
</div>

@can('areas.view')
    <a href="{{ route('areas.index') }}" class="nav-link menu-item {{ request()->routeIs('areas.*') ? 'active' : '' }}">
        <i class="ti ti-building"></i>
        <span>Áreas</span>
    </a>
@endcan

@can('users.view')
    <a href="{{ route('users.index') }}" class="nav-link menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <i class="ti ti-users"></i>
        <span>Usuarios</span>
    </a>
@endcan

@can('roles.view')
    <a href="{{ route('roles.index') }}" class="nav-link menu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
        <i class="ti ti-shield-lock"></i>
        <span>Roles y permisos</span>
    </a>
@endcan

<div class="menu-section">
    <small class="text-uppercase menu-label">Sistema</small>
</div>

@can('settings.view')
    <a href="{{ route('onboarding.welcome') }}" class="nav-link menu-item {{ request()->routeIs('onboarding.*') ? 'active' : '' }}">
        <i class="ti ti-settings"></i>
        <span>Configuración general</span>
    </a>
@endcan
