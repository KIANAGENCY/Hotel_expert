(function () {
  function closeAllMenus(except) {
    document.querySelectorAll('[data-admin-menu]').forEach(function (menu) {
      if (menu !== except) {
        menu.classList.remove('is-open');
      }
    });
  }

  document.querySelectorAll('[data-admin-toggle]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      var targetId = btn.getAttribute('data-admin-toggle');
      var menu = document.getElementById(targetId);
      if (!menu) return;
      var isOpen = menu.classList.contains('is-open');
      closeAllMenus(null);
      if (!isOpen) {
        menu.classList.add('is-open');
      }
    });
  });

  document.addEventListener('click', function () {
    closeAllMenus(null);
  });

  document.querySelectorAll('[data-admin-menu]').forEach(function (menu) {
    menu.addEventListener('click', function (e) {
      e.stopPropagation();
    });
  });

  var sidebarToggle = document.querySelector('[data-admin-sidebar-toggle]');
  var sidebar = document.querySelector('[data-admin-sidebar]');
  var backdrop = document.querySelector('[data-admin-sidebar-backdrop]');

  function setSidebarOpen(open) {
    if (!sidebar) return;
    sidebar.classList.toggle('is-open', open);
    if (backdrop) {
      backdrop.hidden = !open;
      backdrop.classList.toggle('is-visible', open);
    }
    document.body.style.overflow = open ? 'hidden' : '';
  }

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      setSidebarOpen(!sidebar.classList.contains('is-open'));
    });
  }

  if (backdrop) {
    backdrop.addEventListener('click', function () {
      setSidebarOpen(false);
    });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      setSidebarOpen(false);
      closeAllMenus(null);
    }
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
      var search = document.querySelector('[data-admin-search]');
      if (search) {
        e.preventDefault();
        search.focus();
      }
    }
  });

  var globalSearch = document.querySelector('[data-admin-search]');
  if (globalSearch) {
    globalSearch.addEventListener('input', function () {
      var q = globalSearch.value.trim().toLowerCase();
      document.querySelectorAll('[data-admin-list] tbody tr[data-search]').forEach(function (row) {
        var text = row.getAttribute('data-search') || '';
        var filter = row.getAttribute('data-filter') || 'all';
        var filterBar = document.querySelector('[data-admin-filters]');
        var activeFilter = filterBar
          ? (filterBar.querySelector('.admin-filter-pill.is-active') || {}).getAttribute('data-admin-filter') || 'all'
          : 'all';
        var matchesFilter = activeFilter === 'all' || filter === activeFilter;
        var matchesSearch = q === '' || text.indexOf(q) !== -1;
        row.classList.toggle('is-hidden', !(matchesFilter && matchesSearch));
      });
      updateListCount();
    });
  }

  document.querySelectorAll('[data-admin-filters]').forEach(function (bar) {
    bar.querySelectorAll('[data-admin-filter]').forEach(function (pill) {
      pill.addEventListener('click', function () {
        bar.querySelectorAll('[data-admin-filter]').forEach(function (p) {
          p.classList.remove('is-active');
          p.setAttribute('aria-selected', 'false');
        });
        pill.classList.add('is-active');
        pill.setAttribute('aria-selected', 'true');

        var active = pill.getAttribute('data-admin-filter') || 'all';
        var q = globalSearch ? globalSearch.value.trim().toLowerCase() : '';
        document.querySelectorAll('[data-admin-list] tbody tr[data-search]').forEach(function (row) {
          var filter = row.getAttribute('data-filter') || 'all';
          var text = row.getAttribute('data-search') || '';
          var matchesFilter = active === 'all' || filter === active;
          var matchesSearch = q === '' || text.indexOf(q) !== -1;
          row.classList.toggle('is-hidden', !(matchesFilter && matchesSearch));
        });
        updateListCount();
      });
    });
  });

  function updateListCount() {
    document.querySelectorAll('[data-admin-list-count]').forEach(function (el) {
      var listId = el.getAttribute('data-admin-list-count');
      var table = document.getElementById(listId);
      if (!table) return;
      var visible = table.querySelectorAll('tbody tr[data-search]:not(.is-hidden)').length;
      el.textContent = String(visible);
    });
  }

  function bindPasswordToggles(root) {
    (root || document).querySelectorAll('.admin-password-toggle').forEach(function (button) {
      if (button.dataset.bound === '1') return;
      button.dataset.bound = '1';
      button.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        var field = button.closest('.admin-password-field');
        if (!field) return;
        var input = field.querySelector('input');
        if (!input) return;
        var reveal = input.getAttribute('type') === 'password';
        input.setAttribute('type', reveal ? 'text' : 'password');
        button.setAttribute('aria-pressed', reveal ? 'true' : 'false');
        button.setAttribute('aria-label', reveal ? 'Ocultar contraseña' : 'Mostrar contraseña');
      });
    });
  }

  bindPasswordToggles(document);
})();
