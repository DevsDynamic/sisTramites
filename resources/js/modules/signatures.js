import { ajaxFormSubmit } from '../core/ajax';
import { resetModal } from '../core/modal';

/*
|--------------------------------------------------------------------------
| TOGGLE TYPE
|--------------------------------------------------------------------------
*/

function toggleSignatureType(type)
{
    const official =
        document.getElementById(
            'officialFields'
        );

    const visual =
        document.getElementById(
            'visualFields'
        );

    if (!official || !visual) return;

    if (type === 'official') {

        official.style.display = 'flex';

        visual.style.display = 'none';

    } else {

        official.style.display = 'none';

        visual.style.display = 'flex';
    }
}

/*
|--------------------------------------------------------------------------
| INIT
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | RESET MODAL
        |--------------------------------------------------------------------------
        */

        resetModal(
            'createModal',
            'createForm'
        );

        /*
        |--------------------------------------------------------------------------
        | TYPE CHANGE
        |--------------------------------------------------------------------------
        */

        const typeSelect =
            document.getElementById(
                'create_type'
            );

        if (typeSelect) {

            toggleSignatureType(
                typeSelect.value
            );

            typeSelect.addEventListener(
                'change',
                function () {

                    toggleSignatureType(
                        this.value
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | IMAGE PREVIEW
        |--------------------------------------------------------------------------
        */

        const imageInput =
            document.getElementById(
                'create_signature_image'
            );

        if (imageInput) {

            imageInput.addEventListener(
                'change',
                function (e) {

                    const file =
                        e.target.files[0];

                    if (!file) return;

                    const reader =
                        new FileReader();

                    reader.onload =
                        function (ev) {

                            const preview =
                                document.getElementById(
                                    'signaturePreview'
                                );

                            if (!preview) return;

                            preview.src =
                                ev.target.result;

                            preview.classList.remove(
                                'd-none'
                            );
                        };

                    reader.readAsDataURL(
                        file
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE FORM AJAX
        |--------------------------------------------------------------------------
        */

        const createForm =
            document.getElementById(
                'createForm'
            );

        if (createForm) {

            createForm.addEventListener(
                'submit',
                async function (e) {

                    e.preventDefault();

                    await ajaxFormSubmit(
                        createForm,
                        {
                            modalId:
                                'createModal'
                        }
                    );
                }
            );
        }
    }
);