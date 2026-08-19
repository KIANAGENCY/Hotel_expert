(() => {
  const header = document.getElementById("site-header");
  const menuBtn = document.getElementById("menu-btn");
  const menu = document.getElementById("mobile-menu");
  const iconOpen = document.getElementById("icon-open");
  const iconClose = document.getElementById("icon-close");

  const onScroll = () => {
    if (!header) return;
    header.classList.toggle("is-scrolled", window.scrollY > 12);
  };
  onScroll();
  window.addEventListener("scroll", onScroll, { passive: true });

  if (menuBtn && menu) {
    menuBtn.addEventListener("click", () => {
      const open = menu.classList.toggle("hidden") === false;
      menuBtn.setAttribute("aria-expanded", String(open));
      iconOpen?.classList.toggle("hidden", open);
      iconClose?.classList.toggle("hidden", !open);
    });
  }

  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-in");
          io.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.16 }
  );
  document.querySelectorAll(".io-reveal").forEach((el) => io.observe(el));

  document.querySelectorAll("[data-count]").forEach((el) => {
    const target = Number(el.getAttribute("data-count"));
    const suffix = el.getAttribute("data-suffix") || "";
    const once = new IntersectionObserver((entries) => {
      if (!entries[0].isIntersecting) return;
      once.disconnect();
      const start = performance.now();
      const dur = 1100;
      const tick = (now) => {
        const p = Math.min(1, (now - start) / dur);
        const eased = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.round(target * eased) + suffix;
        if (p < 1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
    });
    once.observe(el);
  });

  const water = document.getElementById("dil-water");
  const conc = document.getElementById("dil-conc");
  const mode = document.getElementById("dil-mode");
  const out = document.getElementById("dil-out");

  const renderDilution = () => {
    if (!out) return;
    const m = mode?.value || "20l";
    if (m === "20l") {
      out.innerHTML =
        "<strong>Porrón 20 L:</strong> vierte primero <em>18 L de agua</em> y después <em>2 L de concentrado</em> (1 envase completo).";
      return;
    }
    if (m === "1l") {
      out.innerHTML =
        "<strong>Atomizador 1 L:</strong> vierte primero <em>900 ml de agua</em> y después <em>100 ml de concentrado</em>.";
      return;
    }
    const liters = Number(water?.value || 18);
    const c = +(liters * (2 / 18)).toFixed(2);
    if (conc) conc.value = String(c);
    out.innerHTML = `<strong>Mezcla libre:</strong> ${liters} L de agua + <em>${c} L de concentrado</em>. Siempre agua primero.`;
  };
  mode?.addEventListener("change", renderDilution);
  water?.addEventListener("input", renderDilution);
  renderDilution();
})();
