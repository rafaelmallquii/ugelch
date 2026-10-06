// Escribe la cabecera y el pie de tools/layout.js en todas las páginas del sitio.
// Uso: node tools/build-layout.js
const fs = require("fs"), path = require("path");
const { header, footer } = require("./layout");
const ROOT = path.join(__dirname, "..");

const files = ["index.html", "paginas.html", ...fs.readdirSync(ROOT)
  .filter(d => fs.existsSync(path.join(ROOT, d, "index.html")))
  .map(d => `${d}/index.html`)];

for (const f of files) {
  const file = path.join(ROOT, f);
  let html = fs.readFileSync(file, "utf8");
  const base = f.includes("/") ? "../" : "";
  const slug = f === "index.html" ? "home" : f === "paginas.html" ? "paginas" : f.split("/")[0];
  html = html
    .replace(/<header id="site-header">[\s\S]*?<\/header>/, () => `<header id="site-header">${header(base, slug)}</header>`)
    .replace(/<footer id="site-footer">[\s\S]*?<\/footer>/, () => `<footer id="site-footer">${footer(base)}</footer>`)
    // Los scripts no bloquean el pintado
    .replace(/<script src="([^"]+)"><\/script>/g, '<script src="$1" defer></script>');
  fs.writeFileSync(file, html);
}
console.log(`Cabecera y pie escritos en ${files.length} páginas`);
