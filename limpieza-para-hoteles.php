<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Limpieza profesional para hoteles | Hotel Expert';
$page_description = 'Limpieza profesional para hoteles con aroma insignia. Concentrado multiuso, dilución 1:9 y dos versiones según la necesidad operativa.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="bg-expert text-white py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <nav class="text-sm text-white/50 mb-8" aria-label="Ruta de navegación">
                <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <li><a class="hover:text-aqua" href="<?= e(url('')) ?>">Inicio</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a class="hover:text-aqua" href="<?= e(url('soluciones.php')) ?>">Soluciones</a></li>
                    <li aria-hidden="true">/</li>
                    <li><span class="text-white/80">Limpieza profesional</span></li>
                </ol>
            </nav>
            <h1 class="display text-4xl sm:text-6xl text-white max-w-4xl">Limpieza profesional para hoteles con una experiencia que va más allá de limpiar</h1>
            <p class="mt-5 max-w-2xl text-xl text-white/70">La limpieza es indispensable. Hotel Expert añade a esa operación una capa adicional: el aroma insignia de tu hotel.</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 space-y-16">
            <article class="max-w-3xl">
                <h2 class="display text-3xl sm:text-4xl">Un producto para múltiples superficies</h2>
                <p class="mt-5 text-lg text-charcoal/70">Pisos, mármol, granito, acero inoxidable, baños, cromo, madera, textiles, sillones y alfombras.</p>
                <p class="mt-4 rounded-2xl border border-aqua/30 bg-hielo p-5 text-charcoal/80"><strong class="text-expert">Excepción:</strong> vidrio y espejos.</p>
            </article>
            <article class="max-w-3xl">
                <h2 class="display text-3xl sm:text-4xl">Concentrado para simplificar la operación</h2>
                <p class="mt-5 text-lg text-charcoal/70">100 ml de concentrado + 900 ml de agua producen un litro listo para usar.</p>
                <a class="btn-outline mt-6" href="<?= e(url('manual-de-uso/')) ?>">Ver manual de uso</a>
            </article>
            <article class="max-w-3xl">
                <h2 class="display text-3xl sm:text-4xl">Limpia, desinfecta y aromatiza en una misma aplicación</h2>
                <p class="mt-5 text-lg text-charcoal/70">Cada limpieza también aplica el aroma insignia del hotel, integrando operación e identidad olfativa.</p>
            </article>
            <article>
                <h2 class="display text-3xl sm:text-4xl mb-8">Dos versiones según la necesidad</h2>
                <?php require __DIR__ . '/includes/partials/product-comparison.php'; ?>
            </article>
        </div>
    </section>

    <?php
    $cta_title = 'Solicita información para tu hotel';
    $cta_text = 'Cuéntanos las áreas y necesidades de tu propiedad para diseñar la solución adecuada.';
    $cta_primary_label = 'Solicitar información';
    $cta_primary_href = 'contacto.php';
    require __DIR__ . '/includes/partials/cta-band.php';
    ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>





