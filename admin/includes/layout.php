<?php
declare(strict_types=1);

require_once __DIR__ . '/validation.php';

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
            'deploy' => ['Despliegue y servidor', 'deploy.php', 'fa-server'],
            'stripe' => ['Pagos Stripe', 'stripe.php', 'fa-credit-card'],
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
    <link rel="stylesheet" href="<?= e(url('admin/assets/admin.css?v=2')) ?>">
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
                        <form method="post" action="<?= e(admin_url('logout.php')) ?>">
                            <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
                            <button class="admin-menu-item is-danger" type="submit" style="width:100%;border:0;background:transparent;text-align:left;cursor:pointer"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión</button>
                        </form>
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
<script src="<?= e(url('admin/assets/admin.js?v=2')) ?>"></script>
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

function admin_placeholder(string $name): string
{
    $key = rtrim($name, '[]');
    $placeholders = [
        'titulo' => 'Título visible en el sitio',
        'texto' => 'Describe el beneficio operativo de esta área…',
        'href' => 'para-tu-hotel.php#lobby-areas-comunes',
        'sort_order' => '0',
        'pregunta' => '¿Qué incluye el Sistema ELAH?',
        'respuesta' => 'Explica la respuesta de forma clara para el hotel…',
        'slug' => 'hotel-expert-dual',
        'sku' => 'HE-DUAL-1L',
        'nombre' => 'Hotel Expert Dual',
        'categoria' => 'limpieza',
        'subtitulo' => 'Limpieza, desinfección y aroma insignia',
        'resumen' => 'Resumen breve para tarjetas y listados…',
        'presentacion' => 'Bidón 4 L concentrado',
        'rendimiento' => 'Hasta 40 L listos para usar',
        'precio' => '1290',
        'precio_texto' => '$1,290 MXN',
        'precio_lista' => '1490',
        'imagen' => 'producto-dual.jpg',
        'alt' => 'Hotel Expert Dual en uso',
        'icono' => 'HE',
        'funcion' => 'Limpieza y desinfección de superficies…',
        'especialidad' => 'Neutralización de olores persistentes…',
        'claims' => 'Una línea por beneficio o claim',
        'superficies' => 'Una superficie por línea',
        'no_usar' => 'Una restricción por línea',
        'seo_titulo' => 'Título SEO para buscadores',
        'meta_descripcion' => 'Resumen de 150–160 caracteres para Google…',
        'bajada' => 'Frase de apoyo bajo el título',
        'extracto' => 'Extracto corto para listados del blog',
        'lectura' => '5 min',
        'cover' => 'https://images.unsplash.com/…',
        'cuerpo' => 'Escribe párrafos separados por una línea en blanco…',
        'site_name' => 'Hotel Expert',
        'site_tagline' => 'Estandarización de Limpieza y Aroma en Hoteles',
        'site_claim' => 'Limpieza + aroma insignia para hoteles',
        'site_domain' => 'www.hotelexpert.mx',
        'whatsapp' => '528112497481',
        'whatsapp_display' => '+52 81 1249 7481',
        'email_ventas' => 'ventas@hotelexpert.mx',
        'social_facebook' => 'https://facebook.com/hotelexpert',
        'social_instagram' => 'https://instagram.com/hotelexpert',
        'current_password' => 'Tu contraseña actual del panel',
        'new_password' => 'Nueva clave de al menos 12 caracteres',
        'new_password_confirmation' => 'Repite la nueva contraseña',
        'id' => 'HE-2026-0001',
        'email' => 'compras@hotel.com',
        'hotel' => 'Hotel Boutique Sierra',
        'fecha' => '2026-03-15',
        'eta' => '2026-03-22',
        'items' => '2× Hotel Expert, 1× Difusor lobby…',
        'guia' => 'Guía DHL 1234567890',
        'product_qty' => 'Ej. 2',
        'notas' => 'Seguimiento interno, próximos pasos, observaciones…',
        'APP_URL' => 'https://www.hotelexpert.mx',
        'APP_ENV' => 'production',
        'DB_HOST' => '127.0.0.1',
        'DB_PORT' => '3306',
        'DB_DATABASE' => 'hotel_expert',
        'DB_USERNAME' => 'root',
        'DB_PASSWORD' => 'Dejar vacío para mantener la actual',
        'SMTP_HOST' => 'smtp.example.com',
        'SMTP_PORT' => '587',
        'SMTP_USERNAME' => 'smtp-user',
        'SMTP_PASSWORD' => 'Dejar vacío para mantener la actual',
        'SMTP_FROM_EMAIL' => 'ventas@hotelexpert.mx',
        'SMTP_FROM_NAME' => 'Hotel Expert',
        'ADMIN_SESSION_IDLE_SECONDS' => '1800',
        'ADMIN_SESSION_ABSOLUTE_SECONDS' => '43200',
        'CUSTOMER_SESSION_IDLE_SECONDS' => '1800',
        'CUSTOMER_SESSION_ABSOLUTE_SECONDS' => '43200',
        'DEPLOY_CANONICAL_HOST' => 'www.hotelexpert.mx',
        'DEPLOY_REWRITE_BASE' => 'subcarpeta (solo si aplica)',
        'admin_password' => 'Tu contraseña del panel admin',
        'STRIPE_PUBLISHABLE_KEY' => 'pk_test_… o pk_live_…',
        'STRIPE_SECRET_KEY' => 'sk_test_… o sk_live_… (vacío = mantener)',
        'STRIPE_WEBHOOK_SECRET' => 'whsec_… (vacío = mantener)',
        'STRIPE_CURRENCY' => 'mxn',
    ];

    return $placeholders[$key] ?? '';
}

function admin_field_attrs(array $attrs, string $name): string
{
    $attrs = admin_merge_field_attrs($attrs, $name);
    if (!isset($attrs['placeholder'])) {
        $placeholder = admin_placeholder($name);
        if ($placeholder !== '') {
            $attrs['placeholder'] = $placeholder;
        }
    }
    $extra = '';
    foreach ($attrs as $k => $v) {
        $extra .= ' ' . e($k) . '="' . e($v) . '"';
    }
    return $extra;
}

function admin_password_toggle_markup(string $inputId): void
{
    ?>
    <button class="admin-password-toggle" type="button" aria-controls="<?= e($inputId) ?>" aria-label="Mostrar contraseña" aria-pressed="false">
        <svg class="password-eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/>
            <circle cx="12" cy="12" r="2.5"/>
        </svg>
        <svg class="password-eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path d="m3 3 18 18M10.6 6.2A10.7 10.7 0 0 1 12 6c6 0 9.5 6 9.5 6a16.6 16.6 0 0 1-2.2 2.9M6.3 6.3C3.9 8 2.5 12 2.5 12s3.5 6 9.5 6c1.5 0 2.8-.4 4-1"/>
            <path d="M9.8 9.8a3.1 3.1 0 0 0-.3 1.2 2.5 2.5 0 0 0 3.7 2.2"/>
        </svg>
    </button>
    <?php
}

function admin_field(string $label, string $name, string $value = '', string $type = 'text', array $attrs = []): void
{
    $extra = admin_field_attrs($attrs, $name);
    $isPassword = $type === 'password';
    $inputId = $isPassword ? 'admin-field-' . preg_replace('/[^a-z0-9_]+/', '-', strtolower($name)) : '';
    if ($isPassword): ?>
    <div class="admin-label">
        <label class="admin-label-caption" for="<?= e($inputId) ?>"><?= e($label) ?></label>
        <div class="admin-password-field">
            <input class="admin-input" id="<?= e($inputId) ?>" type="password" name="<?= e($name) ?>" value="<?= e($value) ?>"<?= $extra ?>>
            <?php admin_password_toggle_markup($inputId); ?>
        </div>
    </div>
    <?php
        return;
    endif;
    ?>
    <label class="admin-label">
        <span><?= e($label) ?></span>
        <input class="admin-input" type="<?= e($type) ?>" name="<?= e($name) ?>" value="<?= e($value) ?>"<?= $extra ?>>
    </label>
    <?php
}

function admin_textarea(string $label, string $name, string $value = '', int $rows = 4, array $attrs = []): void
{
    $extra = admin_field_attrs($attrs, $name);
    ?>
    <label class="admin-label">
        <span><?= e($label) ?></span>
        <textarea class="admin-input" name="<?= e($name) ?>" rows="<?= $rows ?>"<?= $extra ?>><?= e($value) ?></textarea>
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
