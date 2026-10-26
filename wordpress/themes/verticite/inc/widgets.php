<?php
/**
 * Pied de page : une colonne par widget (Contact, Menu, Réseaux sociaux).
 *
 */
defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/widgets/social.php';
require_once __DIR__ . '/widgets/contact.php';

add_action( 'widgets_init', function () {
    register_widget( Verticite_Social_Widget::class );
    register_widget( Verticite_Contact_Widget::class );

    register_sidebar( array(
        'name'          => 'Pied de page',
        'id'            => 'footer',
        'description'   => 'Une colonne par widget.',
        'before_widget' => '<div id="%1$s" class="l-footer__column">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="l-footer__title">',
        'after_title'   => '</h2>',
    ) );
});

/**
 * On ajoute des classes aux liens des menus pour correspondre à la maquette.
 * Ça permet de contrer les styles par défaut de dynamic_sidebar().
 */
add_filter( 'widget_nav_menu_args', function ( $nav_args ) {
    $nav_args['menu_class']    = 'l-footer__list';
    $nav_args['depth']         = 1;
    $nav_args['footer_widget'] = true;
    return $nav_args;
});
