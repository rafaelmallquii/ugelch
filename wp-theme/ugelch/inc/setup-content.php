<?php
/**
 * Al activar el tema: crea las páginas, fija la portada y el blog, vuelca el contenido de
 * las páginas interiores en un bloque "HTML personalizado" (solo si están vacías: nunca
 * pisa lo editado) y crea las noticias iniciales.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/posts-seed.php';

/** Páginas del sitio: las interiores del sitio original más las propias del tema. */
function ugelch_pages() {
	$pages = array(
		'inicio'         => array( 'title' => 'Inicio' ),
		'noticias'       => array( 'title' => 'Noticias' ),
		'mapa-del-sitio' => array( 'title' => 'Mapa del sitio' ),
	);
	foreach ( ugelch_static_pages() as $slug => $p ) $pages[ $slug ] = array( 'title' => $p['title'] );
	return apply_filters( 'ugelch_pages', $pages );
}

/** Crea la página si no existe (idempotente). Devuelve su ID. */
function ugelch_ensure_page( $slug, $title ) {
	$ex = get_page_by_path( $slug, OBJECT, 'page' );
	if ( $ex ) return (int) $ex->ID;
	$id = wp_insert_post( array( 'post_title' => $title, 'post_name' => $slug, 'post_status' => 'publish', 'post_type' => 'page', 'comment_status' => 'closed' ) );
	return is_wp_error( $id ) ? 0 : (int) $id;
}

/** Vuelca el HTML del sitio original en las páginas interiores que estén vacías. */
function ugelch_seed_page_content( $ids ) {
	$static = ugelch_static_pages();
	kses_remove_filters(); // el diseño usa SVG y atributos que kses quitaría
	foreach ( $ids as $slug => $pid ) {
		if ( ! $pid || ! isset( $static[ $slug ] ) ) continue;
		$post = get_post( $pid );
		if ( ! $post || trim( (string) $post->post_content ) !== '' ) continue; // no pisa lo editado
		$html = ugelch_partial_raw( 'pages/' . $slug );
		if ( $html === false || trim( $html ) === '' ) continue;
		wp_update_post( array( 'ID' => $pid, 'post_content' => "<!-- wp:html -->\n" . $html . "\n<!-- /wp:html -->" ) );
	}
	kses_init();
}

/** Copia una imagen del tema a la mediateca. */
function ugelch_import_image( $file, $post_id, $alt ) {
	$path = get_template_directory() . '/assets/img/noticias/' . $file;
	if ( ! file_exists( $path ) ) return 0;
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$up = wp_upload_bits( $file, null, file_get_contents( $path ) );
	if ( ! empty( $up['error'] ) ) return 0;
	$att = wp_insert_attachment( array( 'post_mime_type' => 'image/jpeg', 'post_title' => $alt, 'post_status' => 'inherit' ), $up['file'], $post_id );
	if ( ! $att || is_wp_error( $att ) ) return 0;
	wp_update_attachment_metadata( $att, wp_generate_attachment_metadata( $att, $up['file'] ) );
	update_post_meta( $att, '_wp_attachment_image_alt', $alt );
	return (int) $att;
}

/** Crea las noticias iniciales (una vez; no duplica si ya existen). */
function ugelch_seed_posts() {
	// La entrada de ejemplo de WordPress ("Hola mundo"), si nunca se editó, va a la papelera
	foreach ( array( 'hola-mundo', 'hello-world' ) as $sample ) {
		$p = get_page_by_path( $sample, OBJECT, 'post' );
		if ( $p && $p->post_modified === $p->post_date ) wp_trash_post( $p->ID );
	}
	$cat = term_exists( 'Noticias', 'category' );
	if ( ! $cat ) $cat = wp_insert_term( 'Noticias', 'category', array( 'slug' => 'noticias-ugel' ) );
	$cat_id = is_array( $cat ) ? (int) $cat['term_id'] : (int) $cat;
	foreach ( ugelch_seed_posts_data() as $d ) {
		if ( get_page_by_path( $d['slug'], OBJECT, 'post' ) ) continue;
		$id = wp_insert_post( array(
			'post_title'    => $d['title'],
			'post_name'     => $d['slug'],
			'post_status'   => 'publish',
			'post_type'     => 'post',
			'post_date'     => $d['date'],
			'post_excerpt'  => $d['excerpt'],
			'post_content'  => ugelch_seed_blocks( $d['content'], $d['source'] ),
			'post_category' => $cat_id ? array( $cat_id ) : array(),
			'comment_status' => 'closed',
		) );
		if ( ! $id || is_wp_error( $id ) ) continue;
		$att = ugelch_import_image( $d['image'], $id, $d['alt'] );
		if ( $att ) set_post_thumbnail( $id, $att );
	}
}

function ugelch_setup_site() {
	// Tras activar el tema, la petición siguiente y la del cron de WordPress pueden llegar
	// aquí a la vez: un bloqueo atómico (add_option solo lo consigue una) evita duplicados
	if ( ! add_option( 'ugelch_setup_lock', time(), '', false ) ) {
		if ( time() - (int) get_option( 'ugelch_setup_lock' ) < 300 ) return;
		update_option( 'ugelch_setup_lock', time(), false ); // bloqueo antiguo de un proceso que falló
	}
	$ids = array();
	foreach ( ugelch_pages() as $slug => $p ) $ids[ $slug ] = ugelch_ensure_page( $slug, $p['title'] );
	if ( $ids['inicio'] ) { update_option( 'show_on_front', 'page' ); update_option( 'page_on_front', $ids['inicio'] ); }
	if ( $ids['noticias'] ) update_option( 'page_for_posts', $ids['noticias'] );
	if ( ! get_option( 'permalink_structure' ) ) update_option( 'permalink_structure', '/%postname%/' );
	update_option( 'blogname', 'Ugel Chincheros' );
	update_option( 'blogdescription', 'Unidad de Gestión Educativa Local Chincheros' );
	ugelch_seed_page_content( $ids );
	ugelch_seed_posts();
	update_option( 'ugelch_pages_v', UGELCH_PAGES_V );
	flush_rewrite_rules();
	delete_option( 'ugelch_setup_lock' );
}

add_action( 'after_switch_theme', function () {
	// Si ya se creó el contenido de esta versión (reactivación), no se repite
	if ( get_option( 'ugelch_pages_v' ) === UGELCH_PAGES_V ) { flush_rewrite_rules(); return; }
	ugelch_setup_site();
} );

// Al actualizar el tema con páginas nuevas en el registro, se crean sin reactivarlo
add_action( 'admin_init', function () {
	if ( get_option( 'ugelch_pages_v' ) === UGELCH_PAGES_V || ! current_user_can( 'manage_options' ) ) return;
	ugelch_setup_site();
} );
