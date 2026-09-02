<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$page_title = 'Pago cancelado — Hotel Expert';
$page_description = 'El pago no se completó.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-40 pb-24">
    <div class="mx-auto max-w-xl px-4 text-center">
        <p class="eyebrow">Pago cancelado</p>
        <h1 class="display mt-3 text-4xl sm:text-5xl">No se realizó el cobro.</h1>
        <p class="mt-4 text-lg text-charcoal/70">Tu carrito sigue disponible. Puedes intentar de nuevo o solicitar cotización por WhatsApp.</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a class="btn-primary" href="<?= e(url('cotizacion/')) ?>">Volver al carrito</a>
            <a class="btn-outline" href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener">WhatsApp</a>
        </div>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
