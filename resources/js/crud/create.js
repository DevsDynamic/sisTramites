import { resetForm } from '../core/form';
import { initSubmit } from './submit';

export function initCreate(config) {

    initSubmit({
        formId: config.createForm,
        modalId: config.createModal,
        refresh: config.refresh,
    });

    const modal =
        document.getElementById(
            config.createModal
        );

    const form =
        document.getElementById(
            config.createForm
        );

    modal?.addEventListener(

        'hidden.bs.modal',

        () => resetForm(form)

    );

}