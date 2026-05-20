<script>
    document
        .querySelectorAll('.toggle-password')
        .forEach(button => {

            button.addEventListener(
                'click',
                function() {

                    const input =
                        document.getElementById(
                            this.dataset.target
                        );

                    const icon =
                        this.querySelector('i');

                    if (input.type === 'password') {

                        input.type = 'text';

                        icon.classList.remove('ti-eye');

                        icon.classList.add('ti-eye-off');

                    } else {

                        input.type = 'password';

                        icon.classList.remove('ti-eye-off');

                        icon.classList.add('ti-eye');
                    }
                }
            );
        });
</script>
