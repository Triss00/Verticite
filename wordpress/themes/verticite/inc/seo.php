<?php
/**
 * Balise <meta name="description"> : le texte affiché sous le titre dans
 * les résultats de recherche. Elle est générée à partir du contenu existant,
 * sans champ supplémentaire à remplir. Si un plugin SEO est installé, c'est
 * lui qui s'en charge.
 */
defined( 'ABSPATH' ) || exit;

function verticite_meta_description(): string {
    if ( is_front_page() ) {
        $description = verticite_field( 'philo_text' );
    } elseif ( is_singular() ) {
        $post        = get_queried_object();
        $description = has_excerpt( $post ) ? get_the_excerpt( $post ) : strip_shortcodes( $post->post_content );
    } elseif ( is_tax( 'project_type' ) ) {
        $description = term_description() ?: 'Nos projets de réhabilitation urbaine : ' . single_term_title( '', false ) . '.';
    } elseif ( is_post_type_archive( 'project' ) ) {
        $description = 'Nos projets de réhabilitation urbaine : garages, usines et entrepôts transformés en lieux de vie.';
    }

    // En cas de page sans texte, comme L'agence, on utilise le slogan du site
    // (Réglages > Général), sinon le texte de présentation de l'accueil.
    if ( empty( $description ) ) {
        $description = get_bloginfo( 'description' ) ?: verticite_field( 'philo_text', get_option( 'page_on_front' ) );
    }

    // Texte brut sur une ligne, coupé vers 155 caractères (longueur affichée par Google).
    $description = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $description ) ) );
    return wp_html_excerpt( $description, 155, '…' );
}

add_action( 'wp_head', function () {
    // Yoast SEO ou Rank Math génèrent déjà la balise.
    if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
        return;
    }

    $description = verticite_meta_description();
    if ( $description ) {
        echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
    }
}, 1 );
