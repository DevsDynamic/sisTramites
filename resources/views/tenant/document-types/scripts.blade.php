<script>
    document.addEventListener('DOMContentLoaded', function() {
        /* RESET CREATE */
        const createModal =
            document.getElementById('createModal');
        createModal.addEventListener(
            'hidden.bs.modal',
            function() {
                document.getElementById('createForm').reset();
            }
        );

        /* EDIT */
        document
            .querySelectorAll('.edit-btn')
            .forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('edit_modalTitle')
                        .innerText = 'Editar Tipo de documento';
                    document.getElementById('edit_submitButton')
                        .innerText = 'Actualizar';
                    document.getElementById('edit_name')
                        .value = this.dataset.name;
                    document.getElementById('edit_code')
                        .value = this.dataset.code;
                    document.getElementById('edit_active')
                        .checked = this.dataset.active == 1;
                    document
                        .getElementById('editForm')
                        .action =
                        `/document-types/${this.dataset.id}`;
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
                        `/document-types/${this.dataset.id}`;
                });
            });
    });
</script>
