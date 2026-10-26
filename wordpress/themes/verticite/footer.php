<?php
/**
 * Pied de page commun : zone de widgets « Pied de page » + mentions légales.
 *
 */
?>
    <footer class="l-footer">
        <?php if ( is_active_sidebar( 'footer' ) ) : ?>
            <div class="l-footer__columns">
                <?php dynamic_sidebar( 'footer' ); ?>
            </div>
        <?php endif; ?>
        <div class="l-footer__bottom">
            <div class="l-footer__bottom-inner">
                <p class="l-footer__legal">© <?= esc_html( date_i18n( 'Y' ) . ' ' . get_bloginfo( 'name' ) ) ?>. Tous droits réservés.</p>
                <?php if ( has_nav_menu( 'legal' ) ) : ?>
                    <nav aria-label="Informations légales">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'legal',
                            'container'      => false,
                            'menu_class'     => 'l-footer__legal-list',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ) );
                        ?>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>
