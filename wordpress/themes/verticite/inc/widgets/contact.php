<?php
/**
 * Widget « Contact » : affiche les coordonnées saisies dans Réglages > Agence.
 * Le widget ne stocke que son titre, les coordonnées restent à un seul endroit.
 */
defined( 'ABSPATH' ) || exit;

class Verticite_Contact_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct( 'verticite_contact_widget', 'Verticité : contact', array(
            'description' => 'Adresse, téléphone et e-mail de contact de l\'agence.',
        ) );
    }

    public function widget( $args, $instance ): void {
        $email_contact   = get_option( 'agence_email_contact' );
        $phone_contact   = get_option( 'agence_phone_contact' );
        $address_contact = get_option( 'agence_address_contact' );

        if ( ! $address_contact && ! $phone_contact && ! $email_contact ) {
            return;
        }

        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
            echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
        }

        if ( $template = locate_template( 'parts/widgets/contact.php' ) ) {
            include $template; // $address_contact, $phone_contact et $email_contact sont disponibles
        }

        echo $args['after_widget'];
    }

    public function form( $instance ): void {
        $id = $this->get_field_id( 'title' ); ?>
        <p>
            <label for="<?= esc_attr( $id ) ?>">Titre</label>
            <input type="text" class="widefat" id="<?= esc_attr( $id ) ?>" name="<?= esc_attr( $this->get_field_name( 'title' ) ) ?>" value="<?= esc_attr( $instance['title'] ?? '' ) ?>">
        </p>
        <p class="description">Les coordonnées se modifient dans Réglages > Agence.</p>
    <?php }

    public function update( $new_instance, $old_instance ): array {
        return array(
            'title' => sanitize_text_field( $new_instance['title'] ?? '' ),
        );
    }
}
