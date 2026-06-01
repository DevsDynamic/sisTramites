export function showToast(message, type = 'success') {
console.log('TOAST:', message, type);

    const iconMap = {
        success: 'ti ti-circle-check',
        danger: 'ti ti-alert-circle',
        warning: 'ti ti-alert-triangle',
        info: 'ti ti-info-circle'
    };

    const titleMap = {
        success: 'Éxito',
        danger: 'Error',
        warning: 'Advertencia',
        info: 'Información'
    };

    const toast = document.createElement('div');

    toast.className = `app-toast app-toast-${type}`;

    toast.innerHTML = `
        <div class="app-toast-icon">
            <i class="${iconMap[type]}"></i>
        </div>

        <div class="app-toast-content">
            <div class="app-toast-title">
                ${titleMap[type]}
            </div>

            <div class="app-toast-message">
                ${message}
            </div>
        </div>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('show');
    }, 50);

    setTimeout(() => {

        toast.classList.remove('show');

        setTimeout(() => {
            toast.remove();
        }, 300);

    }, 3500);
}