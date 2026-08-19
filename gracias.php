<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Solicitud recibida — Hotel Expert';
$page_description = 'Gracias. El equipo B2B de Hotel Expert se pondrá en contacto.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-40 pb-24">
    <div class="mx-auto max-w-xl px-4 text-center">
        <p class="eyebrow">Listo</p>
        <h1 class="display mt-3 text-4xl sm:text-5xl">Tu solicitud ya está en ventas.</h1>
        <p class="mt-4 text-lg text-charcoal/70">Respondemos a cotizaciones y muestras por correo o WhatsApp. Si es urgente, escribe ahora al <?= WHATSAPP_DISPLAY ?>.</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a class="btn-primary" href="https://wa.me/<?= WHATSAPP ?>?text=<?= WHATSAPP_MSG ?>">Abrir WhatsApp</a>
            <a class="btn-outline" href="<?= e(url('index.php')) ?>">Volver al inicio</a>
        </div>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
