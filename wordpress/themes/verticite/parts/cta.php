<?php
/**
 * Bloc « Contactez-nous » en bas de chaque page.
 * Textes saisis une seule fois dans Réglages > Agence.
 */

$cta   = wp_parse_args( get_option( 'agence_cta', array() ), array(
    'title'       => '',
    'text'        => '',
    'button_text' => '',
) );
$email = get_option( 'agence_email_contact' );

if ( ! $cta['title'] || ! $cta['button_text'] || ! $email ) {
    return;
}
?>
<section class="l-section" aria-labelledby="cta-title">
    <div class="l-container l-container--medium">
        <div class="c-section-header c-section-header--center">
            <h2 class="c-section-header__title" id="cta-title"><?= esc_html( $cta['title'] ) ?></h2>
            <?php if ( $cta['text'] ) : ?>
                <p class="c-section-header__text"><?= esc_html( $cta['text'] ) ?></p>
            <?php endif; ?>
            <a class="c-button" href="mailto:<?= esc_attr( antispambot( $email ) ) ?>"><?= esc_html( $cta['button_text'] ) ?></a>
        </div>
    </div>
</section>
