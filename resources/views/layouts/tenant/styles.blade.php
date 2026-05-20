<link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

@php
    function getContrastColor($hexColor)
    {
        $hexColor = str_replace('#', '', $hexColor);

        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;

        return $brightness > 155 ? '#000000' : '#FFFFFF';
    }

    $sidebarText = getContrastColor(tenant_sidebar_color());

    function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);

        return implode(',', [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))]);
    }

    $tenantPrimaryRgb = hexToRgb(tenant_primary_color());
@endphp

<style>
    :root {
        /* BRAND */
        --tenant-color:
            {{ tenant_primary_color() ?? '#206bc4' }};
        /* --sidebar-width: 280px; */
        --primary: {{ tenant_primary_color() }};
        --tenant-primary: {{ tenant_primary_color() }};
        --tenant-primary-rgb: {{ $tenantPrimaryRgb }};
        --tenant-sidebar: {{ tenant_sidebar_color() }};
        --tenant-sidebar-text: {{ $sidebarText }};
        --tenant-bg: #f5f7fb;
        --tenant-card: #ffffff;
        --tenant-text: #111827;
        --tenant-muted: #6b7280;

        /* LIGHT MODE */
        --app-bg: #f5f7fb;
        --card-bg: #ffffff;
        --topbar-bg: rgba(255, 255, 255, .85);
        --text-primary: #111827;
        --text-secondary: #282828;
        --border-color: #e5e7eb;
        --hover-bg: #f3f4f6;
        --shadow:
            0 4px 14px rgba(0, 0, 0, .05),
            0 1px 3px rgba(0, 0, 0, .06);
    }

    /* BODY */
    body {
        margin: 0;
        background: var(--app-bg);
        color: var(--text-primary);
        font-family: Inter, sans-serif;
        overflow-x: hidden;
        transition:
            background .25s ease,
            color .25s ease;
    }

    * {
        box-sizing: border-box;
    }

    /* LAYOUT */
    .tenant-layout {
        display: flex;
        min-height: 100vh;
    }

    /* MAIN */
    .tenant-main {
        flex: 1;
        margin-left: 280px;
        display: flex;
        flex-direction: column;
    }

    /* CONTENT */
    .tenant-content {
        width: 100%;
        padding: 30px;
        min-width: 0;
        overflow-x: hidden;
    }

    /* CARDS */
    /* .tenant-card {
        background: white;
        border-radius: 22px;
        border: none;
        box-shadow:
            0 10px 40px rgba(0, 0, 0, .04);
    } */
    .tenant-card {
        background: var(--card-bg);
        color: var(--text-primary);

        border-radius: 22px;
        border: 1px solid var(--border-color);

        box-shadow: var(--shadow);

        transition:
            background .25s ease,
            border-color .25s ease,
            color .25s ease,
            transform .2s ease,
            box-shadow .25s ease;
    }

    /* BUTTONS */
    .btn-tenant {
        background: var(--tenant-primary);
        color: #fff;
        border: none;
        transition: all .25s ease;
    }

    .btn-tenant:hover {
        background: color-mix(in srgb, var(--tenant-primary) 85%, black);
        color: #fff;
    }

    .btn-primary {
        background: var(--tenant-primary);
        color: #fff;
        border: none;
        transition: all .25s ease;
    }

    .btn-primary:hover {
        background: color-mix(in srgb, var(--tenant-primary) 85%, black);
        color: #fff;
    }

    .text-primary {
        color: var(--tenant-primary) !important;
    }

    .bg-primary {
        background: var(--tenant-primary) !important;
    }

    .border-primary {
        border-color: var(--tenant-primary) !important;
    }

    .text-tenant {
        color: var(--tenant-primary) !important;
    }

    .bg-tenant {
        background: var(--tenant-primary) !important;
    }

    .border-tenant {
        border-color: var(--tenant-primary) !important;
    }

    /*  RESPONSIVE */
    @media(max-width:992px) {
        .tenant-main {
            margin-left: 0;
        }
    }

    /* SIDEBAR */
    .tenant-sidebar {
        width: 280px;
        background: var(--tenant-sidebar);
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        padding: 24px 18px;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        border-right: 1px solid rgba(255, 255, 255, .08);
        z-index: 999;
        overflow-y: auto;
    }

    /* BRAND */
    .tenant-sidebar-brand {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 35px;
        padding: 10px;
        border-radius: 18px;
        transition: all .25s ease;
    }

    .tenant-sidebar-brand,
    .tenant-sidebar-brand:hover,
    .tenant-sidebar-brand:focus,
    .tenant-sidebar-brand:active {

        text-decoration: none;
    }

    .tenant-sidebar-brand:hover {
        background:
            color-mix(in srgb,
                white 10%,
                transparent);
        transform: translateY(-1px);
    }

    /* .tenant-sidebar-brand::after {
        content: '';
        margin-left: auto;
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #22c55e;
        opacity: .8;
    } */

    .tenant-sidebar-brand::after {
        content: '';
        margin-left: auto;
        width: 10px;
        height: 10px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow:
            0 0 0 rgba(34, 197, 94, 0.7);
        animation:
            tenantPulse 2s infinite;
    }

    .tenant-sidebar-logo {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        object-fit: contain;
        background: white;
        padding: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
    }

    .tenant-sidebar-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--tenant-sidebar-text);
        line-height: 1.2;
    }

    .tenant-sidebar-title:hover {}

    .tenant-sidebar-plan {
        opacity: .7;
        font-size: 13px;
        color: var(--tenant-sidebar-text);
    }

    .tenant-sidebar-plan:hover {}

    .tenant-sidebar-title,
    .tenant-sidebar-plan {
        pointer-events: none;
    }

    /*** MENU */
    .tenant-menu {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    /* SECTION TITLE */
    .tenant-menu-section {
        position: relative;
    }

    .tenant-menu-label {
        /* color: rgba(255, 255, 255, .55); */
        font-size: 11px;
        letter-spacing: .08em;
        font-weight: 700;
    }

    .tenant-menu-section small {
        display: block;
        padding-left: 8px;
    }

    /* DROPDOWN */
    .tenant-menu-dropdown {
        display: flex;
        flex-direction: column;
    }

    /* TOGGLE */
    .tenant-menu-toggle {
        position: relative;
    }

    /* CHEVRON */
    .tenant-menu-toggle .ti-chevron-down {
        transition:
            transform .25s ease;
    }

    /* ROTATE */
    .tenant-menu-toggle[aria-expanded="true"] .ti-chevron-down {
        transform: rotate(180deg);
    }

    /* SUBMENU */
    .tenant-submenu {
        margin-top: 6px;
        margin-left: 14px;

        padding-left: 14px;

        border-left:
            1px solid rgba(255, 255, 255, .08);

        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    /* SUBMENU ITEMS */
    .tenant-submenu .tenant-menu-item {

        padding:
            10px 12px;

        border-radius: 12px;

        font-size: 14px;

        opacity: .92;
    }

    /* SUBMENU ACTIVE */
    .tenant-submenu .tenant-menu-item.active {

        background:
            rgba(255, 255, 255, .12);

        color: white;

        box-shadow: none;
    }

    /* BADGES */
    .tenant-submenu .badge {
        font-size: 10px;
    }

    /*** SEPARATION */
    .tenant-menu-section:not(:first-child) {
        margin-top: 28px !important;
    }

    /* MENU ARROW */
    .menu-arrow {
        transition:
            transform .25s ease;
    }

    /* ROTATE WHEN OPEN */
    .tenant-menu-item[aria-expanded="true"] .menu-arrow {
        transform: rotate(180deg);
    }

    /* SUBMENU */
    .collapse .tenant-menu-item {
        padding:
            10px 14px;

        border-radius: 12px;

        font-size: 14px;

        opacity: .92;
    }

    /* SUBMENU ACTIVE */
    .collapse .tenant-menu-item.active {
        background:
            color-mix(in srgb,
                white 16%,
                transparent);

        color:
            var(--tenant-sidebar-text);

        font-weight: 600;

        transform: translateX(2px);
    }

    /* ITEM */
    .tenant-menu-item {
        border-radius: 14px;
        padding: 13px 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all .25s ease;
        color: var(--tenant-sidebar-text);
        text-decoration: none;
        font-weight: 500;
        position: relative;
    }

    /* ICON */
    .tenant-menu-item i {
        font-size: 19px;
        min-width: 22px;
        text-align: center;
        transition: .25s;
    }

    /* TEXT */
    .tenant-menu-item span {
        flex: 1;
    }

    /* HOVER */
    .tenant-menu-item:hover {
        background: color-mix(in srgb, white 12%, transparent);
        color: var(--tenant-sidebar-text);
        transform: translateX(2px);
    }

    /* ACTIVE */
    .tenant-menu-item.active {
        background: white;
        color: var(--tenant-primary);
        font-weight: 700;
        box-shadow:
            0 4px 14px rgba(0, 0, 0, .10),
            0 2px 6px rgba(0, 0, 0, .06);
    }

    /* ACTIVE ICON */
    .tenant-menu-item.active i {
        color: var(--tenant-primary);
    }

    /* SCROLLBAR */
    .tenant-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .tenant-sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, .18);
        border-radius: 20px;
    }

    /* LOGO */
    .tenant-logo {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        object-fit: cover;
    }

    /* ALERT */
    .tenant-alert {
        border: none;
        border-radius: 20px;
        background: linear-gradient(135deg,
                #fff7ed,
                #ffedd5);
    }

    /* TOPBAR */
    .tenant-topbar {
        position: sticky;
        top: 0;
        z-index: 998;
        background: var(--topbar-bg);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border-bottom: 1px solid var(--border-color);
        transition: all .25s ease;
    }

    /* TITLE */
    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
    }

    /* USER TEXT */
    .tenant-topbar-user {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* ICON BUTTONS */
    .tenant-topbar .btn-icon {
        width: 42px;
        height: 42px;

        border-radius: 14px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: transparent;
        border: 1px solid var(--border-color);

        color: var(--text-primary);

        transition: all .25s ease;
    }

    .tenant-topbar .btn-icon:hover {
        background: var(--hover-bg);
        transform: translateY(-1px);
        text-decoration: none;
    }

    /* LOGOUT BUTTON */
    .tenant-topbar .btn-outline-danger {
        border-radius: 12px;
    }

    /* EXPIRATION BADGE */
    .tenant-expiration {
        padding: 8px 14px;
        border-radius: 999px;

        font-size: 13px;
        font-weight: 600;

        background:
            color-mix(in srgb, var(--tenant-primary) 12%, transparent);

        color: var(--tenant-primary);

        border:
            1px solid color-mix(in srgb, var(--tenant-primary) 25%, transparent);
    }

    .cursor-pointer {
        cursor: pointer;
        transition: all .2s ease;
    }

    .cursor-pointer:hover {
        transform: translateY(-2px);
    }

    .selected-card {
        border: 2px solid var(--tblr-primary);
        background: rgba(var(--tblr-primary-rgb), .05);
    }

    /* DARK MODE */
    body.dark-mode {

        --app-bg: #0f172a;
        --card-bg: #111827;
        --topbar-bg: rgba(17, 24, 39, .85);

        --text-primary: #f9fafb;
        --text-secondary: #9ca3af;

        --border-color: rgba(255, 255, 255, .08);

        --hover-bg: rgba(255, 255, 255, .06);

        --shadow:
            0 4px 14px rgba(0, 0, 0, .35),
            0 1px 3px rgba(0, 0, 0, .30);
    }

    .toast {
        /* TOAST */
        background: var(--card-bg) !important;
        color: var(--text-primary) !important;
        border: 1px solid var(--border-color) !important;
        box-shadow: var(--shadow) !important;
        border-radius: 18px;
        overflow: hidden;
    }

    .toast-body {
        background: var(--card-bg) !important;
        color: var(--text-primary) !important;
    }

    .toast-header {
        border-bottom: 1px solid rgba(255, 255, 255, .05);
    }

    .toast .btn-close {
        filter: none;
    }

    body.dark-mode .toast .btn-close {
        filter: invert(1);
    }

    .card,
    .modal-content,
    .dropdown-menu,
    .offcanvas,
    .list-group-item {
        background: var(--card-bg);
        color: var(--text-primary);
        border-color: var(--border-color);
    }

    @keyframes tenantPulse {

        0% {
            transform: scale(.95);
            box-shadow:
                0 0 0 0 rgba(34, 197, 94, 0.7);
        }

        70% {
            transform: scale(1);
            box-shadow:
                0 0 0 10px rgba(34, 197, 94, 0);
        }

        100% {
            transform: scale(.95);
            box-shadow:
                0 0 0 0 rgba(34, 197, 94, 0);
        }
    }
</style>
