<?php
/**
 * Núcleo: soportes del tema, assets, motor de plantillas y campos editables.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ───────── Soportes ───────── */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_image_size( 'ugelch-card', 828, 466, true );
	register_nav_menus( array( 'primary' => 'Menú principal' ) );
} );

/* ───────── Assets ───────── */
function ugelch_asset_ver( $rel ) {
	$f = get_template_directory() . '/' . $rel;
	return file_exists( $f ) ? (string) filemtime( $f ) : UGELCH_VER;
}

add_action( 'wp_enqueue_scripts', function () {
	$uri = get_template_directory_uri();
	// El CSS de Elementor solo hace falta en las páginas interiores
	if ( ugelch_page_has_static_content() ) {
		wp_enqueue_style( 'ugelch-elementor', $uri . '/assets/vendor/elementor.css', array(), ugelch_asset_ver( 'assets/vendor/elementor.css' ) );
		$css = ugelch_partial_raw( 'pages/' . ugelch_current_slug(), 'css' );
		if ( $css ) wp_add_inline_style( 'ugelch-elementor', $css );
	}
	wp_enqueue_style( 'ugelch-site', $uri . '/assets/css/site.css', array(), ugelch_asset_ver( 'assets/css/site.css' ) );
	if ( is_home() || is_archive() || is_search() || is_singular( 'post' ) || is_404() ) {
		wp_enqueue_style( 'ugelch-blog', $uri . '/assets/css/blog.css', array( 'ugelch-site' ), ugelch_asset_ver( 'assets/css/blog.css' ) );
	}
	wp_enqueue_script( 'ugelch-components', $uri . '/assets/js/components.js', array(), ugelch_asset_ver( 'assets/js/components.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_enqueue_script( 'ugelch-site', $uri . '/assets/js/site.js', array(), ugelch_asset_ver( 'assets/js/site.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
} );

// Sin los estilos globales de WordPress en la parte pública: el diseño es propio. En las
// noticias se mantienen los de los bloques (listas, citas, imágenes del editor).
add_action( 'wp_enqueue_scripts', function () {
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
	if ( ! is_singular( 'post' ) ) wp_dequeue_style( 'wp-block-library' );
}, 100 );
add_action( 'wp_footer', function () { wp_dequeue_style( 'global-styles' ); wp_dequeue_style( 'core-block-supports' ); }, 1 );

// Sin el script de emojis (el navegador ya los muestra)
add_action( 'init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
} );

// Fuentes alojadas en el tema: precarga
add_action( 'wp_head', function () {
	$uri = get_template_directory_uri();
	echo '<link rel="preload" href="' . esc_url( $uri . '/assets/fonts/roboto-latin-400.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	if ( ugelch_page_has_static_content() ) {
		echo '<link rel="preload" href="' . esc_url( $uri . '/assets/fonts/dm-sans-latin-400.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	}
	if ( is_front_page() ) {
		echo '<link rel="preload" as="image" type="image/webp" href="' . esc_url( ugelch_resolve_urls( ugelch_field( 'h_slide_art' ) ) ) . '" fetchpriority="high" media="(min-width: 761px)">' . "\n";
		echo '<link rel="preload" as="image" type="image/webp" href="' . esc_url( ugelch_resolve_urls( ugelch_field( 'h_slide_banner' ) ) ) . '" fetchpriority="high" media="(max-width: 760px)">' . "\n";
	}
}, 2 );

/* ───────── Página actual ───────── */
function ugelch_current_slug() {
	if ( is_front_page() ) return 'home';
	if ( is_home() ) return 'noticias';
	$p = get_queried_object();
	return ( $p instanceof WP_Post ) ? $p->post_name : '';
}

/** ¿La página actual es una página interior con contenido del sitio original? */
function ugelch_page_has_static_content() {
	if ( ! is_page() || is_front_page() ) return false;
	$pages = ugelch_static_pages();
	return isset( $pages[ ugelch_current_slug() ] );
}

/* ───────── Motor de plantillas ───────── */
/**
 * Lee template-parts/<name>.<ext> y resuelve las rutas: THEMEURI (URL del tema) y
 * HOMEURL (raíz del sitio).
 */
function ugelch_partial_raw( $name, $ext = 'html' ) {
	$file = get_template_directory() . '/template-parts/' . $name . '.' . $ext;
	if ( ! file_exists( $file ) ) return false;
	return ugelch_resolve_urls( file_get_contents( $file ) );
}

function ugelch_resolve_urls( $html ) {
	return str_replace(
		array( 'THEMEURI', 'HOMEURL' ),
		array( untrailingslashit( get_template_directory_uri() ), untrailingslashit( home_url() ) ),
		$html
	);
}

/** Imprime una plantilla con sus campos resueltos. */
function ugelch_part( $name ) {
	$html = ugelch_partial_raw( $name );
	if ( $html === false ) return false;
	echo ugelch_apply_tokens( $html ); // phpcs:ignore — los valores se escapan al resolver cada campo
	return true;
}

/* ───────── Campos editables ───────── */
/** Post que guarda los campos de la página actual (la portada usa page_on_front). */
function ugelch_fields_post_id() {
	if ( is_front_page() ) return (int) get_option( 'page_on_front' );
	if ( is_home() ) return (int) get_option( 'page_for_posts' );
	return is_singular() ? (int) get_queried_object_id() : 0;
}

/**
 * Valor de un campo: lo guardado en el post-meta "ugelch_<clave>" o, si nunca se
 * guardó, el valor por defecto del registro. Un campo guardado vacío se respeta.
 */
function ugelch_field( $key ) {
	$pid = ugelch_fields_post_id();
	if ( $pid && metadata_exists( 'post', $pid, 'ugelch_' . $key ) ) {
		return get_post_meta( $pid, 'ugelch_' . $key, true );
	}
	$f = ugelch_text_fields();
	return isset( $f[ $key ] ) ? $f[ $key ]['default'] : '';
}

/** Lista (repeater): lo guardado en "ugelch_rep_<clave>" o la lista por defecto. */
function ugelch_repeater( $key ) {
	$pid = ugelch_fields_post_id();
	if ( $pid && metadata_exists( 'post', $pid, 'ugelch_rep_' . $key ) ) {
		$v = get_post_meta( $pid, 'ugelch_rep_' . $key, true );
		return is_array( $v ) ? $v : array();
	}
	$s = ugelch_repeater_schema();
	return isset( $s[ $key ] ) ? $s[ $key ]['default'] : array();
}

/** Escapa un valor según su tipo antes de insertarlo en el HTML. */
function ugelch_esc_field( $value, $type ) {
	switch ( $type ) {
		case 'image':
		case 'url':
			return esc_url( ugelch_resolve_urls( $value ) );
		case 'html':
			return wp_kses( ugelch_resolve_urls( $value ), ugelch_allowed_html() );
		case 'attr':
			return esc_attr( $value );
		default:
			return esc_html( $value );
	}
}

function ugelch_allowed_html() {
	return array(
		'strong' => array(), 'b' => array(), 'em' => array(), 'i' => array(), 'br' => array(),
		'span' => array( 'class' => true ), 'a' => array( 'href' => true, 'target' => true, 'rel' => true ),
	);
}

/**
 * Sustituye en el HTML las marcas {{clave}}, {{SRCSET:clave}}, {{REP:clave}} y los
 * marcadores globales (GLOBAL:clave, NEWSGRID, HERODOTS…).
 */
function ugelch_apply_tokens( $html ) {
	$fields = ugelch_text_fields();
	$map    = array();

	if ( preg_match_all( '/\{\{([A-Za-z0-9_:]+)\}\}/', $html, $m ) ) {
		foreach ( array_unique( $m[1] ) as $tok ) {
			if ( strpos( $tok, 'REP:' ) === 0 ) {
				$map[ '{{' . $tok . '}}' ] = ugelch_render_repeater( substr( $tok, 4 ) );
			} elseif ( strpos( $tok, 'SRCSET:' ) === 0 ) {
				// srcset solo con la imagen por defecto: si se cambió, la nueva va sola
				$k = substr( $tok, 7 );
				$def = isset( $fields[ $k ] ) ? $fields[ $k ] : null;
				$map[ '{{' . $tok . '}}' ] = ( $def && ! empty( $def['srcset'] ) && ugelch_field( $k ) === $def['default'] )
					? ugelch_resolve_urls( $def['srcset'] ) : '';
			} elseif ( isset( $fields[ $tok ] ) ) {
				$map[ '{{' . $tok . '}}' ] = ugelch_esc_field( ugelch_field( $tok ), $fields[ $tok ]['type'] );
			}
		}
	}
	$html = strtr( $html, $map );

	// Ajustes generales del sitio
	$html = preg_replace_callback( '/GLOBAL:([a-z_]+)/', function ( $mm ) {
		$k = $mm[1];
		$v = ugelch_g( $k );
		return in_array( $k, array( 'direccion', 'horario' ), true ) ? wp_kses( $v, ugelch_allowed_html() ) : ( in_array( $k, array( 'facebook', 'workspace', 'transparencia', 'gobpe' ), true ) ? esc_url( $v ) : esc_html( $v ) );
	}, $html );

	if ( strpos( $html, 'HEROTOTAL' ) !== false || strpos( $html, 'HERODOTS' ) !== false ) {
		$total = 1 + count( ugelch_repeater( 'h_slides' ) );
		$dots  = '';
		for ( $i = 1; $i <= $total; $i++ ) {
			$dots .= '<button type="button" role="tab" aria-label="Aviso ' . $i . '" aria-selected="' . ( $i === 1 ? 'true' : 'false' ) . '"></button>';
		}
		$html = str_replace( array( 'HEROTOTAL', 'HERODOTS' ), array( (string) $total, $dots ), $html );
	}
	if ( strpos( $html, 'NEWSGRID' ) !== false ) $html = str_replace( 'NEWSGRID', ugelch_news_cards( 3 ), $html );
	if ( strpos( $html, 'NEWSURL' ) !== false ) $html = str_replace( 'NEWSURL', esc_url( ugelch_news_url() ), $html );
	$q = rawurlencode( ugelch_g( 'mapa' ) );
	$html = str_replace(
		array( 'MAPSURL', 'MAPEMBED' ),
		array( esc_url( 'https://www.google.com/maps/search/?api=1&query=' . $q ), esc_url( 'https://maps.google.com/maps?q=' . $q . '&t=m&z=17&output=embed&iwloc=near' ) ),
		$html
	);
	return $html;
}

/* ───────── Ajustes generales (una opción para todo el sitio) ───────── */
function ugelch_globals_defaults() {
	return array(
		'entidad'       => 'Unidad de Gestión Educativa Local Chincheros - UGELCH',
		'direccion'     => 'Pasaje Mirador S/N<br>Chincheros - Apurímac, Perú',
		'horario'       => 'Lunes a viernes<br>8:30 a. m. a 4:30 p. m.',
		'resp_transp'   => 'WILBER SALCEDO RAMIREZ',
		'resp_acceso'   => 'CESAR BELISARIO GUTIERREZ TOLEDO',
		'facebook'      => 'https://www.facebook.com/ugel.chinncheros',
		'workspace'     => 'https://workspace.ugelch.gob.pe/',
		'transparencia' => 'https://www.transparencia.gob.pe/enlaces/pte_transparencia_enlaces.aspx?id_entidad=15333',
		'gobpe'         => 'https://www.gob.pe/ugelchincheros',
		'mapa'          => 'UGEL Chincheros',
	);
}

function ugelch_globals_fields() {
	return array(
		'entidad'       => array( 'Nombre de la entidad', 'text' ),
		'direccion'     => array( 'Dirección', 'html' ),
		'horario'       => array( 'Horario de atención', 'html' ),
		'resp_transp'   => array( 'Responsable del Portal de Transparencia', 'text' ),
		'resp_acceso'   => array( 'Responsable del acceso a la información', 'text' ),
		'facebook'      => array( 'Página de Facebook', 'url' ),
		'workspace'     => array( 'Enlace del botón Workspace', 'url' ),
		'transparencia' => array( 'Enlace del Portal de Transparencia', 'url' ),
		'gobpe'         => array( 'Página en gob.pe', 'url' ),
		'mapa'          => array( 'Búsqueda del mapa (Google Maps)', 'text' ),
	);
}

function ugelch_globals() {
	$saved = get_option( 'ugelch_globals', array() );
	return array_merge( ugelch_globals_defaults(), is_array( $saved ) ? $saved : array() );
}

function ugelch_g( $key ) {
	$g = ugelch_globals();
	return isset( $g[ $key ] ) ? $g[ $key ] : '';
}
