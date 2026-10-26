<?php
/**
 * Page introuvable.
 *
 */

get_header();
?>

<main id="contenu">
    <?php get_template_part( 'parts/page-intro', null, array(
        'title'    => 'Page introuvable',
        'subtitle' => 'Cette page n’existe pas ou a été déplacée.',
    ) ); ?>
</main>

<?php
get_footer();
