import './bootstrap';
import './core/echo';
import './ui/tooltips';
import './ui/modal';
import './ui/toast';
import '../css/signature-placement.css';
import { initLayout } from './layout';
import { initPasswordToggle } from './ui/password';
import { initSignaturePlacement } from './modules/signature-placement';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initLayout();
    initPasswordToggle();
    initSignaturePlacement();
});
