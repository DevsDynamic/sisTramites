import { showToast } from './toast';

export async function ajaxFormSubmit(
    form,
    {
        modalId = null,
        reload = true,
        callback = null
    } = {}
) {

    const formData =
        new FormData(form);

    try {

        const response =
            await axios.post(
                form.action,
                formData,
                {
                    headers: {
                        'Content-Type':
                            'multipart/form-data'
                    }
                }
            );

        /* SUCCESS */
        showToast(
            response.data.message,
            'success'
        );

        /* CLOSE MODAL */
        if (modalId) {

            bootstrap.Modal
                .getInstance(
                    document.getElementById(modalId)
                )
                ?.hide();
        }

        /* RESET */
        form.reset();

        /* CALLBACK */
        if (callback) {
            callback(response.data);
        }

        /* RELOAD */
        if (reload) {

            setTimeout(() => {
                location.reload();
            }, 500);
        }

    } catch (error) {

        /* VALIDATION */
        if (error.response?.status === 422) {

            let html = '';

            Object.values(
                error.response.data.errors
            ).forEach(messages => {

                html += messages[0] + '<br>';
            });

            showToast(html, 'danger');

            return;
        }

        /* SERVER */
        showToast(
            'Error interno del servidor.',
            'danger'
        );

        console.error(error);
    }
}