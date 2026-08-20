export function initPasswordToggle() {

    document.addEventListener(
        'click',
        e => {

            const button =
                e.target.closest('.toggle-password');

            if (!button) {
                return;
            }

            const input =
                button
                    .closest('.input-group')
                    ?.querySelector(
                        'input[type="password"],input[type="text"]'
                    );

            if (!input) {
                return;
            }

            const icon =
                button.querySelector('i');

            const visible =
                input.type === 'password';

            input.type =
                visible
                    ? 'text'
                    : 'password';

            icon?.classList.toggle(
                'ti-eye'
            );

            icon?.classList.toggle(
                'ti-eye-off'
            );

        }
    );

}