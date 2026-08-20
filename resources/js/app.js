import './bootstrap';
import './core/echo';
import './ui/tooltips';
import './ui/modal';
import './ui/toast';
import { initLayout } from './layout';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initLayout();
});