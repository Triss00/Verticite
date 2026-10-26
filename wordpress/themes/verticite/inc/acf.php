<?php
/**
 * Accès aux champs ACF sans erreur fatale si le plugin est désactivé.
 *
 */

defined('ABSPATH') || exit;

function verticite_field($selector, $post_id = false, $format_value = true) {
    return function_exists('get_field')
        ? get_field($selector, $post_id, $format_value)
        : null;
}

add_action('admin_notices', function () {
    if (!function_exists('get_field')) {
        echo '<div class="notice notice-warning"><p>Le thème Verticité a besoin du plugin <strong>Advanced Custom Fields</strong> pour afficher le contenu des pages.</p></div>';
    }
});