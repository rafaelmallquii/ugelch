<?php
/**
 * Editor en la propia página (como en Grenvíos): botón flotante "Editar página" y panel
 * lateral con los campos de cada sección, las listas y los ajustes generales. Solo lo
 * ven usuarios con permiso de edición. Guarda por REST en post-meta (ugelch_<clave>,
 * ugelch_rep_<clave>) y en la opción ugelch_globals.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ugelch_editor_enabled() {
	return is_user_logged_in() && current_user_can( 'edit_posts' ) && ! is_admin();
}

add_action( 'wp_enqueue_scripts', function () {
	if ( ! ugelch_editor_enabled() ) return;
	$uri = get_template_directory_uri();
	if ( is_front_page() ) wp_enqueue_media();
	wp_enqueue_style( 'ugelch-editor', $uri . '/assets/css/editor.css', array(), ugelch_asset_ver( 'assets/css/editor.css' ) );
	wp_enqueue_script( 'ugelch-editor', $uri . '/assets/js/editor.js', array(), ugelch_asset_ver( 'assets/js/editor.js' ), array( 'in_footer' => true ) );
	wp_localize_script( 'ugelch-editor', 'ugelchEditor', array(
		'postId'     => ugelch_fields_post_id(),
		'nonce'      => wp_create_nonce( 'wp_rest' ),
		'restUrl'    => rest_url( 'ugelch/v1/save-page' ),
		'restUrlAlt' => add_query_arg( 'rest_route', '/ugelch/v1/save-page', home_url( '/' ) ),
	) );
}, 100 );

/* ───────── REST ───────── */
add_action( 'rest_api_init', function () {
	register_rest_route( 'ugelch/v1', '/save-page', array(
		'methods'             => 'POST',
		'callback'            => 'ugelch_rest_save_page',
		'permission_callback' => function () { return current_user_can( 'edit_posts' ); },
	) );
} );

function ugelch_sanitize_value( $value, $type ) {
	$value = is_scalar( $value ) ? (string) $value : '';
	switch ( $type ) {
		case 'image':
		case 'url':
			// Se admiten URL absolutas y las del tema/sitio con marcador
			if ( strpos( $value, 'THEMEURI/' ) === 0 || strpos( $value, 'HOMEURL/' ) === 0 ) return preg_replace( '/[^A-Za-z0-9_\-\.\/~%#?&=:]/', '', $value );
			return esc_url_raw( $value );
		case 'html':
			return wp_kses( $value, ugelch_allowed_html() );
		case 'textarea':
			return sanitize_textarea_field( $value );
		case 'color':
			return sanitize_hex_color( $value ) ?: '';
		case 'select':
			return sanitize_key( $value );
		default:
			return sanitize_text_field( $value );
	}
}

function ugelch_rest_save_page( WP_REST_Request $req ) {
	$post_id   = (int) $req->get_param( 'post_id' );
	$fields    = (array) $req->get_param( 'fields' );
	$repeaters = (array) $req->get_param( 'repeaters' );
	$globals   = (array) $req->get_param( 'globals' );

	if ( ( $fields || $repeaters ) && ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) ) {
		return new WP_REST_Response( array( 'success' => false, 'message' => 'No tienes permiso para editar esta página.' ), 403 );
	}

	$known = ugelch_text_fields();
	foreach ( $fields as $key => $value ) {
		$key = sanitize_key( $key );
		if ( ! isset( $known[ $key ] ) ) continue;
		update_post_meta( $post_id, 'ugelch_' . $key, ugelch_sanitize_value( $value, $known[ $key ]['type'] ) );
	}

	$schema = ugelch_repeater_schema();
	foreach ( $repeaters as $rkey => $rows ) {
		$rkey = sanitize_key( $rkey );
		if ( ! isset( $schema[ $rkey ] ) || ! is_array( $rows ) ) continue;
		$clean = array();
		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) ) continue;
			$item = array();
			foreach ( $schema[ $rkey ]['fields'] as $sub => $def ) {
				$item[ $sub ] = ugelch_sanitize_value( isset( $row[ $sub ] ) ? $row[ $sub ] : '', $def[1] );
			}
			if ( implode( '', $item ) !== '' ) $clean[] = $item;
		}
		update_post_meta( $post_id, 'ugelch_rep_' . $rkey, $clean );
	}

	if ( $globals ) {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return new WP_REST_Response( array( 'success' => false, 'message' => 'Solo un administrador puede cambiar los ajustes generales.' ), 403 );
		}
		$gf    = ugelch_globals_fields();
		$saved = get_option( 'ugelch_globals', array() );
		$saved = is_array( $saved ) ? $saved : array();
		foreach ( $globals as $k => $v ) {
			$k = sanitize_key( $k );
			if ( isset( $gf[ $k ] ) ) $saved[ $k ] = ugelch_sanitize_value( $v, $gf[ $k ][1] );
		}
		update_option( 'ugelch_globals', $saved );
	}

	return new WP_REST_Response( array( 'success' => true ), 200 );
}

/* ───────── Panel ───────── */
function ugelch_editor_input( $attr_name, $key, $type, $value, $extra = '' ) {
	$a = $attr_name . '="' . esc_attr( $key ) . '"';
	$show = ugelch_resolve_urls( (string) $value );
	switch ( $type ) {
		case 'textarea':
		case 'html':
			return '<textarea ' . $a . ' rows="3">' . esc_textarea( $value ) . '</textarea>';
		case 'image':
			return '<div class="uge-img"><img src="' . esc_url( $show ) . '" alt=""><input type="hidden" ' . $a . ' value="' . esc_attr( $value ) . '">'
				. '<button type="button" class="uge-btn uge-img-pick">Cambiar imagen</button></div>';
		case 'color':
			return '<input type="color" ' . $a . ' value="' . esc_attr( $value ) . '">';
		case 'select':
			$o = '';
			foreach ( (array) $extra as $k => $label ) $o .= '<option value="' . esc_attr( $k ) . '"' . selected( $value, $k, false ) . '>' . esc_html( $label ) . '</option>';
			return '<select ' . $a . '>' . $o . '</select>';
		case 'url':
			return '<input type="text" inputmode="url" ' . $a . ' value="' . esc_attr( $value ) . '">';
		default:
			return '<input type="text" ' . $a . ' value="' . esc_attr( $value ) . '">';
	}
}

function ugelch_editor_repeater_html( $rkey ) {
	$schema = ugelch_repeater_schema();
	if ( ! isset( $schema[ $rkey ] ) ) return '';
	$s = $schema[ $rkey ];
	$item = function ( $row, $n ) use ( $s ) {
		$h = '<div class="uge-rep-item"><div class="uge-rep-head"><b>' . esc_html( $s['item'] ) . ' <span class="uge-rep-n">' . $n . '</span></b>'
			. '<span><button type="button" class="uge-icon-btn uge-rep-up" aria-label="Subir">↑</button><button type="button" class="uge-icon-btn uge-rep-down" aria-label="Bajar">↓</button><button type="button" class="uge-icon-btn uge-rep-del" aria-label="Quitar">✕</button></span></div>';
		foreach ( $s['fields'] as $sub => $def ) {
			$h .= '<label class="uge-field"><span>' . esc_html( $def[0] ) . '</span>' . ugelch_editor_input( 'data-rep-sub', $sub, $def[1], isset( $row[ $sub ] ) ? $row[ $sub ] : '', isset( $def[2] ) ? $def[2] : '' ) . '</label>';
		}
		return $h . '</div>';
	};
	$rows = ugelch_repeater( $rkey );
	$html = '<div class="uge-rep" data-rep-key="' . esc_attr( $rkey ) . '"><p class="uge-sub">' . esc_html( $s['label'] ) . '</p>';
	if ( ! empty( $s['hint'] ) ) $html .= '<p class="uge-note">' . esc_html( $s['hint'] ) . '</p>';
	$html .= '<div class="uge-rep-list">';
	foreach ( array_values( $rows ) as $i => $row ) $html .= $item( $row, $i + 1 );
	$html .= '</div><template class="uge-rep-tpl">' . $item( array(), 0 ) . '</template>';
	$html .= '<button type="button" class="uge-btn uge-rep-add">+ ' . esc_html( $s['add'] ) . '</button></div>';
	return $html;
}

add_action( 'wp_footer', function () {
	if ( ! ugelch_editor_enabled() ) return;
	$slug = ugelch_current_slug();
	$reg  = ugelch_text_registry();
	$pid  = ugelch_fields_post_id();

	$body = '';
	if ( isset( $reg[ $slug ] ) && $pid ) {
		foreach ( $reg[ $slug ]['sections'] as $sid => $sec ) {
			$inner = '';
			foreach ( $sec['fields'] as $k => $f ) {
				$inner .= '<label class="uge-field"><span>' . esc_html( $f[0] ) . '</span>' . ugelch_editor_input( 'data-field-key', $k, $f[1], ugelch_field( $k ) ) . '</label>';
			}
			if ( ! empty( $sec['repeater'] ) ) $inner .= ugelch_editor_repeater_html( $sec['repeater'] );
			if ( ! empty( $sec['note'] ) ) $inner .= '<p class="uge-note">' . esc_html( $sec['note'] ) . '</p>';
			$body .= '<div class="uge-acc" data-sel="' . esc_attr( isset( $sec['sel'] ) ? $sec['sel'] : '' ) . '"><button type="button" class="uge-acc-head">' . esc_html( $sec['label'] ) . '</button><div class="uge-acc-body">' . $inner . '</div></div>';
		}
	} elseif ( is_singular() ) {
		$what = is_singular( 'post' ) ? 'esta noticia' : 'esta página';
		$body .= '<div class="uge-info"><p>El contenido de ' . $what . ' se edita en el editor de WordPress.</p>';
		if ( current_user_can( 'edit_post', get_queried_object_id() ) ) {
			$body .= '<a class="uge-btn uge-btn-primary" href="' . esc_url( get_edit_post_link( get_queried_object_id(), 'raw' ) ) . '">Editar en WordPress</a>';
		}
		if ( is_page() ) $body .= '<p class="uge-note">Las páginas interiores tienen su contenido en un bloque "HTML personalizado": cambia solo el texto entre las etiquetas y no borres las clases (class="…"), que son las que dan el diseño.</p>';
		$body .= '</div>';
	} else {
		$body .= '<div class="uge-info"><p>Para publicar una noticia: Escritorio → Entradas → Añadir nueva. Se mostrará aquí y en la portada.</p><a class="uge-btn uge-btn-primary" href="' . esc_url( admin_url( 'post-new.php' ) ) . '">Nueva noticia</a></div>';
	}

	if ( current_user_can( 'edit_theme_options' ) ) {
		$g = ugelch_globals();
		$inner = '';
		foreach ( ugelch_globals_fields() as $k => $f ) {
			$inner .= '<label class="uge-field"><span>' . esc_html( $f[0] ) . '</span>' . ugelch_editor_input( 'data-global-key', $k, $f[1], $g[ $k ] ) . '</label>';
		}
		$body .= '<div class="uge-acc uge-acc-global" data-sel="#site-footer"><button type="button" class="uge-acc-head">Ajustes generales (cabecera y pie)</button><div class="uge-acc-body"><p class="uge-note">Se aplican a todas las páginas.</p>' . $inner . '</div></div>';
	}
	?>
	<button type="button" id="uge-fab" aria-controls="uge-panel" aria-expanded="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20h4L19 9l-4-4L4 16z"/><path d="m13.5 6.5 4 4"/></svg><span>Editar página</span></button>
	<aside id="uge-panel" aria-label="Editor de la página" hidden>
		<header class="uge-head">
			<b>Editar página</b>
			<span class="uge-status" role="status" aria-live="polite"></span>
			<button type="button" class="uge-btn uge-btn-primary" id="uge-save" disabled>Guardar</button>
			<button type="button" class="uge-icon-btn" id="uge-close" aria-label="Cerrar">✕</button>
		</header>
		<div class="uge-body"><?php echo $body; // phpcs:ignore — construido y escapado arriba ?></div>
	</aside>
	<?php
}, 50 );
