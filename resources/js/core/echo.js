import { showToast } from './toast';

if (window.Echo) {

    window.Echo
        .channel(
            `user.${window.AppData.userId}`
        )
        .listen(
            '.document.notification',
            (e) => {

                let badge =
                    document.getElementById(
                        'notif-count'
                    );

                if (badge) {

                    badge.innerText =
                        parseInt(
                            badge.innerText || 0
                        ) + 1;
                }

                showToast(
                    e.message,
                    'success'
                );
            }
        );
}