<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/customer-auth.php';

$portalScript = str_replace('\\', '/', (string) ($_SERVER['PHP_SELF'] ?? ''));
$isAccountPortalRoute = is_customer_portal_route();

$portalCustomer = null;
if (!empty($_SESSION['customer_id'])) {
    $portalCustomer = current_customer();
}

$isAccountPortal = should_use_customer_portal_header($portalCustomer);
?>
<header id="site-header" class="site-header fixed inset-x-0 top-0 z-50<?= $isAccountPortal ? ' is-account-portal' : '' ?>">
    <div class="mx-auto max-w-7xl px-3 sm:px-6">
        <?php if ($isAccountPortal): ?>
            <div class="header-shell account-portal-shell mt-3 rounded-2xl px-4 py-3 sm:px-6">
                <a href="<?= e($portalCustomer ? account_url() : url('')) ?>" class="brand-lockup shrink-0" aria-label="<?= $portalCustomer ? 'Portal de clientes' : 'Hotel Expert — inicio' ?>">
                    <img src="<?= e(url('assets/img/logo-light.svg?v=spaced-lockup-1')) ?>" alt="Hotel Expert" class="brand-lockup-logo" width="320" height="100">
                </a>
                <?php if ($portalCustomer): ?>
                    <div class="account-portal-header-nav">
                        <?php require __DIR__ . '/partials/account-portal-nav.php'; ?>
                    </div>
                    <form method="post" action="<?= e(account_url('logout/')) ?>" class="account-portal-logout-form" id="account-logout-form">
                        <input type="hidden" name="csrf" value="<?= e(customer_csrf()) ?>">
                        <button
                            class="account-portal-link account-portal-logout-trigger"
                            type="button"
                            data-logout-trigger
                            aria-haspopup="dialog"
                            aria-controls="account-logout-dialog"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                            Cerrar sesión
                        </button>
                    </form>
                    <dialog id="account-logout-dialog" class="account-logout-dialog" aria-labelledby="account-logout-title">
                        <div class="account-logout-dialog-card">
                            <p class="eyebrow">Portal de clientes</p>
                            <h2 id="account-logout-title">¿Cerrar sesión?</h2>
                            <p>Tu sesión terminará y volverás al inicio de sesión. Tus pedidos y datos del hotel se conservan en tu cuenta.</p>
                            <div class="account-logout-dialog-actions">
                                <button class="btn-outline justify-center" type="button" data-logout-cancel>Cancelar</button>
                                <button class="btn-primary justify-center" type="button" data-logout-confirm>Cerrar sesión</button>
                            </div>
                        </div>
                    </dialog>
                <?php else: ?>
                    <a class="account-portal-back" href="<?= e(url('')) ?>">← Volver al sitio</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
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
                        <a href="<?= e(url(!empty($_SESSION['customer_id']) ? 'cuenta/' : 'cuenta/login/')) ?>" class="header-account-cta" aria-label="<?= !empty($_SESSION['customer_id']) ? 'Abrir mi cuenta' : 'Iniciar sesión' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>
                            <span><?= !empty($_SESSION['customer_id']) ? 'Mi cuenta' : 'Iniciar sesión' ?></span>
                        </a>
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
        <?php endif; ?>
    </div>
</header>
