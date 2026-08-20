import { setPluginValue } from './plugins';
import { showImagePreview } from '../ui/preview';
import { clearPreview } from '../ui/preview';

/* FILL FORM */
export function fillForm(prefix, dataset) {
    Object.entries(dataset)
        .forEach(([key, value]) => {

            const field =
                document.getElementById(
                    `${prefix}_${key}`
                );

            if (!field) {
                return;
            }

            fillField(
                field,
                value
            );

        });
}

export function fillField(
    field,
    value
) {
    if (setPluginValue(field, value)) {
        return;
    }

    switch (field.type) {

        case 'checkbox':

            field.checked =
                value == 1 ||
                value === true ||
                value === 'true';

            break;

        case 'radio':

            document
                .querySelector(
                    `[name="${field.name}"][value="${value}"]`
                )
                ?.click();

            break;

        case 'file':

            break;

        default:

            field.value =
                value ?? '';

    }

}

/* SERIALIZE FORM */
export function serializeForm(form) {
    return Object.fromEntries(

        new FormData(form)

    );
}

/* RESET FORM */
export function resetForm(form) {
    form.reset();

    clearValidation(form);
    clearPreview(form);
}

/* READONLY */
export function setReadonly(
    form,
    readonly = true
) {

    form
        .querySelectorAll(
            'input,select,textarea'
        )
        .forEach(field => {

            field.readOnly =
                readonly;

        });

}

export function setDisabled(
    form,
    disabled = true
) {

    form
        .querySelectorAll(
            'input,select,textarea,button'
        )
        .forEach(field => {

            field.disabled =
                disabled;

        });

}

/* CLEAR VALIDATION */
export function clearValidation(form) {
    form
        .querySelectorAll(
            '.is-invalid'
        )
        .forEach(el => {

            el.classList.remove(
                'is-invalid'
            );

        });

    form
        .querySelectorAll(
            '.invalid-feedback'
        )
        .forEach(el => {

            el.innerHTML = '';

        });
}

/* IMAGE PREVIEW */
export function showPreview(
    input,
    preview
) {

    if (
        !input.files.length
    ) {

        preview.src = '';

        preview.classList.add('d-none');

        return;

    }

    const reader =
        new FileReader();

    reader.onload =
        e => {

            preview.src =
                e.target.result;

            preview.classList.remove('d-none');

        };

    reader.readAsDataURL(
        input.files[0]
    );

}