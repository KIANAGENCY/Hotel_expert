<?php
declare(strict_types=1);
http_response_code(404);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Página no encontrada — Hotel Expert';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-40 pb-24 px-4 text-center">
    <p class="eyebrow">404</p>
    <h1 class="display mt-3 text-4xl sm:text-5xl">Esta ruta no está en el sistema.</h1>
    <p class="mt-4 text-charcoal/70">Vuelve al inicio o al catálogo B2B.</p>
    <div class="mt-8 flex justify-center gap-3">
        <a class="btn-primary" href="<?= e(url('index.php')) ?>">Inicio</a>
        <a class="btn-outline" href="<?= e(url('catalogo.php')) ?>">Catálogo</a>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
