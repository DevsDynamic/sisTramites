{{-- DASHBOARD --}}
@can('dashboard.view')
    <a href="{{ route('dashboard') }}"
        class="nav-link menu-item
   {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="ti ti-dashboard"></i>
        <span>Dashboard</span>
    </a>
@endcan
{{-- GESTIÓN DOCUMENTAL --}}
<div class="menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase menu-label">
        Gestión Documental
    </small>
</div>
{{-- DOCUMENTOS --}}
@can('documents.view')
    <div class="menu-dropdown">
        <button type="button" data-bs-toggle="collapse" data-bs-target="#documentMenu" aria-expanded="false"
            class="menu-item menu-toggle border-0 bg-transparent w-100">

            <i class="ti ti-file-text"></i>
            <span>Documentos</span>
            <i class="ti ti-chevron-down"></i>

        </button>

        <div class="collapse {{ request()->routeIs('documents.*') ? 'show' : '' }}" id="documentMenu">
            <div class="submenu">
                {{-- BANDEJA ENTRADA --}}
                {{-- <a href="{{ route('documents.inbox') }}"
                    class="nav-link menu-item
                                    {{ request()->routeIs('documents.inbox') ? 'active' : '' }}">
                    <i class="ti ti-inbox"></i>
                    <span>Bandeja Entrada</span>
                    <span class="badge bg-danger ms-auto">
                        {{ $pendingInbox ?? 0 }}
                    </span>
                </a> --}}
                {{-- BANDEJA SALIDA --}}
                {{-- <a href="{{ route('documents.outbox') }}"
                    class="nav-link menu-item
                                    {{ request()->routeIs('documents.outbox') ? 'active' : '' }}">
                    <i class="ti ti-send"></i>
                    <span>Bandeja Salida</span>
                </a> --}}
                {{-- MIS DOCUMENTOS --}}
                <a href="{{ route('documents.index') }}"
                    class="nav-link menu-item
                                    {{ request()->routeIs('documents.index') ? 'active' : '' }}">
                    <i class="ti ti-folder"></i>
                    <span>Mis Documentos</span>
                </a>
                {{-- CREAR --}}
                <a href="{{ route('documents.create') }}"
                    class="nav-link menu-item
                                    {{ request()->routeIs('documents.create') ? 'active' : '' }}">
                    <i class="ti ti-circle-plus"></i>
                    <span>Crear Documento</span>
                </a>
                {{-- TRACKING --}}
                {{-- <a href="{{ route('documents.tracking') }}"
                    class="nav-link menu-item
                                    {{ request()->routeIs('documents.tracking') ? 'active' : '' }}">
                    <i class="ti ti-route"></i>
                    <span>Seguimiento</span>
                </a> --}}
                {{-- SEARCH --}}
                {{-- <a href="{{ route('documents.search') }}"
                    class="nav-link menu-item
                                    {{ request()->routeIs('documents.search') ? 'active' : '' }}">
                    <i class="ti ti-search"></i>
                    <span>Búsqueda Global</span>
                </a> --}}
                {{-- ARCHIVADOS --}}
                {{-- <a href="{{ route('documents.archived') }}" class="nav-link menu-item">
                    <i class="ti ti-archive"></i>
                    <span>Archivados</span>
                </a> --}}
            </div>
        </div>
    </div>
@endcan
{{-- WORKFLOW --}}
{{-- <div class="menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase menu-label">
        Workflow
    </small>
</div>
@can('flows.view')
    <div class="menu-dropdown">
        <a href="#workflowMenu" data-bs-toggle="collapse"
            class="nav-link menu-item menu-toggle
                {{ request()->routeIs('workflow.*') ? 'active' : '' }}">
            <i class="ti ti-git-branch"></i>
            <span>Workflow</span>
            <i class="ti ti-chevron-down"></i>
        </a>
        <div class="collapse
                    {{ request()->routeIs('workflow.*') ? 'show' : '' }}"
            id="workflowMenu">
            <div class="submenu">
                <a href="{{ route('workflow.flows') }}" class="nav-link menu-item">
                    <i class="ti ti-arrows-transfer-up"></i>
                    <span>Flujos</span>
                </a>
                <a href="{{ route('workflow.sla') }}" class="nav-link menu-item">
                    <i class="ti ti-clock-hour-4"></i>
                    <span>SLA</span>
                </a>
                <a href="{{ route('workflow.rules') }}" class="nav-link menu-item">
                    <i class="ti ti-shield-cog"></i>
                    <span>Reglas</span>
                </a>
            </div>
        </div>
    </div>
@endcan --}}
{{-- CONFIGURACIÓN DOCUMENTAL --}}
<div class="menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase menu-label">
        Configuración
    </small>
</div>
{{-- DOCUMENT TYPES --}}
@can('document-types.view')
    <a href="{{ route('document-types.index') }}"
        class="nav-link menu-item
                {{ request()->routeIs('document-types.*') ? 'active' : '' }}">
        <i class="ti ti-file-settings"></i>
        <span>Tipos Documento</span>
    </a>
@endcan
{{-- SERIES --}}
@can('document-series.view')
    <a href="{{ route('document-series.index') }}"
        class="nav-link menu-item
                {{ request()->routeIs('document-series.*') ? 'active' : '' }}">
        <i class="ti ti-number"></i>
        <span>Series</span>
    </a>
@endcan
{{-- FIRMA --}}
@can('signature.view')
    <a href="{{ route('signatures.index') }}"
        class="nav-link menu-item
                {{ request()->routeIs('signatures.*') ? 'active' : '' }}">
        <i class="ti ti-signature"></i>
        <span>Firma Digital</span>
    </a>
@endcan
{{-- ORGANIZACIÓN --}}
<div class="menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase menu-label">
        Organización
    </small>
</div>
{{-- USERS --}}
@can('users.view')
    <a href="{{ route('users.index') }}"
        class="nav-link menu-item
                {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <i class="ti ti-users"></i>
        <span>Usuarios</span>
    </a>
@endcan
{{-- ROLES --}}
@can('roles.view')
    <a href="{{ route('roles.index') }}"
        class="nav-link menu-item
                {{ request()->routeIs('roles.*') ? 'active' : '' }}">
        <i class="ti ti-shield-lock"></i>
        <span>Roles</span>
    </a>
@endcan
{{-- AREAS --}}
@can('areas.view')
    <a href="{{ route('areas.index') }}"
        class="nav-link menu-item
                {{ request()->routeIs('areas.*') ? 'active' : '' }}">
        <i class="ti ti-building"></i>
        <span>Áreas</span>
    </a>
@endcan
{{-- ANALYTICS --}}
{{-- <div class="menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase menu-label">
        Reportes
    </small>
</div>
<a href="{{ route('analytics.documents') }}" class="nav-link menu-item">
    <i class="ti ti-chart-bar"></i>
    <span>Analytics</span>
</a> --}}
{{-- COMUNICACIÓN --}}
{{-- <div class="menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase menu-label">
        Comunicación
    </small>
</div>
<a href="{{ route('notifications.index') }}" class="nav-link menu-item">
    <i class="ti ti-bell"></i>
    <span>Notificaciones</span>
</a> --}}
{{-- SISTEMA --}}
<div class="menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase menu-label">
        Sistema
    </small>
</div>
@can('settings.view')
    <a href="{{ route('onboarding.welcome') }}" class="nav-link menu-item">
        <i class="ti ti-settings"></i>
        <span>Configuración</span>
    </a>
@endcan
