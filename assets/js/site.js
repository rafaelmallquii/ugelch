(function(){
  const slider = document.querySelector("[data-slider]");
  if (slider) {
    const track = slider.querySelector(".hero-track"), slides = [...track.children], dots = [...slider.querySelectorAll(".hero-dots button")];
    const still = matchMedia("(prefers-reduced-motion: reduce)").matches;
    let i = 0, timer;
    const go = n => {
      i = (n + slides.length) % slides.length;
      track.style.transform = "translateX(-" + i * 100 + "%)";
      slides.forEach((s, k) => s.inert = k !== i);
      dots.forEach((d, k) => d.setAttribute("aria-selected", String(k === i)));
    };
    const play = () => { if (!still) { clearInterval(timer); timer = setInterval(() => go(i + 1), 7000); } };
    dots.forEach((d, k) => d.onclick = () => { go(k); play(); });
    slider.addEventListener("mouseenter", () => clearInterval(timer));
    slider.addEventListener("mouseleave", play);
    slider.addEventListener("focusin", () => clearInterval(timer));
    let x0 = null;
    slider.addEventListener("touchstart", e => x0 = e.touches[0].clientX, {passive: true});
    slider.addEventListener("touchend", e => {
      if (x0 === null) return;
      const dx = e.changedTouches[0].clientX - x0; x0 = null;
      if (Math.abs(dx) > 40) { go(i + (dx < 0 ? 1 : -1)); play(); }
    });
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
