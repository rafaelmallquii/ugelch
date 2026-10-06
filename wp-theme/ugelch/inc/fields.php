<?php
/**
 * Registro de campos editables (portada) y listas (repeaters).
 *
 * Estructura: página → secciones → campos. Cada campo: [etiqueta, tipo, valor por defecto,
 * srcset opcional]. Tipos: text, textarea, html (negrita, cursiva, saltos y enlaces),
 * image, url, attr. 'sel' es el selector de la sección en la página: el panel la resalta y
 * hace scroll hasta ella.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ugelch_img( $file ) {
	return 'THEMEURI/assets/img/' . $file;
}

function ugelch_text_registry() {
	$gob = 'https://www.gob.pe/institucion/ugelchincheros/informes-publicaciones/8485578-reasignacion-de-auxiliares-de-educacion-2026-por-interes-personal-y-unidad-familiar';
	return apply_filters( 'ugelch_text_registry', array(
		'home' => array(
			'label'    => 'Inicio',
			'sections' => array(
				'slide' => array(
					'label'  => 'Carrusel · Aviso principal',
					'sel'    => '.hero',
					'fields' => array(
						'h_slide_tag'        => array( 'Etiqueta roja', 'text', 'CRONOGRAMA REGIONAL' ),
						'h_slide_title'      => array( 'Título (cursiva = texto en rojo)', 'html', 'Reasignación de Auxiliares de Educación <em>2026</em>' ),
						'h_slide_sub'        => array( 'Subtítulo', 'text', 'Por Interés Personal y Unidad Familiar' ),
						'h_slide_btn'        => array( 'Texto del botón', 'text', 'Ver cronograma' ),
						'h_slide_link'       => array( 'Enlace del botón y del aviso', 'url', $gob ),
						'h_slide_art'        => array( 'Ilustración (escritorio)', 'image', ugelch_img( 'hero-cronograma-1300.webp' ),
							'srcset="THEMEURI/assets/img/hero-cronograma-650.webp 650w, THEMEURI/assets/img/hero-cronograma-900.webp 900w, THEMEURI/assets/img/hero-cronograma-1300.webp 1300w" sizes="(max-width: 760px) 92vw, 56vw"' ),
						'h_slide_art_alt'    => array( 'Descripción de la ilustración', 'attr', 'Resolución y cronograma de reasignación junto a una familia' ),
						'h_slide_banner'     => array( 'Aviso en imagen (móvil, proporción 2560×981)', 'image', ugelch_img( 'slide-cronograma-800.webp' ),
							'srcset="THEMEURI/assets/img/slide-cronograma-800.webp 800w, THEMEURI/assets/img/slide-cronograma-1600.webp 1600w" sizes="100vw"' ),
						'h_slide_banner_alt' => array( 'Descripción del aviso en imagen', 'attr', 'Cronograma regional: reasignación de auxiliares de educación 2026 por interés personal y unidad familiar' ),
					),
				),
				'slides' => array( 'label' => 'Carrusel · Otros avisos', 'sel' => '.hero', 'repeater' => 'h_slides', 'fields' => array() ),
				'comunicados' => array(
					'label'  => 'Comunicados',
					'sel'    => '.row-3 .card:nth-child(1)',
					'fields' => array(
						'h_com_title' => array( 'Título', 'text', 'Comunicados' ),
						'h_com_more'  => array( 'Enlace "Ver todos"', 'url', 'HOMEURL/documentos-de-interes/' ),
					),
					'repeater' => 'h_comunicados',
				),
				'oficios' => array(
					'label'  => 'Oficios',
					'sel'    => '.row-3 .card:nth-child(2)',
					'fields' => array(
						'h_ofi_title' => array( 'Título', 'text', 'Oficios' ),
						'h_ofi_more'  => array( 'Enlace "Ver todos"', 'url', 'https://www.gob.pe/institucion/ugelchincheros/informes-publicaciones/tipos/353-oficio-multiple' ),
					),
					'repeater' => 'h_oficios',
				),
				'redes' => array(
					'label'  => 'Redes sociales',
					'sel'    => '.row-3 .card:nth-child(3)',
					'fields' => array(
						'h_fb_title' => array( 'Título', 'text', 'Redes sociales' ),
						'h_fb_cover' => array( 'Portada de Facebook', 'image', ugelch_img( 'fb-cover.webp' ) ),
						'h_fb_name'  => array( 'Nombre de la página', 'text', 'UGEL Chincheros' ),
						'h_fb_sub'   => array( 'Texto bajo el nombre', 'text', 'Página oficial en Facebook' ),
						'h_fb_text'  => array( 'Descripción', 'textarea', 'Nueva página de la Unidad de Gestión Educativa Local de Chincheros' ),
						'h_fb_kind'  => array( 'Categoría', 'text', 'Sitio web de educación' ),
						'h_fb_btn'   => array( 'Texto del botón', 'text', 'Seguir' ),
					),
				),
				'ubicacion' => array(
					'label'  => 'Ubicación',
					'sel'    => '.row-2 .card',
					'fields' => array(
						'h_loc_title'       => array( 'Título', 'text', 'Ubicación' ),
						'h_loc_hours_title' => array( 'Título del horario', 'text', 'Horario de atención' ),
					),
					'note' => 'La dirección, el horario y la búsqueda del mapa se cambian en "Ajustes generales".',
				),
				'video' => array(
					'label'  => 'Video institucional',
					'sel'    => '.video',
					'fields' => array(
						'h_video_id'       => array( 'Código del video de YouTube (lo que va después de "v=")', 'attr', 'cXwymR4O0K4' ),
						'h_video_title'    => array( 'Título del video', 'text', 'Directromes - Fundación Romero' ),
						'h_video_channel'  => array( 'Canal', 'text', 'Fundación Romero' ),
						'h_video_initials' => array( 'Iniciales del canal', 'text', 'FR' ),
						'h_video_thumb'    => array( 'Miniatura', 'image', ugelch_img( 'yt-thumb.webp' ) ),
					),
				),
				'noticias' => array(
					'label'  => 'Noticias y actividades',
					'sel'    => '.news',
					'fields' => array( 'h_news_title' => array( 'Título', 'text', 'Noticias y actividades' ) ),
					'note'   => 'Se muestran las 3 últimas entradas del blog. Para añadir una noticia: Escritorio → Entradas → Añadir nueva.',
				),
				'accesos' => array(
					'label'    => 'Accesos rápidos',
					'sel'      => '.quick',
					'fields'   => array( 'h_quick_title' => array( 'Título', 'text', 'Accesos rápidos' ) ),
					'repeater' => 'h_quick',
				),
			),
		),
	) );
}

/** Registro aplanado: clave => [label, type, default, srcset, page, section]. */
function ugelch_text_fields() {
	static $flat = null;
	if ( $flat !== null ) return $flat;
	$flat = array();
	foreach ( ugelch_text_registry() as $page => $p ) {
		foreach ( $p['sections'] as $sid => $s ) {
			foreach ( $s['fields'] as $k => $f ) {
				$flat[ $k ] = array( 'label' => $f[0], 'type' => $f[1], 'default' => $f[2], 'srcset' => isset( $f[3] ) ? $f[3] : '', 'page' => $page, 'section' => $sid );
			}
		}
	}
	return $flat;
}

/* ───────── Listas (repeaters) ───────── */
function ugelch_quick_icons() {
	return array( 'q_normas' => 'Documento', 'q_tramites' => 'Personas', 'q_transparencia' => 'Carpeta', 'q_estadisticas' => 'Gráfico', 'q_calendario' => 'Libro', 'q_mesa' => 'Usuario' );
}

function ugelch_repeater_schema() {
	$gob   = 'https://www.gob.pe/institucion/ugelchincheros/informes-publicaciones/8485578-reasignacion-de-auxiliares-de-educacion-2026-por-interes-personal-y-unidad-familiar';
	$ofi   = 'https://www.gob.pe/institucion/ugelchincheros/informes-publicaciones/tipos/353-oficio-multiple';
	$doc_fields = array(
		'fecha'  => array( 'Fecha (texto)', 'text' ),
		'titulo' => array( 'Título', 'text' ),
		'enlace' => array( 'Enlace', 'url' ),
	);
	return apply_filters( 'ugelch_repeater_schema', array(
		'h_slides' => array(
			'label' => 'Avisos del carrusel', 'item' => 'Aviso', 'add' => 'Añadir aviso',
			'hint'  => 'Imágenes con proporción 2560×981 (por ejemplo, 1600×614 px) para que se vean completas.',
			'fields' => array(
				'img'    => array( 'Imagen del aviso', 'image' ),
				'alt'    => array( 'Descripción para lectores de pantalla (qué dice el aviso)', 'textarea' ),
				'enlace' => array( 'Enlace al tocar el aviso (opcional)', 'url' ),
			),
			'default' => array(
				array( 'img' => ugelch_img( 'slide-juegos-florales-1600.webp' ), 'alt' => 'Juegos Florales etapa UGEL: categoría D primaria el viernes 21 de agosto de 2026 y categorías E y F secundaria y EBA el lunes 24 de agosto de 2026, 9:00 a. m., UGEL Chincheros', 'enlace' => '' ),
				array( 'img' => ugelch_img( 'slide-onem-2026-1600.webp' ), 'alt' => 'Olimpiada Nacional Escolar de Matemática: examen etapa UGEL el 20 de agosto, 8:30 a. m., I.E.S.M. José María Arguedas - Uripa', 'enlace' => '' ),
			),
		),
		'h_comunicados' => array(
			'label' => 'Lista de comunicados', 'item' => 'Comunicado', 'add' => 'Añadir comunicado', 'fields' => $doc_fields,
			'default' => array(
				array( 'fecha' => '15 de mar. de 2025', 'titulo' => 'Cronograma Regional de Reasignación de Auxiliares de Educación 2026', 'enlace' => $gob ),
				array( 'fecha' => '10 de mar. de 2025', 'titulo' => 'Precisiones sobre el proceso de reasignación - Interés Personal', 'enlace' => 'HOMEURL/documentos-de-interes/' ),
				array( 'fecha' => '05 de mar. de 2025', 'titulo' => 'Capacitación a directores de II.EE. para el Buen Inicio del Año Escolar 2025', 'enlace' => 'HOMEURL/documentos-de-interes/' ),
			),
		),
		'h_oficios' => array(
			'label' => 'Lista de oficios', 'item' => 'Oficio', 'add' => 'Añadir oficio', 'fields' => $doc_fields,
			'default' => array(
				array( 'fecha' => '2026', 'titulo' => 'Oficio Múltiple 2026 – Comunicado importante', 'enlace' => $ofi ),
				array( 'fecha' => '08 de mar. de 2025', 'titulo' => 'Comunicado Importante sobre proceso de reasignación', 'enlace' => 'HOMEURL/oficios/' ),
				array( 'fecha' => '03 de mar. de 2025', 'titulo' => 'Oficio Múltiple N.° 018-2025 Orientaciones BIAE 2025', 'enlace' => 'HOMEURL/oficios/' ),
			),
		),
		'h_quick' => array(
			'label' => 'Accesos', 'item' => 'Acceso', 'add' => 'Añadir acceso',
			'fields' => array(
				'texto'  => array( 'Texto (usa <br> para partir la línea)', 'html' ),
				'enlace' => array( 'Enlace', 'url' ),
				'icono'  => array( 'Icono', 'select', ugelch_quick_icons() ),
				'color'  => array( 'Color del icono', 'color' ),
			),
			'default' => array(
				array( 'texto' => 'Normas<br>y directivas', 'enlace' => 'HOMEURL/marco-legal/', 'icono' => 'q_normas', 'color' => '#1463d8' ),
				array( 'texto' => 'Trámites<br>y servicios', 'enlace' => 'HOMEURL/tupa/', 'icono' => 'q_tramites', 'color' => '#e3262b' ),
				array( 'texto' => 'Transparencia<br>y acceso a la información', 'enlace' => 'https://www.transparencia.gob.pe/enlaces/pte_transparencia_enlaces.aspx?id_entidad=15333', 'icono' => 'q_transparencia', 'color' => '#f59e0b' ),
				array( 'texto' => 'Estadísticas<br>educativas', 'enlace' => 'https://escale.minedu.gob.pe/', 'icono' => 'q_estadisticas', 'color' => '#16a34a' ),
				array( 'texto' => 'Calendario<br>institucional', 'enlace' => 'HOMEURL/agenda-institucional/', 'icono' => 'q_calendario', 'color' => '#9333ea' ),
				array( 'texto' => 'Mesa de partes<br>virtual', 'enlace' => 'HOMEURL/consulta-tu-expediente/', 'icono' => 'q_mesa', 'color' => '#64748b' ),
			),
		),
	) );
}

/** HTML de una lista con el marcado del diseño. */
function ugelch_render_repeater( $key ) {
	$rows = ugelch_repeater( $key );
	$u    = function ( $v ) { return esc_url( ugelch_resolve_urls( (string) $v ) ); };
	$out  = '';
	switch ( $key ) {
		case 'h_slides':
			$total = 1 + count( $rows );
			foreach ( array_values( $rows ) as $i => $r ) {
				$img = '<img src="' . $u( $r['img'] ?? '' ) . '" alt="' . esc_attr( $r['alt'] ?? '' ) . '" width="2560" height="981" loading="lazy" decoding="async">';
				// Las imágenes por defecto tienen versión ligera para móvil
				if ( preg_match( '#/(slide-[a-z0-9-]+)-1600\.webp$#', (string) ( $r['img'] ?? '' ), $mm ) ) {
					$base = untrailingslashit( get_template_directory_uri() ) . '/assets/img/' . $mm[1];
					$img  = str_replace( '<img ', '<img srcset="' . esc_url( $base . '-800.webp' ) . ' 800w, ' . esc_url( $base . '-1600.webp' ) . ' 1600w" sizes="100vw" ', $img );
				}
				if ( ! empty( $r['enlace'] ) ) $img = '<a href="' . $u( $r['enlace'] ) . '">' . $img . '</a>';
				$out .= '<div class="hero-slide hero-img" aria-roledescription="diapositiva" aria-label="' . ( $i + 2 ) . ' de ' . $total . '">' . $img . '</div>';
			}
			break;
		case 'h_comunicados':
		case 'h_oficios':
			$tone = $key === 'h_oficios' ? 'doc-red' : 'doc-blue';
			foreach ( $rows as $r ) {
				$out .= '<li><a href="' . $u( $r['enlace'] ?? '' ) . '"><span class="doc ' . $tone . '">' . ugelch_icon( 'doc' ) . '</span><span class="doc-txt"><time>' . esc_html( $r['fecha'] ?? '' ) . '</time>' . esc_html( $r['titulo'] ?? '' ) . '</span>' . ugelch_icon( 'go' ) . '</a></li>';
			}
			break;
		case 'h_quick':
			foreach ( $rows as $r ) {
				$icon  = isset( $r['icono'] ) && ugelch_icon( $r['icono'] ) ? $r['icono'] : 'q_normas';
				$color = isset( $r['color'] ) && preg_match( '/^#[0-9a-f]{3,8}$/i', $r['color'] ) ? $r['color'] : '#1463d8';
				$out  .= '<a class="quick-item" href="' . $u( $r['enlace'] ?? '' ) . '"><span class="q-ic" style="--c:' . $color . '">' . ugelch_icon( $icon ) . '</span><b>' . wp_kses( $r['texto'] ?? '', ugelch_allowed_html() ) . '</b></a>';
			}
			break;
	}
	return $out;
}
