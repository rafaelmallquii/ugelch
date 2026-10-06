(function(){
  const slider = document.querySelector("[data-slider]");
  if (slider) {
    const track = slider.querySelector(".hero-track"), slides = [...track.children], dots = [...slider.querySelectorAll(".hero-dots button")];
    const still = matchMedia("(prefers-reduced-motion: reduce)").matches;
    // En móvil cada diapositiva tiene su altura (el aviso completo a todo el ancho);
    // el carrusel se adapta a la que está a la vista y no avanza solo, para que la
    // página no salte mientras se lee
    const narrow = matchMedia("(max-width: 760px)");
    const fit = () => track.style.height = narrow.matches ? slides[i].offsetHeight + "px" : "";
    let i = 0, timer;
    const go = n => {
      i = (n + slides.length) % slides.length;
      track.style.transform = "translateX(-" + i * 100 + "%)";
      fit();
      slides.forEach((s, k) => s.inert = k !== i);
      dots.forEach((d, k) => d.setAttribute("aria-selected", String(k === i)));
    };
    const play = () => { clearInterval(timer); if (!still && !narrow.matches) timer = setInterval(() => go(i + 1), 7000); };
    addEventListener("resize", fit);
    narrow.addEventListener("change", () => { fit(); play(); });
    slides.forEach(s => s.querySelectorAll("img").forEach(im => im.complete || im.addEventListener("load", fit, {once: true})));
    dots.forEach((d, k) => d.onclick = () => { go(k); play(); });
    slider.addEventListener("mouseenter", () => clearInterval(timer));
    slider.addEventListener("mouseleave", play);
    slider.addEventListener("focusin", () => clearInterval(timer));

    // Arrastre con dedo o ratón: la diapositiva sigue al puntero y al soltar avanza,
    // retrocede o vuelve a su sitio según la distancia y la velocidad del gesto
    let drag = null, moved = false;
    const at = dx => track.style.transform = "translateX(calc(-" + i * 100 + "% + " + dx + "px))";
    track.addEventListener("dragstart", e => e.preventDefault());
    track.addEventListener("pointerdown", e => {
      if (e.button !== 0 || e.target.closest(".hero-dots")) return;
      drag = {x: e.clientX, y: e.clientY, t: e.timeStamp, dx: 0, id: e.pointerId, axis: null};
      moved = false;
      clearInterval(timer);
    });
    track.addEventListener("pointermove", e => {
      if (!drag || e.pointerId !== drag.id) return;
      const dx = e.clientX - drag.x, dy = e.clientY - drag.y;
      // Decide el eje en los primeros píxeles: si es vertical, deja hacer scroll a la página
      if (!drag.axis && Math.hypot(dx, dy) > 6) {
        drag.axis = Math.abs(dx) > Math.abs(dy) ? "x" : "y";
        if (drag.axis === "x") { track.setPointerCapture(e.pointerId); track.classList.add("dragging"); }
      }
      if (drag.axis !== "x") return;
      moved = true;
      drag.dx = dx;
      at(dx);
    });
    const end = e => {
      if (!drag || e.pointerId !== drag.id) return;
      const {dx, t, axis} = drag; drag = null;
      track.classList.remove("dragging");
      if (axis === "x") {
        const w = slider.clientWidth, v = Math.abs(dx) / Math.max(e.timeStamp - t, 1);
        go(Math.abs(dx) > w * .2 || (v > .4 && Math.abs(dx) > 20) ? i + (dx < 0 ? 1 : -1) : i);
      }
      play();
    };
    track.addEventListener("pointerup", end);
    track.addEventListener("pointercancel", end);
    // Un arrastre no debe activar el enlace que hay debajo
    track.addEventListener("click", e => { if (moved) { e.preventDefault(); e.stopPropagation(); moved = false; } }, true);

    // Flechas del teclado sobre los puntos
    dots.forEach((d, k) => d.addEventListener("keydown", e => {
      if (e.key !== "ArrowRight" && e.key !== "ArrowLeft") return;
      e.preventDefault();
      go(i + (e.key === "ArrowRight" ? 1 : -1));
      dots[i].focus();
    }));
    go(0); play();
  }

  document.querySelectorAll("[data-video]").forEach(v => {
    v.querySelector(".video-play").onclick = () => {
      const f = document.createElement("iframe");
      f.src = "https://www.youtube-nocookie.com/embed/" + v.dataset.video + "?autoplay=1&rel=0";
      f.title = "Directromes - Fundación Romero";
      f.allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share";
      f.allowFullscreen = true;
      v.replaceChildren(f);
    };
  });

  // Animaciones de entrada de Elementor: igual que en el sitio en vivo, el elemento
  // aparece con su animación cuando entra en pantalla
  const hidden = document.querySelectorAll(".elementor-invisible[data-settings]");
  const reveal = el => {
    let st = {};
    try { st = JSON.parse(el.dataset.settings); } catch (e) {}
    setTimeout(() => {
      el.classList.remove("elementor-invisible");
      if (st._animation) el.classList.add("animated", st._animation);
    }, +st._animation_delay || 0);
  };
  if (hidden.length) {
    if ("IntersectionObserver" in window) {
      const io = new IntersectionObserver(entries => entries.forEach(e => {
        if (e.isIntersecting) { io.unobserve(e.target); reveal(e.target); }
      }));
      hidden.forEach(el => io.observe(el));
    } else hidden.forEach(reveal);
  }
})();

// Iframes pesados (Google Maps): se cargan solo cuando llegan a la pantalla
(function(){
  const frames = document.querySelectorAll("iframe[data-src]");
  const load = f => { f.src = f.dataset.src; f.removeAttribute("data-src"); };
  if (!("IntersectionObserver" in window)) return frames.forEach(load);
  const io = new IntersectionObserver(entries => entries.forEach(e => {
    if (e.isIntersecting) { io.unobserve(e.target); load(e.target); }
  }), { rootMargin: "200px 0px" });
  frames.forEach(f => io.observe(f));
})();
