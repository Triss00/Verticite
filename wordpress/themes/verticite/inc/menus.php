<?php
/**
 * Gestion des menus du thème.
 */
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {
    register_nav_menus( array(
        'header' => 'Navigation principale',
        'legal'  => 'Pied de page : mentions légales',
    ) );
});

/**
 * On ajoute des classes aux liens des menus pour correspondre à la maquette.
 * Ça permet de contrer les styles par défaut de wp_nav_menu().
 */
add_filter( 'nav_menu_link_attributes', function ( $atts, $item, $args ) {
    if ( 'header' === $args->theme_location ) {
        $atts['class'] = 'c-nav__link';

        // « Nos réalisations » reste actif sur une fiche projet ou un filtre.
        $is_projects = 'post_type_archive' === $item->type && 'project' === $item->object;
        if ( $is_projects && ( is_singular( 'project' ) || is_tax( 'project_type' ) ) ) {
            $atts['aria-current'] = 'true';
        }
    } elseif ( 'legal' === $args->theme_location ) {
        $atts['class'] = 'l-footer__legal-link';
    } elseif ( ! empty( $args->footer_widget ) ) {
        $atts['class'] = 'l-footer__link';
    }

    return $atts;
}, 10, 3 );
