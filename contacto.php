<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Contacto y cotización mayoreo — Hotel Expert';
$page_description = 'Solicita cotización B2B, muestra o prueba en piso. WhatsApp +52 81 1249 7481 · ventas@hotelexpert.mx';
$pref = preg_replace('/[^a-z]/', '', strtolower((string) ($_GET['tipo'] ?? '')));
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="py-16 lg:py-22 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-12 gap-12">
            <div class="lg:col-span-5">
                <p class="eyebrow">Contacto B2B</p>
                <h1 class="display mt-3 text-4xl sm:text-5xl">Cotización, muestra o demo. El mismo canal.</h1>
                <p class="mt-4 text-lg text-charcoal/70">Formulario directo a ventas. Preparado para conectarse a HubSpot cuando el portal esté activo.</p>
                <ul class="mt-8 space-y-4">
                    <li class="rounded-2xl bg-white p-5">
                        <p class="text-xs uppercase tracking-wider text-charcoal/40 font-heading font-bold">WhatsApp Business</p>
                        <a class="font-heading font-extrabold text-xl text-expert hover:text-turquesa" href="https://wa.me/<?= WHATSAPP ?>?text=<?= WHATSAPP_MSG ?>"><?= WHATSAPP_DISPLAY ?></a>
                    </li>
                    <li class="rounded-2xl bg-white p-5">
                        <p class="text-xs uppercase tracking-wider text-charcoal/40 font-heading font-bold">Correo</p>
                        <a class="font-heading font-extrabold text-xl text-expert hover:text-turquesa" href="mailto:<?= EMAIL_VENTAS ?>"><?= EMAIL_VENTAS ?></a>
                    </li>
                </ul>
            </div>
            <div class="lg:col-span-7 rounded-[1.6rem] bg-white p-8 sm:p-10 shadow-glass">
                <?php if (!empty($_SESSION['form_error'])): ?>
                    <p class="mb-5 rounded-xl bg-expert text-white px-4 py-3 text-sm"><?= e($_SESSION['form_error']) ?></p>
                    <?php unset($_SESSION['form_error']); ?>
                <?php endif; ?>
                <?php require __DIR__ . '/includes/form-contacto.php'; ?>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>