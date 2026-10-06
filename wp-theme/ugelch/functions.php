<?php
/**
 * Tema UGEL Chincheros.
 *
 * Mismo sistema de edición que el tema Grenvíos:
 * - Las plantillas HTML de template-parts/ llevan marcas {{clave}} (texto/imagen) y
 *   {{REP:clave}} (listas). El valor sale del post-meta "ugelch_<clave>" de la página o,
 *   si nunca se editó, del valor por defecto del registro (inc/fields.php).
 * - Un panel en la propia página (solo para quien puede editar) guarda los cambios por
 *   REST (inc/editor.php).
 * - Las páginas interiores se vuelcan al activar el tema en un bloque "HTML
 *   personalizado" y se editan desde WordPress; lo editado nunca se sobrescribe.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'UGELCH_VER', '1.0.0' );
define( 'UGELCH_PAGES_V', '1' ); // súbelo al añadir páginas al registro para crearlas sin reactivar el tema

require_once __DIR__ . '/inc/pages-data.php';
require_once __DIR__ . '/inc/icons.php';
require_once __DIR__ . '/inc/core.php';
require_once __DIR__ . '/inc/fields.php';
require_once __DIR__ . '/inc/layout.php';
require_once __DIR__ . '/inc/blog.php';
require_once __DIR__ . '/inc/seo.php';
require_once __DIR__ . '/inc/editor.php';
require_once __DIR__ . '/inc/setup-content.php';
