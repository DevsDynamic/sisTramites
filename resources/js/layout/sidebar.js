export function initSidebar() {

    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const toggle = document.getElementById('sidebarToggle');

    if (!sidebar || !toggle || !overlay) {
        return;
    }

    function openSidebar() {

        sidebar.classList.add('show');
        overlay.classList.add('show');

    }

    function closeSidebar() {

        sidebar.classList.remove('show');
        overlay.classList.remove('show');

    }

    toggle.addEventListener('click', e => {

        e.stopPropagation();

        if (sidebar.classList.contains('show')) {

            closeSidebar();

        } else {

            openSidebar();

        }

    });

    overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', e => {

        if (e.key === 'Escape') {

            closeSidebar();

        }

    });

    window.addEventListener('resize', () => {

        if (window.innerWidth >= 992) {

            closeSidebar();

        }

    });

}