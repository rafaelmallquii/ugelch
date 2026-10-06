<?php get_header(); echo ugelch_page_title_html( 'Página no encontrada' ); // phpcs:ignore ?>
<main id="contenido" class="wrap blog not-found">
	<p>La página que buscas no existe o cambió de dirección.</p>
	<p><a class="btn-all" href="<?php echo esc_url( home_url( '/' ) ); ?>">Ir al inicio</a> <a class="btn-all btn-ghost" href="<?php echo esc_url( home_url( '/mapa-del-sitio/' ) ); ?>">Ver el mapa del sitio</a></p>
</main>
<?php get_footer();
