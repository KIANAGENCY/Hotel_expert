<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Sistema ELAH: limpieza y aroma bajo una misma identidad | Hotel Expert';
$page_description = 'ELAH significa Estandarización de Limpieza y Aroma en Hoteles. Integra limpieza cotidiana e identidad olfativa en una misma experiencia.';
$breadcrumbs = [
    ['label' => 'Inicio', 'href' => ''],
    ['label' => 'Sistema ELAH'],
];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="internal-page-hero internal-hero-elah relative py-20 lg:py-28 bg-expert text-white overflow-hidden noise">
        <div class="absolute inset-0 grid-dots opacity-30"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6">
            <?php
            // breadcrumbs on dark: override colors via wrapper
            ?>
            <nav class="text-sm text-white/50 mb-8" aria-label="Ruta de navegación">
                <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <li><a class="hover:text-aqua" href="<?= e(url('')) ?>">Inicio</a></li>
                    <li aria-hidden="true">/</li>
                    <li><span class="text-white/80">Sistema ELAH</span></li>
                </ol>
            </nav>
            <p class="eyebrow text-aqua">Sistema</p>
            <h1 class="elah-wordmark text-white">ELAH</h1>
            <p class="mt-6 max-w-2xl font-heading text-xl font-bold text-white sm:text-3xl">Estandarización de Limpieza y Aroma en Hoteles</p>
            <p class="elah-equation mt-7">Limpieza <span>+</span> Aroma <span>=</span> ELAH</p>
            <p class="mt-4 max-w-2xl text-lg text-white/65">Es el sistema de Hotel Expert para integrar la limpieza cotidiana y la identidad olfativa del establecimiento dentro de una misma experiencia.</p>
            <a class="btn-primary mt-8" href="<?= e(url('contacto/?tipo=sistema')) ?>">Solicita una propuesta</a>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-white">
        <div class="mx-auto max-w-3xl px-4 sm:px-6">
            <h2 class="display text-3xl sm:text-5xl">Dos necesidades que los hoteles suelen resolver por separado</h2>
            <div class="mt-6 space-y-4 text-lg text-charcoal/70 leading-relaxed">
                <p>Limpieza por un lado.</p>
                <p>Aromatización por otro.</p>
                <p>El resultado puede ser una experiencia fragmentada entre productos, proveedores y aromas diferentes.</p>
                <p>ELAH propone integrarlos bajo una misma estrategia.</p>
            </div>
        </div>
    </section>

    <section id="como-funciona" class="py-20 lg:py-28 bg-hielo scroll-mt-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <h2 class="display text-3xl sm:text-5xl">¿Cómo funciona el Sistema ELAH?</h2>
            <div class="mt-12 grid lg:grid-cols-3 gap-6">
                <article class="elah-card bg-white p-8 io-reveal">
                    <span class="font-heading font-extrabold text-5xl text-aqua">01</span>
                    <h3 class="mt-6 font-heading font-extrabold text-2xl text-expert">Limpieza cotidiana</h3>
                    <p class="mt-3 text-charcoal/70">Hotel Expert y Hotel Expert Dual incorporan el aroma insignia durante la limpieza.</p>
                </article>
                <article class="elah-card bg-white p-8 io-reveal">
                    <span class="font-heading font-extrabold text-5xl text-aqua">02</span>
                    <h3 class="mt-6 font-heading font-extrabold text-2xl text-expert">Refuerzo puntual</h3>
                    <p class="mt-3 text-charcoal/70">El spray permite reforzar el aroma cuando y donde se necesita.</p>
                </article>
                <article class="elah-card bg-white p-8 io-reveal">
                    <span class="font-heading font-extrabold text-5xl text-aqua">03</span>
                    <h3 class="mt-6 font-heading font-extrabold text-2xl text-expert">Aromatización continua</h3>
                    <p class="mt-3 text-charcoal/70">Los difusores mantienen presencia aromática en espacios seleccionados.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <h2 class="display text-3xl sm:text-5xl max-w-3xl mb-12">De una operación cotidiana a una experiencia de marca</h2>
            <?php require __DIR__ . '/includes/partials/process-steps.php'; ?>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-arena">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="display text-3xl sm:text-5xl">ELAH se adapta a diferentes tipos de propiedad</h2>
                <p class="mt-5 text-lg text-charcoal/70">Desde un hotel independiente que busca desarrollar una identidad diferenciada hasta un grupo hotelero que necesita mayor consistencia entre áreas o propiedades.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a class="btn-outline" href="<?= e(url('para-tu-hotel.php')) ?>">Ver áreas de aplicación</a>
                    <a class="btn-outline" href="<?= e(url('productos/')) ?>">Conocer productos</a>
                </div>
            </div>
            <div class="rounded-[1.75rem] bg-expert text-white p-8 sm:p-10">
                <p class="eyebrow text-aqua">Siguiente paso</p>
                <h2 class="font-heading font-extrabold text-3xl mt-3">Conoce ELAH dentro de tu propia operación</h2>
                <p class="mt-4 text-white/70">Solicita información sobre el paquete muestra y evalúa el sistema en tu hotel.</p>
                <a class="btn-primary mt-7" href="<?= e(url('contacto/?tipo=muestra')) ?>">Solicitar muestra</a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
