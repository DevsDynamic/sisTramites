<link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

@php
    $primaryRgb = hex_to_rgb(primary_color());
@endphp

<style>
    :root {
        /* BRAND */
        --primary: {{ primary_color() }};
        --primary-rgb: {{ $primaryRgb }};
        --sidebar: {{ sidebar_color() }};
        --sidebar-text: {{ sidebar_text_color() }};


        /* LIGHT MODE */
        --app-bg: #f5f7fb;
        --card-bg: #ffffff;
        --topbar-bg: rgba(255, 255, 255, .85);
        --text-primary: #111827;
        --text-secondary: #282828;
        --border-color: #e5e7eb;
        --hover-bg: #f3f4f6;
        --shadow: 0 4px 14px rgba(0, 0, 0, .05),
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
    .layout {
        display: flex;
        min-height: 100vh;
    }

    /* MAIN */
    .main {
        flex: 1;
        margin-left: 280px;
        display: flex;
        flex-direction: column;
    }

    /* CONTENT */
    .content {
        width: 100%;
        padding: 30px;
        min-width: 0;
        overflow-x: hidden;
    }

    /* CARDS */
    .card {
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
    .btn {
        background: var(--primary);
        color: #fff;
        border: none;
        transition: all .25s ease;
    }

    .btn:hover {
        background: color-mix(in srgb, var(--primary) 85%, black);
        color: #fff;
    }

    .btn-primary {
        background: var(--primary);
        color: #fff;
        border: none;
        transition: all .25s ease;
    }

    .btn-primary:hover {
        background: color-mix(in srgb, var(--primary) 85%, black);
        color: #fff;
    }

    .text-primary {
        color: var(--primary) !important;
    }

    .bg-primary {
        background: var(--primary) !important;
    }

    .border-primary {
        border-color: var(--primary) !important;
    }

    .text {
        color: var(--primary) !important;
    }

    .bg {
        background: var(--primary) !important;
    }

    .border {
        border-color: var(--primary) !important;
    }

    /*  RESPONSIVE */
    @media(max-width:992px) {
        .main {
            margin-left: 0;
        }
    }

    /* SIDEBAR */
    .sidebar {
        width: 280px;
        background: var(--sidebar);
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
        /* overflow-y: auto; */
        overflow-y: scroll;
        overflow-x: hidden;
    }

    .collapse.show {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    /* BRAND */
    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 35px;
        padding: 10px;
        border-radius: 18px;
        transition: all .25s ease;
    }

    .sidebar-brand,
    .sidebar-brand:hover,
    .sidebar-brand:focus,
    .sidebar-brand:active {

        text-decoration: none;
    }

    .sidebar-brand:hover {
        background:
            color-mix(in srgb,
                white 10%,
                transparent);
        transform: translateY(-1px);
    }

    /* .sidebar-brand::after {
        content: '';
        margin-left: auto;
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #22c55e;
        opacity: .8;
    } */

    .sidebar-brand::after {
        content: '';
        margin-left: auto;
        width: 10px;
        height: 10px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow:
            0 0 0 rgba(34, 197, 94, 0.7);
        animation:
            Pulse 2s infinite;
    }

    .sidebar-logo {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        object-fit: contain;
        background: white;
        padding: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
    }

    .sidebar-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--sidebar-text);
        line-height: 1.2;
    }

    .sidebar-title:hover {}

    .sidebar-plan {
        opacity: .7;
        font-size: 13px;
        color: var(--sidebar-text);
    }

    .sidebar-plan:hover {}

    .sidebar-title,
    .sidebar-plan {
        pointer-events: none;
    }

    /*** MENU */
    .menu {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    /* SECTION TITLE */
    .menu-section {
        position: relative;
    }

    .menu-label {
        /* color: rgba(255, 255, 255, .55); */
        font-size: 11px;
        letter-spacing: .08em;
        font-weight: 700;
    }

    .menu-section small {
        display: block;
        padding-left: 8px;
    }

    /* DROPDOWN */
    .menu-dropdown {
        display: flex;
        flex-direction: column;
    }

    /* TOGGLE */
    .menu-toggle {
        position: relative;
    }

    /* CHEVRON */
    .menu-toggle .ti-chevron-down {
        transition:
            transform .25s ease;
    }

    /* ROTATE */
    .menu-toggle[aria-expanded="true"] .ti-chevron-down {
        /* transform: rotate(180deg); */
    }

    /* SUBMENU */
    .submenu {
        margin-top: 6px;
        margin-left: 14px;
        padding-left: 14px;
        border-left: 1px solid rgba(255, 255, 255, .08);
    }

    .submenu {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    /* SUBMENU ITEMS */
    /* .menu-item {
        display: flex;
        align-items: center;
        gap: 10px;

        padding: 10px 12px;
        margin-bottom: 4px;

        border-radius: 12px;
        font-size: 14px;
        opacity: .92;
    } */

    /* ACTIVE */
    .menu-item.active {
        background: rgba(255, 255, 255, .12);
        color: white;
    }

    /* BADGES */
    .badge {
        font-size: 10px;
    }

    /*** SEPARATION */
    .menu-section:not(:first-child) {
        margin-top: 28px !important;
    }

    /* MENU ARROW */
    .menu-arrow {
        transition:
            transform .25s ease;
    }

    /* ROTATE WHEN OPEN */
    .menu-item[aria-expanded="true"] .menu-arrow {
        /* transform: rotate(180deg); */
    }

    .menu-toggle {
        width: 100%;
        cursor: pointer;
    }

    .menu-item,
    .submenu,
    .collapse {
        transform: translateZ(0);
        backface-visibility: hidden;
    }

    /* SUBMENU */
    /* .collapse .menu-item {
        padding:
            10px 14px;
        border-radius: 12px;
        font-size: 14px;
        opacity: .92;
    } */

    /* SUBMENU ACTIVE */
    /* .collapse .menu-item.active {
        background:
            color-mix(in srgb,
                white 16%,
                transparent);
        color:
            var(--sidebar-text);
        font-weight: 600;
        transform: translateX(2px);
    } */

    .collapse {
        transition: height .35s ease !important;
    }

    /* ITEM */
    .menu-item {
        border-radius: 14px;
        padding: 13px 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition:
            background .25s ease,
            color .25s ease,
            transform .25s ease;
        color: var(--sidebar-text);
        text-decoration: none;
        font-weight: 500;
        position: relative;
    }

    .menu-item {
        width: 100%;
        text-align: left;
    }

    .menu-item>span:first-of-type {
        flex: 1;
    }

    .collapse {
        transition: height .35s ease !important;
    }

    /* .submenu {
        overflow: hidden;
    } */

    /* ICON */
    .menu-item i {
        font-size: 19px;
        min-width: 22px;
        text-align: center;
        transition: .25s;
    }

    /* TEXT */
    /* .menu-item span {
        flex: 1;
    } */

    .menu-item>span:first-of-type {
        flex: 1;
    }

    /* HOVER */
    .menu-item:hover {
        background:
            color-mix(in srgb, white 12%, transparent);
        color: var(--sidebar-text);
    }

    /* ACTIVE */
    .menu-item.active {
        background: white;
        color: var(--primary);
        font-weight: 700;
        box-shadow:
            0 4px 14px rgba(0, 0, 0, .10),
            0 2px 6px rgba(0, 0, 0, .06);
    }

    /* ACTIVE ICON */
    .menu-item.active i {
        color: var(--primary);
    }

    /* SCROLLBAR */
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, .18);
        border-radius: 20px;
    }

    /* LOGO */
    .logo {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        object-fit: cover;
    }

    /* ALERT */
    .alert {
        border: none;
        border-radius: 20px;
        background: linear-gradient(135deg,
                #fff7ed,
                #ffedd5);
    }

    /* TOPBAR */
    .topbar {
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
    .topbar-user {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* ICON BUTTONS */
    .topbar .btn-icon {
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

    .topbar .btn-icon:hover {
        background: var(--hover-bg);
        transform: translateY(-1px);
        text-decoration: none;
    }

    /* LOGOUT BUTTON */
    .topbar .btn-outline-danger {
        border-radius: 12px;
    }

    /* EXPIRATION BADGE */
    .expiration {
        padding: 8px 14px;
        border-radius: 999px;

        font-size: 13px;
        font-weight: 600;

        background:
            color-mix(in srgb, var(--primary) 12%, transparent);

        color: var(--primary);

        border:
            1px solid color-mix(in srgb, var(--primary) 25%, transparent);
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

    @keyframes Pulse {

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
