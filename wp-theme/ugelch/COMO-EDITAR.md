# Cómo editar el contenido (tema UGEL Chincheros)

El sitio se edita de tres formas, según qué quieras cambiar.

## 1. Portada: con el botón "Editar página"

1. Inicia sesión en WordPress y abre la portada del sitio.
2. Abajo a la derecha aparece el botón **Editar página** (solo lo ven los usuarios con permiso de edición).
3. Se abre un panel con una sección por cada parte de la portada. Al abrir una sección, la página va hasta esa parte y la resalta.
4. Cambia lo que necesites y pulsa **Guardar**. La página se recarga con los cambios.

| Sección | Qué se puede cambiar |
|---|---|
| Carrusel · Aviso principal | Etiqueta roja, título (lo que va en *cursiva* sale en rojo), subtítulo, botón y su enlace, ilustración de escritorio y aviso en imagen para móvil |
| Carrusel · Otros avisos | Añadir, quitar u ordenar avisos en imagen. Usa imágenes con proporción 2560×981 (por ejemplo, 1600×614 px) para que se vean completas |
| Comunicados / Oficios | Título, enlace "Ver todos" y la lista (fecha, título y enlace de cada elemento) |
| Redes sociales | Textos y portada de la tarjeta de Facebook |
| Ubicación | Títulos de la tarjeta |
| Video institucional | Código del video de YouTube, título, canal y miniatura |
| Noticias y actividades | Título. Las noticias se muestran solas: son las 3 últimas entradas |
| Accesos rápidos | Título y la lista de accesos (texto, enlace, icono y color) |
| Ajustes generales | Dirección, horario, responsables y enlaces de la cabecera y el pie (Facebook, Workspace, Portal de Transparencia, gob.pe y la búsqueda del mapa). Solo administradores. Se aplican a todo el sitio |

- Solo se guardan los campos que cambias. Lo que nunca has tocado sigue mostrando el texto original del tema.
- En los campos de título puedes usar `<em>…</em>` (rojo en el aviso), `<strong>…</strong>` y `<br>` para partir una línea.
- Para volver al texto original de un campo, borra su valor guardado (Escritorio → la página "Inicio" → Campos personalizados, `ugelch_<campo>`) o pide ayuda a quien administra el sitio.

## 2. Páginas interiores: desde el editor de WordPress

Al activar el tema, cada página interior (Sobre Nosotros, Convocatorias, TUPA, etc.) recibe su contenido en un bloque **HTML personalizado**.

1. Escritorio → **Páginas** → abre la página → **Editar**. También puedes usar el botón "Editar página" → "Editar en WordPress" desde la propia página.
2. Haz clic dentro del bloque HTML y cambia solo el **texto** entre las etiquetas, por ejemplo `<p>Texto</p>`.
3. **No borres** las etiquetas `<…>` ni las clases (`class="…"`): son las que dan el diseño.
4. **Actualizar**. Usa la vista previa antes de publicar.

El tema nunca sobrescribe lo que edites. Para volver al contenido original de una página, vacíala y vuelve a activar el tema (Apariencia → Temas).

El título de la banda azul es el título de la página en WordPress. El menú lateral de cada sección se genera solo.

## 3. Noticias: como entradas del blog

1. Escritorio → **Entradas** → **Añadir nueva**.
2. Escribe el título y el texto con el editor de bloques (párrafos, subtítulos, listas, citas, imágenes).
3. Añade un **extracto** (resumen de 1–2 líneas): sale en las tarjetas y bajo el título.
4. Elige una **imagen destacada** (horizontal, al menos 1200 px de ancho).
5. **Publicar**. La noticia aparece en la portada (las 3 más recientes), en la página **Noticias** y en el mapa del sitio.
