# Tema de WordPress UGEL Chincheros

`wp-theme/ugelch/` es el portal convertido en tema de WordPress. Usa el mismo sistema de edición que el tema Grenvíos. La guía para quienes editan el contenido está en [`ugelch/COMO-EDITAR.md`](ugelch/COMO-EDITAR.md).

## Instalar

1. Comprime la carpeta del tema:
   ```bash
   cd wp-theme && zip -r ugelch.zip ugelch
   ```
2. WordPress → Apariencia → Temas → Añadir nuevo → Subir tema → `ugelch.zip` → **Activar**.
3. Ajustes → Generales → **Idioma del sitio: Español de Perú**.

Al activar el tema:
- **Páginas:** crea Inicio, Noticias, Mapa del sitio y las 34 páginas interiores, cada una con su contenido en un bloque "HTML personalizado".
- **Ajustes:** fija la portada y la página de noticias, y activa los enlaces `/%postname%/` si no había ninguna estructura.
- **Noticias:** crea 3 noticias reales de la UGEL Chincheros, tomadas de sus notas en gob.pe, con su imagen destacada.
- **Entrada de ejemplo:** si "Hola mundo" no se ha editado nunca, la mueve a la papelera.

No duplica nada si se reactiva, y nunca sobrescribe páginas ya editadas.

## Cómo está hecho (igual que Grenvíos)

| Pieza | Archivo |
|---|---|
| Plantilla de la portada con campos `{{clave}}` y listas `{{REP:clave}}` | `template-parts/content-home.html` |
| Registro de campos (secciones, tipos, valores por defecto) y listas | `inc/fields.php` |
| Lectura de valores (post-meta `ugelch_<clave>`, o el valor por defecto si nunca se guardó) y sustitución de campos | `inc/core.php` |
| Panel "Editar página" y endpoint REST `ugelch/v1/save-page` (nonce, permisos y saneamiento por tipo) | `inc/editor.php`, `assets/js/editor.js`, `assets/css/editor.css` |
| Ajustes generales (opción `ugelch_globals`) | `inc/core.php` |
| Cabecera, pie, banda de título, menú lateral y mapa del sitio | `inc/layout.php` |
| Creación de páginas y noticias al activar | `inc/setup-content.php`, `inc/posts-seed.php` |
| Noticias: listado, detalle y tarjetas | `index.php`, `single.php`, `inc/blog.php`, `assets/css/blog.css` |
| SEO: títulos, descripción, Open Graph y schema `NewsArticle` | `inc/seo.php` |

## Mantener el tema sincronizado con el sitio estático

Las páginas interiores (`template-parts/pages/*.html` y `*.css`), los datos de las páginas (`inc/pages-data.php`) y los assets se generan a partir del sitio estático de la raíz del repositorio:

```bash
node tools/build-theme.js
```

Ejecútalo después de cambiar el sitio estático (contenido de páginas, `assets/css/site.css`, `assets/js/*`, imágenes) y haz commit también de los cambios en `wp-theme/`. La plantilla de la portada (`content-home.html`) y la cabecera y el pie (`inc/layout.php`) se mantienen a mano.

Si una página interior ya está publicada en WordPress, su contenido vive en la base de datos: el generador solo afecta a las instalaciones nuevas, o a las páginas que se vacíen y se vuelvan a crear.
