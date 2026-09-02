<?php
declare(strict_types=1);
$accountRoute = basename((string) ($_SERVER['PHP_SELF'] ?? 'index.php'), '.php');
$accountPath = str_replace('\\', '/', (string) ($_SERVER['PHP_SELF'] ?? ''));
$account_nav_active = $account_nav_active ?? match (true) {
    str_contains($accountPath, '/cuenta/') => 'dashboard',
    $accountRoute === 'cotizacion' => 'cotizacion',
    in_array($accountRoute, ['catalogo', 'productos', 'producto'], true) => 'catalogo',
    $accountRoute === 'contacto' => 'contacto',
    default => 'home',
};
$links = [
    'home' => ['label' => 'Inicio', 'href' => url('/')],
    'catalogo' => ['label' => 'Catálogo', 'href' => url('catalogo/')],
    'cotizacion' => ['label' => 'Nueva cotización', 'href' => url('cotizacion/')],
    'dashboard' => ['label' => 'Mi cuenta', 'href' => url('cuenta/')],
    'contacto' => ['label' => 'Contacto', 'href' => url('contacto/')],
];
?>
<nav class="account-portal-nav" aria-label="Navegación del portal">
    <?php foreach ($links as $key => $link): ?>
        <a
            class="account-portal-link<?= $account_nav_active === $key ? ' is-active' : '' ?>"
            href="<?= e($link['href']) ?>"
            <?= $account_nav_active === $key ? 'aria-current="page"' : '' ?>
        ><?= e($link['label']) ?></a>
    <?php endforeach; ?>
</nav>
