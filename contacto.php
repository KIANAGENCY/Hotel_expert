<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Cotiza el Sistema ELAH para tu Hotel | Contacto Hotel Expert';
$page_description = 'Solicita asesoría para diseñar el sistema de limpieza y aromatización de tu hotel. Cobertura nacional en México. Respuesta por WhatsApp o correo.';
$pref = preg_replace('/[^a-z]/', '', strtolower((string) ($_GET['tipo'] ?? '')));
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="py-16 lg:py-24 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-12 gap-12">
            <div class="lg:col-span-5">
                <p class="eyebrow">Asesoría especializada</p>
                <h1 class="display mt-3 text-4xl sm:text-5xl">Diseñemos el Sistema ELAH para tu hotel.</h1>
                <p class="mt-4 text-lg text-charcoal/70">Cuéntanos el tamaño, las áreas y las necesidades de tu propiedad. Te ayudamos a elegir concentrados, aromas y difusores.</p>
                <div class="mt-7 rounded-2xl bg-expert text-white p-5">
                    <p class="font-heading font-bold text-aqua">Cobertura nacional</p>
                    <p class="mt-2 text-white/70">Enviamos a cualquier punto de la República y confirmamos los tiempos según tu ubicación.</p>
                </div>
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