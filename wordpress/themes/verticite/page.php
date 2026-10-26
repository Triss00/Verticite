<?php
/**
 * Page standard (mentions légales, politique de confidentialité…) :
 * en-tête + contenu de l'éditeur.
 */

get_header();
?>

<main id="contenu">
    <?php while ( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'parts/page-intro', null, array( 'subtitle' => verticite_field( 'subheading' ) ) ); ?>

        <section class="l-section l-section--secondary">
            <div class="l-container l-container--narrow c-prose">
                <?php if (get_the_content()) : ?>
                    <?php the_content(); ?>
                <?php else : ?>
                    <p>Cette page ne contient aucun contenu.</p>
                <?php endif; ?>
            </div>
        </section>
    <?php endwhile; ?>

    <?php get_template_part( 'parts/cta' ); ?>
</main>

<?php
get_footer();
