<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$productos = require __DIR__ . '/data/productos.php';
$page_title = 'Catálogo B2B — Hotel Expert';
$page_description = 'Concentrado Estándar, Dual (neutralizador + aroma insignia), porrones de 20 L y atomizadores industriales de 1 L. Envases retornables.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
$insigna = [$productos['estandar'], $productos['dual']];
$accesorios = [$productos['porron'], $productos['atomizador']];
?>
<main id="contenido" class="pt-28">
    <section class="bg-expert text-white py-16 sm:py-20 noise relative overflow-hidden">
        <div class="absolute inset-0 grid-dots opacity-30"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6">
            <p class="eyebrow text-aqua">Catálogo / Tienda B2B</p>
            <h1 class="display mt-3 text-4xl sm:text-6xl text-white">Dos productos insignia.<br>Un sistema retornable.</h1>
            <p class="mt-4 max-w-xl text-white/70 text-lg">Exposición clara para compras y operaciones. Las compras se cotizan a mayoreo; no es un checkout de menudeo.</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <h2 class="display text-3xl sm:text-4xl">Concentrados 2 L</h2>
            <div class="mt-10 grid lg:grid-cols-2 gap-6">
                <?php foreach ($insigna as $p): ?>
                <article class="rounded-[1.6rem] bg-white p-8 border border-expert/5 io-reveal flex flex-col">
                    <div class="flex gap-6 items-start">
                        <img src="<?= e(url('assets/img/bottle-' . ($p['slug'] === 'dual' ? 'dual' : 'std') . '.svg')) ?>" alt="" class="h-44 w-auto shrink-0">
                        <div>
                            <span class="text-xs font-heading font-bold tracking-widest uppercase text-turquesa"><?= e($p['sku']) ?></span>
                            <h3 class="font-heading font-extrabold text-2xl text-expert mt-1"><?= e($p['nombre']) ?></h3>
                            <p class="text-charcoal/70 mt-2"><?= e($p['resumen']) ?></p>
                        </div>
                    </div>
                    <ul class="mt-6 flex flex-wrap gap-2">
                        <?php foreach ($p['claims'] as $c): ?>
                            <li class="rounded-full bg-hielo px-3 py-1 text-xs font-heading font-semibold text-expert"><?= e($c) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <p class="mt-4 text-sm font-heading font-semibold text-expert"><?= e($p['rendimiento']) ?></p>
                    <div class="mt-auto pt-6 flex flex-wrap gap-3">
                        <a class="btn-primary" href="<?= e(url('producto.php?slug=' . $p['slug'])) ?>">Ver ficha</a>
                        <a class="btn-outline" href="<?= e(url('contacto.php')) ?>">Cotizar</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <h2 class="display text-3xl sm:text-4xl">Accesorios del sistema</h2>
            <div class="mt-10 grid md:grid-cols-2 gap-6">
                <?php foreach ($accesorios as $p): ?>
                <article class="rounded-[1.6rem] bg-arena p-8 io-reveal">
                    <img src="<?= e(url('assets/img/' . ($p['slug'] === 'porron' ? 'porron' : 'atomizador') . '.svg')) ?>" alt="" class="h-36">
                    <h3 class="mt-4 font-heading font-extrabold text-2xl text-expert"><?= e($p['nombre']) ?></h3>
                    <p class="mt-2 text-charcoal/70"><?= e($p['resumen']) ?></p>
                    <p class="mt-3 text-sm font-heading font-semibold"><?= e($p['rendimiento']) ?></p>
                    <a class="btn-outline mt-6" href="<?= e(url('producto.php?slug=' . $p['slug'])) ?>">Detalle</a>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="retornable" class="py-16 lg:py-24 bg-expert text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div class="io-reveal">
                <p class="eyebrow text-aqua">Proceso de envases retornables</p>
                <h2 class="display mt-3 text-3xl sm:text-5xl text-white">Vacío no es basura. Es el siguiente llenado.</h2>
                <ol class="mt-8 space-y-4 text-white/80">
                    <li><strong class="text-aqua">1.</strong> Recibes bidones de 2 L y, si aplica, porrón y atomizadores.</li>
                    <li><strong class="text-aqua">2.</strong> Diluyes en sitio (agua primero).</li>
                    <li><strong class="text-aqua">3.</strong> En el reabastecimiento recolectamos los 2 L vacíos.</li>
                    <li><strong class="text-aqua">4.</strong> Se higienizan y rellenan para el siguiente ciclo.</li>
                </ol>
            </div>
            <div class="rounded-[1.6rem] bg-white/10 border border-white/10 p-8 io-reveal">
                <p class="font-heading font-extrabold text-2xl">Comparativa rápida</p>
                <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-aqua font-heading font-bold mb-2">Estándar</p>
                        <p class="text-white/75">Limpieza + desinfección + aroma insignia. Rutina diaria de superficies duras.</p>
                    </div>
                    <div>
                        <p class="text-aqua font-heading font-bold mb-2">Dual</p>
                        <p class="text-white/75">Suma neutralización de olores de raíz. Textiles, humedad, humo, mascotas, alimentos.</p>
                    </div>
                </div>
                <a class="btn-primary mt-8" href="<?= e(url('contacto.php')) ?>">Pedir prueba comparativa</a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
