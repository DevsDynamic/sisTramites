import { ajaxFormSubmit } from '../core/ajax';
import { resetModal } from '../core/modal';

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /* RESET */
        resetModal(
            'createModal',
            'createForm'
        );

        /* CREATE */
        const createForm =
            document.getElementById(
                'createForm'
            );

        if (createForm) {

            createForm.addEventListener(
                'submit',
                async function (e) {

                    e.preventDefault();

                    ajaxFormSubmit(
                        createForm,
                        {
                            modalId: 'createModal'
                        }
                    );
                }
            );
        }

        /* EDIT */
        const editForm =
            document.getElementById(
                'editForm'
            );

        if (editForm) {

            editForm.addEventListener(
                'submit',
                async function (e) {

                    e.preventDefault();

                    ajaxFormSubmit(
                        editForm,
                        {
                            modalId: 'editModal'
                        }
                    );
                }
            );
        }

        /* DELETE */
        const deleteForm =
            document.getElementById(
                'deleteForm'
            );

        if (deleteForm) {

            deleteForm.addEventListener(
                'submit',
                async function (e) {

                    e.preventDefault();

                    ajaxFormSubmit(
                        deleteForm,
                        {
                            modalId: 'deleteModal'
                        }
                    );
                }
            );
        }
    }
);