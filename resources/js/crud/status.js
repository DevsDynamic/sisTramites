import { CRUD_ACTIONS } from './constants';
import { initSubmit } from './submit';
import {
    setText,
    setHtml,
    setAction,
} from '../ui/dom';

export function initStatus(config) {

    initSubmit({
        formId: config.statusForm,
        modalId: config.statusModal,
        refresh: config.refresh,
    });

    document
        .getElementById(config.statusModal)
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
                    button.dataset.status
                    ];

                if (!action) {
                    return;
                }

                setText(
                    'statusTitle',
                    action.title
                );

                setText(
                    'statusActionText',
                    action.action
                );

                setText(
                    'statusEntityName',
                    `"${button.dataset.name}"`
                );

                const submit =
                    document.getElementById(
                        'statusSubmit'
                    );

                submit.className =
                    `btn ${action.buttonClass}`;

                setHtml(
                    'statusSubmit',
                    action.buttonHtml
                );

                document
                    .getElementById(
                        'statusIcon'
                    )
                    .className =
                    action.iconClass;

                setAction(
                    config.statusForm,
                    button.dataset.url
                );
            }
        );
}