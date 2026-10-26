<?php
/**
 * Réglages > Agence : coordonnées et appel à l'action, partagés par
 * toutes les pages (pied de page, bloc « Contact »).
 * Construite avec la Settings API : WordPress gère l'enregistrement,
 * le nonce et les droits ; chaque option a sa fonction de nettoyage.
 */

defined( 'ABSPATH' ) || exit;

class Verticite_Agence_Options {

    const GROUP = 'agence_options';

    public static function register () {
        add_action('admin_menu', [self::class, 'addMenu']);
        add_action('admin_init', [self::class, 'registerSettings']);
    }

    public static function registerSettings () {
        register_setting(self::GROUP, 'agence_email_contact', [
            'sanitize_callback' => 'sanitize_email',
        ]);
        register_setting(self::GROUP, 'agence_cta', [
            'sanitize_callback' => [self::class, 'sanitizeCta'],
        ]);
        register_setting(self::GROUP, 'agence_phone_contact', [
            'sanitize_callback' => [self::class, 'sanitizePhone'],
        ]);
        register_setting(self::GROUP, 'agence_address_contact', [
            'sanitize_callback' => 'sanitize_textarea_field',
        ]);

        add_settings_section('agence_options_section', 'Paramètres', function () {
            echo "Vous pouvez ici gérer les paramètres liés à votre agence.";
        }, self::GROUP);

        add_settings_field('agence_email_contact', "Email de contact", function () {
            ?>
            <input type="email" name="agence_email_contact" value="<?= esc_attr(get_option('agence_email_contact')) ?>" class="regular-text">
            <?php
        }, self::GROUP, 'agence_options_section');

        add_settings_field('agence_phone_contact', "Téléphone de contact", function () {
            ?>
            <input type="tel" name="agence_phone_contact" value="<?= esc_attr(get_option('agence_phone_contact')) ?>" class="regular-text">
            <?php
        }, self::GROUP, 'agence_options_section');

        add_settings_field('agence_address_contact', "Adresse de contact", function () {
            ?>
            <textarea name="agence_address_contact" class="regular-text" rows="4"><?= esc_textarea(get_option('agence_address_contact')) ?></textarea>
            <?php
        }, self::GROUP, 'agence_options_section');

        add_settings_field('agence_cta_field', "Appel à l'action", function () {
            $cta = get_option('agence_cta', []);
            $title = isset($cta['title']) ? $cta['title'] : '';
            $text  = isset($cta['text']) ? $cta['text'] : '';
            $button_text = isset($cta['button_text']) ? $cta['button_text'] : '';
            ?>
            <p>
                <label>Titre<br>
                <input type="text" name="agence_cta[title]" value="<?= esc_attr($title) ?>" class="regular-text">
                </label>
            </p>
            <p>
                <label>Texte<br>
                <textarea name="agence_cta[text]" class="regular-text" rows="4"><?= esc_textarea($text) ?></textarea>
                </label>
            </p>
            <p>
                <label>Texte du bouton<br>
                <input type="text" name="agence_cta[button_text]" value="<?= esc_attr($button_text) ?>" class="regular-text">
                </label>
            </p>
            <?php
        }, self::GROUP, 'agence_options_section');
    }

    public static function addMenu () {
        add_options_page("Gestion de l'agence", "Agence", "manage_options", self::GROUP, [self::class, 'render']);
    }

    public static function render () {
        ?>
        <h1>Gestion de l'agence</h1>

        <form action="options.php" method="post">
            <?php 
            settings_fields(self::GROUP);
            do_settings_sections(self::GROUP);
            submit_button();
            ?>
        </form>
        <?php 
    }

    public static function sanitizeCta ($input) {
        $sanitized = [];
        if (isset($input['title'])) {
            $sanitized['title'] = sanitize_text_field($input['title']);
        }
        if (isset($input['text'])) {
            $sanitized['text'] = sanitize_textarea_field($input['text']);
        }
        if (isset($input['button_text'])) {
            $sanitized['button_text'] = sanitize_text_field($input['button_text']);
        }
        return $sanitized;
    }

    public static function sanitizePhone ($input) {
        $phone = sanitize_text_field($input);
        if (preg_match('/^[0-9+\s\-\.\(\)]{8,20}$/', $phone)) {
            return $phone;
        }
        return '';
    }

}

Verticite_Agence_Options::register();
