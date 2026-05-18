<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        /* CREATE MODAL RESET */

        const createModal =
            document.getElementById('createModal');

        if (createModal) {
            createModal.addEventListener(
                'hidden.bs.modal',
                function() {

                    /* RESET FORM */
                    document
                        .getElementById('createForm')
                        .reset();
                }
            );
        }
    });

    function toggleDarkMode() {

        document.body.classList.toggle('dark-mode');

        localStorage.setItem(
            'dark-mode',
            document.body.classList.contains('dark-mode')
        );
    }

    if (localStorage.getItem('dark-mode') === 'true') {
        document.body.classList.add('dark-mode');
    }
</script>
<script>
    document.addEventListener(
        'DOMContentLoaded',
        function() {

            document
                .querySelectorAll('.toast')
                .forEach(toastEl => {
                    const toast =
                        new bootstrap.Toast(
                            toastEl, {
                                delay: 5000
                            }
                        );
                    toast.show();
                });
        }
    );
</script>
<script>
    if (window.Echo) {

        window.Echo
            .channel('tenant.' + TENANT_ID + '.user.' + USER_ID)
            .listen('.document.notification', (e) => {

                console.log('Nueva notificación:', e);

                let badge = document.getElementById('notif-count');

                if (badge) {
                    badge.innerText =
                        parseInt(badge.innerText || 0) + 1;
                }

                alert(e.message);
            });

    }
</script>
