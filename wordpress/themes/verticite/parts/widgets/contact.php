<?php
/**
 * Contenu du widget Contact (inc/widgets/contact.php).
 * L'adresse e-mail est encodée par antispambot() contre les robots.
 */
?>
<address class="l-footer__list">
    <?php if ( $address_contact ) : ?>
        <span class="l-footer__item"><?= nl2br( esc_html( $address_contact ) ) ?></span>
    <?php endif; ?>

    <?php if ( $phone_contact ) : ?>
        <a class="l-footer__link" href="<?= esc_url( verticite_tel_href( $phone_contact ) ) ?>"><?= esc_html( $phone_contact ) ?></a>
    <?php endif; ?>

    <?php if ( $email_contact ) : ?>
        <a class="l-footer__link" href="mailto:<?= esc_attr( antispambot( $email_contact ) ) ?>"><?= esc_html( antispambot( $email_contact ) ) ?></a>
    <?php endif; ?>
</address>
