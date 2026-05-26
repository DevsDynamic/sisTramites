<script>
    document.addEventListener('DOMContentLoaded', function() {
        /* CREATE */
        const createModal = document.getElementById('createModal');
        createModal.addEventListener(
            'hidden.bs.modal',
            function() {
                document.getElementById('createForm').reset();
                document.getElementById('create_modalTitle').innerText = 'Nueva Serie';
                document.getElementById('create_submitButton').innerText = 'Guardar';
            }
        );

        /* EDIT */
        document
            .querySelectorAll('.edit-btn')
            .forEach(button => {

                button.addEventListener(
                    'click',
                    function() {

                        document
                            .getElementById('edit_modalTitle')
                            .innerText = 'Editar Serie';

                        document
                            .getElementById('edit_submitButton')
                            .innerText = 'Actualizar';

                        document
                            .getElementById('edit_document_type_id')
                            .value = this.dataset.document_type_id;

                        document
                            .getElementById('edit_area_id')
                            .value = this.dataset.area_id ?? '';

                        document
                            .getElementById('edit_prefix')
                            .value = this.dataset.prefix;

                        document
                            .getElementById('edit_current_number')
                            .value = this.dataset.current_number;

                        document
                            .getElementById('edit_padding')
                            .value = this.dataset.padding;

                        document
                            .getElementById('edit_reset_yearly')
                            .checked = this.dataset.reset_yearly == 1;

                        document
                            .getElementById('edit_active')
                            .checked = this.dataset.active == 1;

                        document
                            .getElementById('editForm')
                            .action =
                            `/document-series/${this.dataset.id}`;
                    }
                );
            });

        /* DELETE */
        document
            .querySelectorAll('.delete-btn')
            .forEach(button => {

                button.addEventListener(
                    'click',
                    function() {

                        document
                            .getElementById('deleteForm')
                            .action =
                            `/document-series/${this.dataset.id}`;
                    }
                );
            });

        function updateSeriesPreview(prefix) {
            const prefixValue =
                document.getElementById(`${prefix}_prefix`)?.value || 'DOC';

            const numberValue =
                parseInt(
                    document.getElementById(`${prefix}_current_number`)?.value || 0
                ) + 1;

            const paddingValue =
                parseInt(
                    document.getElementById(`${prefix}_padding`)?.value || 6
                );

            const formatted =
                String(numberValue)
                .padStart(paddingValue, '0');

            document.getElementById(
                `${prefix}_seriesPreview`
            ).innerText = `${prefixValue}-${formatted}`;
        }

        [
            'create',
            'edit'
        ].forEach(prefix => {

            [
                'prefix',
                'current_number',
                'padding'
            ].forEach(field => {

                document
                    .getElementById(`${prefix}_${field}`)
                    ?.addEventListener(
                        'input',
                        () => updateSeriesPreview(prefix)
                    );
            });
        });
    });
</script>
