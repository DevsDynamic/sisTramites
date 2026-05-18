<script>
    document.addEventListener('DOMContentLoaded', function() {

        /* CREATE */
        const createModal =
            document.getElementById('createModal');

        if (createModal) {

            createModal.addEventListener(
                'show.bs.modal',
                function() {
                    const form =
                        document.getElementById('createForm');
                    if (!form) return;
                    form.reset();

                    /* RESET CHECKBOXES */
                    form.querySelectorAll(
                        'input[type="checkbox"]'
                    ).forEach(el => {
                        el.checked = false;
                        const card =
                            el.closest('.card');
                        if (card) {
                            card.classList.remove(
                                'selected-card'
                            );
                        }
                    });
                }
            );
        }

        /* EDIT */
        document
            .querySelectorAll('.edit-btn')
            .forEach(button => {

                button.addEventListener('click', function() {

                    /* ACTION */
                    document
                        .getElementById('editForm')
                        .action =
                        `/users/${this.dataset.id}`;

                    /* TITLE */
                    document
                        .getElementById('edit_modalTitle')
                        .innerText =
                        'Editar Usuario';

                    /* BUTTON */
                    document
                        .getElementById('edit_submitButton')
                        .innerText =
                        'Actualizar';

                    /* FIELDS */
                    document
                        .getElementById('edit_name')
                        .value =
                        this.dataset.name;

                    document
                        .getElementById('edit_email')
                        .value =
                        this.dataset.email;

                    document
                        .getElementById('edit_password')
                        .value = '';

                    /* RESET CHECKBOXES */
                    document
                        .querySelectorAll(
                            '#editForm input[type="checkbox"]'
                        )
                        .forEach(el => {
                            el.checked = false;
                            const card =
                                el.closest('.card');
                            if (card) {
                                card.classList.remove(
                                    'selected-card'
                                );
                            }
                        });

                    /* ROLES */
                    const roles =
                        JSON.parse(this.dataset.roles);

                    roles.forEach(role => {

                        const checkbox =
                            document.querySelector(
                                `#editForm input[name="roles[]"][value="${role}"]`
                            );

                        if (checkbox) {

                            checkbox.checked = true;

                            const card =
                                checkbox.closest('.card');

                            if (card) {

                                card.classList.add(
                                    'selected-card'
                                );
                            }
                        }
                    });

                    /* AREAS */
                    const areas =
                        JSON.parse(this.dataset.areas);

                    areas.forEach(area => {

                        const checkbox =
                            document.querySelector(
                                `#editForm input[name="areas[]"][value="${area}"]`
                            );

                        if (checkbox) {

                            checkbox.checked = true;

                            const card =
                                checkbox.closest('.card');

                            if (card) {

                                card.classList.add(
                                    'selected-card'
                                );
                            }
                        }
                    });

                });

            });

        /* DELETE */
        document
            .querySelectorAll('.delete-btn')
            .forEach(button => {

                button.addEventListener('click', function() {

                    document
                        .getElementById('deleteForm')
                        .action =
                        `/users/${this.dataset.id}`;
                });

            });

        /* TOGGLE PASSWORD */
        document
            .querySelectorAll('.toggle-password')
            .forEach(button => {

                button.addEventListener('click', function() {

                    const target =
                        document.getElementById(
                            this.dataset.target
                        );

                    const icon =
                        this.querySelector('i');

                    if (target.type === 'password') {

                        target.type = 'text';

                        icon.classList.remove('ti-eye');
                        icon.classList.add('ti-eye-off');

                    } else {

                        target.type = 'password';

                        icon.classList.remove('ti-eye-off');
                        icon.classList.add('ti-eye');
                    }
                });
            });

        /* SELECT ALL */
        document
            .querySelectorAll('.select-all')
            .forEach(button => {

                button.addEventListener('change', function() {

                    const target =
                        this.dataset.target;

                    document
                        .querySelectorAll(`.${target}-checkbox`)
                        .forEach(checkbox => {

                            checkbox.checked =
                                this.checked;

                            toggleCard(checkbox);
                        });

                });

            });

        /* INDIVIDUAL CHECKBOX */
        document
            .querySelectorAll(
                '.roles-checkbox, .areas-checkbox'
            )
            .forEach(checkbox => {

                checkbox.addEventListener(
                    'change',
                    function() {

                        toggleCard(this);
                    }
                );

                /* INIT */
                toggleCard(checkbox);

            });

        /* CARD STYLE */
        function toggleCard(checkbox) {

            const card =
                checkbox.closest('.card');

            if (!card) return;

            if (checkbox.checked) {

                card.classList.add(
                    'selected-card'
                );

            } else {

                card.classList.remove(
                    'selected-card'
                );
            }
        }
    });
</script>
