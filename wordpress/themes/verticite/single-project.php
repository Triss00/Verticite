<?php
/**
 * Fiche projet : bandeau, description, fiche technique, chiffres, galerie
 * et navigation vers les projets voisins.
 */

get_header();

$terms = get_the_terms( get_the_ID(), 'project_type' );
$type  = $terms && ! is_wp_error( $terms ) ? $terms[0] : null;

$fact_sheet = array_filter(
	array(
		'Programme'          => verticite_field( 'programme' ),
		'Lieu'               => verticite_field( 'place' ),
		'Bâtiment d’origine' => verticite_field( 'original_building' ),
		'Surface'            => verticite_surface(),
		'Livraison'          => verticite_field( 'delivery_year' ),
		'Maîtrise d’ouvrage' => verticite_field( 'project_management' ),
		'Équipe'             => verticite_field( 'team' ),
	)
);

// 8 champs image numérotés car ACF gratuit n'a pas de « Répéteur ».
$gallery = array();
for ( $i = 1; $i <= 8; $i++ ) {
	$image_id = verticite_field( 'galerie_' . $i );
	if ( $image_id ) {
		$gallery[] = $image_id;
	}
}

// Les projets sont triés du plus récent au plus ancien : le « projet
// précédent » dans la liste est donc le projet publié après celui-ci.
$previous = get_next_post();
$next     = get_previous_post();

while (have_posts()) : the_post();
?>

<main id="contenu">
    <!-- ============ Fil d'Ariane ============ -->
    <div class="s-breadcrumb">
        <div class="l-container">
            <nav class="c-breadcrumb" aria-label="Fil d'Ariane">
                <ol class="c-breadcrumb__list">
                    <li class="c-breadcrumb__item"><a class="c-breadcrumb__link" href="<?= esc_url( get_post_type_archive_link( 'project' ) ); ?>"><svg class="c-breadcrumb__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="M11.125 5.974L5 12l6.125 6.026"/></svg>Nos réalisations</a></li>
                    <?php if ( $type ) : ?>
                        <li class="c-breadcrumb__item"><a class="c-breadcrumb__link" href="<?= esc_url( get_term_link( $type ) ); ?>"><?= esc_html( $type->name ); ?></a></li>
                    <?php endif; ?>
                    <li class="c-breadcrumb__item" aria-current="page"><?php the_title(); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- ============ Bandeau ============ -->
    <section class="s-page-hero" aria-labelledby="page-title">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="s-page-hero__media">
                <?php the_post_thumbnail( 'full', array( 'alt' => '', 'width' => 1440, 'height' => 530, 'sizes' => verticite_sizes( 'full' ), 'fetchpriority' => 'high' ) ); ?>
            </div>
        <?php endif; ?>
        <div class="s-page-hero__inner">
            <h1 class="s-page-hero__title" id="page-title"><?php the_title(); ?></h1>
            <p class="s-page-hero__details"><?= esc_html( verticite_project_details() ); ?></p>
        </div>
    </section>

    <!-- ============ Le projet + fiche technique ============ -->
    <?php if ( get_the_content() || $fact_sheet ) : ?>
        <section class="l-section l-section--secondary s-project-description" aria-label="Le projet">
            <div class="s-project-description__inner">
                <?php if ( get_the_content() ) : ?>
                    <div class="s-project-description__intro">
                        <h2 class="s-project-description__title" id="projet-title">Le projet</h2>
                        <div class="s-project-description__text c-prose"><?php the_content(); ?></div>
                    </div>
                <?php endif; ?>
                <?php if ( ! empty( $fact_sheet ) ) : ?>
                    <aside class="s-project-description__aside c-fact-sheet" aria-labelledby="fiche-title">
                        <h2 class="c-fact-sheet__title" id="fiche-title">Fiche technique</h2>
                        <dl class="c-fact-sheet__list">
                            <?php foreach ( $fact_sheet as $label => $value ) : ?>
                                <div class="c-fact-sheet__row">
                                    <dt class="c-fact-sheet__label"><?= esc_html( $label ); ?></dt>
                                    <dd class="c-fact-sheet__value"><?= esc_html( $value ); ?></dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    </aside>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- ============ Chiffres du projet ============ -->
    <?php get_template_part( 'parts/key-numbers', null, array( 'label' => 'Le projet en chiffres' ) ); ?>

    <!-- ============ Galerie ============ -->
    <?php if ( $gallery ) : ?>
        <section class="l-section l-section--secondary l-section--flush-bottom s-gallery" aria-labelledby="galerie-title">
            <div class="s-gallery__inner">
                <h2 class="s-gallery__title" id="galerie-title">Galerie</h2>
                <ul class="l-grid s-gallery__grid" role="list">
                    <?php foreach ( $gallery as $index => $image_id ) : ?>
						<li class="s-gallery__item">
							<?php
							echo wp_get_attachment_image(
								$image_id,
								'verticite-card',
								false,
								array(
									'class' => 's-gallery__image',
									'alt'   => get_the_title() . ', vue ' . ( $index + 1 ),
									'sizes' => verticite_sizes( 'grid' ),
								)
							);
							?>
						</li>
					<?php endforeach; ?>
                </ul>
            </div>
        </section>
    <?php endif; ?>

    <!-- ============ Projet précédent / suivant ============ -->
    <?php if ( $previous || $next ) :  ?>
        <nav class="s-project-pager" aria-label="Autres projets">
            <div class="l-container">
                <div class="c-project-nav">
                    <?php if ( $previous ) : ?>
                        <a class="c-project-nav__link" href="<?= esc_url( get_permalink( $previous->ID ) ); ?>" rel="prev">
                            <span class="c-project-nav__label"><svg class="c-project-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="M11.125 5.974L5 12l6.125 6.026"/></svg>Projet précédent</span>
                            <span class="c-project-nav__name"><?= esc_html( get_the_title( $previous->ID ) ); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if ( $next ) : ?>
                        <a class="c-project-nav__link c-project-nav__link--next" href="<?= esc_url( get_permalink( $next->ID ) ); ?>" rel="next">
                            <span class="c-project-nav__label">Projet suivant<svg class="c-project-nav__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M12.875 18.026L19 12l-6.125-6.026"/></svg></span>
                            <span class="c-project-nav__name"><?= esc_html( get_the_title( $next->ID ) ); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    <?php endif; ?>
    

    <!-- ============ Appel à l'action ============ -->
    <?php get_template_part( 'parts/cta' ); ?>
</main>

<?php
endwhile;
get_footer();