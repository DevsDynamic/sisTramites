export function showMessage({

    icon = 'ti ti-info-circle',

    iconClass = 'text-primary',

    title = 'Información',

    text = '',

    buttons = []

}) {

    const modal = document.getElementById('messageModal');

    document.getElementById('messageIcon').className =
        `${icon} ${iconClass}`;

    document.getElementById('messageTitle').textContent =
        title;

    document.getElementById('messageText').innerHTML =
        text;

    const footer =
        document.getElementById('messageFooter');

    footer.innerHTML = '';

    if (!buttons.length) {

        buttons = [

            {

                text: 'Aceptar',

                class: 'btn-primary',

                dismiss: true

            }

        ];

    }

    buttons.forEach(button => {

        const btn = document.createElement('button');

        btn.type = 'button';

        btn.className =
            `btn ${button.class}`;

        btn.textContent =
            button.text;

        if (button.dismiss) {

            btn.setAttribute(
                'data-bs-dismiss',
                'modal'
            );

        }

        if (button.callback) {

            btn.addEventListener(

                'click',

                () => button.callback()

            );

        }

        footer.appendChild(btn);

    });

    bootstrap.Modal
        .getOrCreateInstance(modal)
        .show();

}

export function info(title, text) {

    showMessage({

        icon: 'ti ti-info-circle',

        iconClass: 'text-primary',

        title,

        text

    });

}

export function error(title, text) {

    showMessage({

        icon: 'ti ti-circle-x',

        iconClass: 'text-danger',

        title,

        text

    });

}

export function warning(title, text) {

    showMessage({

        icon: 'ti ti-alert-triangle',

        iconClass: 'text-warning',

        title,

        text

    });

}

export function success(title, text) {

    showMessage({

        icon: 'ti ti-circle-check',

        iconClass: 'text-success',

        title,

        text

    });

}

export function confirm({

    title,

    text,

    confirmText = 'Aceptar',

    cancelText = 'Cancelar',

    confirmColor = 'btn-primary'

}) {

    return new Promise(resolve => {

        showMessage({

            icon: 'ti ti-help-circle',

            iconClass: 'text-warning',

            title,

            text,

            buttons: [

                {

                    text: cancelText,

                    class: 'btn-outline-secondary',

                    dismiss: true,

                    callback: () => resolve(false)

                },

                {

                    text: confirmText,

                    class: confirmColor,

                    dismiss: true,

                    callback: () => resolve(true)

                }

            ]

        });

    });

}