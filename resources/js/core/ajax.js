import { showToast } from '../ui/toast';
import { showLoading, restoreLoading } from '../ui/loading';

export async function ajaxFormSubmit(
    form,
    {
        modalId = null,
        reload = true,
        callback = null,
        redirect = true,

        reset = true,
        closeModal = true,
        success = null,
        error = null,

    } = {}
) {
    const formData = new FormData(form);

    try {

        const submitButton = form.querySelector('[type="submit"]');

        if (submitButton) {

            submitButton.disabled = true;

            submitButton
                .querySelector('.btn-content')
                ?.classList.add('d-none');

            submitButton
                .querySelector('.btn-loading')
                ?.classList.remove('d-none');

        }

        showLoading(form);

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

        form.reset();

        if (callback) {
            await callback(response.data);
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

        if (error.response?.status === 413) {
            showToast(
                'El archivo supera el tamaño máximo permitido de 50 MB.',
                'danger'
            );
            return;
        }

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

    finally {

        restoreLoading(form);

    }
}
