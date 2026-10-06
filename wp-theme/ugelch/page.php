<?php
/**
 * Páginas interiores. Orden de resolución del contenido:
 * 1. Mapa del sitio: se genera a partir del registro de páginas.
 * 2. El contenido de la página en WordPress (al activar el tema se vuelca ahí el HTML
 *    del sitio original, en un bloque "HTML personalizado").
 * 3. Si la página está vacía, la plantilla template-parts/pages/<slug>.html.
 */
get_header();
the_post();
$slug   = ugelch_current_slug();
$static = ugelch_static_pages();
$is_el  = isset( $static[ $slug ] );

echo ugelch_page_title_html( get_the_title() ); // phpcs:ignore — escapado en la función

if ( $slug === 'mapa-del-sitio' ) : ?>
<main id="contenido"><article class="content sitemap"><?php echo ugelch_sitemap_html(); // phpcs:ignore ?></article></main>
<?php else :
	$nav     = $is_el ? ugelch_side_nav_html( $slug ) : '';
	$content = trim( (string) get_post_field( 'post_content', get_the_ID() ) );
	if ( $content !== '' ) {
		// do_blocks y no the_content: wpautop/wptexturize romperían el marcado del diseño
		$html = ugelch_resolve_urls( do_blocks( $content ) );
		if ( ! has_blocks( $content ) ) $html = wpautop( $html );
	} else {
		$html = $is_el ? (string) ugelch_partial_raw( 'pages/' . $slug ) : '';
	}
	?>
<main id="contenido"<?php echo $nav ? ' class="page-layout"' : ''; ?>><?php echo $nav; // phpcs:ignore ?><article class="content<?php echo $is_el ? ' el elementor-kit-1316' : ''; ?>"><?php echo $html; // phpcs:ignore — contenido de la página ?></article></main>
<?php endif;
get_footer();

