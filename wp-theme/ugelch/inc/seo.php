<?php
/**
 * SEO: títulos como en el sitio original ("Página – Ugel Chincheros"), descripción,
 * canonical y Open Graph.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'document_title_separator', function () { return '–'; } );

add_filter( 'pre_get_document_title', function ( $title ) {
	if ( is_front_page() ) return 'Ugel Chincheros – Unidad de Gestión Educativa Local Chincheros';
	if ( is_404() ) return 'Página no encontrada – Ugel Chincheros';
	$pages = ugelch_static_pages();
	$slug  = ugelch_current_slug();
	if ( is_page() && isset( $pages[ $slug ] ) && get_the_title() === $pages[ $slug ]['title'] ) return $pages[ $slug ]['seo'];
	return $title;
} );

add_filter( 'document_title_parts', function ( $parts ) {
	$parts['site'] = 'Ugel Chincheros';
	unset( $parts['tagline'] );
	return $parts;
} );

function ugelch_meta_description() {
	if ( is_front_page() ) return 'Portal de la Unidad de Gestión Educativa Local Chincheros (Apurímac): comunicados, oficios, convocatorias, trámites y noticias de la UGEL.';
	if ( is_home() ) return 'Noticias y actividades de la Unidad de Gestión Educativa Local Chincheros.';
	if ( is_singular( 'post' ) ) return ugelch_excerpt( get_queried_object_id(), 30 );
	$pages = ugelch_static_pages();
	$slug  = ugelch_current_slug();
	if ( isset( $pages[ $slug ] ) ) return $pages[ $slug ]['desc'];
	if ( $slug === 'mapa-del-sitio' ) return 'Mapa del sitio de la UGEL Chincheros: todas las páginas del portal ordenadas por sección.';
	return get_bloginfo( 'description' );
}

add_action( 'wp_head', function () {
	$desc  = ugelch_meta_description();
	$title = wp_get_document_title();
	$url   = is_front_page() ? home_url( '/' ) : ( is_singular() ? get_permalink() : ( is_home() ? ugelch_news_url() : '' ) );
	$img   = ( is_singular( 'post' ) && has_post_thumbnail() ) ? get_the_post_thumbnail_url( null, 'large' ) : get_template_directory_uri() . '/assets/img/fb-cover.jpg';
	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta name="application-name" content="UGEL Chincheros">' . "\n";
	echo '<meta name="theme-color" content="#0b55c9">' . "\n";
	echo '<meta property="og:locale" content="es_PE">' . "\n";
	echo '<meta property="og:type" content="' . ( is_singular( 'post' ) ? 'article' : 'website' ) . '">' . "\n";
	echo '<meta property="og:site_name" content="UGEL Chincheros">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	if ( $url ) echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $img ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	if ( is_singular( 'post' ) ) {
		echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c' ) ) . '">' . "\n";
		$schema = array(
			'@context' => 'https://schema.org', '@type' => 'NewsArticle',
			'headline' => get_the_title(), 'datePublished' => get_the_date( 'c' ), 'dateModified' => get_the_modified_date( 'c' ),
			'image' => array( $img ), 'description' => $desc,
			'publisher' => array( '@type' => 'GovernmentOrganization', 'name' => 'Unidad de Gestión Educativa Local Chincheros', 'logo' => array( '@type' => 'ImageObject', 'url' => get_template_directory_uri() . '/assets/img/logo-ugel.png' ) ),
			'mainEntityOfPage' => get_permalink(),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}
}, 5 );

// Icono del sitio por defecto (si no se configuró uno en Personalizar → Identidad del sitio)
add_action( 'wp_head', function () {
	if ( has_site_icon() ) return;
	$u = get_template_directory_uri() . '/assets/img/logo-ugel.png';
	echo '<link rel="icon" href="' . esc_url( $u ) . '" sizes="150x150" type="image/png">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $u ) . '">' . "\n";
}, 6 );
