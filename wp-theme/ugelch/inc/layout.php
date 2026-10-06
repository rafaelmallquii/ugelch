<?php
/**
 * Cabecera, pie, banda de título y menú lateral. Mismo marcado que el sitio estático
 * (tools/layout.js); los enlaces y datos de contacto salen de los ajustes generales.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ugelch_svg( $d, $cls = 'ic' ) {
	return '<svg class="' . $cls . '" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . $d . '</svg>';
}

function ugelch_svg_paths() {
	return array(
		'chev'     => '<path d="m6 9 6 6 6-6"/>',
		'building' => '<path d="M4 21V5l8-3 8 3v16"/><path d="M2 21h20M9 21v-4h6v4M8 8h2M14 8h2M8 12h2M14 12h2"/>',
		'pin'      => '<path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z"/><circle cx="12" cy="10" r="2.5"/>',
		'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'book'     => '<path d="M2 5h7a3 3 0 0 1 3 3v12a2 2 0 0 0-2-2H2zM22 5h-7a3 3 0 0 0-3 3v12a2 2 0 0 1 2-2h8z"/>',
	);
}

/** Grupos del menú principal. */
function ugelch_menu_groups() {
	return apply_filters( 'ugelch_menu_groups', array(
		array( 'n' => 'INSTITUCIONAL', 'i' => array( array( 'Sobre Nosotros', 'sobre-nosotros' ), array( 'Directorio y Declaración Jurada', 'directorio-y-declaracion-jurada' ), array( 'Convenios', 'convenios' ), array( 'Marco Legal', 'marco-legal' ), array( 'Jurisdicción', 'jurisdiccion' ), array( 'Organigrama', 'organigrama' ), array( 'Agenda Institucional', 'agenda-institucional' ) ) ),
		array( 'n' => 'ÁREAS', 'i' => array( array( 'Dirección', 'direccion' ), array( 'Órgano de Control', 'organo-de-control' ), array( 'Órganos de Línea', 'organo-de-linea' ), array( 'Órganos de Asesoramiento', 'organos-de-asesoramiento' ), array( 'Órganos de Apoyo', 'organos-de-apoyo' ) ) ),
		array( 'n' => 'GESTIÓN', 'i' => array( array( 'Documentos de Interés', 'documentos-de-interes' ), array( 'Convocatorias', 'convocatorias' ), array( 'Planes', 'planes' ), array( 'Convenios', 'convenios' ), array( 'Actas', 'actas' ) ) ),
		array( 'n' => 'SERVICIOS', 'i' => array( array( 'Consulta tu Expediente', 'consulta-tu-expediente' ), array( 'Comunidad Educativa', 'comunidad-educativa' ), array( 'TUPA', 'tupa' ), array( 'Preguntas Frecuentes', 'preguntas-frecuentes' ), array( 'Chincheros Lee', 'chincheros-lee' ), array( 'Noticias', 'noticias' ) ) ),
	) );
}

function ugelch_page_url( $slug ) {
	return home_url( '/' . $slug . '/' );
}

function ugelch_header_html() {
	$p    = ugelch_svg_paths();
	$uri  = untrailingslashit( get_template_directory_uri() );
	$slug = ugelch_current_slug();
	if ( is_singular( 'post' ) || is_category() || is_tag() || is_date() ) $slug = 'noticias';

	$groups = '';
	foreach ( ugelch_menu_groups() as $k => $g ) {
		$here  = in_array( $slug, wp_list_pluck( $g['i'], 1 ), true );
		$items = '';
		foreach ( $g['i'] as $it ) {
			$items .= '<a href="' . esc_url( ugelch_page_url( $it[1] ) ) . '"' . ( $it[1] === $slug ? ' aria-current="page"' : '' ) . '>' . esc_html( $it[0] ) . '</a>';
		}
		$groups .= '<div class="nav-group"><button type="button" aria-expanded="false" aria-controls="menu-' . $k . '"' . ( $here ? ' class="active"' : '' ) . '>' . esc_html( $g['n'] ) . ugelch_svg( $p['chev'], 'chev' ) . '</button>'
			. '<div class="dropdown" id="menu-' . $k . '">' . $items . '</div></div>';
	}

	$fb = '<svg class="fb" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="12" fill="currentColor"/><path fill="#0b55c9" d="M13.4 19.5v-6.2h2.1l.3-2.4h-2.4V9.4c0-.7.2-1.2 1.2-1.2h1.3V6.1a17 17 0 0 0-1.9-.1c-1.9 0-3.2 1.1-3.2 3.3v1.7H8.7v2.4h2.1v6.2z"/></svg>';

	return '<a class="skip" href="#contenido">Ir al contenido</a>'
		. '<div class="topbar"><div class="wrap"><img class="gov" src="' . esc_url( $uri . '/assets/img/brand-strip.webp' ) . '" alt="Perú – Ministerio de Educación, DRE Apurímac y UGEL Chincheros" width="890" height="94">'
		. '<a class="intranet" href="' . esc_url( ugelch_g( 'workspace' ) ) . '" aria-label="Workspace">' . ugelch_svg( $p['building'] ) . '<span>Workspace</span></a></div></div>'
		. '<nav class="mainnav" aria-label="Principal"><div class="wrap">'
		. '<a class="logo" href="' . esc_url( home_url( '/' ) ) . '"><span class="logo-badge"><img src="' . esc_url( $uri . '/assets/img/logo-ugel.webp' ) . '" alt="" width="56" height="56"></span><span><b>UGEL Chincheros</b><small>Unidad de Gestión Educativa Local</small></span></a>'
		. '<button class="menu" type="button" aria-label="Abrir menú" aria-expanded="false" aria-controls="menu-principal"><span></span><span></span><span></span></button>'
		. '<div class="nav-links" id="menu-principal">'
		. '<a href="' . esc_url( home_url( '/' ) ) . '"' . ( $slug === 'home' ? ' class="active" aria-current="page"' : '' ) . '>INICIO</a>'
		. $groups
		. '<a href="' . esc_url( ugelch_g( 'gobpe' ) ) . '">GOB.PE</a>'
		. '<a class="trans" href="' . esc_url( ugelch_g( 'transparencia' ) ) . '"><img src="' . esc_url( $uri . '/assets/img/logo-portal-transparencia.webp' ) . '" alt="Portal de Transparencia Estándar" width="371" height="158"></a>'
		. '<a class="fb-link" href="' . esc_url( ugelch_g( 'facebook' ) ) . '">' . $fb . 'Facebook</a>'
		. '</div></div></nav>';
}

function ugelch_footer_html() {
	$p   = ugelch_svg_paths();
	$uri = untrailingslashit( get_template_directory_uri() );
	$kses = ugelch_allowed_html();
	return '<div class="foot-main"><div class="wrap">'
		. '<div class="foot-brand"><span class="logo-badge"><img src="' . esc_url( $uri . '/assets/img/logo-ugel.webp' ) . '" alt="" width="64" height="64" loading="lazy"></span><span><b>UGEL Chincheros</b><small>Unidad de Gestión Educativa Local</small></span></div>'
		. '<div class="foot-info"><p>' . ugelch_svg( $p['pin'] ) . '<span><b>' . esc_html( ugelch_g( 'entidad' ) ) . '</b>' . wp_kses( str_replace( '<br>', ', ', ugelch_g( 'direccion' ) ), $kses ) . '</span></p>'
		. '<p>' . ugelch_svg( $p['clock'] ) . '<span><b class="inline">Horario de atención:</b> ' . wp_kses( str_replace( '<br>', ' de ', ugelch_g( 'horario' ) ), $kses ) . '</span></p></div>'
		. '<div class="foot-resp"><b>Responsables:</b><p><b class="inline">Portal de Transparencia:</b> ' . esc_html( ugelch_g( 'resp_transp' ) ) . '</p><p><b class="inline">Acceso a la información:</b> ' . esc_html( ugelch_g( 'resp_acceso' ) ) . '</p></div>'
		. '</div></div>'
		. '<div class="foot-bar"><div class="wrap">'
		. '<img class="foot-gov" src="' . esc_url( $uri . '/assets/img/gob-minedu.webp' ) . '" alt="Perú – Ministerio de Educación" width="194" height="42" loading="lazy">'
		. '<nav aria-label="Enlaces del pie"><ul><li><a href="' . esc_url( ugelch_page_url( 'mapa-del-sitio' ) ) . '">Mapa del sitio</a></li><li><a href="' . esc_url( ugelch_page_url( 'tupa' ) ) . '">TUPA</a></li><li><a href="' . esc_url( ugelch_page_url( 'preguntas-frecuentes' ) ) . '">Preguntas frecuentes</a></li>'
		. '<li><a href="https://reclamos.servicios.gob.pe/">' . ugelch_svg( $p['book'] ) . 'Libro de reclamaciones</a></li></ul></nav>'
		. '<small>Copyright © ' . esc_html( wp_date( 'Y' ) ) . ' | UGEL Chincheros</small>'
		. '</div></div>';
}

/** Banda azul con el título de la página. */
function ugelch_page_title_html( $title, $eyebrow = 'UGEL CHINCHEROS' ) {
	return '<section class="page-title"><small>' . esc_html( $eyebrow ) . '</small><h1>' . esc_html( $title ) . '</h1></section>';
}

/** Menú lateral de la sección a la que pertenece la página. */
function ugelch_side_nav_html( $slug ) {
	$pages = ugelch_static_pages();
	if ( empty( $pages[ $slug ]['nav'] ) ) return '';
	$navs = ugelch_side_navs();
	$items = isset( $navs[ $pages[ $slug ]['nav'] ] ) ? $navs[ $pages[ $slug ]['nav'] ] : array();
	if ( ! $items ) return '';
	$out = '';
	foreach ( $items as $it ) {
		$out .= '<a href="' . esc_url( ugelch_page_url( $it[0] ) ) . '"' . ( $it[0] === $slug ? ' aria-current="page"' : '' ) . '>' . esc_html( $it[1] ) . '</a>';
	}
	return '<aside class="side-nav"><nav aria-label="Páginas de la sección">' . $out . '</nav></aside>';
}

/** Mapa del sitio: páginas agrupadas como en el menú principal. */
function ugelch_sitemap_html() {
	$groups = array(
		'Institucional'   => array( 'sobre-nosotros', 'directorio-y-declaracion-jurada', 'convenios', 'jurisdiccion', 'marco-legal', 'organigrama', 'agenda-institucional' ),
		'Áreas'           => array( 'direccion', 'organo-de-control', 'organo-de-linea', 'organos-de-asesoramiento', 'organos-de-apoyo' ),
		'Control interno' => array( 'actas', 'diagnostico', 'planes', 'implementacion', 'evaluacion-del-sistema-de-control-interno' ),
		'Convocatorias'   => array( 'convocatorias', 'auxiliar-de-educacion', 'convocatorias-cas', 'convocatorias-docentes', 'convocatoria-practicante', 'convocatoria-promotores', 'convocatoria-deportivos', 'convocatoria-ugel-chincheros', 'convocatoria-personal-administrativo' ),
		'Documentos'      => array( 'documentos-de-interes', 'oficios' ),
		'Servicios'       => array( 'consulta-tu-expediente', 'comunidad-educativa', 'app-interes', 'chincheros-lee', 'tupa', 'preguntas-frecuentes', 'noticias' ),
	);
	$out = '';
	foreach ( $groups as $name => $slugs ) {
		$li = '';
		foreach ( $slugs as $s ) {
			$p = get_page_by_path( $s, OBJECT, 'page' );
			if ( $p && $p->post_status === 'publish' ) $li .= '<li><a href="' . esc_url( get_permalink( $p ) ) . '">' . esc_html( get_the_title( $p ) ) . '</a></li>';
		}
		if ( $li ) $out .= '<section class="sitemap-group"><h2>' . esc_html( $name ) . '</h2><ul>' . $li . '</ul></section>';
	}
	$recent = get_posts( array( 'numberposts' => 6 ) );
	if ( $recent ) {
		$li = '';
		foreach ( $recent as $r ) $li .= '<li><a href="' . esc_url( get_permalink( $r ) ) . '">' . esc_html( get_the_title( $r ) ) . '</a></li>';
		$out .= '<section class="sitemap-group"><h2>Últimas noticias</h2><ul>' . $li . '</ul></section>';
	}
	return '<p class="sitemap-intro">Todas las páginas del portal de la UGEL Chincheros. También puedes ir al <a href="' . esc_url( home_url( '/' ) ) . '">inicio</a>.</p><div class="sitemap-grid">' . $out . '</div>';
}
