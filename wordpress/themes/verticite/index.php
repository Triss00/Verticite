<?php
/**
 * Gabarit de secours, obligatoire dans tout thème : WordPress l'utilise
 * quand aucun gabarit plus précis n'existe (articles, recherche…).
 */

get_header();
?>

<main id="contenu">
    <?php get_template_part( 'parts/page-intro', null, array(
        'title' => is_singular() ? get_the_title() : wp_strip_all_tags( get_the_archive_title() ),
    ) ); ?>

    <section class="l-section l-section--secondary">
        <div class="l-container l-container--narrow c-prose">
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php if ( is_singular() ) : ?>
                        <?php the_content(); ?>
                    <?php else : ?>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php the_excerpt(); ?>
                    <?php endif; ?>
                <?php endwhile; ?>
                <?php the_posts_pagination(); ?>
            <?php else : ?>
                <p>Aucun contenu pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
