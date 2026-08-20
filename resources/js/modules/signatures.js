import { initCrud } from '../crud';

document.addEventListener('DOMContentLoaded', () => {
    initCrud({
        entity: 'signatures',
        createForm: 'createForm',
        editForm: 'editForm',
        deleteForm: 'deleteForm',
        activeForm: 'activeForm',
        createModal: 'createModal',
        editModal: 'editModal',
        deleteModal: 'deleteModal',
        activeModal: 'activeModal',
        resultsContainer: 'signaturesResults',
        resultsUrl: '/signatures/cards',
        browserUrl: '/signatures',
        searchInput: '[data-crud-filter]',
        paginationContainer: 'signaturesResults',
    });

    ['create', 'edit'].forEach(initSignatureForm);
});

function initSignatureForm(prefix) {
    const modal = document.getElementById(`${prefix}Modal`);
    const type = document.getElementById(`${prefix}_type`);
    const officialFields = document.getElementById(`${prefix}_officialFields`);
    const visualFields = document.getElementById(`${prefix}_visualFields`);
    const pfxFile = document.getElementById(`${prefix}_pfx_file`);
    const pfxPassword = document.getElementById(`${prefix}_pfx_password`);
    const image = document.getElementById(`${prefix}_signature_image`);
    const preview = document.getElementById(`${prefix}_signaturePreview`);
    let existingImageUrl = '';

    if (!type || !officialFields || !visualFields || !pfxFile || !pfxPassword || !image || !preview) {
        return;
    }

    const toggleType = () => {
        const isOfficial = type.value === 'official';

        officialFields.classList.toggle('d-none', !isOfficial);
        visualFields.classList.toggle('d-none', isOfficial);
        pfxFile.required = prefix === 'create' && isOfficial;
        image.required = prefix === 'create' && !isOfficial;
        pfxFile.disabled = !isOfficial;
        pfxPassword.disabled = !isOfficial;
        image.disabled = isOfficial;

        if (isOfficial) {
            image.value = '';
            preview.src = '';
            preview.classList.add('d-none');
        } else if (!image.files.length && existingImageUrl) {
            preview.src = existingImageUrl;
            preview.classList.remove('d-none');
        }
    };

    type.addEventListener('change', toggleType);

    image.addEventListener('change', () => {
        const file = image.files[0];

        if (!file) {
            preview.src = '';
            preview.classList.add('d-none');
            return;
        }

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    });

    modal?.addEventListener('show.bs.modal', event => {
        existingImageUrl = prefix === 'edit'
            ? event.relatedTarget?.dataset.signature_image_url ?? ''
            : '';

        toggleType();
    });

    modal?.addEventListener('hidden.bs.modal', () => {
        existingImageUrl = '';
        preview.src = '';
        preview.classList.add('d-none');
        toggleType();
    });

    toggleType();
}
