<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Aroma insignia: identidad olfativa para hoteles | Hotel Expert';
$page_description = 'Convierte el aroma de tu hotel en parte de su identidad con el aroma insignia y el Sistema ELAH.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="internal-page-hero internal-hero-aroma bg-expert text-white py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <nav class="text-sm text-white/50 mb-8" aria-label="Ruta de navegación">
                <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <li><a class="hover:text-aqua" href="<?= e(url('')) ?>">Inicio</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a class="hover:text-aqua" href="<?= e(url('soluciones.php')) ?>">Soluciones</a></li>
                    <li aria-hidden="true">/</li>
                    <li><span class="text-white/80">Aroma insignia</span></li>
                </ol>
            </nav>
            <h1 class="display text-4xl sm:text-6xl text-white max-w-4xl">Aroma insignia: convierte el aroma de tu hotel en parte de su identidad</h1>
            <p class="mt-5 max-w-2xl text-xl text-white/70">Tu marca tiene nombre, colores, arquitectura y personalidad.</p>
            <p class="mt-3 max-w-2xl text-lg text-white/65">El aroma también puede formar parte de esa identidad.</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 space-y-16">
            <article class="max-w-3xl">
                <h2 class="display text-3xl sm:text-4xl">¿Qué es un aroma insignia?</h2>
                <p class="mt-5 text-lg text-charcoal/70">Es el concepto aromático elegido para representar sensorialmente la personalidad y experiencia que el hotel quiere transmitir.</p>
            </article>
            <article>
                <h2 class="display text-3xl sm:text-4xl mb-8">Del aroma agradable a la identidad olfativa</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <?php foreach (['Marca', 'Propiedad', 'Huésped', 'Espacios', 'Operación'] as $i => $item): ?>
                        <div class="rounded-3xl bg-hielo p-6 text-center">
                            <span class="font-heading font-extrabold text-aqua text-2xl">0<?= $i + 1 ?></span>
                            <p class="mt-3 font-heading font-bold text-expert"><?= e($item) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </article>
            <article class="max-w-3xl">
                <h2 class="display text-3xl sm:text-4xl">El aroma insignia dentro del Sistema ELAH</h2>
                <p class="mt-5 text-lg text-charcoal/70">Se incorpora durante la limpieza, se refuerza con spray ambiental y se mantiene con difusores en espacios seleccionados, bajo una misma identidad olfativa.</p>
                <a class="btn-outline mt-6" href="<?= e(url('sistema-elah/')) ?>">Conocer el Sistema ELAH</a>
            </article>
            <article class="max-w-3xl">
                <h2 class="display text-3xl sm:text-4xl">Construyamos el brief aromático de tu hotel</h2>
                <p class="mt-5 text-lg text-charcoal/70">Cuéntanos la personalidad de tu propiedad y los espacios prioritarios para avanzar hacia una propuesta de aroma insignia.</p>
            </article>
        </div>
    </section>

    <?php
    $cta_title = 'Quiero crear mi aroma insignia';
    $cta_text = 'Solicita una propuesta y hablemos del aroma que representará a tu hotel.';
    $cta_primary_label = 'Quiero crear mi aroma insignia';
    $cta_primary_href = 'contacto.php?tipo=difusores';
    require __DIR__ . '/includes/partials/cta-band.php';
    ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>




