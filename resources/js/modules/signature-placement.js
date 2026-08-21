import * as pdfjsLib from 'pdfjs-dist';
import pdfWorker from 'pdfjs-dist/build/pdf.worker.min.mjs?url';

pdfjsLib.GlobalWorkerOptions.workerSrc = pdfWorker;

export function initSignaturePlacement() {
    document.querySelectorAll('[data-signature-placement]').forEach(initForm);
}

function initForm(form) {
    const placement = form.querySelector('[data-placement-select]');
    const pageField = form.querySelector('[data-page-field]');
    const pageNumber = form.querySelector('[name="page_number"]');
    const positionMode = form.querySelector('[data-position-mode]');
    const editor = form.querySelector('[data-position-editor]');
    const canvas = form.querySelector('[data-pdf-canvas]');
    const canvasWrap = form.querySelector('[data-signature-canvas]');
    const box = form.querySelector('[data-signature-box]');
    const label = form.querySelector('[data-position-page-label]');
    const inputs = {
        x: form.querySelector('[data-position-x]'),
        y: form.querySelector('[data-position-y]'),
        width: form.querySelector('[data-position-width]'),
        height: form.querySelector('[data-position-height]'),
    };
    let pdf = null;
    let loaded = false;

    const sync = async () => {
        const specific = placement.value === 'specific';
        pageField.classList.toggle('d-none', !specific);
        pageNumber.required = specific;
        const manual = positionMode.value === 'manual';
        editor.classList.toggle('d-none', !manual);
        if (manual) await render();
    };

    const render = async () => {
        if (!form.dataset.pdfUrl) return;
        if (!pdf) pdf = await pdfjsLib.getDocument(form.dataset.pdfUrl).promise;
        const requestedPage = placement.value === 'specific' ? Number(pageNumber.value || 1) : placement.value === 'last' ? pdf.numPages : 1;
        const page = await pdf.getPage(Math.max(1, Math.min(requestedPage, pdf.numPages)));
        const naturalViewport = page.getViewport({ scale: 1 });
        const availableWidth = Math.max(260, Math.min(520, editor.clientWidth - 24 || 420));
        const scale = Math.min(availableWidth / naturalViewport.width, 420 / naturalViewport.height);
        const viewport = page.getViewport({ scale });
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        canvasWrap.style.width = `${viewport.width}px`;
        canvasWrap.style.height = `${viewport.height}px`;
        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
        label.textContent = placement.value === 'all' ? `Referencia: hoja 1 de ${pdf.numPages}` : `Hoja ${requestedPage} de ${pdf.numPages}`;
        placeBox();
        loaded = true;
    };

    const placeBox = () => {
        const canvasWidth = canvasWrap.clientWidth;
        const canvasHeight = canvasWrap.clientHeight;
        const width = Number(inputs.width.value) * canvasWidth;
        const height = Number(inputs.height.value) * canvasHeight;
        const left = Math.max(0, Math.min(Number(inputs.x.value) * canvasWidth, canvasWidth - width));
        const top = Math.max(0, Math.min(Number(inputs.y.value) * canvasHeight, canvasHeight - height));
        box.style.width = `${width}px`;
        box.style.height = `${height}px`;
        box.style.left = `${left}px`;
        box.style.top = `${top}px`;
        inputs.x.value = (left / canvasWidth).toFixed(5);
        inputs.y.value = (top / canvasHeight).toFixed(5);
    };

    let drag = null;
    box.addEventListener('pointerdown', event => {
        if (!loaded) return;
        event.preventDefault();
        box.setPointerCapture(event.pointerId);
        const bounds = canvasWrap.getBoundingClientRect();
        drag = {
            x: event.clientX - bounds.left - box.offsetLeft,
            y: event.clientY - bounds.top - box.offsetTop,
        };
    });
    box.addEventListener('pointermove', event => {
        if (!drag) return;
        const bounds = canvasWrap.getBoundingClientRect();
        const left = Math.max(0, Math.min(event.clientX - bounds.left - drag.x, canvasWrap.clientWidth - box.offsetWidth));
        const top = Math.max(0, Math.min(event.clientY - bounds.top - drag.y, canvasWrap.clientHeight - box.offsetHeight));
        box.style.left = `${left}px`;
        box.style.top = `${top}px`;
        inputs.x.value = (left / canvasWrap.clientWidth).toFixed(5);
        inputs.y.value = (top / canvasWrap.clientHeight).toFixed(5);
    });
    box.addEventListener('pointerup', () => { drag = null; });
    placement.addEventListener('change', sync);
    positionMode.addEventListener('change', sync);
    pageNumber.addEventListener('change', () => positionMode.value === 'manual' && render());
    sync();
}
