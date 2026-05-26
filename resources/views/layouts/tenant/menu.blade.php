{{-- DASHBOARD --}}
@can('dashboard.view')
    <a href="{{ route('tenant.dashboard') }}"
        class="nav-link tenant-menu-item
   {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
        <i class="ti ti-dashboard"></i>
        <span>Dashboard</span>
    </a>
@endcan
{{-- GESTIÓN DOCUMENTAL --}}
<div class="tenant-menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase tenant-menu-label">
        Gestión Documental
    </small>
</div>
{{-- DOCUMENTOS --}}
@can('documents.view')
    <div class="tenant-menu-dropdown">
        <button type="button" data-bs-toggle="collapse" data-bs-target="#documentMenu" aria-expanded="false"
            class="tenant-menu-item tenant-menu-toggle border-0 bg-transparent w-100">

            <i class="ti ti-file-text"></i>
            <span>Documentos</span>
            <i class="ti ti-chevron-down"></i>

        </button>

        <div class="collapse {{ request()->routeIs('tenant.documents.*') ? 'show' : '' }}" id="documentMenu">
            <div class="tenant-submenu">
                {{-- BANDEJA ENTRADA --}}
                <a href="{{ route('tenant.documents.inbox') }}"
                    class="nav-link tenant-menu-item
                                    {{ request()->routeIs('tenant.documents.inbox') ? 'active' : '' }}">
                    <i class="ti ti-inbox"></i>
                    <span>Bandeja Entrada</span>
                    <span class="badge bg-danger ms-auto">
                        {{ $pendingInbox ?? 0 }}
                    </span>
                </a>
                {{-- BANDEJA SALIDA --}}
                <a href="{{ route('tenant.documents.outbox') }}"
                    class="nav-link tenant-menu-item
                                    {{ request()->routeIs('tenant.documents.outbox') ? 'active' : '' }}">
                    <i class="ti ti-send"></i>
                    <span>Bandeja Salida</span>
                </a>
                {{-- MIS DOCUMENTOS --}}
                <a href="{{ route('tenant.documents.index') }}"
                    class="nav-link tenant-menu-item
                                    {{ request()->routeIs('tenant.documents.index') ? 'active' : '' }}">
                    <i class="ti ti-folder"></i>
                    <span>Mis Documentos</span>
                </a>
                {{-- CREAR --}}
                <a href="{{ route('tenant.documents.create') }}"
                    class="nav-link tenant-menu-item
                                    {{ request()->routeIs('tenant.documents.create') ? 'active' : '' }}">
                    <i class="ti ti-circle-plus"></i>
                    <span>Crear Documento</span>
                </a>
                {{-- TRACKING --}}
                <a href="{{ route('tenant.documents.tracking') }}"
                    class="nav-link tenant-menu-item
                                    {{ request()->routeIs('tenant.documents.tracking') ? 'active' : '' }}">
                    <i class="ti ti-route"></i>
                    <span>Seguimiento</span>
                </a>
                {{-- SEARCH --}}
                <a href="{{ route('tenant.documents.search') }}"
                    class="nav-link tenant-menu-item
                                    {{ request()->routeIs('tenant.documents.search') ? 'active' : '' }}">
                    <i class="ti ti-search"></i>
                    <span>Búsqueda Global</span>
                </a>
                {{-- ARCHIVADOS --}}
                <a href="{{ route('tenant.documents.archived') }}" class="nav-link tenant-menu-item">
                    <i class="ti ti-archive"></i>
                    <span>Archivados</span>
                </a>
            </div>
        </div>
    </div>
@endcan
{{-- WORKFLOW --}}
{{-- <div class="tenant-menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase tenant-menu-label">
        Workflow
    </small>
</div>
@can('flows.view')
    <div class="tenant-menu-dropdown">
        <a href="#workflowMenu" data-bs-toggle="collapse"
            class="nav-link tenant-menu-item tenant-menu-toggle
                {{ request()->routeIs('tenant.workflow.*') ? 'active' : '' }}">
            <i class="ti ti-git-branch"></i>
            <span>Workflow</span>
            <i class="ti ti-chevron-down"></i>
        </a>
        <div class="collapse
                    {{ request()->routeIs('tenant.workflow.*') ? 'show' : '' }}"
            id="workflowMenu">
            <div class="tenant-submenu">
                <a href="{{ route('tenant.workflow.flows') }}" class="nav-link tenant-menu-item">
                    <i class="ti ti-arrows-transfer-up"></i>
                    <span>Flujos</span>
                </a>
                <a href="{{ route('tenant.workflow.sla') }}" class="nav-link tenant-menu-item">
                    <i class="ti ti-clock-hour-4"></i>
                    <span>SLA</span>
                </a>
                <a href="{{ route('tenant.workflow.rules') }}" class="nav-link tenant-menu-item">
                    <i class="ti ti-shield-cog"></i>
                    <span>Reglas</span>
                </a>
            </div>
        </div>
    </div>
@endcan --}}
{{-- CONFIGURACIÓN DOCUMENTAL --}}
<div class="tenant-menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase tenant-menu-label">
        Configuración
    </small>
</div>
{{-- DOCUMENT TYPES --}}
@can('document-types.view')
    <a href="{{ route('tenant.document-types.index') }}"
        class="nav-link tenant-menu-item
                {{ request()->routeIs('tenant.document-types.*') ? 'active' : '' }}">
        <i class="ti ti-file-settings"></i>
        <span>Tipos Documento</span>
    </a>
@endcan
{{-- SERIES --}}
@can('document-series.view')
    <a href="{{ route('tenant.document-series.index') }}"
        class="nav-link tenant-menu-item
                {{ request()->routeIs('tenant.document-series.*') ? 'active' : '' }}">
        <i class="ti ti-number"></i>
        <span>Series</span>
    </a>
@endcan
{{-- FIRMA --}}
@can('signature.view')
    <a href="{{ route('tenant.signatures.index') }}"
        class="nav-link tenant-menu-item
                {{ request()->routeIs('tenant.signatures.*') ? 'active' : '' }}">
        <i class="ti ti-signature"></i>
        <span>Firma Digital</span>
    </a>
@endcan
{{-- ORGANIZACIÓN --}}
<div class="tenant-menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase tenant-menu-label">
        Organización
    </small>
</div>
{{-- USERS --}}
@can('users.view')
    <a href="{{ route('tenant.users.index') }}"
        class="nav-link tenant-menu-item
                {{ request()->routeIs('tenant.users.*') ? 'active' : '' }}">
        <i class="ti ti-users"></i>
        <span>Usuarios</span>
    </a>
@endcan
{{-- ROLES --}}
@can('roles.view')
    <a href="{{ route('tenant.roles.index') }}"
        class="nav-link tenant-menu-item
                {{ request()->routeIs('tenant.roles.*') ? 'active' : '' }}">
        <i class="ti ti-shield-lock"></i>
        <span>Roles</span>
    </a>
@endcan
{{-- AREAS --}}
@can('areas.view')
    <a href="{{ route('tenant.areas.index') }}"
        class="nav-link tenant-menu-item
                {{ request()->routeIs('tenant.areas.*') ? 'active' : '' }}">
        <i class="ti ti-building"></i>
        <span>Áreas</span>
    </a>
@endcan
{{-- ANALYTICS --}}
{{-- <div class="tenant-menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase tenant-menu-label">
        Reportes
    </small>
</div>
<a href="{{ route('tenant.analytics.documents') }}" class="nav-link tenant-menu-item">
    <i class="ti ti-chart-bar"></i>
    <span>Analytics</span>
</a> --}}
{{-- COMUNICACIÓN --}}
{{-- <div class="tenant-menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase tenant-menu-label">
        Comunicación
    </small>
</div>
<a href="{{ route('tenant.notifications.index') }}" class="nav-link tenant-menu-item">
    <i class="ti ti-bell"></i>
    <span>Notificaciones</span>
</a> --}}
{{-- SISTEMA --}}
<div class="tenant-menu-section mt-4 mb-2 px-2">
    <small class="text-uppercase tenant-menu-label">
        Sistema
    </small>
</div>
@can('settings.view')
    <a href="{{ route('tenant.onboarding.welcome') }}" class="nav-link tenant-menu-item">
        <i class="ti ti-settings"></i>
        <span>Configuración</span>
    </a>
@endcan
