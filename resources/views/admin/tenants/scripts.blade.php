<script>
    document.addEventListener('DOMContentLoaded', function() {

        document
            .querySelectorAll('.toggle-password')
            .forEach(toggle => {

                toggle.addEventListener('click', function() {

                    const group =
                        this.closest('.input-group');

                    const input =
                        group.querySelector('.password-input');

                    const icon =
                        this.querySelector('i');

                    if (!input) return;

                    if (input.type === 'password') {

                        input.type = 'text';

                        icon.classList.remove('ti-eye');

                        icon.classList.add('ti-eye-off');

                    } else {

                        input.type = 'password';

                        icon.classList.remove('ti-eye-off');

                        icon.classList.add('ti-eye');

                    }

                });

            });

    });
</script>