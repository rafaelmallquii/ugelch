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
})();
