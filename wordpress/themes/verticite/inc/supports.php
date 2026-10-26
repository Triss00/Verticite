<?php
/**
 * Gestion des fonctionnalités du thème.
 */
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'navigation-widgets', 'gallery', 'caption', 'style', 'script' ) );

    add_image_size( 'verticite-card', 768, 830, true );
});

add_filter( 'document_title_separator', function () {
    return '|';
});

// Pour empêcher l'affichage de la barre d'administration.
add_filter( 'show_admin_bar', '__return_false' );

// Les tailles d'image générées par WordPress (miniatures, cartes, grand format…)
// sont enregistrées en WebP, bien plus léger que le JPEG ou le PNG à qualité égale.
// L'image en taille réelle est convertie aussi ; le fichier importé est
// conservé comme original. On utilise Regenerate Thumbnails pour régénérer les images déjà importées.
add_filter( 'image_editor_output_format', function ( $formats ) {
    $formats['image/jpeg'] = 'image/webp';
    $formats['image/png']  = 'image/webp';
    return $formats;
});

// Un SVG peut contenir du JavaScript : l'envoi n'est autorisé qu'aux
// utilisateurs qui ont déjà le droit de publier du HTML non filtré (admins).
add_filter( 'upload_mimes', function ( $mimes ) {
    if ( current_user_can( 'unfiltered_html' ) ) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
});
