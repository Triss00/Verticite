<?php
/**
 * Page d'accueil : contenu saisi dans les champs ACF de la page « Accueil ».
 * 
 */

get_header();

$hero_image     = verticite_field( 'hero_image' );
$hero_link      = verticite_field( 'hero_link' );
$philo_title    = verticite_field( 'philo_title' );
$philo_text     = verticite_field( 'philo_text' );
$philo_link     = verticite_field( 'philo_link' );
$projects_title = verticite_field( 'projects_title' );
$projects_text  = verticite_field( 'projects_text' );
$projects_link  = verticite_field( 'projects_link' );

// Projets choisis dans l'admin, sinon les 4 plus récents.
$featured = verticite_field( 'featured_projects' );
$projects = new WP_Query( array(
    'post_type'      => 'project',
    'posts_per_page' => $featured ? count( $featured ) : 4,
    'post__in'       => $featured ?: array(),
    'orderby'        => $featured ? 'post__in' : 'date',
    'no_found_rows'  => true, // pas de pagination : requête plus légère
) );
?>
<main id="contenu">
    <!-- ============ Hero ============ -->
    <section class="s-hero" aria-labelledby="hero-title">
        <?php if ( $hero_image ) : ?>
            <div class="s-hero__media">
                <?= wp_get_attachment_image( $hero_image, 'full', false, array(
                    'alt'           => '',
                    'sizes'         => verticite_sizes( 'full' ),
                    'fetchpriority' => 'high', // image LCP : chargée en priorité
                    'loading'       => 'eager',
                ) ) ?>
            </div>
        <?php endif; ?>
        <div class="s-hero__content">
            <h1 class="s-hero__title" id="hero-title"><?= esc_html( verticite_field( 'hero_title' ) ?: get_the_title() ) ?></h1>
            <?php if ( ! empty( $hero_link['url'] ) ) : ?>
                <a class="c-button c-button--on-image" href="<?= esc_url( $hero_link['url'] ) ?>"><?= esc_html( $hero_link['title'] ) ?></a>
            <?php endif; ?>
        </div>
    </section>

    <!-- ============ Notre philosophie ============ -->
    <?php if ( $philo_title ) : ?>
        <section class="l-section l-section--secondary" aria-labelledby="philosophie-title">
            <div class="l-container l-container--narrow">
                <div class="c-section-header c-section-header--center">
                    <h2 class="c-section-header__title" id="philosophie-title"><?= esc_html( $philo_title ) ?></h2>
                    <?php if ( $philo_text ) : ?>
                        <p class="c-section-header__text"><?= esc_html( $philo_text ) ?></p>
                    <?php endif; ?>
                    <?php if ( ! empty( $philo_link['url'] ) ) : ?>
                        <a class="c-button c-button--link" href="<?= esc_url( $philo_link['url'] ) ?>"><?= esc_html( $philo_link['title'] ) ?><span class="u-visually-hidden"> sur l'agence</span></a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- ============ Chiffres clés ============ -->
    <?php get_template_part( 'parts/key-numbers', null, array( 'label' => 'Chiffres clés' ) ); ?>

    <!-- ============ Nos métamorphoses ============ -->
    <?php if ( $projects->have_posts() ) : ?>
        <section class="l-section l-section--secondary s-projects-overview" aria-labelledby="metamorphoses-title">
            <div class="s-projects-overview__inner">
                <div class="s-projects-overview__intro c-section-header">
                    <h2 class="c-section-header__title" id="metamorphoses-title"><?= esc_html( $projects_title ?: 'Nos métamorphoses' ) ?></h2>
                    <?php if ( $projects_text ) : ?>
                        <p class="c-section-header__text"><?= esc_html( $projects_text ) ?></p>
                    <?php endif; ?>
                    <?php if ( ! empty( $projects_link['url'] ) ) : ?>
                        <a class="c-button c-button--link" href="<?= esc_url( $projects_link['url'] ) ?>"><?= esc_html( $projects_link['title'] ) ?><span class="u-visually-hidden"> les projets</span></a>
                    <?php endif; ?>
                </div>

                <div class="s-projects-overview__slider c-slider" data-slider>
                    <ul class="c-slider__track" data-slider-track aria-label="Projets à la une">
                        <?php while ( $projects->have_posts() ) : $projects->the_post(); ?>
                            <li class="c-slider__slide">
                                <?php get_template_part( 'parts/project-card' ); ?>
                            </li>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); // rend la main à la page d'accueil ?>
                    </ul>

                    <div class="c-slider__controls">
                        <div class="c-slider__progress" aria-hidden="true">
                            <span class="c-slider__progress-bar" data-slider-progress></span>
                        </div>
                        <div class="c-slider__arrows">
                            <button class="c-slider__arrow" type="button" aria-label="Projet précédent" data-slider-prev>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg>
                            </button>
                            <button class="c-slider__arrow" type="button" aria-label="Projet suivant" data-slider-next>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- ============ Appel à l'action ============ -->
    <?php get_template_part( 'parts/cta' ); ?>
</main>
<?php
get_footer();
