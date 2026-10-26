<?php
/**
 * Fonctions de formatage et de récupération de données pour le thème.
 * 
 */
defined( 'ABSPATH' ) || exit;

/**
 * Surface au format français : 1200 → « 1 200 m² ».
 */
function verticite_surface( $post_id = null ) {
	$surface = verticite_field( 'surface', $post_id );
	return $surface ? number_format_i18n( $surface ) . ' m²' : '';
}

/**
 * Ligne de détails d'un projet : « Lyon 7e · 2023 · Logements ».
 */
function verticite_project_details( $post_id = null ) {
	$terms = get_the_terms( $post_id ?: get_the_ID(), 'project_type' );

	$parts = array(
		verticite_field( 'place', $post_id ),
		verticite_field( 'delivery_year', $post_id ),
		$terms && ! is_wp_error( $terms ) ? $terms[0]->name : '',
	);

	return implode( ' · ', array_filter( $parts ) );
}

/**
 * Chiffres clés de la page ou du projet courant.
 * La version gratuite d'ACF n'a pas de « Répéteur » donc on utilise les champs numérotés.
 */
function verticite_stats( $post_id = null ) {
	$stats = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$stat = verticite_field( 'number_' . $i, $post_id );
		if ( ! empty( $stat['value'] ) ) {
			$stats[] = $stat;
		}
	}
	return $stats;
}

/**
 * Lien téléphone : « 02 23 37 43 00 » → « tel:+33223374300 ».
 */
function verticite_tel_href( string $phone ): string {
	$digits = preg_replace( '/[^\d+]/', '', $phone );
	if ( preg_match( '/^0\d{9}$/', $digits ) ) {
		$digits = '+33' . substr( $digits, 1 );
	}
	return 'tel:' . $digits;
}

/**
 * Selon la mise en page, on définit les valeurs de l'attribut sizes de sorte que l'on obtienne
 * la largeur réelle à laquelle l'image est affichée.
 * Le navigateur s'en sert pour choisir le fichier le plus léger
 * du srcset. Mêmes valeurs que dans les pages HTML de l'intégration.
 */
function verticite_sizes( string $layout ): string {
	$sizes = array(
		'full'   => '100vw',
		'grid'   => '(min-width: 1024px) 384px, (min-width: 768px) 50vw, 100vw',
		'slider' => '(min-width: 768px) 320px, 85vw',
		'mosaic' => '(min-width: 768px) 33vw, 100vw',
		'member' => '(max-width: 359px) 100vw, 320px',
	);
	return $sizes[ $layout ] ?? '100vw';
}
