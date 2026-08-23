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
$page = 'catalogo';
$page_title = $p['nombre'] . ' — Sistema ELAH';
$page_description = $p['resumen'];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="py-12 lg:py-20 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <nav class="text-sm text-charcoal/50 mb-8" aria-label="Ruta">
                <a class="hover:text-turquesa" href="<?= e(url('catalogo.php')) ?>">Tienda</a>
                <span class="mx-2">/</span>
                <span><?= e($p['nombre']) ?></span>
            </nav>
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="product-visual min-h-[30rem] bg-white shadow-glass">
                    <?php if ($p['imagen']): ?>
                        <img src="<?= e(url('assets/img/' . $p['imagen'])) ?>" alt="<?= e($p['nombre']) ?>" class="!max-h-[25rem] bottle-float">
                    <?php else: ?>
                        <span class="product-monogram !text-7xl"><?= e($p['icono']) ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <p class="eyebrow"><?= e($p['categoria']) ?> · <?= e($p['sku']) ?></p>
                    <h1 class="display mt-3 text-4xl sm:text-6xl"><?= e($p['nombre']) ?></h1>
                    <p class="mt-4 text-xl text-turquesa font-heading font-semibold"><?= e($p['subtitulo']) ?></p>
                    <p class="mt-5 text-lg text-charcoal/70 leading-relaxed"><?= e($p['resumen']) ?></p>
                    <div class="mt-7 flex flex-wrap gap-2">
                        <?php foreach ($p['claims'] as $claim): ?>
                            <span class="rounded-full bg-white border border-expert/10 px-3 py-1.5 text-xs font-heading font-bold text-expert"><?= e($claim) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-9 border-y border-expert/10 py-7">
                        <p class="price-display"><?= e($p['precio_texto']) ?> <small>+ IVA</small></p>
                        <?php if (!empty($p['precio_lista'])): ?>
                            <p class="mt-2 text-charcoal/45 line-through">Precio de lista <?= e($p['precio_lista']) ?></p>
                        <?php endif; ?>
                        <p class="mt-3 font-heading font-semibold text-expert"><?= e($p['presentacion']) ?> · <?= e($p['rendimiento']) ?></p>
                    </div>
                    <div class="mt-7 flex flex-wrap items-center gap-3">
                        <div class="quantity-control" aria-label="Cantidad">
                            <button type="button" data-qty-minus aria-label="Reducir cantidad">−</button>
                            <input id="product-qty" type="number" min="1" max="99" value="1" aria-label="Cantidad">
                            <button type="button" data-qty-plus aria-label="Aumentar cantidad">+</button>
                        </div>
                        <button class="btn-primary btn-lg" type="button" data-cart-add="<?= e($slug) ?>" data-quantity-source="product-qty">Agregar a cotización</button>
                        <a class="btn-outline btn-lg" href="<?= e(url('cotizacion.php')) ?>">Ver cotización</a>
                    </div>
                    <p class="mt-4 text-sm text-charcoal/50">La solicitud no genera un cobro. Confirmaremos disponibilidad, IVA y entrega antes de cerrar el pedido.</p>
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
                <a class="btn-primary mt-7" href="<?= e(url('como-funciona.php')) ?>">Cómo funciona ELAH</a>
            </aside>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
