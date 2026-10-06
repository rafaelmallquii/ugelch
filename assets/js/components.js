(function(){
  const b = document.body.dataset.base || "";
  const home = document.body.dataset.page === "home";
  const icon = (d, cls) => '<svg class="' + (cls || "ic") + '" viewBox="0 0 24 24" aria-hidden="true">' + d + '</svg>';
  const I = {
    chev: '<path d="m6 9 6 6 6-6"/>',
    search: '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
    building: '<path d="M4 21V5l8-3 8 3v16"/><path d="M2 21h20M9 21v-4h6v4M8 8h2M14 8h2M8 12h2M14 12h2"/>',
    pin: '<path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z"/><circle cx="12" cy="10" r="2.5"/>',
    clock: '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    book: '<path d="M2 5h7a3 3 0 0 1 3 3v12a2 2 0 0 0-2-2H2zM22 5h-7a3 3 0 0 0-3 3v12a2 2 0 0 1 2-2h8z"/>'
  };
  const fb = '<svg class="fb" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="12" fill="currentColor"/><path fill="#0b55c9" d="M13.4 19.5v-6.2h2.1l.3-2.4h-2.4V9.4c0-.7.2-1.2 1.2-1.2h1.3V6.1a17 17 0 0 0-1.9-.1c-1.9 0-3.2 1.1-3.2 3.3v1.7H8.7v2.4h2.1v6.2z"/></svg>';
  const groups = [
    {n: "INSTITUCIONAL", i: [["Sobre Nosotros","sobre-nosotros"],["Directorio y Declaración Jurada","directorio-y-declaracion-jurada"],["Convenios","convenios"],["Marco Legal","marco-legal"],["Jurisdicción","jurisdiccion"],["Organigrama","organigrama"],["Agenda Institucional","agenda-institucional"]]},
    {n: "ÁREAS", i: [["Dirección","direccion"],["Órgano de Control","organo-de-control"],["Órganos de Línea","organo-de-linea"],["Órganos de Asesoramiento","organos-de-asesoramiento"],["Órganos de Apoyo","organos-de-apoyo"]]},
    {n: "GESTIÓN", i: [["Documentos de Interés","documentos-de-interes"],["Convocatorias","convocatorias"],["Planes","planes"],["Convenios","convenios"],["Actas","actas"]]},
    {n: "SERVICIOS", i: [["Consulta tu Expediente","consulta-tu-expediente"],["Comunidad Educativa","comunidad-educativa"],["TUPA","tupa"],["Preguntas Frecuentes","preguntas-frecuentes"],["Chincheros Lee","chincheros-lee"]]}
  ];
  const drop = g => '<div class="nav-group"><button type="button" aria-expanded="false">' + g.n + icon(I.chev, "chev") + '</button><div class="dropdown">' + g.i.map(x => '<a href="' + b + x[1] + '/index.html">' + x[0] + '</a>').join("") + '</div></div>';

  document.querySelector("#site-header").innerHTML =
    '<a class="skip" href="#contenido">Ir al contenido</a>' +
    '<div class="topbar"><div class="wrap"><img class="gov" src="' + b + 'assets/img/brand-strip.jpeg" alt="Perú – Ministerio de Educación, DRE Apurímac y UGEL Chincheros" width="560" height="59">' +
    '<a class="intranet" href="' + b + 'paginas.html">' + icon(I.building) + 'Intranet</a></div></div>' +
    '<nav class="mainnav"><div class="wrap">' +
      '<a class="logo" href="' + b + 'index.html"><span class="logo-badge"><img src="' + b + 'assets/img/logo-ugel.png" alt="" width="56" height="56"></span><span><b>UGEL Chincheros</b><small>Unidad de Gestión Educativa Local</small></span></a>' +
      '<button class="menu" type="button" aria-label="Abrir menú" aria-expanded="false"><span></span><span></span><span></span></button>' +
      '<div class="nav-links">' +
        '<a href="' + b + 'index.html"' + (home ? ' class="active" aria-current="page"' : '') + '>INICIO</a>' +
        groups.map(drop).join("") +
        '<a href="https://www.gob.pe/ugelchincheros">GOB.PE</a>' +
        '<a class="trans" href="https://www.transparencia.gob.pe/enlaces/pte_transparencia_enlaces.aspx?id_entidad=15333">' + icon(I.search) + '<span>Portal de<br>Transparencia</span></a>' +
        '<a class="fb-link" href="https://www.facebook.com/ugel.chinncheros">' + fb + 'Facebook</a>' +
      '</div></div></nav>';

  document.querySelector("#site-footer").innerHTML =
    '<div class="foot-main"><div class="wrap">' +
      '<div class="foot-brand"><span class="logo-badge"><img src="' + b + 'assets/img/logo-ugel.png" alt="" width="64" height="64"></span><span><b>UGEL Chincheros</b><small>Unidad de Gestión Educativa Local</small></span></div>' +
      '<div class="foot-info"><p>' + icon(I.pin) + '<span><b>Unidad de Gestión Educativa Local Chincheros - UGELCH</b>Pasaje Mirador S/N, Chincheros - Apurímac, Perú</span></p>' +
      '<p>' + icon(I.clock) + '<span><b class="inline">Horario de atención:</b> Lunes a viernes de 8:30 a. m. a 4:30 p. m.</span></p></div>' +
      '<div class="foot-resp"><b>Responsables:</b><p><b class="inline">Portal de Transparencia:</b> WILBER SALCEDO RAMIREZ</p><p><b class="inline">Acceso a la información:</b> CESAR BELISARIO GUTIERREZ TOLEDO</p></div>' +
    '</div></div>' +
    '<div class="foot-bar"><div class="wrap">' +
      '<img class="foot-gov" src="' + b + 'assets/img/gob-minedu.jpg" alt="Perú – Ministerio de Educación" width="194" height="42">' +
      '<ul><li><a href="' + b + 'paginas.html">Mapa del sitio</a></li><li><a href="' + b + 'tupa/index.html">TUPA</a></li><li><a href="' + b + 'preguntas-frecuentes/index.html">Preguntas frecuentes</a></li>' +
      '<li><a href="https://reclamos.servicios.gob.pe/">' + icon(I.book) + 'Libro de reclamaciones</a></li></ul>' +
      '<small>Copyright © ' + new Date().getFullYear() + ' | UGEL Chincheros</small>' +
    '</div></div>';

  const menu = document.querySelector(".menu"), links = document.querySelector(".nav-links");
  menu.onclick = () => { const open = links.classList.toggle("open"); menu.setAttribute("aria-expanded", String(open)); };
  document.querySelectorAll(".nav-group>button").forEach(x => x.onclick = () => {
    const open = x.parentElement.classList.toggle("show"); x.setAttribute("aria-expanded", String(open));
  });
})();
