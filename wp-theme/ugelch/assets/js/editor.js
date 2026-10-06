/* Editor de la página: panel lateral con acordeones, imágenes de la mediateca, listas
   y guardado por REST de solo lo que ha cambiado. */
(function () {
  "use strict";
  // El panel se imprime en el pie después de este script: se espera al DOM completo
  if (document.readyState === "loading") return document.addEventListener("DOMContentLoaded", init);
  init();
  function init() {
  const cfg = window.ugelchEditor;
  const fab = document.getElementById("uge-fab"), panel = document.getElementById("uge-panel");
  if (!cfg || !fab || !panel) return;
  const save = document.getElementById("uge-save"), status = panel.querySelector(".uge-status");

  /* Abrir y cerrar */
  const open = v => {
    panel.hidden = !v;
    fab.setAttribute("aria-expanded", String(v));
    document.documentElement.classList.toggle("uge-open", v);
    if (v) panel.querySelector(".uge-acc-head, a, button")?.focus(); else { clearHl(); fab.focus(); }
  };
  fab.addEventListener("click", () => open(panel.hidden));
  document.getElementById("uge-close").addEventListener("click", () => open(false));
  document.addEventListener("keydown", e => { if (e.key === "Escape" && !panel.hidden && !document.querySelector(".media-modal")) open(false); });

  /* Acordeones: al abrir uno, la página va a esa sección y la resalta */
  const clearHl = () => document.querySelectorAll(".uge-hl").forEach(el => el.classList.remove("uge-hl"));
  panel.querySelectorAll(".uge-acc-head").forEach(h => h.addEventListener("click", () => {
    const acc = h.closest(".uge-acc"), was = acc.classList.contains("open");
    panel.querySelectorAll(".uge-acc.open").forEach(a => a.classList.remove("open"));
    clearHl();
    if (was) return;
    acc.classList.add("open");
    let target = null;
    try { target = acc.dataset.sel ? document.querySelector(acc.dataset.sel) : null; } catch (e) {}
    if (target) { target.classList.add("uge-hl"); target.scrollIntoView({ behavior: "smooth", block: "center" }); }
  }));

  /* Marca de cambios */
  const markDirty = () => { save.disabled = false; status.textContent = "Cambios sin guardar"; };
  const snapshot = el => { el.dataset.orig = el.value; };
  panel.querySelectorAll("[data-field-key],[data-global-key]").forEach(snapshot);
  panel.addEventListener("input", markDirty);
  panel.addEventListener("change", markDirty);

  /* Imágenes de la mediateca */
  panel.addEventListener("click", e => {
    const btn = e.target.closest(".uge-img-pick");
    if (!btn || !window.wp || !wp.media) return;
    const box = btn.closest(".uge-img"), input = box.querySelector("input"), img = box.querySelector("img");
    const frame = wp.media({ title: "Elegir imagen", button: { text: "Usar esta imagen" }, multiple: false, library: { type: "image" } });
    frame.on("select", () => {
      const att = frame.state().get("selection").first().toJSON();
      input.value = att.url; img.src = att.url; markDirty();
    });
    frame.open();
  });

  /* Listas: añadir, quitar y ordenar */
  const renumber = list => list.querySelectorAll(":scope > .uge-rep-item").forEach((it, i) => { it.querySelector(".uge-rep-n").textContent = i + 1; });
  panel.addEventListener("click", e => {
    const rep = e.target.closest(".uge-rep");
    if (!rep) return;
    const list = rep.querySelector(".uge-rep-list"), item = e.target.closest(".uge-rep-item");
    if (e.target.closest(".uge-rep-add")) {
      const node = rep.querySelector(".uge-rep-tpl").content.firstElementChild.cloneNode(true);
      list.appendChild(node); renumber(list); markDirty();
      node.querySelector("input,textarea,select")?.focus();
    } else if (item && e.target.closest(".uge-rep-del")) {
      if (confirm("¿Quitar este elemento de la lista?")) { item.remove(); renumber(list); markDirty(); }
    } else if (item && e.target.closest(".uge-rep-up") && item.previousElementSibling) {
      list.insertBefore(item, item.previousElementSibling); renumber(list); markDirty();
    } else if (item && e.target.closest(".uge-rep-down") && item.nextElementSibling) {
      list.insertBefore(item.nextElementSibling, item); renumber(list); markDirty();
    }
  });

  /* Guardar: solo campos cambiados; las listas tocadas se envían completas */
  save.addEventListener("click", async () => {
    const fields = {}, globals = {}, repeaters = {};
    panel.querySelectorAll("[data-field-key]").forEach(el => { if (el.value !== el.dataset.orig) fields[el.dataset.fieldKey] = el.value; });
    panel.querySelectorAll("[data-global-key]").forEach(el => { if (el.value !== el.dataset.orig) globals[el.dataset.globalKey] = el.value; });
    panel.querySelectorAll(".uge-rep").forEach(rep => {
      const rows = [...rep.querySelectorAll(".uge-rep-list > .uge-rep-item")].map(it => {
        const row = {}; it.querySelectorAll("[data-rep-sub]").forEach(el => { row[el.dataset.repSub] = el.value; }); return row;
      });
      const before = rep.dataset.orig, now = JSON.stringify(rows);
      if (before === undefined) rep.dataset.orig = now; // primera lectura
      else if (before !== now) repeaters[rep.dataset.repKey] = rows;
    });
    save.disabled = true; status.textContent = "Guardando…";
    const body = JSON.stringify({ post_id: cfg.postId, fields, globals, repeaters });
    const post = url => fetch(url, { method: "POST", credentials: "same-origin", headers: { "Content-Type": "application/json", "X-WP-Nonce": cfg.nonce }, body });
    try {
      let res = await post(cfg.restUrl);
      if (!res.ok && res.status === 404) res = await post(cfg.restUrlAlt);
      const data = await res.json().catch(() => ({}));
      if (!res.ok || !data.success) throw new Error(data.message || "No se pudo guardar (" + res.status + ")");
      status.textContent = "Guardado. Recargando…";
      setTimeout(() => location.reload(), 500);
    } catch (err) {
      status.textContent = err.message; save.disabled = false;
    }
  });
  // Estado inicial de las listas para detectar cambios
  panel.querySelectorAll(".uge-rep").forEach(rep => {
    rep.dataset.orig = JSON.stringify([...rep.querySelectorAll(".uge-rep-list > .uge-rep-item")].map(it => {
      const row = {}; it.querySelectorAll("[data-rep-sub]").forEach(el => { row[el.dataset.repSub] = el.value; }); return row;
    }));
  });
  }
})();
