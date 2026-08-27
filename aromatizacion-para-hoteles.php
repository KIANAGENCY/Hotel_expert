<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Aromatización para hoteles integrada a la experiencia de marca | Hotel Expert';
$page_description = 'Aromatización hotelera con limpieza, spray ambiental y difusores para una identidad olfativa consistente.';
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
                    <li><span class="text-white/80">Aromatización</span></li>
                </ol>
            </nav>
            <h1 class="display text-4xl sm:text-6xl text-white max-w-4xl">Aromatización para hoteles integrada a la experiencia de marca</h1>
            <p class="mt-5 max-w-2xl text-xl text-white/70">Un aroma puede estar presente durante unos minutos. Una identidad olfativa necesita consistencia.</p>
            <p class="mt-3 max-w-2xl text-lg text-white/65">Hotel Expert integra diferentes formas de aplicación para llevar el aroma del hotel a distintos puntos de contacto.</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 space-y-16">
            <article class="max-w-3xl">
                <h2 class="display text-3xl sm:text-4xl">Más que colocar un difusor en el lobby</h2>
                <p class="mt-5 text-lg text-charcoal/70">La aromatización forma parte de un sistema que también incluye la limpieza cotidiana y el refuerzo puntual, para que el aroma acompañe la experiencia del huésped de manera coherente.</p>
            </article>
            <article>
                <h2 class="display text-3xl sm:text-4xl mb-8">Tres formas de mantener el aroma</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="elah-card bg-hielo p-8">
                        <h3 class="font-heading font-extrabold text-xl text-expert">Durante la limpieza</h3>
                        <p class="mt-3 text-charcoal/70">Hotel Expert y Hotel Expert Dual incorporan el aroma insignia en cada aplicación.</p>
                    </div>
                    <div class="elah-card bg-hielo p-8">
                        <h3 class="font-heading font-extrabold text-xl text-expert">Mediante spray ambiental</h3>
                        <p class="mt-3 text-charcoal/70">Refuerza el aroma en zonas o momentos específicos.</p>
                    </div>
                    <div class="elah-card bg-hielo p-8">
                        <h3 class="font-heading font-extrabold text-xl text-expert">Mediante difusores</h3>
                        <p class="mt-3 text-charcoal/70">Mantiene presencia aromática continua en espacios seleccionados.</p>
                    </div>
                </div>
            </article>
            <article class="max-w-3xl">
                <h2 class="display text-3xl sm:text-4xl">Un aroma coherente en diferentes espacios</h2>
                <p class="mt-5 text-lg text-charcoal/70">Habitaciones, baños, pasillos, lobby y áreas de servicio pueden compartir la misma identidad olfativa.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a class="btn-outline" href="<?= e(url('aroma-insignia/')) ?>">Conocer el aroma insignia</a>
                    <a class="btn-outline" href="<?= e(url('sistema-elah/')) ?>">Ver el Sistema ELAH</a>
                </div>
            </article>
        </div>
    </section>

    <?php
    $cta_title = 'Creemos la propuesta aromática de tu hotel';
    $cta_text = 'Cuéntanos el tipo de propiedad y los espacios prioritarios para diseñar una propuesta de aroma.';
    $cta_primary_label = 'Crear una propuesta de aroma para mi hotel';
    $cta_primary_href = 'contacto.php?tipo=difusores';
    require __DIR__ . '/includes/partials/cta-band.php';
    ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>





