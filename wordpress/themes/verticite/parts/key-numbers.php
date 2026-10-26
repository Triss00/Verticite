<?php
/**
 * Chiffres clés (accueil et fiche projet).
 * Argument : label (nom de la section pour les lecteurs d'écran).
 */

$stats = verticite_stats();
if ( ! $stats ) {
    return;
}
$label = $args['label'] ?? 'Chiffres clés';
?>
<section class="l-section s-key-numbers" aria-label="<?= esc_attr( $label ) ?>">
    <div class="l-container">
        <ul class="s-key-numbers__list" role="list">
            <?php foreach ( $stats as $stat ) : ?>
                <li class="c-stat">
                    <span class="c-stat__value"><?= esc_html( $stat['value'] ) ?></span>
                    <span class="c-stat__label"><?= esc_html( $stat['wording'] ?? '' ) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
