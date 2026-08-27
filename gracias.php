<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$isQuote = ($_SESSION['last_request_type'] ?? '') === 'cotizacion';
unset($_SESSION['last_request_type']);
$page_title = 'Solicitud recibida — Sistema ELAH';
$page_description = 'Gracias. El equipo de Hotel Expert revisará tu solicitud ELAH.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-40 pb-24" <?= $isQuote ? 'data-clear-cart' : '' ?>>
    <div class="mx-auto max-w-xl px-4 text-center">
        <p class="eyebrow">Listo</p>
        <h1 class="display mt-3 text-4xl sm:text-5xl">Recibimos tu solicitud ELAH.</h1>
        <p class="mt-4 text-lg text-charcoal/70">Revisaremos productos, cantidades, IVA y entrega para responderte por correo o WhatsApp.</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a class="btn-primary" href="<?= e(whatsapp_url()) ?>">Abrir WhatsApp</a>
            <a class="btn-outline" href="<?= e(url('catalogo.php')) ?>">Volver a la tienda</a>
        </div>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
