// Point d'entrée, chargé en module ES (<script type="module"> dans les pages
// HTML, wp_enqueue_script_module() dans le thème WordPress).
// Chaque module vérifie la présence de ses accroches [data-*] avant d'agir.
import { initMenu } from './modules/menu.js';
import { initSliders } from './modules/slider.js';

initMenu();
initSliders();
