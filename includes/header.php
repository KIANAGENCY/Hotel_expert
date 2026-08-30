<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
?>
<header id="site-header" class="site-header fixed inset-x-0 top-0 z-50">
    <div class="mx-auto max-w-7xl px-3 sm:px-6">
        <div class="header-shell mt-3 rounded-2xl px-4 py-3 sm:px-6 sm:py-3.5">
            <div class="header-inner flex min-h-16 items-center justify-between sm:min-h-[7.25rem]">
                <a href="<?= e(url('')) ?>" class="brand-lockup shrink-0" aria-label="Hotel Expert — inicio">
                    <img src="<?= e(url('assets/img/logo-light.svg?v=spaced-lockup-1')) ?>" alt="Hotel Expert" class="brand-lockup-logo" width="320" height="100">
                </a>
                <nav class="header-nav hidden xl:flex items-center" aria-label="Principal">
                    <?php foreach ($nav as [$label, $href, $slug]): ?>
                        <a href="<?= e(url($href)) ?>" data-nav-key="<?= e($slug) ?>" class="nav-link <?= is_active($slug) ? 'is-active' : '' ?>" <?= is_active($slug) ? 'aria-current="page"' : '' ?>><?= e($label) ?></a>
                    <?php endforeach; ?>
                </nav>
                <div class="header-actions flex items-center gap-3">
                    <a href="<?= e(url('contacto/?tipo=muestra')) ?>" class="header-sample-cta">Solicitar muestra</a>
                    <button id="menu-btn" type="button" class="menu-btn xl:hidden" aria-expanded="false" aria-controls="mobile-menu" aria-label="Abrir menú">
                        <svg id="icon-open" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                        <svg id="icon-close" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
                    </button>
                </div>
            </div>
            <nav id="mobile-menu" class="mobile-menu hidden xl:hidden" aria-label="Menú móvil" hidden>
                <div class="mobile-menu-links">
                    <?php foreach ($nav as [$label, $href, $slug]): ?>
                        <a href="<?= e(url($href)) ?>" data-nav-key="<?= e($slug) ?>" class="mobile-menu-link <?= is_active($slug) ? 'is-active' : '' ?>" <?= is_active($slug) ? 'aria-current="page"' : '' ?>><?= e($label) ?></a>
                    <?php endforeach; ?>
                </div>
            </nav>
        </div>
    </div>
</header>
