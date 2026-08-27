<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Productos Hotel Expert para limpieza y aromatización hotelera';
$page_description = 'Hotel Expert, Hotel Expert Dual, aromas para difusor, difusores y spray ambiental para la operación e identidad olfativa del hotel.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="internal-page-hero internal-hero-products bg-expert text-white py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <nav class="text-sm text-white/50 mb-8" aria-label="Ruta de navegación">
                <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <li><a class="hover:text-aqua" href="<?= e(url('')) ?>">Inicio</a></li>
                    <li aria-hidden="true">/</li>
                    <li><span class="text-white/80">Productos</span></li>
                </ol>
            </nav>
            <h1 class="display text-4xl sm:text-6xl text-white max-w-4xl">Productos Hotel Expert para limpieza y aromatización hotelera</h1>
            <p class="mt-5 max-w-2xl text-xl text-white/70">Soluciones profesionales desarrolladas para integrarse dentro de la operación y la identidad olfativa del hotel.</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid md:grid-cols-2 xl:grid-cols-3 gap-6">
            <article class="elah-card bg-white p-8 io-reveal flex flex-col">
                <h2 class="font-heading font-extrabold text-2xl text-expert">Hotel Expert</h2>
                <p class="mt-4 text-charcoal/70 flex-1">Limpieza + desinfección + aroma insignia.</p>
                <a class="btn-outline mt-6 self-start" href="<?= e(url('productos/hotel-expert/')) ?>">Ver Hotel Expert</a>
            </article>
            <article class="elah-card bg-white p-8 io-reveal flex flex-col">
                <h2 class="font-heading font-extrabold text-2xl text-expert">Hotel Expert Dual</h2>
                <p class="mt-4 text-charcoal/70 flex-1">Limpieza + desinfección + aroma insignia + neutralización de malos olores.</p>
                <a class="btn-outline mt-6 self-start" href="<?= e(url('productos/hotel-expert-dual/')) ?>">Ver Hotel Expert Dual</a>
            </article>
            <article id="aromas" class="elah-card bg-white p-8 io-reveal flex flex-col scroll-mt-32">
                <h2 class="font-heading font-extrabold text-2xl text-expert">Aromas para difusor</h2>
                <p class="mt-4 text-charcoal/70 flex-1">Soluciones aromáticas para reforzar la identidad olfativa del establecimiento.</p>
                <a class="btn-outline mt-6 self-start" href="<?= e(url('producto.php?slug=aroma-difusor')) ?>">Ver aromas para difusor</a>
            </article>
            <article id="difusores" class="elah-card bg-white p-8 io-reveal flex flex-col scroll-mt-32">
                <h2 class="font-heading font-extrabold text-2xl text-expert">Difusores</h2>
                <p class="mt-4 text-charcoal/70 flex-1">Aromatización continua para espacios seleccionados del hotel.</p>
                <div class="mt-6 flex flex-wrap gap-2">
                    <a class="btn-outline" href="<?= e(url('producto.php?slug=difusor-pequeno')) ?>">Difusor pequeño</a>
                    <a class="btn-outline" href="<?= e(url('producto.php?slug=difusor-grande')) ?>">Difusor grande</a>
                </div>
            </article>
            <article class="elah-card bg-white p-8 io-reveal flex flex-col">
                <h2 class="font-heading font-extrabold text-2xl text-expert">Spray ambiental</h2>
                <p class="mt-4 text-charcoal/70 flex-1">Refuerzo aromático puntual en zonas o momentos específicos.</p>
                <a class="btn-outline mt-6 self-start" href="<?= e(url('producto.php?slug=caja-aromas')) ?>">Ver spray ambiental</a>
            </article>
            <article class="elah-card bg-expert text-white p-8 io-reveal flex flex-col">
                <h2 class="font-heading font-extrabold text-2xl">¿Listo para cotizar?</h2>
                <p class="mt-4 text-white/70 flex-1">Conoce cómo integrar los productos dentro de la operación de tu hotel.</p>
                <a class="btn-primary mt-6 self-start" href="<?= e(url('contacto/?tipo=sistema')) ?>">Solicitar propuesta</a>
            </article>
        </div>
        <div id="comparacion" class="mx-auto max-w-7xl px-4 sm:px-6 mt-12 scroll-mt-32">
            <h2 class="display text-3xl mb-8">Comparar Hotel Expert y Hotel Expert Dual</h2>
            <?php require __DIR__ . '/includes/partials/product-comparison.php'; ?>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
