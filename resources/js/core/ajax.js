import { showToast } from './toast';

export async function ajaxFormSubmit(
    form,
    {
        modalId = null,
        reload = true,
        callback = null,
        redirect = true,  // ← nuevo
    } = {}
) {
    const formData = new FormData(form);

    try {
        const response = await axios.post(
            form.action,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            }
        );

        showToast(response.data.message, 'success');

        if (modalId) {

            const modalElement =
                document.getElementById(modalId);

            const modal =
                bootstrap.Modal.getInstance(modalElement);

            modal?.hide();

            modalElement.addEventListener(
                'hidden.bs.modal',
                () => {

                    document
                        .querySelectorAll('.modal-backdrop')
                        .forEach(el => el.remove());

                    document.body.classList.remove('modal-open');

                    document.body.style.removeProperty('padding-right');

                },
                { once: true }
            );
        }

        // if (modalId) {

        //     const modalElement =
        //         document.getElementById(modalId);

        //     const modal =
        //         bootstrap.Modal.getOrCreateInstance(
        //             modalElement
        //         );

        //     modal.hide();

        //     modalElement.addEventListener(
        //         'hidden.bs.modal',
        //         () => {

        //             if (callback) {
        //                 callback(response.data);
        //             }

        //         },
        //         { once: true }
        //     );
        // }

        form.reset();

        if (callback) {
            callback(response.data);
        }

        // ← redirect tiene prioridad sobre reload
        if (redirect && response.data.redirect) {
            setTimeout(() => {
                window.location.href = response.data.redirect;
            }, 800);
            return;
        }

        if (reload) {
            setTimeout(() => {
                location.reload();
            }, 500);
        }

    } catch (error) {

        if (error.response?.status === 422) {
            let html = '';
            Object.values(error.response.data.errors).forEach(messages => {
                html += messages[0] + '<br>';
            });
            showToast(html, 'danger');
            return;
        }

        showToast(
            error.response?.data?.message ?? 'Error interno del servidor.',
            'danger'
        );
        console.error(error);
    }
}