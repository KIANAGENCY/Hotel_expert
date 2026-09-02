<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Solicita una muestra | Hotel Expert';
$page_description = 'Solicita una muestra del Sistema ELAH para evaluar Hotel Expert dentro de la operación de tu hotel.';
$pref = 'muestra';
$lock_interest = true;
$submit_label = 'Solicitar mi muestra';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="sample-page-hero">
        <div class="section-shell sample-page-grid">
            <div class="sample-page-copy">
                <nav class="text-sm text-white/55" aria-label="Ruta de navegación">
                    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                        <li><a class="hover:text-aqua" href="<?= e(url('')) ?>">Inicio</a></li>
                        <li aria-hidden="true">/</li>
                        <li><span class="text-white/80">Solicitar muestra</span></li>
                    </ol>
                </nav>
                <p class="eyebrow text-aqua">Prueba Hotel Expert</p>
                <h1>Evalúa el Sistema ELAH en tu propio hotel.</h1>
                <p class="sample-page-lead">Solicita una muestra para conocer el desempeño, aroma y facilidad de implementación antes de preparar una propuesta completa.</p>
                <ul class="sample-benefits">
                    <li><span>01</span><div><strong>Prueba en operación</strong><small>Evalúa el producto en áreas reales de tu propiedad.</small></div></li>
                    <li><span>02</span><div><strong>Acompañamiento</strong><small>Un asesor te ayuda a definir el uso y siguiente paso.</small></div></li>
                    <li><span>03</span><div><strong>Sin cotización obligatoria</strong><small>Primero conoce la solución; después decides cómo avanzar.</small></div></li>
                </ul>
            </div>
            <div class="sample-request-card">
                <p class="eyebrow">Solicitud de muestra</p>
                <h2>Cuéntanos sobre tu hotel</h2>
                <p>Usaremos estos datos únicamente para preparar el contacto y coordinar la muestra.</p>
                <?php if (!empty($_SESSION['form_error'])): ?>
                    <p class="account-alert is-error"><?= e($_SESSION['form_error']) ?></p>
                    <?php unset($_SESSION['form_error']); ?>
                <?php endif; ?>
                <?php require __DIR__ . '/includes/form-contacto.php'; ?>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
