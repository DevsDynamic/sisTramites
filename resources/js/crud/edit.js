import { fillForm, resetForm } from '../core/form';
import { initSubmit } from './submit';

export function initEdit(config) {

    initSubmit({
        formId: config.editForm,
        modalId: config.editModal,
        refresh: config.refresh,
    });

    document
        .getElementById(config.editModal)
        ?.addEventListener(

            'show.bs.modal',

            e => {

                const button =
                    e.relatedTarget;

                if (!button) {
                    return;
                }

                fillForm(
                    'edit',
                    button.dataset
                );

                document
                    .getElementById(config.editForm)
                    .action =
                    button.dataset.url;

            }
        );

    document
        .getElementById(config.editModal)
        ?.addEventListener(

            'hidden.bs.modal',

            () => resetForm(
                document.getElementById(config.editForm)
            )

        );
}
