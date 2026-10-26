<?php
/**
 * En-tête de page : titre (h1) et sous-titre facultatif.
 * Arguments : title (titre de la page par défaut), subtitle, filters (bool,
 * affiche les filtres par type de projet).
 */

$title    = $args['title'] ?? get_the_title();
$subtitle = $args['subtitle'] ?? '';
?>
<section class="s-page-intro" aria-labelledby="page-title">
    <div class="s-page-intro__inner">
        <hgroup>
            <h1 class="s-page-intro__title" id="page-title"><?= esc_html( $title ) ?></h1>
            <?php if ( $subtitle ) : ?>
                <p class="s-page-intro__subtitle"><?= esc_html( $subtitle ) ?></p>
            <?php endif; ?>
        </hgroup>
        <?php if ( ! empty( $args['filters'] ) ) : ?>
            <?php get_template_part( 'parts/project-filters' ); ?>
        <?php endif; ?>
    </div>
</section>
