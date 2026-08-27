<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Aroma, limpieza y experiencia del huésped | Hotel Expert';
$page_description = 'Marketing sensorial, aroma e identidad olfativa para construir una experiencia consistente en hoteles.';
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
                    <li><span class="text-white/80">Experiencia del huésped</span></li>
                </ol>
            </nav>
            <h1 class="display text-4xl sm:text-6xl text-white max-w-4xl">Aroma, limpieza y experiencia del huésped</h1>
            <p class="mt-5 max-w-2xl text-xl text-white/70">La experiencia de un hotel se construye mediante múltiples puntos de contacto.</p>
            <p class="mt-3 max-w-2xl text-lg text-white/65">Hotel Expert trabaja sobre uno de ellos: la relación entre limpieza, frescura, aroma e identidad.</p>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 space-y-16">
            <article class="max-w-3xl io-reveal">
                <h2 class="display text-3xl sm:text-4xl">Marketing sensorial en hoteles</h2>
                <p class="mt-5 text-lg text-charcoal/70 leading-relaxed">El aroma forma parte de la percepción del huésped desde el check-in hasta la habitación. Hotel Expert integra ese estímulo dentro de la operación cotidiana de limpieza y de soluciones de aromatización.</p>
                <a class="btn-outline mt-6" href="<?= e(url('aroma-insignia/')) ?>">Conocer el aroma insignia</a>
            </article>
            <article class="max-w-3xl io-reveal">
                <h2 class="display text-3xl sm:text-4xl">Aroma y experiencia del huésped</h2>
                <p class="mt-5 text-lg text-charcoal/70 leading-relaxed">No se trata únicamente de que el hotel huela bien. Se trata de decidir a qué quieres que huela tu marca y mantener esa intención de manera consistente en diferentes puntos de contacto.</p>
                <a class="btn-outline mt-6" href="<?= e(url('aromatizacion-para-hoteles.php')) ?>">Ver aromatización para hoteles</a>
            </article>
            <article class="max-w-3xl io-reveal">
                <h2 class="display text-3xl sm:text-4xl">Consistencia de marca</h2>
                <p class="mt-5 text-lg text-charcoal/70 leading-relaxed">El Sistema ELAH ayuda a estandarizar limpieza y aroma para que la experiencia olfativa acompañe la identidad del hotel en habitaciones, áreas comunes y espacios de servicio.</p>
                <a class="btn-primary mt-6" href="<?= e(url('sistema-elah/')) ?>">Descubrir el Sistema ELAH</a>
            </article>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>





