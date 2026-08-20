import { ajaxFormSubmit } from '../core/ajax';
import '../crud';
import { initSearch } from '../crud/search';
import { initPagination } from '../crud/pagination';

function initCreateForm() {
    const createForm = document.getElementById('createForm');
    if (!createForm) return;

    // Clonar el elemento para eliminar TODOS los listeners previos
    const cleanForm = createForm.cloneNode(true);
    createForm.parentNode.replaceChild(cleanForm, createForm);

    cleanForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = cleanForm.querySelector('#submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';

        ajaxFormSubmit(this, {
            reload:   false,
            redirect: true,
        }).finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-device-floppy"></i> Guardar documento';
        });
    });

    const type = cleanForm.querySelector('#document_type_id');
    const area = cleanForm.querySelector('#area_id');
    const preview = document.getElementById('seriesPreview');
    const previewCode = document.getElementById('seriesPreviewCode');
    const previewScope = document.getElementById('seriesPreviewScope');
    const previewIcon = document.getElementById('seriesPreviewIcon');
    const previewTitle = document.getElementById('seriesPreviewTitle');
    const previewMessage = document.getElementById('seriesPreviewMessage');
    const previewActions = document.getElementById('seriesPreviewActions');
    const submitButton = cleanForm.querySelector('#submitBtn');

    const refreshSeriesPreview = async () => {
        if (!type?.value || !area?.value || !preview) {
            preview?.classList.add('d-none');
            if (submitButton) submitButton.disabled = false;
            return;
        }

        preview.classList.add('d-none');

        try {
            const response = await axios.get('/documents/series-preview', {
                params: {
                    document_type_id: type.value,
                    area_id: area.value,
                },
            });

            preview.classList.remove('alert-warning');
            preview.classList.add('alert-info');
            previewIcon.className = 'ti ti-list-numbers fs-2';
            previewTitle.textContent = 'Correlativo listo para asignar';
            previewMessage.innerHTML = `Se asignar&aacute; el correlativo <strong>${response.data.code}</strong> <span class="ms-1">(${response.data.scope})</span>`;
            previewActions?.classList.add('d-none');
            if (submitButton) submitButton.disabled = false;
            preview.classList.remove('d-none');
        } catch (error) {
            preview.classList.remove('alert-info');
            preview.classList.add('alert-warning');
            previewIcon.className = 'ti ti-alert-triangle fs-2';
            previewTitle.textContent = 'No se puede asignar un correlativo';
            previewMessage.innerHTML = 'No hay una serie activa para el tipo y el &aacute;rea seleccionados. Crea o activa una serie antes de guardar el documento.';
            previewActions?.classList.remove('d-none');
            if (submitButton) submitButton.disabled = true;
            previewCode.textContent = '';
            previewScope.textContent = 'No existe una serie activa para esta combinación.';
            preview.classList.remove('d-none');
        }
    };

    type?.addEventListener('change', refreshSeriesPreview);
    area?.addEventListener('change', refreshSeriesPreview);

    const addAndSelectOption = (select, item) => {
        if (!select || !item) return;

        const option = new Option(item.name, item.id, true, true);
        select.add(option);
        select.dispatchEvent(new Event('change'));
    };

    const bindQuickCreate = (formId, modalId, onCreated) => {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', event => {
            event.preventDefault();

            ajaxFormSubmit(form, {
                modalId,
                reload: false,
                redirect: false,
                callback: response => onCreated(response.item),
            });
        });
    };

    bindQuickCreate('quickDocumentTypeForm', 'quickDocumentTypeModal', item => {
        addAndSelectOption(type, item);
        addAndSelectOption(document.getElementById('quickSeries_document_type_id'), item);
    });

    bindQuickCreate('quickAreaForm', 'quickAreaModal', item => {
        addAndSelectOption(area, item);
        addAndSelectOption(document.getElementById('quickSeries_area_id'), item);
    });

    const quickSeriesModal = document.getElementById('quickSeriesModal');
    quickSeriesModal?.addEventListener('show.bs.modal', () => {
        const quickType = document.getElementById('quickSeries_document_type_id');
        const quickArea = document.getElementById('quickSeries_area_id');

        if (quickType) quickType.value = type.value;
        if (quickArea) quickArea.value = area.value;
    });

    bindQuickCreate('quickSeriesForm', 'quickSeriesModal', () => {
        refreshSeriesPreview();
    });

    const fileInput = cleanForm.querySelector('#document_file');
    const fileInfo = cleanForm.querySelector('#selectedFileInfo');
    const fileName = cleanForm.querySelector('#selectedFileName');
    const fileMeta = cleanForm.querySelector('#selectedFileMeta');
    const filePreview = cleanForm.querySelector('#selectedFilePreview');
    const fileFrame = cleanForm.querySelector('#selectedFileFrame');
    let fileObjectUrl = null;

    const formatFileSize = (bytes) => {
        if (bytes < 1024 * 1024) return `${Math.ceil(bytes / 1024)} KB`;

        return `${(bytes / (1024 * 1024)).toFixed(2)} MB`;
    };

    const countPdfPages = async (file) => {
        const buffer = await file.arrayBuffer();
        const source = new TextDecoder('latin1').decode(buffer);

        return (source.match(/\/Type\s*\/Page\b/g) || []).length;
    };

    fileInput?.addEventListener('change', async () => {
        const file = fileInput.files?.[0];

        if (fileObjectUrl) URL.revokeObjectURL(fileObjectUrl);

        if (!file) {
            fileInfo?.classList.add('d-none');
            filePreview?.classList.add('d-none');
            return;
        }

        fileName.textContent = file.name;
        fileMeta.textContent = `${formatFileSize(file.size)} · Calculando paginas...`;
        fileInfo?.classList.remove('d-none');

        fileObjectUrl = URL.createObjectURL(file);
        fileFrame.src = fileObjectUrl;
        filePreview?.classList.remove('d-none');

        try {
            const pages = await countPdfPages(file);
            fileMeta.textContent = pages
                ? `${formatFileSize(file.size)} · ${pages} pagina${pages === 1 ? '' : 's'}`
                : `${formatFileSize(file.size)} · Numero de paginas no disponible`;
        } catch (error) {
            fileMeta.textContent = `${formatFileSize(file.size)} · Numero de paginas no disponible`;
        }
    });

    const signatureMode = cleanForm.querySelector('#signature_mode');
    const signerField = cleanForm.querySelector('#signerField');
    const signer = cleanForm.querySelector('#signer_user_id');

    signatureMode?.addEventListener('change', () => {
        const needsSigner = signatureMode.value === 'request';
        signerField?.classList.toggle('d-none', !needsSigner);
        if (!needsSigner && signer) signer.value = '';
    });
}

// Ejecutar solo una vez
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCreateForm, { once: true });
} else {
    initCreateForm();
}

document.addEventListener('DOMContentLoaded', () => {
    const results = document.getElementById('documentsResults');
    if (!results) return;

    const config = {
        resultsContainer: 'documentsResults',
        resultsUrl: '/documents/cards',
        browserUrl: '/documents',
    };

    initSearch(config);
    initPagination(config);
});
