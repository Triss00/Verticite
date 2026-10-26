<?php
/**
 * Contenu du widget Réseaux sociaux (inc/widgets/social.php).
 *
 */
?>
<?php if ( $links ) : ?>
    <ul class="l-footer__list">
        <?php foreach ( $links as $link ) : ?>
            <li>
                <a class="l-footer__link" href="<?= esc_url( $link['url'] ) ?>" target="_blank" rel="noopener"><?= esc_html( $link['label'] ) ?><span class="u-visually-hidden"> (nouvel onglet)</span></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
