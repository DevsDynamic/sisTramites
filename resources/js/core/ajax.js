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
            bootstrap.Modal
                .getInstance(document.getElementById(modalId))
                ?.hide();
        }

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