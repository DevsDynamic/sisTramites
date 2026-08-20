import { initSubmit } from './submit';

import {
    setText,
    setAction,
} from '../ui/dom';

export function initDelete(config) {

    initSubmit({
        formId: config.deleteForm,
        modalId: config.deleteModal,
        refresh: config.refresh,
    });

    document
        .getElementById(config.deleteModal)
        ?.addEventListener(

            'show.bs.modal',

            e => {

                const button =
                    e.relatedTarget;

                if (!button) {
                    return;
                }

                setText(
                    'deleteEntity',
                    button.dataset.entity
                );

                setText(
                    'deleteName',
                    button.dataset.name
                );

                setAction(
                    config.deleteForm,
                    button.dataset.url
                );

            }

        );

}