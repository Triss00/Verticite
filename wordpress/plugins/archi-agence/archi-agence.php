<?php
/**
 * Plugin Name:       Archi-Agence
 * Description:       Déclare le type de contenu « Projet » et la taxonomie « Type de projet ». Ces données sont dans un plugin pour survivre à un changement de thème.
 * Version:           1.1.0
 * Requires at least: 6.5
 * Requires PHP:      8.1
 * Author:            Anonymous
 * License:           GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

function archi_agence_register_content_types() {
	register_post_type( 'project', array(
		'labels'        => array(
			'name'          => 'Projets',
			'singular_name' => 'Projet',
			'archives'      => 'Nos réalisations',
			'add_new_item'  => 'Ajouter un projet',
			'edit_item'     => 'Modifier le projet',
			'all_items'     => 'Tous les projets',
		),
		'public'        => true,
		'has_archive'   => true,  // liste des projets : /project/
		'show_in_rest'  => true,  // éditeur de blocs et API REST
		'menu_icon'     => 'dashicons-building',
		'supports'      => array( 'title', 'editor', 'thumbnail' ),
		'rewrite'       => array( 'slug' => 'project' ),
	) );

	register_taxonomy( 'project_type', 'project', array(
		'labels'            => array(
			'name'          => 'Types de projet',
			'singular_name' => 'Type de projet',
			'all_items'     => 'Tous les types de projet',
			'edit_item'     => 'Modifier le type de projet',
			'add_new_item'  => 'Ajouter un type de projet',
		),
		'hierarchical'      => true,  // cases à cocher dans l'admin, comme les catégories
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'project-type' ),
	) );
}
add_action( 'init', 'archi_agence_register_content_types' );

// Les URL des projets n'existent qu'une fois les règles de réécriture
// régénérées : on le fait à l'activation, après avoir déclaré les types.
register_activation_hook( __FILE__, function () {
	archi_agence_register_content_types();
	flush_rewrite_rules();
} );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
