<?php
/**
 * Template Name: Agence
 *
 * Page « L'agence » : fondateurs, méthode en 4 étapes, mosaïque image /
 * citation / image. Champs ACF : acf-json/group_verticite_agence.json.
 */

get_header();

// Membres et étapes sont des champs numérotés car ACF gratuit n'a pas de « Répéteur ».

$members = array();
for ( $i = 1; $i <= 2; $i++ ) {
	$member = verticite_field( 'member_' . $i );
	if ( ! empty( $member['name'] ) ) {
		$members[] = $member;
	}
}

$steps = array();
for ( $i = 1; $i <= 4; $i++ ) {
	$step = verticite_field( 'step_' . $i );
	if ( ! empty( $step['title'] ) ) {
		$steps[] = $step;
	}
}
?>

<main id="contenu">
    <!-- ============ En-tête de page ============ -->
    <?php if ( verticite_field( 'agency_hide_header' ) ) : ?>
        <h1 class="u-visually-hidden"><?php the_title(); ?></h1>
    <?php else : ?>
        <?php get_template_part( 'parts/page-intro', null, array( 'subtitle' => verticite_field( 'agency_subheading' ) ) ); ?>
    <?php endif; ?>

    <!-- ============ Fondateurs ============ -->
    <?php if ( $members ) : ?>
        <section class="l-section l-section--secondary s-founders" aria-labelledby="fondateurs-title">
            <h2 class="u-visually-hidden" id="fondateurs-title">Nos fondateurs</h2>
            <ul class="s-founders__list" role="list">
                <?php foreach ( $members as $member ) : ?>
                    <li class="s-founders__item">
                        <article class="c-member">
                            <?php if ( ! empty( $member['photo'] ) ) : ?>
                                <?= wp_get_attachment_image( $member['photo'], 'medium_large', false, array( 'class' => 'c-member__photo', 'alt' => '', 'sizes' => verticite_sizes( 'member' ) ) ); ?>
                            <?php else : ?>
                                <img class="c-member__photo" src="<?= esc_url( get_theme_file_uri( 'assets/images/portrait.svg' ) ); ?>" alt="" width="320" height="320" loading="lazy">
                            <?php endif; ?>
                            <div class="c-member__body">
                                <div>
                                    <h3 class="c-member__name"><?= esc_html( $member['name'] ); ?></h3>
                                    <?php if ( ! empty( $member['role'] ) ) : ?>
                                        <p class="c-member__role"><?= esc_html( $member['role'] ); ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php if ( ! empty( $member['bio'] ) ) : ?>
                                    <p class="c-member__bio"><?= esc_html( $member['bio'] ); ?></p>
                                <?php endif; ?>
                            </div>
                        </article>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <!-- ============ Notre méthode ============ -->
    <?php if ( $steps ) : ?>
    <section class="l-section s-method" aria-labelledby="methode-title">
        <div class="s-method__inner">
            <hgroup class="s-method__header">
                <h2 class="s-method__title" id="methode-title"><?= esc_html( verticite_field( 'method_title' ) ) ?></h2>
                <p class="s-method__subtitle"><?= esc_html( verticite_field( 'method_subheading' ) ) ?></p>
            </hgroup>
            <ol class="l-grid s-method__steps">
                <?php foreach ( $steps as $step ) : ?>
                    <li class="s-method__step c-step">
                        <h3 class="c-step__title"><?= esc_html( $step['title'] ) ?></h3>
                        <p class="c-step__text"><?= esc_html( $step['text'] ) ?></p>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </section>
    <?php endif; ?>

    <!-- ============ Image · citation · image ============ -->
    <section class="l-section l-section--secondary s-mosaic" aria-label="L'agence en images">
        <div class="l-container">
            <div class="s-mosaic__grid">
				<?php if ( verticite_field( 'mosaic_image_1' ) ) : ?>
					<?= wp_get_attachment_image( verticite_field( 'mosaic_image_1' ), 'verticite-card', false, array( 'class' => 's-mosaic__image', 'sizes' => verticite_sizes( 'mosaic' ) ) ); ?>
				<?php endif; ?>

				<?php if ( verticite_field( 'quote' ) ) : ?>
					<figure class="s-mosaic__quote c-quote">
						<blockquote class="c-quote__text">
							<p>« <?= esc_html( verticite_field( 'quote' ) ); ?> »</p>
						</blockquote>
						<?php if ( verticite_field( 'quote_author' ) ) : ?>
							<figcaption class="c-quote__author"><?= esc_html( verticite_field( 'quote_author' ) ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endif; ?>

				<?php if ( verticite_field( 'mosaic_image_2' ) ) : ?>
					<?= wp_get_attachment_image( verticite_field( 'mosaic_image_2' ), 'verticite-card', false, array( 'class' => 's-mosaic__image', 'sizes' => verticite_sizes( 'mosaic' ) ) ); ?>
				<?php endif; ?>
			</div>
        </div>
    </section>

    <!-- ============ Appel à l'action ============ -->
    <?php get_template_part( 'parts/cta' ); ?>
</main>

<?php
get_footer();
