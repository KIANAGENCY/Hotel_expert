<?php
declare(strict_types=1);

function admin_nav_groups(): array
{
    return [
        'Operación' => [
            'index' => ['Dashboard', 'index.php', 'fa-gauge-high'],
            'leads' => ['Leads', 'leads.php', 'fa-inbox'],
            'pedidos' => ['Pedidos', 'pedidos.php', 'fa-truck-fast'],
        ],
        'Catálogo' => [
            'productos' => ['Productos', 'productos.php', 'fa-box'],
        ],
        'Contenido' => [
            'blog' => ['Blog', 'blog.php', 'fa-newspaper'],
            'faq' => ['FAQ', 'faq.php', 'fa-circle-question'],
            'areas' => ['Áreas', 'areas.php', 'fa-map-location-dot'],
        ],
        'Sistema' => [
            'config' => ['Configuración', 'config.php', 'fa-gear'],
        ],
    ];
}

function admin_nav_items(): array
{
    $items = [];
    foreach (admin_nav_groups() as $groupItems) {
        foreach ($groupItems as $key => $item) {
            $items[$key] = $item;
        }
    }
    return $items;
}

function admin_user_initial(): string
{
    $user = admin_user();
    return $user !== '' ? strtoupper(substr($user, 0, 1)) : 'A';
}

function admin_brand_markup(): void
{
    ?>
        <a href="<?= e(admin_url('index.php')) ?>" class="admin-brand" aria-label="Hotel Expert — panel">
            <img src="<?= e(url('assets/img/logo-mark.png')) ?>" alt="" class="admin-brand-mark" width="132" height="132">
            <div class="admin-brand-text">
                <div class="admin-brand-title">SISTEMA</div>
                <div class="admin-brand-sub">ELAH</div>
            </div>
        </a>
    <?php
}

function admin_layout_start(string $title, string $active = ''): void
{
    $flash = admin_flash_consume();
    $stats = admin_stats();
    $leadsNuevos = (int) $stats['leads_nuevos'];
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> — Panel Hotel Expert</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800;900&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= e(url('admin/assets/admin.css')) ?>">
</head>
<body class="admin-app">
<div class="admin-sidebar-backdrop" data-admin-sidebar-backdrop hidden></div>
<div class="admin-shell">
    <aside class="admin-sidebar" data-admin-sidebar>
        <?php admin_brand_markup(); ?>

        <nav class="admin-nav" aria-label="Administración">
            <?php foreach (admin_nav_groups() as $groupLabel => $groupItems): ?>
                <p class="admin-nav-label"><?= e($groupLabel) ?></p>
                <?php foreach ($groupItems as $key => [$label, $href, $icon]): ?>
                    <a href="<?= e(admin_url($href)) ?>" class="admin-nav-link <?= $active === $key ? 'is-active' : '' ?>">
                        <i class="fa-solid <?= e($icon) ?>"></i>
                        <span><?= e($label) ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </nav>

        <div class="admin-sidebar-foot">
            <a href="<?= e(url('index.php')) ?>" class="admin-nav-link" target="_blank" rel="noopener">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>Ver sitio</span>
            </a>
        </div>
    </aside>

    <div class="admin-main-col">
        <header class="admin-topbar">
            <button type="button" class="admin-mobile-toggle" data-admin-sidebar-toggle aria-label="Menú">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="admin-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" data-admin-search placeholder="Buscar leads, productos, artículos…" aria-label="Buscar">
                <span class="admin-search-kbd">⌘K</span>
            </div>

            <div class="admin-topbar-actions">
                <div class="admin-dropdown">
                    <button type="button" class="admin-btn admin-btn-primary admin-btn-sm" data-admin-toggle="menu-create">
                        <i class="fa-solid fa-plus"></i> Crear
                    </button>
                    <div class="admin-menu" id="menu-create" data-admin-menu>
                        <a class="admin-menu-item" href="<?= e(admin_url('producto.php')) ?>"><i class="fa-solid fa-box"></i> Producto</a>
                        <a class="admin-menu-item" href="<?= e(admin_url('post.php')) ?>"><i class="fa-solid fa-pen-nib"></i> Artículo</a>
                        <a class="admin-menu-item" href="<?= e(admin_url('pedido.php')) ?>"><i class="fa-solid fa-truck-fast"></i> Pedido</a>
                        <a class="admin-menu-item" href="<?= e(admin_url('leads.php')) ?>"><i class="fa-solid fa-inbox"></i> Ver leads</a>
                    </div>
                </div>

                <div class="admin-dropdown">
                    <button type="button" class="admin-icon-btn" data-admin-toggle="menu-notif" aria-label="Notificaciones">
                        <i class="fa-regular fa-bell"></i>
                        <?php if ($leadsNuevos > 0): ?><span class="admin-dot"></span><?php endif; ?>
                    </button>
                    <div class="admin-menu" id="menu-notif" data-admin-menu style="width:280px">
                        <p class="admin-menu-label">Notificaciones</p>
                        <?php if ($leadsNuevos > 0): ?>
                            <a class="admin-menu-item" href="<?= e(admin_url('leads.php')) ?>">
                                <div>
                                    <div class="admin-menu-notif-text"><?= (int) $leadsNuevos ?> lead<?= $leadsNuevos === 1 ? '' : 's' ?> nuevo<?= $leadsNuevos === 1 ? '' : 's' ?> por revisar</div>
                                    <div class="admin-menu-notif-time">Sistema ELAH · B2B</div>
                                </div>
                            </a>
                        <?php else: ?>
                            <div class="admin-menu-item" style="cursor:default">
                                <div>
                                    <div class="admin-menu-notif-text">Sin alertas pendientes</div>
                                    <div class="admin-menu-notif-time">Todo al día</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="admin-dropdown">
                    <button type="button" class="admin-profile-btn" data-admin-toggle="menu-profile">
                        <span class="admin-avatar"><?= e(admin_user_initial()) ?></span>
                        <span><?= e(admin_user()) ?></span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="admin-menu" id="menu-profile" data-admin-menu style="width:170px">
                        <a class="admin-menu-item" href="<?= e(admin_url('config.php')) ?>"><i class="fa-regular fa-user"></i> Perfil</a>
                        <div class="admin-menu-divider"></div>
                        <a class="admin-menu-item is-danger" href="<?= e(admin_url('logout.php')) ?>"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </header>

        <main class="admin-content">
            <?php if ($flash): ?>
                <div class="admin-alert admin-alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
            <?php endif; ?>
    <?php
}

function admin_layout_end(): void
{
    ?>
        </main>
    </div>
</div>
<script src="<?= e(url('admin/assets/admin.js')) ?>"></script>
</body>
</html>
    <?php
}

function admin_page_header(string $eyebrow, string $title, string $subtitle = '', string $actions = ''): void
{
    ?>
    <div class="admin-page-head">
        <div>
            <?php if ($eyebrow !== ''): ?><p class="admin-eyebrow"><?= e($eyebrow) ?></p><?php endif; ?>
            <h1 class="admin-page-title"><?= e($title) ?></h1>
            <?php if ($subtitle !== ''): ?><p class="admin-page-sub"><?= e($subtitle) ?></p><?php endif; ?>
        </div>
        <?php if ($actions !== ''): ?>
            <div class="admin-page-actions"><?= $actions ?></div>
        <?php endif; ?>
    </div>
    <?php
}

function admin_filter_bar(array $filters, string $active = 'all'): void
{
    ?>
    <div class="admin-filter-bar" data-admin-filters role="tablist" aria-label="Filtrar listado">
        <?php foreach ($filters as $key => $label): ?>
            <button type="button"
                    class="admin-filter-pill<?= $key === $active ? ' is-active' : '' ?>"
                    data-admin-filter="<?= e($key) ?>"
                    role="tab"
                    aria-selected="<?= $key === $active ? 'true' : 'false' ?>">
                <?= e($label) ?>
            </button>
        <?php endforeach; ?>
    </div>
    <?php
}

function admin_empty_state(string $icon, string $title, string $text, string $actionHtml = ''): void
{
    ?>
    <div class="admin-empty">
        <span class="admin-empty-icon"><i class="fa-solid <?= e($icon) ?>"></i></span>
        <h3 class="admin-empty-title"><?= e($title) ?></h3>
        <p class="admin-empty-text"><?= e($text) ?></p>
        <?php if ($actionHtml !== ''): ?>
            <div class="admin-empty-action"><?= $actionHtml ?></div>
        <?php endif; ?>
    </div>
    <?php
}

function admin_field(string $label, string $name, string $value = '', string $type = 'text', array $attrs = []): void
{
    $extra = '';
    foreach ($attrs as $k => $v) {
        $extra .= ' ' . e($k) . '="' . e($v) . '"';
    }
    ?>
    <label class="admin-label">
        <span><?= e($label) ?></span>
        <input class="admin-input" type="<?= e($type) ?>" name="<?= e($name) ?>" value="<?= e($value) ?>"<?= $extra ?>>
    </label>
    <?php
}

function admin_textarea(string $label, string $name, string $value = '', int $rows = 4): void
{
    ?>
    <label class="admin-label">
        <span><?= e($label) ?></span>
        <textarea class="admin-input" name="<?= e($name) ?>" rows="<?= $rows ?>"><?= e($value) ?></textarea>
    </label>
    <?php
}

function admin_select(string $label, string $name, array $options, string $selected = ''): void
{
    ?>
    <label class="admin-label">
        <span><?= e($label) ?></span>
        <select class="admin-input" name="<?= e($name) ?>">
            <?php foreach ($options as $val => $text): ?>
                <option value="<?= e($val) ?>" <?= $val === $selected ? 'selected' : '' ?>><?= e($text) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <?php
}
