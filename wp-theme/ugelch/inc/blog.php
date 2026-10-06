<?php
/**
 * Noticias (entradas del blog): tarjetas, fechas e imagen destacada.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ugelch_news_url() {
	$pid = (int) get_option( 'page_for_posts' );
	return $pid ? get_permalink( $pid ) : home_url( '/' );
}

/** Imagen de la tarjeta: la destacada o, si no hay, el logo de la UGEL sobre fondo. */
function ugelch_post_image( $post_id, $size = 'ugelch-card' ) {
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail( $post_id, $size, array( 'loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(max-width: 760px) 92vw, 400px' ) );
	}
	return '<span class="news-noimg"><img src="' . esc_url( get_template_directory_uri() . '/assets/img/logo-ugel.webp' ) . '" alt="" width="96" height="96" loading="lazy"></span>';
}

/** Fecha en español con el formato del sitio: "9 de abr. de 2025" (sin depender del idioma de WordPress). */
function ugelch_fecha( $post_id ) {
	$m = array( 'ene.', 'feb.', 'mar.', 'abr.', 'may.', 'jun.', 'jul.', 'ago.', 'set.', 'oct.', 'nov.', 'dic.' );
	$t = get_post_timestamp( $post_id );
	return $t ? wp_date( 'j', $t ) . ' de ' . $m[ (int) wp_date( 'n', $t ) - 1 ] . ' de ' . wp_date( 'Y', $t ) : '';
}

function ugelch_post_date_html( $post_id ) {
	return '<time datetime="' . esc_attr( get_the_date( 'c', $post_id ) ) . '">' . ugelch_icon( 'cal' ) . esc_html( ugelch_fecha( $post_id ) ) . '</time>';
}

function ugelch_excerpt( $post_id, $words = 22 ) {
	$p = get_post( $post_id );
	$t = has_excerpt( $post_id ) ? $p->post_excerpt : wp_strip_all_tags( strip_shortcodes( $p->post_content ) );
	return wp_trim_words( $t, $words, '…' );
}

/** Una tarjeta de noticia, con el mismo marcado que la portada estática. */
function ugelch_news_card( $post_id ) {
	return '<a class="news-item" href="' . esc_url( get_permalink( $post_id ) ) . '">'
		. ugelch_post_image( $post_id )
		. '<span class="news-body">' . ugelch_post_date_html( $post_id )
		. '<b>' . esc_html( get_the_title( $post_id ) ) . '</b><span>' . esc_html( ugelch_excerpt( $post_id ) ) . '</span></span>'
		. ugelch_icon( 'go' ) . '</a>';
}

/** Últimas noticias para la portada. */
function ugelch_news_cards( $n = 3 ) {
	$q = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => $n, 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
	if ( ! $q->have_posts() ) {
		return '<p class="news-empty">Pronto publicaremos las noticias y actividades de la UGEL Chincheros.</p>';
	}
	$out = '';
	foreach ( $q->posts as $p ) $out .= ugelch_news_card( $p->ID );
	return $out;
}

/** Minutos de lectura aproximados. */
function ugelch_reading_minutes( $post_id ) {
	$words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) );
	return max( 1, (int) round( $words / 200 ) );
}

add_action( 'pre_get_posts', function ( $q ) {
	if ( ! is_admin() && $q->is_main_query() && ( $q->is_home() || $q->is_archive() ) ) $q->set( 'posts_per_page', 9 );
} );
