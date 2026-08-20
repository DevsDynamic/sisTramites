import { CRUD_ACTIONS } from './constants';
import { initSubmit } from './submit';
import {
    setText,
    setHtml,
    setAction,
} from '../ui/dom';

export function initActive(config) {

    initSubmit({
        formId: config.activeForm,
        modalId: config.activeModal,
        refresh: config.refresh,
    });

    document
        .getElementById(config.activeModal)
        ?.addEventListener(

            'show.bs.modal',

            e => {

                const button =
                    e.relatedTarget;

                if (!button) {
                    return;
                }

                const action =
                    CRUD_ACTIONS[
                    button.dataset.active
                    ];

                if (!action) {
                    return;
                }

                setText(
                    'activeTitle',
                    action.title
                );

                setText(
                    'activeActionText',
                    action.action
                );

                setText(
                    'activeEntityName',
                    `"${button.dataset.name}"`
                );

                const submit =
                    document.getElementById(
                        'activeSubmit'
                    );

                submit.className =
                    `btn ${action.buttonClass}`;

                setHtml(
                    'activeSubmit',
                    action.buttonHtml
                );

                document
                    .getElementById(
                        'activeIcon'
                    )
                    .className =
                    action.iconClass;

                setAction(
                    config.activeForm,
                    button.dataset.url
                );
            }
        );
}