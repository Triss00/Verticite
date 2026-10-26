<?php
/**
 * Ajout de la possibilité de choisir un logo dans le Customizer.
 * Apparence > Personnaliser > Identité du site
 */
defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', function ( WP_Customize_Manager $manager ) {
    $manager->add_setting( 'logo', array(
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $manager->add_control( new WP_Customize_Image_Control( $manager, 'logo', array(
        'label'       => 'Logo',
        'section'     => 'title_tagline',
        'description' => 'Téléchargez le logo de votre site',
        'priority'    => 50,
    ) ) );
});
