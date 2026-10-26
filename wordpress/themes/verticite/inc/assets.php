<?php
/**
 * La version d'un fichier du thème correspond à sa date de dernière modification.
 * Le navigateur recharge le fichier dès qu'il change.
 */
defined( 'ABSPATH' ) || exit;

function verticite_asset_version( string $path ): string {
    $file = get_theme_file_path( $path );
    return file_exists( $file ) ? (string) filemtime( $file ) : wp_get_theme()->get( 'Version' );
}

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'verticite',
        get_theme_file_uri( 'assets/css/main.css' ),
        array(),
        verticite_asset_version( 'assets/css/main.css' )
    );

    // Chargé en module ES
    wp_enqueue_script_module(
        'verticite',
        get_theme_file_uri( 'assets/js/main.js' ),
        array(),
        verticite_asset_version( 'assets/js/main.js' )
    );
});

// Script du widget « Réseaux sociaux », uniquement sur l'écran Widgets.
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( 'widgets.php' === $hook ) {
        wp_enqueue_script(
            'verticite-social-widget',
            get_theme_file_uri( 'assets/js/social-widget.js' ),
            array( 'jquery' ),
            verticite_asset_version( 'assets/js/social-widget.js' ),
            true
        );
    }
});
