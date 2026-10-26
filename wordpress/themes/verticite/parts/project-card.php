<?php
/**
 * Carte projet : image, titre, surface (+ lieu, année et type en version détaillée).
 * Argument : detailed (bool) — version de la page « Nos réalisations », où
 * le titre de la carte est un h2 (pas de h2 de section au-dessus).
 */

$detailed = $args['detailed'] ?? false;
$heading  = $detailed ? 'h2' : 'h3';
$surface  = verticite_surface();
$details  = $detailed ? verticite_project_details() : '';
?>
<a class="c-project-card<?= $detailed ? ' c-project-card--detailed' : '' ?>" href="<?php the_permalink(); ?>">
    <div class="c-project-card__media">
        <?php
        $image_attributes = array(
            'class' => 'c-project-card__image',
            'sizes' => verticite_sizes( $detailed ? 'grid' : 'slider' ),
        );
        // Le carrousel de l'accueil est toujours sous la ligne de flottaison.
        // WordPress ne le devine pas (requête personnalisée) : on charge ses
        // images à la demande pour laisser la bande passante à l'image principale.
        if ( ! $detailed ) {
            $image_attributes['loading'] = 'lazy';
        }
        the_post_thumbnail( 'verticite-card', $image_attributes );
        ?>
    </div>
    <div class="c-project-card__body">
        <<?= $heading ?> class="c-project-card__title"><?php the_title(); ?></<?= $heading ?>>
        <?php if ( $surface ) : ?>
            <span class="c-project-card__meta"><?= esc_html( $surface ) ?></span>
        <?php endif; ?>
        <?php if ( $details ) : ?>
            <p class="c-project-card__details"><?= esc_html( $details ) ?></p>
        <?php endif; ?>
    </div>
</a>
