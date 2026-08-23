<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
$isHomePage = $page === 'index';
$homeAnchors = [
    'index' => '#inicio',
    'catalogo' => '#tienda',
    'como-funciona' => '#como-funciona',
    'nosotros' => '#nosotros',
    'blog' => '#blog',
    'contacto' => '#contacto',
];
?>
<header id="site-header" class="site-header page-<?= e($page) ?> fixed top-0 inset-x-0 z-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="header-shell mt-3 rounded-2xl px-4 py-3 sm:px-5">
            <div class="flex items-center justify-between gap-3">
                <a href="<?= e($isHomePage ? '#inicio' : url('index.php')) ?>" class="brand-lockup flex items-center gap-2.5 shrink-0" aria-label="Hotel Expert inicio">
                <span class="brand-mark-shell">
                    <img src="<?= e(url('assets/img/logo-mark-256.png')) ?>" alt="" class="h-[4.4rem] w-[4.4rem] object-contain" width="70" height="70">
                </span>
                <span class="leading-none">
                    <span class="brand-kicker block font-heading font-extrabold tracking-[0.24em] text-white/90">HOTEL</span>
                    <span class="brand-name block font-heading font-extrabold tracking-[0.06em] text-aqua mt-1">EXPERT</span>
                </span>
                </a>
                <nav class="hidden lg:flex items-center gap-1" aria-label="Principal">
                    <?php foreach ($nav as [$label, $href, $slug]): ?>
                        <a href="<?= e($isHomePage ? $homeAnchors[$slug] : url($href)) ?>"
                           data-nav-target="<?= e($homeAnchors[$slug]) ?>"
                           class="nav-link <?= is_active($slug) ? 'is-active' : '' ?>"><?= e($label) ?></a>
                    <?php endforeach; ?>
                </nav>
                <div class="flex items-center gap-2">
                    <a href="<?= e(url('cotizacion.php')) ?>" class="cart-trigger" aria-label="Ver carrito de cotización">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h2l2.2 10.1a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L20.5 8H6.1M10 20h.01M17 20h.01"/></svg>
                        <span class="hidden sm:inline">Cotización</span>
                        <span class="cart-count" data-cart-count>0</span>
                    </a>
                </div>
            </div>
            <nav class="mobile-nav-strip lg:hidden" aria-label="Navegación móvil desplazable">
                <?php foreach ($nav as [$label, $href, $slug]): ?>
                    <a href="<?= e($isHomePage ? $homeAnchors[$slug] : url($href)) ?>"
                       data-nav-target="<?= e($homeAnchors[$slug]) ?>"
                       class="mobile-nav-link <?= is_active($slug) ? 'is-active' : '' ?>"><?= e($label) ?></a>
                <?php endforeach; ?>
            </nav>
        </div>
    </div>
</header>
