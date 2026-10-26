<?php
/**
 * Liste des projets (/project/) et projets filtrés par type
 * (taxonomy-project_type.php réutilise ce gabarit).
 */

get_header();
?>

<main id="contenu">
    <!-- ============ En-tête de page ============ -->
    <?php get_template_part( 'parts/page-intro', null, array(
        'title'    => 'Nos réalisations',
        'subtitle' => 'Nos projets de réhabilitation urbaine',
        'filters'  => true,
    ) ); ?>

    <!-- ============ Liste des projets ============ -->
    <section class="l-section l-section--secondary s-projects-list" aria-label="Liste des projets">
        <div class="s-projects-list__inner">
            <?php if ( have_posts() ) : ?>
                <ul class="s-projects-list__grid" role="list">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <li class="s-projects-list__item">
                            <?php get_template_part( 'parts/project-card', null, array( 'detailed' => true ) ); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>

                <?php if ( get_next_posts_link() ) : ?>
                    <a class="c-button" href="<?= esc_url( get_next_posts_page_link() ) ?>">Voir plus de projets</a>
                <?php endif; ?>
            <?php else : ?>
                <p>Aucun projet pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- ============ Appel à l'action ============ -->
    <?php get_template_part( 'parts/cta' ); ?>
</main>

<?php
get_footer();
