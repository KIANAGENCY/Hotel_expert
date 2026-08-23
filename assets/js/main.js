(() => {
  const header = document.getElementById("site-header");
  const menuBtn = document.getElementById("menu-btn");
  const menu = document.getElementById("mobile-menu");
  const iconOpen = document.getElementById("icon-open");
  const iconClose = document.getElementById("icon-close");
  const mobileNav = document.querySelector(".mobile-nav-strip");
  const homeScrollNav =
    window.location.pathname.endsWith("/") ||
    window.location.pathname.endsWith("/index.php");
  const desktopNavLinks = Array.from(
    document.querySelectorAll('nav[aria-label="Principal"] .nav-link')
  );
  const mobileNavLinks = Array.from(
    document.querySelectorAll(".mobile-nav-strip .mobile-nav-link")
  );
  const navSections = Array.from(
    document.querySelectorAll("[data-nav-section]")
  );
  let currentNavSection = "";

  const setCurrentNavSection = (sectionId) => {
    if (!sectionId || sectionId === currentNavSection) return;
    currentNavSection = sectionId;
    const target = `#${sectionId}`;
    [...desktopNavLinks, ...mobileNavLinks].forEach((link) => {
      link.classList.toggle(
        "is-scroll-current",
        link.getAttribute("data-nav-target") === target
      );
    });

    const mobileActive = mobileNavLinks.find(
      (link) => link.getAttribute("data-nav-target") === target
    );
    if (mobileNav && mobileActive && window.innerWidth < 1024) {
      const left =
        mobileActive.offsetLeft -
        (mobileNav.clientWidth - mobileActive.offsetWidth) / 2;
      mobileNav.scrollTo({ left: Math.max(0, left), behavior: "smooth" });
    }
  };

  const onScroll = () => {
    if (header) {
      header.classList.toggle("is-scrolled", window.scrollY > 12);
      header.classList.toggle("is-home-scrollnav", homeScrollNav);
    }
    if (homeScrollNav && navSections.length) {
      const marker = (header?.offsetHeight || 0) + 40;
      let visibleSection = navSections[0];
      navSections.forEach((section) => {
        if (section.getBoundingClientRect().top <= marker) {
          visibleSection = section;
        }
      });
      setCurrentNavSection(visibleSection.id);
    }
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

(() => {
  const STORAGE_KEY = "hotelExpertElahCart";
  const products = window.ELAH_PRODUCTS || {};
  const base = window.ELAH_BASE || "";

  const readCart = () => {
    try {
      const parsed = JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}");
      return parsed && typeof parsed === "object" ? parsed : {};
    } catch {
      return {};
    }
  };

  const writeCart = (cart) => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
    updateCounts(cart);
    syncQuoteField(cart);
  };

  if (document.querySelector("[data-clear-cart]")) {
    localStorage.removeItem(STORAGE_KEY);
  }

  const updateCounts = (cart = readCart()) => {
    const count = Object.values(cart).reduce((sum, qty) => sum + Number(qty || 0), 0);
    document.querySelectorAll("[data-cart-count]").forEach((el) => {
      el.textContent = String(count);
    });
  };

  const money = (value) =>
    new Intl.NumberFormat("es-MX", {
      style: "currency",
      currency: "MXN",
      maximumFractionDigits: 0,
    }).format(value);

  let toastTimer;
  const showToast = (message) => {
    let toast = document.querySelector(".cart-toast");
    if (!toast) {
      toast = document.createElement("div");
      toast.className = "cart-toast";
      toast.setAttribute("role", "status");
      document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.classList.add("is-visible");
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove("is-visible"), 2400);
  };

  const addItem = (slug, quantity = 1) => {
    if (!products[slug]) return;
    const cart = readCart();
    cart[slug] = Math.min(99, Number(cart[slug] || 0) + Math.max(1, Number(quantity || 1)));
    writeCart(cart);
    showToast(`${products[slug].nombre} agregado a tu cotización`);
    renderCart();
  };

  document.addEventListener("click", (event) => {
    const addButton = event.target.closest("[data-cart-add]");
    if (addButton) {
      const sourceId = addButton.getAttribute("data-quantity-source");
      const quantity = sourceId ? document.getElementById(sourceId)?.value : 1;
      addItem(addButton.getAttribute("data-cart-add"), quantity);
      return;
    }

    const plus = event.target.closest("[data-qty-plus]");
    const minus = event.target.closest("[data-qty-minus]");
    if (plus || minus) {
      const input = (plus || minus).parentElement.querySelector("input");
      const delta = plus ? 1 : -1;
      input.value = String(Math.max(1, Math.min(99, Number(input.value || 1) + delta)));
      return;
    }

    const cartAction = event.target.closest("[data-cart-action]");
    if (!cartAction) return;
    const slug = cartAction.getAttribute("data-cart-slug");
    const action = cartAction.getAttribute("data-cart-action");
    const cart = readCart();
    if (action === "remove") {
      delete cart[slug];
    } else {
      cart[slug] = Math.max(1, Math.min(99, Number(cart[slug] || 1) + (action === "plus" ? 1 : -1)));
    }
    writeCart(cart);
    renderCart();
  });

  const cartItems = (cart = readCart()) =>
    Object.entries(cart)
      .filter(([slug, qty]) => products[slug] && Number(qty) > 0)
      .map(([slug, qty]) => ({ ...products[slug], qty: Number(qty) }));

  const syncQuoteField = (cart = readCart()) => {
    const field = document.getElementById("cart-json");
    if (field) field.value = JSON.stringify(cartItems(cart));
  };

  const renderCart = () => {
    const root = document.querySelector("[data-cart-root]");
    if (!root) return;
    const items = cartItems();
    const total = items.reduce((sum, item) => sum + item.precio * item.qty, 0);
    const summary = document.querySelector("[data-cart-summary]");
    const whatsapp = document.querySelector("[data-cart-whatsapp]");
    const layout = document.querySelector("[data-cart-layout]");
    const dependents = document.querySelectorAll("[data-cart-dependent]");
    const isEmpty = items.length === 0;

    layout?.classList.toggle("is-empty", isEmpty);
    dependents.forEach((element) => {
      element.hidden = isEmpty;
    });

    if (isEmpty) {
      root.innerHTML = `
        <div class="cart-empty">
          <p class="font-heading font-extrabold text-2xl text-expert">Tu cotización está vacía</p>
          <p class="mt-2 text-charcoal/60">Agrega paquetes, concentrados, aromas o difusores desde la tienda.</p>
          <a class="btn-primary mt-6" href="${base}/catalogo.php">Explorar la tienda</a>
        </div>`;
      syncQuoteField({});
      return;
    }

    root.innerHTML = items.map((item) => {
      const visual = item.imagen
        ? `<img src="${base}/assets/img/${item.imagen}" alt="" class="h-16 w-16 object-contain">`
        : `<span class="grid place-items-center h-16 w-16 rounded-2xl bg-hielo font-heading font-extrabold text-expert">${item.icono}</span>`;
      return `
        <article class="cart-row">
          ${visual}
          <div>
            <p class="font-heading font-extrabold text-expert">${item.nombre}</p>
            <p class="text-sm text-charcoal/55">${item.presentacion} · ${item.precio_texto} + IVA c/u</p>
          </div>
          <div class="cart-row-actions flex items-center gap-3">
            <div class="quantity-control">
              <button type="button" data-cart-action="minus" data-cart-slug="${item.slug}" aria-label="Reducir">−</button>
              <input type="text" readonly value="${item.qty}" aria-label="Cantidad">
              <button type="button" data-cart-action="plus" data-cart-slug="${item.slug}" aria-label="Aumentar">+</button>
            </div>
            <p class="min-w-[6rem] text-right font-heading font-extrabold text-expert">${money(item.precio * item.qty)}</p>
            <button type="button" class="text-sm text-charcoal/45 hover:text-red-700" data-cart-action="remove" data-cart-slug="${item.slug}">Eliminar</button>
          </div>
        </article>`;
    }).join("");

    if (summary) {
      summary.innerHTML = `
        <div class="flex justify-between text-charcoal/60"><span>Productos (${items.reduce((s, i) => s + i.qty, 0)})</span><span>${money(total)}</span></div>
        <div class="mt-4 pt-4 border-t border-expert/10 flex justify-between items-end">
          <span class="font-heading font-bold text-expert">Subtotal</span>
          <strong class="price-display !text-3xl">${money(total)}</strong>
        </div>
        <p class="mt-2 text-xs text-charcoal/45 text-right">Más IVA. Entrega por confirmar.</p>`;
    }

    if (whatsapp) {
      const lines = items.map((item) => `• ${item.qty} × ${item.nombre} (${money(item.precio * item.qty)})`);
      const message = `Hola Hotel Expert, quiero cotizar el Sistema ELAH:\n\n${lines.join("\n")}\n\nSubtotal: ${money(total)} + IVA.`;
      whatsapp.href = `https://wa.me/528112497481?text=${encodeURIComponent(message)}`;
    }
    syncQuoteField(readCart());
  };

  document.querySelector("[data-quote-form]")?.addEventListener("submit", (event) => {
    if (!cartItems().length) {
      event.preventDefault();
      showToast("Agrega al menos un producto a tu cotización");
    }
  });

  updateCounts();
  renderCart();
})();
