<?php
/**
 * Listado de noticias (página "Noticias"), archivos de categoría/fecha y búsqueda.
 */
get_header();

if ( is_search() ) {
	$heading = 'Resultados para «' . get_search_query() . '»';
} elseif ( is_archive() ) {
	$heading = wp_strip_all_tags( get_the_archive_title() );
} else {
	$heading = 'Noticias y actividades';
}
echo ugelch_page_title_html( $heading ); // phpcs:ignore
$paged = max( 1, (int) get_query_var( 'paged' ) );
?>
<main id="contenido" class="wrap blog">
	<?php if ( have_posts() ) : ?>
		<?php
		the_post();
		if ( $paged === 1 && ! is_search() ) : // la más reciente, destacada
			$id = get_the_ID(); ?>
			<a class="news-featured" href="<?php the_permalink(); ?>">
				<span class="news-featured-img"><?php echo ugelch_post_image( $id, 'large' ); // phpcs:ignore ?></span>
				<span class="news-featured-body">
					<span class="post-cat">Lo más reciente</span>
					<?php echo ugelch_post_date_html( $id ); // phpcs:ignore ?>
					<b><?php the_title(); ?></b>
					<span><?php echo esc_html( ugelch_excerpt( $id, 40 ) ); ?></span>
					<span class="news-featured-more">Leer noticia <?php echo ugelch_icon( 'more' ); // phpcs:ignore ?></span>
				</span>
			</a>
		<?php else :
			rewind_posts();
		endif; ?>
		<div class="news-grid blog-grid">
			<?php while ( have_posts() ) : the_post(); echo ugelch_news_card( get_the_ID() ); endwhile; // phpcs:ignore ?>
		</div>
		<?php
		$links = paginate_links( array( 'type' => 'list', 'prev_text' => '‹ Anteriores', 'next_text' => 'Siguientes ›' ) );
		if ( $links ) echo '<nav class="pager" aria-label="Páginas de noticias">' . $links . '</nav>'; // phpcs:ignore
		?>
	<?php else : ?>
		<p class="news-empty">No hay noticias publicadas todavía.</p>
	<?php endif; ?>
</main>
<?php get_footer();
