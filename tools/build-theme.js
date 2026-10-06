// Genera las partes del tema de WordPress (wp-theme/ugelch) a partir del sitio estático:
// el HTML de cada página interior, su CSS de Elementor, los datos de las páginas
// (título, descripción, menú lateral) y una copia de los assets.
// Uso: node tools/build-theme.js
const fs = require("fs"), path = require("path");
const ROOT = path.join(__dirname, "..");
const THEME = path.join(ROOT, "wp-theme", "ugelch");

// Las tres "Noticia 1/2/3" del sitio original son texto de relleno: en el tema las
// sustituyen entradas reales del blog
const SKIP = new Set(["noticia1", "noticia-2", "noticia-3"]);
const slugs = fs.readdirSync(ROOT)
  .filter(d => fs.existsSync(path.join(ROOT, d, "index.html")) && !SKIP.has(d))
  .sort();

const unesc = s => s.replace(/&amp;/g, "&").replace(/&quot;/g, '"').replace(/&#39;/g, "'").replace(/&lt;/g, "<").replace(/&gt;/g, ">");
const php = v => "'" + String(v).replace(/\\/g, "\\\\").replace(/'/g, "\\'") + "'";

// Rutas del sitio estático (relativas a una página interior) → marcadores del tema
function toTheme(html) {
  return html
    .replace(/\.\.\/assets\//g, "THEMEURI/assets/")
    .replace(/\.\.\/index\.html(#[^"']*)?/g, (m, h) => "HOMEURL/" + (h || ""))
    .replace(/\.\.\/paginas\.html/g, "HOMEURL/mapa-del-sitio/")
    .replace(/\.\.\/([a-z0-9-]+)\/index\.html(#[^"']*)?/g, (m, s, h) => `HOMEURL/${s}/` + (h || ""));
}

fs.mkdirSync(path.join(THEME, "template-parts", "pages"), { recursive: true });
const pages = {};
const navs = {};
for (const slug of slugs) {
  const html = fs.readFileSync(path.join(ROOT, slug, "index.html"), "utf8");
  const title = unesc(html.match(/<section class="page-title">[\s\S]*?<h1>([\s\S]*?)<\/h1>/)[1]);
  const seo = unesc(html.match(/<title>([\s\S]*?)<\/title>/)[1]).trim();
  const desc = unesc((html.match(/<meta name="description" content="([^"]*)"/) || [, ""])[1]);
  const article = html.match(/<article class="content el elementor-kit-1316">([\s\S]*)<\/article><\/main>/);
  const css = (html.match(/<style id="page-css">([\s\S]*?)<\/style>/) || [, ""])[1];
  fs.writeFileSync(path.join(THEME, "template-parts", "pages", `${slug}.html`), toTheme(article ? article[1] : ""));
  fs.writeFileSync(path.join(THEME, "template-parts", "pages", `${slug}.css`), toTheme(css).replace(/url\(\.\.\//g, "url(THEMEURI/"));

  // Menú lateral de la sección (mismo grupo = mismo menú)
  const aside = html.match(/<aside class="side-nav">([\s\S]*?)<\/aside>/);
  let nav = "";
  if (aside) {
    const items = [...aside[1].matchAll(/<a href="\.\.\/([a-z0-9-]+)\/index\.html"[^>]*>([^<]*)<\/a>/g)].map(m => [m[1], unesc(m[2])]);
    nav = items.map(i => i[0]).join(",");
    navs[nav] = items;
  }
  pages[slug] = { title, seo, desc, nav };
}

// inc/pages-data.php: datos generados (no editar a mano)
let out = "<?php\n// Generado por tools/build-theme.js a partir del sitio estático. No editar a mano.\n";
out += "if ( ! defined( 'ABSPATH' ) ) exit;\n\n";
out += "function ugelch_static_pages() {\n\treturn array(\n";
for (const [s, p] of Object.entries(pages)) {
  out += `\t\t${php(s)} => array( 'title' => ${php(p.title)}, 'seo' => ${php(p.seo)}, 'desc' => ${php(p.desc)}, 'nav' => ${php(p.nav)} ),\n`;
}
out += "\t);\n}\n\n";
out += "// Menús laterales de sección: clave = lista de slugs, valor = [slug, etiqueta]\n";
out += "function ugelch_side_navs() {\n\treturn array(\n";
for (const [k, items] of Object.entries(navs)) {
  out += `\t\t${php(k)} => array( ` + items.map(i => `array( ${php(i[0])}, ${php(i[1])} )`).join(", ") + " ),\n";
}
out += "\t);\n}\n";
fs.mkdirSync(path.join(THEME, "inc"), { recursive: true });
fs.writeFileSync(path.join(THEME, "inc", "pages-data.php"), out);

// Copia de assets (el tema es autónomo)
function copyDir(src, dst) {
  fs.mkdirSync(dst, { recursive: true });
  for (const f of fs.readdirSync(src)) {
    const s = path.join(src, f), d = path.join(dst, f);
    if (fs.statSync(s).isDirectory()) copyDir(s, d); else fs.copyFileSync(s, d);
  }
}
for (const dir of ["css", "js", "img", "media", "fonts", "vendor"]) {
  copyDir(path.join(ROOT, "assets", dir), path.join(THEME, "assets", dir));
}
console.log(`Tema: ${slugs.length} páginas interiores, ${Object.keys(navs).length} menús laterales, assets copiados`);
