import './bootstrap';
import Alpine from 'alpinejs';
// Plugin Alpine (akan kita pakai nanti)
import focus from '@alpinejs/focus';
import persist from '@alpinejs/persist';
// Import semua helper kita
import { showToast, formatRupiah, formatTanggal } from './helpers';


Alpine.plugin(focus);
Alpine.plugin(persist);

// Expose ke window agar bisa dipakai di Blade tanpa import
window.showToast    = showToast;
window.formatRupiah = formatRupiah;
window.formatTanggal = formatTanggal;
window.Alpine       = Alpine;

Alpine.start();
