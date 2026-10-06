<?php
/**
 * Detalle de una noticia.
 */
get_header();
the_post();
$id      = get_the_ID();
$cats    = get_the_category();
$url     = get_permalink();
$title   = get_the_title();
$share_t = rawurlencode( $title );
$share_u = rawurlencode( $url );
?>
<main id="contenido" class="post-main">
	<article class="post">
		<header class="post-head">
			<div class="wrap post-head-inner">
				<nav class="crumbs" aria-label="Ruta de navegación">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a><span aria-hidden="true">›</span>
					<a href="<?php echo esc_url( ugelch_news_url() ); ?>">Noticias</a>
				</nav>
				<?php if ( $cats ) : ?><span class="post-cat"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
				<h1><?php echo esc_html( $title ); ?></h1>
				<p class="post-meta">
					<?php echo ugelch_post_date_html( $id ); // phpcs:ignore ?>
					<span><?php echo esc_html( ugelch_reading_minutes( $id ) ); ?> min de lectura</span>
				</p>
				<?php if ( has_excerpt() ) : ?><p class="post-lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
			</div>
		</header>

		<div class="wrap post-layout">
			<div class="post-body">
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="post-cover">
						<?php the_post_thumbnail( 'large', array( 'fetchpriority' => 'high', 'loading' => 'eager', 'sizes' => '(max-width: 1024px) 92vw, 760px' ) ); ?>
						<?php $cap = wp_get_attachment_caption( get_post_thumbnail_id() ) ?: get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ); ?>
						<?php if ( $cap ) : ?><figcaption><?php echo esc_html( $cap ); ?></figcaption><?php endif; ?>
					</figure>
				<?php endif; ?>

				<div class="post-content"><?php the_content(); ?></div>

				<footer class="post-foot">
					<p class="share-label">Compartir</p>
					<div class="share">
						<a class="share-fb" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_u; // phpcs:ignore ?>" target="_blank" rel="noopener">Facebook</a>
						<a class="share-wa" href="https://wa.me/?text=<?php echo $share_t . '%20' . $share_u; // phpcs:ignore ?>" target="_blank" rel="noopener">WhatsApp</a>
						<a class="share-x" href="https://twitter.com/intent/tweet?text=<?php echo $share_t; // phpcs:ignore ?>&amp;url=<?php echo $share_u; // phpcs:ignore ?>" target="_blank" rel="noopener">X</a>
						<button type="button" class="share-copy" data-url="<?php echo esc_url( $url ); ?>">Copiar enlace</button>
					</div>
					<nav class="post-nav" aria-label="Más noticias">
						<?php
						$prev = get_previous_post();
						$next = get_next_post();
						if ( $prev ) echo '<a class="post-nav-prev" href="' . esc_url( get_permalink( $prev ) ) . '"><small>Anterior</small>' . esc_html( get_the_title( $prev ) ) . '</a>';
						if ( $next ) echo '<a class="post-nav-next" href="' . esc_url( get_permalink( $next ) ) . '"><small>Siguiente</small>' . esc_html( get_the_title( $next ) ) . '</a>';
						?>
					</nav>
				</footer>
			</div>

			<aside class="post-aside" aria-label="Más noticias">
				<h2>Más noticias</h2>
				<?php
				$more = get_posts( array( 'numberposts' => 4, 'post__not_in' => array( $id ) ) );
				if ( $more ) {
					echo '<ul class="mini-list">';
					foreach ( $more as $m ) {
						echo '<li><a href="' . esc_url( get_permalink( $m ) ) . '">'
							. ( has_post_thumbnail( $m ) ? get_the_post_thumbnail( $m, 'thumbnail', array( 'loading' => 'lazy', 'alt' => '' ) ) : '' )
							. '<span><b>' . esc_html( get_the_title( $m ) ) . '</b>' . ugelch_post_date_html( $m->ID ) . '</span></a></li>'; // phpcs:ignore
					}
					echo '</ul>';
				} else {
					echo '<p class="mini-empty">Pronto habrá más noticias.</p>';
				}
				?>
				<a class="btn-all" href="<?php echo esc_url( ugelch_news_url() ); ?>">Ver todas las noticias</a>
			</aside>
		</div>
	</article>
</main>
<script>
document.querySelector(".share-copy")?.addEventListener("click", function () {
	var b = this; navigator.clipboard && navigator.clipboard.writeText(b.dataset.url).then(function () { b.textContent = "Enlace copiado"; setTimeout(function () { b.textContent = "Copiar enlace"; }, 2000); });
});
</script>
<?php get_footer();
