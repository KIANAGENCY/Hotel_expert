<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Hablemos de tu hotel | Contacto Hotel Expert';
$page_description = 'Solicita información, muestra o propuesta del Sistema ELAH. WhatsApp y correo para hoteles en México.';
$pref = preg_replace('/[^a-z]/', '', strtolower((string) ($_GET['tipo'] ?? '')));
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="internal-page-hero internal-hero-contact py-16 lg:py-24 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-12 gap-12">
            <div class="contact-intro-panel lg:col-span-5">
                <nav class="text-sm text-charcoal/50 mb-8" aria-label="Ruta de navegación">
                    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                        <li><a class="hover:text-turquesa" href="<?= e(url('')) ?>">Inicio</a></li>
                        <li aria-hidden="true">/</li>
                        <li><span class="text-charcoal/70">Contacto</span></li>
                    </ol>
                </nav>
                <p class="eyebrow">Contacto comercial</p>
                <h1 class="display mt-3 text-4xl sm:text-5xl">Hablemos de tu hotel</h1>
                <p class="mt-4 text-lg text-charcoal/70">Cuéntanos sobre tu propiedad, operación y objetivos de aroma para ayudarte a identificar la solución adecuada.</p>

                <div class="mt-8 space-y-6">
                    <div>
                        <h2 class="font-heading font-extrabold text-xl text-expert">¿Prefieres probar primero?</h2>
                        <p class="mt-2 text-charcoal/70">Solicita información sobre el paquete muestra y evalúa Hotel Expert dentro de tu propia operación.</p>
                        <a class="btn-outline mt-4" href="<?= e(url('contacto/?tipo=muestra')) ?>">Solicitar muestra</a>
                    </div>
                    <div>
                        <h2 class="font-heading font-extrabold text-xl text-expert">Habla con Hotel Expert</h2>
                        <ul class="mt-4 space-y-4">
                            <li class="rounded-2xl bg-white p-5">
                                <p class="text-xs uppercase tracking-wider text-charcoal/40 font-heading font-bold">WhatsApp</p>
                                <a class="font-heading font-extrabold text-xl text-expert hover:text-turquesa" href="<?= e(whatsapp_url($pref === 'muestra' ? WHATSAPP_MSG_MUESTRA : WHATSAPP_MSG)) ?>"><?= WHATSAPP_DISPLAY ?></a>
                            </li>
                            <li class="rounded-2xl bg-white p-5">
                                <p class="text-xs uppercase tracking-wider text-charcoal/40 font-heading font-bold">Correo</p>
                                <a class="font-heading font-extrabold text-xl text-expert hover:text-turquesa" href="mailto:<?= EMAIL_VENTAS ?>"><?= EMAIL_VENTAS ?></a>
                            </li>
                        </ul>
                        <a class="btn-ghost mt-4 inline-flex !text-expert !border-expert/20" href="<?= e(whatsapp_url($pref === 'muestra' ? WHATSAPP_MSG_MUESTRA : WHATSAPP_MSG)) ?>" target="_blank" rel="noopener">Hablar por WhatsApp</a>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-7">
                <div class="rounded-[1.6rem] bg-white p-8 sm:p-10 shadow-glass">
                    <h2 class="font-heading font-extrabold text-2xl text-expert mb-6">Solicita información</h2>
                    <?php if (!empty($_SESSION['form_error'])): ?>
                        <p class="mb-5 rounded-xl bg-expert text-white px-4 py-3 text-sm"><?= e($_SESSION['form_error']) ?></p>
                        <?php unset($_SESSION['form_error']); ?>
                    <?php endif; ?>
                    <?php require __DIR__ . '/includes/form-contacto.php'; ?>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>




