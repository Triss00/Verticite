<?php
/**
 * Filtres « Tous / Logements / Bureaux… » de la liste des projets.
 * Ce sont de vrais liens vers les pages de la taxonomie : ils fonctionnent
 * sans JavaScript et chaque filtre a sa propre URL.
 */

$types = get_terms( array(
    'taxonomy' => 'project_type',
    'orderby'  => 'term_id',
) );
?>
<nav class="c-filters" aria-label="Filtrer les projets par type">
    <ul class="c-filters__list">
        <li class="c-filters__item">
            <a class="c-filters__link" href="<?= esc_url( get_post_type_archive_link( 'project' ) ) ?>"<?= is_post_type_archive( 'project' ) ? ' aria-current="page"' : '' ?>>Tous</a>
        </li>
        <?php if ( ! is_wp_error( $types ) ) : ?>
            <?php foreach ( $types as $type ) : ?>
                <li class="c-filters__item">
                    <a class="c-filters__link" href="<?= esc_url( get_term_link( $type ) ) ?>"<?= is_tax( 'project_type', $type->term_id ) ? ' aria-current="page"' : '' ?>><?= esc_html( $type->name ) ?></a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</nav>
