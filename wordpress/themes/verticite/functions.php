<?php
/**
 * Point d'entrée du thème : il ne fait que charger les fichiers de inc/,
 * chacun ayant une seule responsabilité.
 */

defined( 'ABSPATH' ) || exit;

require_once get_template_directory() . '/inc/acf.php';             // Repli si ACF est désactivé
require_once get_template_directory() . '/inc/supports.php';        // Fonctionnalités du thème, formats d'image
require_once get_template_directory() . '/inc/assets.php';          // CSS et JS
require_once get_template_directory() . '/inc/appearance.php';      // Personnalisateur (logo)
require_once get_template_directory() . '/inc/menus.php';           // Emplacements de menu et classes des liens
require_once get_template_directory() . '/inc/widgets.php';         // Zone de widgets du pied de page
require_once get_template_directory() . '/inc/options-agence.php';  // Réglages > Agence
require_once get_template_directory() . '/inc/helpers.php';         // Fonctions utilisées dans les gabarits
require_once get_template_directory() . '/inc/seo.php';             // Meta description
