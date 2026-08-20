import { ajaxFormSubmit } from '../core/ajax';

export function initSubmit({

    formId,

    modalId,

    refresh,

}) {

    const form =
        document.getElementById(formId);

    if (!form) {
        return;
    }

    form.addEventListener(
        'submit',
        async e => {

            e.preventDefault();

            await ajaxFormSubmit(
                form,
                {
                    modalId,
                    reload: false,
                    redirect: false,
                    callback: refresh,
                }
            );

        }
    );

}