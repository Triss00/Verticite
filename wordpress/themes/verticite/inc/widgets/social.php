<?php
/**
 * Widget « Réseaux sociaux » : liste de liens dont on ajoute ou retire
 * les lignes dans l'admin (voir assets/js/social-widget.js).
 */
defined( 'ABSPATH' ) || exit;

class Verticite_Social_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct('verticite_social_widget', 'Verticité : réseaux sociaux', [
            'description' => 'Liens vers les réseaux sociaux.',
        ]);
    }

    public function widget($args, $instance): void {
        $links = $instance['links'] ?? [];

        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }
        if ($template = locate_template('parts/widgets/social.php')) {
            include $template; // $links est disponible dans le template
        }
        echo $args['after_widget'];
    }

    public function form($instance): void {
        $name = $this->get_field_name('links'); ?>
        <div class="js-social">
            <p>
                <label for="<?= $this->get_field_id('title') ?>">Titre</label>
                <input type="text" class="widefat"
                    id="<?= $this->get_field_id('title') ?>"
                    name="<?= $this->get_field_name('title') ?>"
                    value="<?= esc_attr($instance['title'] ?? '') ?>">
            </p>

            <div class="js-social-rows">
                <?php foreach ($instance['links'] ?? [] as $i => $link) {
                    $this->row($name, $i, $link['label'], $link['url']);
                } ?>
            </div>

            <template><?php $this->row($name, '__row__'); ?></template>

            <p><button type="button" class="button js-social-add">+ Ajouter un réseau</button></p>
        </div>
    <?php }

    private function row(string $name, $i, string $label = '', string $url = ''): void { ?>
        <p class="js-social-row" style="display:flex;gap:4px">
            <input type="text" placeholder="Réseau" aria-label="Nom du réseau" style="flex:1"
                name="<?= esc_attr($name) ?>[<?= esc_attr($i) ?>][label]" value="<?= esc_attr($label) ?>">
            <input type="url" placeholder="https://" aria-label="URL" style="flex:2"
                name="<?= esc_attr($name) ?>[<?= esc_attr($i) ?>][url]" value="<?= esc_attr($url) ?>">
            <button type="button" class="button js-social-remove" aria-label="Supprimer">−</button>
        </p>
    <?php }

    public function update($newInstance, $oldInstance): array {
        $links = [];
        foreach ($newInstance['links'] ?? [] as $link) {
            $label = sanitize_text_field($link['label'] ?? '');
            $url   = esc_url_raw($link['url'] ?? '');
            if ($label && $url) {
                $links[] = ['label' => $label, 'url' => $url];
            }
        }
        return [
            'title' => sanitize_text_field($newInstance['title'] ?? ''),
            'links' => $links,
        ];
    }
}