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
                    form.querySelectorAll(
                        'input[type="checkbox"]'
                    ).forEach(el => {
                        el.checked = false;
                    });
                }
            );
        }

        /* EDIT */
        document
            .querySelectorAll('.edit-btn')
            .forEach(button => {
                button.addEventListener('click', function() {
                    /* FORM ACTION */
                    document
                        .getElementById('editForm')
                        .action =
                        `/roles/${this.dataset.id}`;

                    /* NAME */
                    document
                        .getElementById('edit_name')
                        .value =
                        this.dataset.name;

                    /* RESET CHECKBOXES */
                    document
                        .querySelectorAll(
                            '#editForm input[type="checkbox"]'
                        )
                        .forEach(el => {
                            el.checked = false;
                        });

                    /* MARK ROLE PERMISSIONS */
                    const permissions =
                        JSON.parse(this.dataset.permissions);

                    permissions.forEach(permission => {

                        const checkbox =
                            document.querySelector(
                                `#editForm input[value="${permission}"]`
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
                        `/roles/${this.dataset.id}`;
                });
            });

        /* SELECT ALL PERMISSIONS */
        document
            .querySelectorAll('.select-all-permissions')
            .forEach(selectAll => {

                selectAll.addEventListener(
                    'change',
                    function() {

                        const checked =
                            this.checked;

                        document
                            .querySelectorAll(
                                '.permission-checkbox'
                            )
                            .forEach(permission => {

                                permission.checked =
                                    checked;
                            });

                        document
                            .querySelectorAll(
                                '.module-checkbox'
                            )
                            .forEach(module => {

                                module.checked =
                                    checked;
                            });

                    }
                );

            });

        /* MODULES */
        document
            .querySelectorAll('.module-checkbox')
            .forEach(moduleCheckbox => {

                moduleCheckbox.addEventListener(
                    'change',
                    function() {

                        const module =
                            this.dataset.module;

                        document
                            .querySelectorAll(
                                `.permission-module-${module}`
                            )
                            .forEach(permission => {

                                permission.checked =
                                    this.checked;
                            });

                    }
                );

            });
    });
</script>
