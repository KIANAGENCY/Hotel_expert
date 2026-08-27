<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$productos = require __DIR__ . '/data/productos.php';
$slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) ($_GET['slug'] ?? '')));
if (!isset($productos[$slug])) {
    http_response_code(404);
    $page_title = 'Producto no encontrado — Hotel Expert';
    require __DIR__ . '/includes/head.php';
    require __DIR__ . '/includes/header.php';
    echo '<main id="contenido" class="pt-40 pb-24 px-4 text-center"><h1 class="display text-4xl">Producto no encontrado</h1><a class="btn-primary mt-6" href="' . e(url('catalogo.php')) . '">Volver a la tienda</a></main>';
    require __DIR__ . '/includes/footer.php';
    exit;
}
$p = $productos[$slug];
$page = 'productos';
if ($slug === 'estandar') {
    $page_title = 'Hotel Expert: limpieza profesional con el aroma insignia de tu hotel';
    $page_description = 'Hotel Expert limpia, desinfecta y aromatiza con concentrado 1:9 para múltiples superficies del hotel. Solicita una muestra.';
} elseif ($slug === 'dual') {
    $page_title = 'Hotel Expert Dual: limpieza profesional y neutralización de malos olores';
    $page_description = 'Hotel Expert Dual limpia, desinfecta, aromatiza y neutraliza malos olores en habitaciones y espacios cerrados.';
} else {
    $page_title = $p['nombre'] . ' — Sistema ELAH';
    $page_description = $p['resumen'];
}
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="py-12 lg:py-20 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <nav class="text-sm text-charcoal/50 mb-8" aria-label="Ruta de navegación">
                <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <li><a class="hover:text-turquesa" href="<?= e(url('')) ?>">Inicio</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a class="hover:text-turquesa" href="<?= e(url('productos/')) ?>">Productos</a></li>
                    <li aria-hidden="true">/</li>
                    <li><span class="text-charcoal/70"><?= e($p['nombre']) ?></span></li>
                </ol>
            </nav>
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="product-visual min-h-[30rem] bg-white shadow-glass">
                    <?php if ($p['imagen']): ?>
                        <img src="<?= e(url('assets/img/' . $p['imagen'])) ?>" alt="<?= e($p['alt'] ?? $p['nombre']) ?>" class="!max-h-[25rem] bottle-float">
                    <?php else: ?>
                        <span class="product-monogram !text-7xl"><?= e($p['icono']) ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <p class="eyebrow"><?= e($p['categoria']) ?> · <?= e($p['sku']) ?></p>
                    <h1 class="display mt-3 text-4xl sm:text-6xl">
                        <?php if ($slug === 'estandar'): ?>
                            Hotel Expert: limpieza profesional con el aroma insignia de tu hotel
                        <?php elseif ($slug === 'dual'): ?>
                            Hotel Expert Dual: limpieza profesional y neutralización de malos olores
                        <?php else: ?>
                            <?= e($p['nombre']) ?>
                        <?php endif; ?>
                    </h1>
                    <p class="mt-4 text-xl text-turquesa font-heading font-semibold"><?= e($p['subtitulo']) ?></p>
                    <p class="mt-5 text-lg text-charcoal/70 leading-relaxed"><?= e($p['resumen']) ?></p>
                    <div class="mt-7 flex flex-wrap gap-2">
                        <?php foreach ($p['claims'] as $claim): ?>
                            <span class="rounded-full bg-white border border-expert/10 px-3 py-1.5 text-xs font-heading font-bold text-expert"><?= e($claim) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-9 border-y border-expert/10 py-7">
                        <p class="font-heading text-xl font-bold text-expert"><?= e($p['presentacion']) ?></p>
                        <p class="mt-2 text-charcoal/65"><?= e($p['rendimiento']) ?></p>
                    </div>
                    <div class="mt-7 flex flex-wrap gap-3">
                        <a class="btn-primary btn-lg" href="<?= e(url('contacto/?tipo=muestra')) ?>">Solicitar muestra</a>
                        <a class="btn-outline btn-lg" href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener noreferrer">Hablar con un asesor</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-12 gap-8">
            <article class="lg:col-span-7 elah-card p-8 sm:p-10 bg-white">
                <p class="eyebrow">Ficha comercial</p>
                <h2 class="display mt-3 text-3xl">Diseñado para operar como sistema.</h2>
                <dl class="mt-8 divide-y divide-expert/10">
                    <div class="py-4 grid sm:grid-cols-3 gap-2"><dt class="font-heading font-bold text-expert">Función</dt><dd class="sm:col-span-2 text-charcoal/70"><?= e($p['funcion']) ?></dd></div>
                    <div class="py-4 grid sm:grid-cols-3 gap-2"><dt class="font-heading font-bold text-expert">Presentación</dt><dd class="sm:col-span-2 text-charcoal/70"><?= e($p['presentacion']) ?></dd></div>
                    <div class="py-4 grid sm:grid-cols-3 gap-2"><dt class="font-heading font-bold text-expert">Rendimiento</dt><dd class="sm:col-span-2 text-charcoal/70"><?= e($p['rendimiento']) ?></dd></div>
                    <div class="py-4 grid sm:grid-cols-3 gap-2"><dt class="font-heading font-bold text-expert">Especialidad</dt><dd class="sm:col-span-2 text-charcoal/70"><?= e($p['especialidad']) ?></dd></div>
                </dl>
            </article>
            <aside class="lg:col-span-5 rounded-[1.75rem] bg-expert text-white p-8 sm:p-10">
                <p class="eyebrow text-aqua">Sistema ELAH</p>
                <h2 class="font-heading font-extrabold text-3xl mt-3">Una sola identidad en cada punto de contacto.</h2>
                <?php if ($p['superficies']): ?>
                    <p class="mt-6 font-heading font-bold text-aqua">Superficies aptas</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <?php foreach ($p['superficies'] as $s): ?>
                            <span class="rounded-full bg-white/10 px-3 py-1 text-sm"><?= e($s) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($p['no_usar']): ?>
                    <p class="mt-7 rounded-2xl border border-aqua/30 bg-aqua/10 p-4"><strong class="text-aqua">Excepción:</strong> no usar en <?= e(implode(' ni ', $p['no_usar'])) ?>.</p>
                <?php endif; ?>
                <a class="btn-primary mt-7" href="<?= e(url('sistema-elah/')) ?>">Cómo funciona ELAH</a>
            </aside>
        </div>
    </section>

    <?php if ($slug === 'estandar'): ?>
    <section class="py-16 lg:py-24 bg-hielo">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 space-y-12">
            <article>
                <h2 class="display text-3xl">Limpia, desinfecta y aromatiza</h2>
                <p class="mt-4 text-lg text-charcoal/70">Hotel Expert limpia, desinfecta e incorpora el aroma insignia del hotel en cada aplicación.</p>
            </article>
            <article>
                <h2 class="display text-3xl">Concentrado 1:9</h2>
                <p class="mt-4 text-lg text-charcoal/70">100 ml de producto + 900 ml de agua producen 1 litro listo para usar.</p>
            </article>
            <article>
                <h2 class="display text-3xl">Un producto, múltiples superficies</h2>
                <p class="mt-4 text-lg text-charcoal/70">Pisos, mármol, granito, acero inoxidable, baños, cromo, madera, textiles, sillones y alfombras.</p>
                <p class="mt-4 text-charcoal/70"><strong>No utilizar en vidrio.</strong></p>
            </article>
            <article>
                <h2 class="display text-3xl">Cada limpieza también aplica el aroma insignia</h2>
                <p class="mt-4 text-lg text-charcoal/70">Integra el aroma de marca en una actividad que ocurre todos los días en el hotel.</p>
            </article>
            <article>
                <h2 class="display text-3xl">¿Dónde recomendamos Hotel Expert?</h2>
                <p class="mt-4 text-lg text-charcoal/70">Especialmente en áreas abiertas y de alto tránsito dentro de la lógica actual del sistema.</p>
                <a class="btn-primary mt-6" href="<?= e(url('contacto/?tipo=muestra')) ?>">Solicitar muestra</a>
            </article>
        </div>
    </section>
    <?php elseif ($slug === 'dual'): ?>
    <section class="py-16 lg:py-24 bg-hielo">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 space-y-12">
            <article>
                <h2 class="display text-3xl">Limpia, desinfecta, aromatiza y neutraliza</h2>
                <p class="mt-4 text-lg text-charcoal/70">Hotel Expert Dual limpia, desinfecta, aromatiza e incorpora neutralización de malos olores.</p>
            </article>
            <article>
                <h2 class="display text-3xl">Cuando cubrir el olor no es suficiente</h2>
                <p class="mt-4 text-lg text-charcoal/70">Cuando el problema no es la falta de fragancia sino la presencia de malos olores, cubrirlos con un aroma más intenso no resuelve la necesidad operativa.</p>
            </article>
            <article>
                <h2 class="display text-3xl">Especialmente útil en habitaciones y espacios cerrados</h2>
                <p class="mt-4 text-lg text-charcoal/70">Dual se orienta a espacios donde los olores pueden retenerse y la percepción del huésped es crítica.</p>
            </article>
            <article>
                <h2 class="display text-3xl">Textiles, alfombras y tapicería</h2>
                <p class="mt-4 text-lg text-charcoal/70">Apoyo específico para el control de malos olores en superficies textiles dentro de la operación de limpieza.</p>
            </article>
            <article>
                <h2 class="display text-3xl">También lleva el aroma insignia de tu hotel</h2>
                <p class="mt-4 text-lg text-charcoal/70">Además de neutralizar, Dual incorpora el aroma insignia del establecimiento.</p>
                <a class="btn-primary mt-6" href="<?= e(url('contacto/?tipo=muestra')) ?>">Probar Hotel Expert Dual</a>
            </article>
        </div>
    </section>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>



