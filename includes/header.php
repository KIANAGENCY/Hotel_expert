<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
?>
<header id="site-header" class="site-header fixed top-0 inset-x-0 z-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="header-shell mt-3 flex items-center justify-between gap-4 rounded-2xl px-4 py-3 sm:px-5">
            <a href="<?= e(url('index.php')) ?>" class="flex items-center gap-3 shrink-0" aria-label="Hotel Expert inicio">
                <img src="<?= e(url('assets/img/logo.svg')) ?>" alt="Hotel Expert" class="h-10 w-auto">
            </a>
            <nav class="hidden lg:flex items-center gap-1" aria-label="Principal">
                <?php foreach ($nav as [$label, $href, $slug]): ?>
                    <a href="<?= e(url($href)) ?>"
                       class="nav-link <?= is_active($slug) ? 'is-active' : '' ?>"><?= e($label) ?></a>
                <?php endforeach; ?>
            </nav>
            <div class="flex items-center gap-2">
                <a href="<?= e(url('contacto.php')) ?>" class="btn-primary hidden sm:inline-flex">Solicitar cotización</a>
                <button type="button" id="menu-btn" class="lg:hidden grid place-items-center h-11 w-11 rounded-xl border border-white/20 text-white" aria-expanded="false" aria-controls="mobile-menu" aria-label="Abrir menú">
                    <svg id="icon-open" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                    <svg id="icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>
        </div>
    </div>
    <div id="mobile-menu" class="lg:hidden hidden px-4 sm:px-6">
        <nav class="mt-2 rounded-2xl bg-expert/95 backdrop-blur-xl border border-white/10 p-4 flex flex-col gap-1" aria-label="Móvil">
            <?php foreach ($nav as [$label, $href, $slug]): ?>
                <a href="<?= e(url($href)) ?>" class="rounded-xl px-4 py-3 text-white/90 hover:bg-white/10 <?= is_active($slug) ? 'bg-white/10 text-aqua' : '' ?>"><?= e($label) ?></a>
            <?php endforeach; ?>
            <a href="<?= e(url('contacto.php')) ?>" class="btn-primary mt-2 justify-center">Solicitar cotización</a>
        </nav>
    </div>
</header>
