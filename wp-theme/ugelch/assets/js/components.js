// Comportamiento del menú principal. El marcado de la cabecera y el pie es HTML fijo
// generado con tools/build-layout.js.
(function(){
  const menu = document.querySelector(".menu"), links = document.querySelector(".nav-links");
  const groups = [...document.querySelectorAll(".nav-group")];
  if (!menu || !links) return;

  const setMenu = open => {
    links.classList.toggle("open", open);
    menu.setAttribute("aria-expanded", String(open));
    menu.setAttribute("aria-label", open ? "Cerrar menú" : "Abrir menú");
  };
  const setGroup = (g, open) => {
    g.classList.toggle("show", open);
    g.querySelector("button").setAttribute("aria-expanded", String(open));
  };

  menu.addEventListener("click", () => setMenu(!links.classList.contains("open")));
  groups.forEach(g => g.querySelector("button").addEventListener("click", () => {
    const open = !g.classList.contains("show");
    groups.forEach(o => o !== g && setGroup(o, false));
    setGroup(g, open);
  }));

  // Escape cierra el desplegable o el menú y devuelve el foco a su botón
  document.addEventListener("keydown", e => {
    if (e.key !== "Escape") return;
    const g = groups.find(o => o.classList.contains("show"));
    if (g) { setGroup(g, false); g.querySelector("button").focus(); }
    else if (links.classList.contains("open")) { setMenu(false); menu.focus(); }
  });
  // Un clic fuera cierra los desplegables
  document.addEventListener("click", e => {
    if (!e.target.closest(".nav-group")) groups.forEach(g => setGroup(g, false));
  });
})();
