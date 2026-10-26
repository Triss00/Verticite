<?php
/**
 * En-tête commun : logo, bouton du menu mobile et navigation principale.
 *
 */

// Le logo choisi dans le Personnalisateur a la priorité sur les logos fournis avec le thème.
$custom_logo  = get_theme_mod( 'logo' );
$logo = $custom_logo ?: get_theme_file_uri( 'assets/images/logo.svg' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <p class="c-demo-badge">Démo fictive</p>

    <a class="u-skip-link" href="#contenu">Aller au contenu</a>

    <header class="l-header">
        <div class="l-header__inner">
            <a href="<?= esc_url( home_url( '/' ) ) ?>"<?= is_front_page() ? ' aria-current="page"' : '' ?>>
                <picture>
                    <source media="(min-width: 768px)" srcset="<?= esc_url( $logo ) ?>" width="177" height="50">
                    <img src="<?= esc_url( $logo ) ?>" width="123" height="38" alt="<?= esc_attr( get_bloginfo( 'name' ) ) ?>, accueil">
                </picture>
            </a>

            <?php if ( has_nav_menu( 'header' ) ) : ?>
                <button class="c-menu-button l-header__menu-button" type="button" aria-expanded="false" aria-controls="navigation" aria-label="Ouvrir le menu" data-menu-button>
                    <svg class="c-menu-button__icon c-menu-button__icon--open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
                    <svg class="c-menu-button__icon c-menu-button__icon--close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>

                <nav class="c-nav l-header__nav" id="navigation" aria-label="Navigation principale">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'header',
                        'container'      => false,
                        'menu_class'     => 'c-nav__list',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ) );
                    ?>
                </nav>
            <?php endif; ?>
        </div>
    </header>
