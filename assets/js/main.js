(() => {
  const header = document.getElementById("site-header");
  const menuBtn = document.getElementById("menu-btn");
  const menu = document.getElementById("mobile-menu");
  const iconOpen = document.getElementById("icon-open");
  const iconClose = document.getElementById("icon-close");

  const setMenuOpen = (open) => {
    if (!menuBtn || !menu) return;
    menuBtn.setAttribute("aria-expanded", String(open));
    menuBtn.setAttribute("aria-label", open ? "Cerrar menú" : "Abrir menú");
    menu.classList.toggle("hidden", !open);
    menu.hidden = !open;
    iconOpen?.classList.toggle("hidden", open);
    iconClose?.classList.toggle("hidden", !open);
    document.body.classList.toggle("menu-open", open);
  };

  menuBtn?.addEventListener("click", () => {
    setMenuOpen(menu?.hidden !== false);
  });

  menu?.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => setMenuOpen(false));
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") setMenuOpen(false);
  });

  window.matchMedia("(min-width: 1280px)").addEventListener("change", (event) => {
    if (event.matches) setMenuOpen(false);
  });

  const onScroll = () => {
    if (header) {
      header.classList.toggle("is-scrolled", window.scrollY > 12);
    }
  };
  onScroll();
  window.addEventListener("scroll", onScroll, { passive: true });

  document.querySelectorAll("[data-password-toggle]").forEach((button) => {
    const input = document.getElementById(button.getAttribute("aria-controls"));
    if (!input) return;
    button.addEventListener("click", () => {
      const showPassword = input.type === "password";
      input.type = showPassword ? "text" : "password";
      button.setAttribute("aria-pressed", String(showPassword));
      button.setAttribute("aria-label", showPassword ? "Ocultar contraseña" : "Mostrar contraseña");
    });
  });

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

  const portalRoot = document.querySelector(
    ".account-dashboard, .account-auth-page, .account-order-page, .account-single-page"
  );
  if (portalRoot) {
    const portalTextSelector = [
      ".eyebrow",
      "h1",
      "h2",
      "h3",
      ".account-lead",
      ".account-order-date",
      ".account-muted",
      ".account-field-help",
      ".account-profile-intro > p",
      ".account-auth-story > p",
      ".account-auth-story li",
      ".account-quick-label",
      ".account-detail-link",
      ".account-auth-switch",
      ".account-back-link",
      ".account-shipping-grid > div > span",
      ".account-shipping-grid > div > strong",
      ".account-dashboard-summary span",
      ".account-dashboard-summary small",
      ".account-dashboard-hero > div > p:last-of-type",
      ".account-order-header > div > p:last-of-type",
      ".account-quick-links a",
      ".account-portal-cta h2",
      ".account-empty > h2",
      ".account-empty > p:not(.account-alert)",
      ".account-order-id",
    ].join(", ");

    const markPortalText = (container) => {
      container.querySelectorAll(portalTextSelector).forEach((element, index) => {
        if (element.closest(".account-form")) return;
        element.classList.add("portal-text");
        element.style.setProperty("--portal-delay", `${(index % 6) * 70}ms`);
      });
    };

    const revealPortalText = (container) => {
      container.querySelectorAll(".portal-text:not(.is-visible)").forEach((element) => {
        element.classList.add("is-visible");
      });
    };

    const portalObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          const target = entry.target;
          target.classList.add("is-visible");
          revealPortalText(target);
          portalObserver.unobserve(target);
        });
      },
      { threshold: 0.14, rootMargin: "0px 0px -6%" }
    );

    portalRoot.querySelectorAll(".account-dashboard-hero, .account-order-header, .account-auth-story").forEach((hero) => {
      hero.classList.add("portal-hero");
      markPortalText(hero);
      requestAnimationFrame(() => {
        requestAnimationFrame(() => revealPortalText(hero));
      });
    });

    portalRoot.querySelectorAll(
      ".account-panel, .account-empty, .account-current-order, .account-portal-cta, .account-operations-grid > *, .account-dashboard-shell > .account-alert, .account-auth-card, .account-single-card, .account-order-row, .order-timeline-step"
    ).forEach((panel, index) => {
      panel.classList.add("portal-panel");
      panel.style.setProperty("--portal-panel-delay", `${Math.min(index % 4, 3) * 90}ms`);
      markPortalText(panel);
      portalObserver.observe(panel);
    });

    const summary = portalRoot.querySelector(".account-dashboard-summary");
    if (summary) {
      summary.classList.add("portal-panel", "portal-panel-summary");
      markPortalText(summary);
      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          summary.classList.add("is-visible");
          revealPortalText(summary);
        });
      });
    }
  }

  const scrollFlow = document.querySelector("[data-scroll-flow]");
  if (scrollFlow) {
    document.documentElement.classList.add("has-scroll-flow");

    const animatedText = scrollFlow.querySelectorAll(
      ".eyebrow, h1, h2, h3, .elah-equation, .section-lead, .statement, .feature-statement"
    );
    const textObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          entry.target.classList.add("is-text-visible");
          textObserver.unobserve(entry.target);
        });
      },
      { threshold: 0.18, rootMargin: "0px 0px -8%" }
    );

    animatedText.forEach((element, index) => {
      element.classList.add("text-reveal");
      element.style.setProperty("--text-delay", `${Math.min(index % 4, 3) * 70}ms`);
      textObserver.observe(element);
    });

    const navLinks = document.querySelectorAll("[data-nav-key]");
    const setActiveNav = (key) => {
      navLinks.forEach((link) => {
        const active = link.dataset.navKey === key;
        link.classList.toggle("is-context-active", active);
        if (active) link.setAttribute("aria-current", "location");
        else if (!link.classList.contains("is-active")) link.removeAttribute("aria-current");
      });
    };

    const flowSections = [...scrollFlow.querySelectorAll("[data-nav-section]")];
    let navTicking = false;
    const updateContextNav = () => {
      const readingLine = window.innerHeight * 0.34;
      const closest = flowSections.reduce((selected, section) => {
        const distance = Math.abs(section.getBoundingClientRect().top - readingLine);
        return !selected || distance < selected.distance ? { section, distance } : selected;
      }, null);
      if (closest) setActiveNav(closest.section.dataset.navSection);
      navTicking = false;
    };
    window.addEventListener("scroll", () => {
      if (navTicking) return;
      navTicking = true;
      requestAnimationFrame(updateContextNav);
    }, { passive: true });
    updateContextNav();
  }

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
})();

(() => {
  const STORAGE_KEY = "hotelExpertElahCart";
  const products = window.ELAH_PRODUCTS || {};
  const base = window.ELAH_BASE || "";
  const cartConfig = window.ELAH_CART || {};
  const ivaRate = Number(cartConfig.ivaRate ?? 16);

  const readCart = () => {
    try {
      const parsed = JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}");
      return parsed && typeof parsed === "object" ? parsed : {};
    } catch {
      return {};
    }
  };

  if (window.ELAH_REORDER_CART && typeof window.ELAH_REORDER_CART === "object") {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(window.ELAH_REORDER_CART));
    delete window.ELAH_REORDER_CART;
  }

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
      .map(([slug, qty]) => {
        const product = products[slug];
        const lineSubtotal = Number(product.precio) * Number(qty);
        const appliesIva = Boolean(product.iva);
        const lineIva = appliesIva ? Math.round(lineSubtotal * ivaRate / 100) : 0;
        return {
          ...product,
          qty: Number(qty),
          line_subtotal: lineSubtotal,
          line_iva: lineIva,
          line_total: lineSubtotal + lineIva,
        };
      });

  const cartTotals = (items = cartItems()) => {
    const subtotal = items.reduce((sum, item) => sum + item.line_subtotal, 0);
    const iva = items.reduce((sum, item) => sum + item.line_iva, 0);
    return {
      items,
      subtotal,
      iva,
      total: subtotal + iva,
      count: items.reduce((sum, item) => sum + item.qty, 0),
    };
  };

  const syncPayForm = (items = cartItems()) => {
    const payJson = document.querySelector("[data-cart-pay-json]");
    if (payJson) {
      payJson.value = JSON.stringify(items.map(({ slug, qty }) => ({ slug, qty })));
    }
    const quoteForm = document.querySelector("[data-quote-form]");
    if (!quoteForm) return;
    const setField = (selector, name) => {
      const source = quoteForm.querySelector(`[name="${name}"]`);
      const target = document.querySelector(selector);
      if (source && target) target.value = source.value.trim();
    };
    setField("[data-cart-pay-nombre]", "nombre");
    setField("[data-cart-pay-hotel]", "hotel");
    setField("[data-cart-pay-email]", "email");
    setField("[data-cart-pay-telefono]", "telefono");
  };

  const syncQuoteField = (cart = readCart()) => {
    const field = document.getElementById("cart-json");
    if (field) field.value = JSON.stringify(cartItems(cart));
  };

  const renderCart = () => {
    const root = document.querySelector("[data-cart-root]");
    if (!root) return;
    const totals = cartTotals();
    const items = totals.items;
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
      syncPayForm([]);
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
            <p class="min-w-[6rem] text-right font-heading font-extrabold text-expert">${money(item.line_subtotal)}</p>
            <button type="button" class="text-sm text-charcoal/45 hover:text-red-700" data-cart-action="remove" data-cart-slug="${item.slug}">Eliminar</button>
          </div>
        </article>`;
    }).join("");

    if (summary) {
      summary.innerHTML = `
        <div class="flex justify-between text-charcoal/60"><span>Productos (${totals.count})</span><span>${money(totals.subtotal)}</span></div>
        <div class="flex justify-between text-charcoal/60 mt-2"><span>IVA (${ivaRate}%)</span><span>${money(totals.iva)}</span></div>
        <div class="mt-4 pt-4 border-t border-expert/10 flex justify-between items-end">
          <span class="font-heading font-bold text-expert">Total</span>
          <strong class="price-display !text-3xl">${money(totals.total)}</strong>
        </div>
        <p class="mt-2 text-xs text-charcoal/45 text-right">Subtotal sin IVA: ${money(totals.subtotal)} · Entrega por confirmar.</p>`;
    }

    if (whatsapp) {
      const lines = items.map((item) => `• ${item.qty} × ${item.nombre} (${money(item.line_subtotal)} + IVA ${money(item.line_iva)})`);
      const message = `Hola Hotel Expert, quiero cotizar el Sistema ELAH:\n\n${lines.join("\n")}\n\nSubtotal: ${money(totals.subtotal)}\nIVA (${ivaRate}%): ${money(totals.iva)}\nTotal: ${money(totals.total)}`;
      whatsapp.href = `https://wa.me/${window.ELAH_WHATSAPP || ''}?text=${encodeURIComponent(message)}`;
    }
    syncQuoteField(readCart());
    syncPayForm(items);
  };

  document.querySelector("[data-cart-pay-form]")?.addEventListener("submit", (event) => {
    const totals = cartTotals();
    if (!totals.items.length) {
      event.preventDefault();
      showToast("Agrega al menos un producto antes de pagar");
      return;
    }
    syncPayForm(totals.items);
    const quoteForm = document.querySelector("[data-quote-form]");
    const nombre = quoteForm?.querySelector('[name="nombre"]')?.value.trim() || "";
    const hotel = quoteForm?.querySelector('[name="hotel"]')?.value.trim() || "";
    const email = quoteForm?.querySelector('[name="email"]')?.value.trim() || "";
    if (!nombre || !hotel || !email) {
      event.preventDefault();
      showToast("Completa nombre, hotel y correo en el formulario antes de pagar");
      quoteForm?.scrollIntoView({ behavior: "smooth", block: "start" });
    }
  });

  document.querySelector("[data-cart-pay-trigger]")?.addEventListener("click", () => {
    const payForm = document.querySelector("[data-cart-pay-form]");
    if (payForm) {
      syncPayForm(cartItems());
      payForm.requestSubmit();
      return;
    }
    document.querySelector("[data-quote-form]")?.scrollIntoView({ behavior: "smooth", block: "start" });
  });

  document.querySelector("[data-quote-form]")?.addEventListener("submit", (event) => {
    if (!cartItems().length) {
      event.preventDefault();
      showToast("Agrega al menos un producto a tu cotización");
    }
  });

  updateCounts();
  renderCart();

  const logoutDialog = document.getElementById("account-logout-dialog");
  const logoutForm = document.getElementById("account-logout-form");
  const logoutTrigger = document.querySelector("[data-logout-trigger]");

  const closeLogoutDialog = () => {
    if (!logoutDialog?.open) return;
    logoutDialog.close();
    logoutTrigger?.focus();
  };

  logoutTrigger?.addEventListener("click", () => {
    if (!logoutDialog) return;
    logoutDialog.showModal();
    logoutDialog.querySelector("[data-logout-cancel]")?.focus();
  });

  logoutDialog?.querySelector("[data-logout-cancel]")?.addEventListener("click", closeLogoutDialog);
  logoutDialog?.querySelector("[data-logout-confirm]")?.addEventListener("click", () => logoutForm?.requestSubmit());
  logoutDialog?.addEventListener("cancel", (event) => {
    event.preventDefault();
    closeLogoutDialog();
  });
  logoutDialog?.addEventListener("click", (event) => {
    if (event.target === logoutDialog) closeLogoutDialog();
  });
})();
