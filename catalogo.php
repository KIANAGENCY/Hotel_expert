<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$productos = require __DIR__ . '/data/productos.php';
$page_title = 'Tienda Sistema ELAH — Hotel Expert';
$page_description = 'Compra el Sistema ELAH, concentrados Hotel Expert, difusores, aromas y paquetes para hoteles. Precios base más IVA.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$grupos = [
    'paquetes' => ['titulo' => 'Empieza con ELAH', 'slugs' => ['paquete-muestra', 'paquete-entrada']],
    'productos' => ['titulo' => 'Reabasto y componentes', 'slugs' => ['estandar', 'dual', 'descontaminador', 'aroma-difusor', 'caja-aromas', 'difusor-pequeno', 'difusor-grande']],
];
?>
<main id="contenido" class="pt-28">
    <section class="relative bg-expert text-white py-20 sm:py-28 overflow-hidden noise">
        <div class="absolute inset-0 grid-dots opacity-30"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-12 gap-10 items-end">
            <div class="lg:col-span-8">
                <p class="eyebrow text-aqua">Tienda B2B · Sistema ELAH</p>
                <h1 class="display mt-4 text-4xl sm:text-6xl text-white">Una identidad olfativa.<br>Todo el sistema.</h1>
                <p class="mt-5 max-w-2xl text-lg text-white/70">Elige una puerta de entrada o arma el reabasto según el consumo real de tu hotel. Todos los precios son base + IVA.</p>
            </div>
            <div class="lg:col-span-4 rounded-3xl bg-white/5 border border-white/10 p-6">
                <p class="font-heading font-extrabold text-xl">Compra asistida B2B</p>
                <p class="mt-2 text-white/65">Agrega productos y cantidades. Enviaremos la cotización final con tiempos de entrega para tu ubicación.</p>
            </div>
        </div>
    </section>

    <nav class="sticky top-[5.5rem] z-30 bg-white/90 backdrop-blur-xl border-b border-expert/10" aria-label="Categorías de tienda">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 py-3 flex gap-2 overflow-x-auto">
            <a class="filter-chip is-active" href="#paquetes">Paquetes</a>
            <a class="filter-chip" href="#productos">Concentrados y aromas</a>
            <a class="filter-chip" href="#productos">Difusores</a>
            <a class="filter-chip" href="<?= e(url('cotizacion.php')) ?>">Mi cotización (<span data-cart-count>0</span>)</a>
        </div>
    </nav>

    <?php foreach ($grupos as $groupId => $grupo): ?>
    <section id="<?= e($groupId) ?>" class="py-16 lg:py-24 <?= $groupId === 'paquetes' ? 'bg-hielo' : 'bg-white' ?>">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex items-end justify-between gap-5">
                <div>
                    <p class="eyebrow"><?= $groupId === 'paquetes' ? 'Escalera de venta ELAH' : 'Compra por separado' ?></p>
                    <h2 class="display mt-3 text-3xl sm:text-5xl"><?= e($grupo['titulo']) ?></h2>
                </div>
                <?php if ($groupId === 'productos'): ?>
                    <p class="hidden md:block max-w-sm text-right text-charcoal/60">El reabasto se compra a precio de lista según consumo. Los difusores son compra única.</p>
                <?php endif; ?>
            </div>
            <div class="mt-12 grid md:grid-cols-2 <?= $groupId === 'productos' ? 'xl:grid-cols-3' : '' ?> gap-6">
                <?php foreach ($grupo['slugs'] as $slug): $p = $productos[$slug]; ?>
                <article class="product-card bg-white p-5 sm:p-6 io-reveal flex flex-col" data-product-category="<?= e($p['categoria']) ?>">
                    <a class="product-visual" href="<?= e(url('producto.php?slug=' . $slug)) ?>">
                        <?php if ($p['imagen']): ?>
                            <img src="<?= e(url('assets/img/' . $p['imagen'])) ?>" alt="<?= e($p['nombre']) ?>">
                        <?php else: ?>
                            <span class="product-monogram"><?= e($p['icono']) ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="pt-6 flex flex-col flex-1">
                        <p class="eyebrow"><?= e($p['categoria']) ?></p>
                        <h3 class="mt-2 font-heading font-extrabold text-2xl text-expert"><?= e($p['nombre']) ?></h3>
                        <p class="mt-2 text-charcoal/65"><?= e($p['subtitulo']) ?></p>
                        <div class="mt-5">
                            <p class="price-display"><?= e($p['precio_texto']) ?> <small>+ IVA</small></p>
                            <?php if (!empty($p['precio_lista'])): ?>
                                <p class="mt-2 text-sm text-charcoal/45 line-through">Precio de lista <?= e($p['precio_lista']) ?></p>
                            <?php endif; ?>
                            <p class="mt-2 text-sm font-heading font-semibold text-turquesa"><?= e($p['presentacion']) ?> · <?= e($p['rendimiento']) ?></p>
                        </div>
                        <div class="mt-auto pt-6 flex flex-wrap gap-2">
                            <button class="btn-primary" type="button" data-cart-add="<?= e($slug) ?>">Agregar</button>
                            <a class="btn-outline" href="<?= e(url('producto.php?slug=' . $slug)) ?>">Ver detalle</a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endforeach; ?>

    <section class="py-16 bg-arena">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-3 gap-6">
            <article class="elah-card bg-white p-7">
                <p class="eyebrow">Cobertura nacional</p>
                <h2 class="font-heading font-extrabold text-2xl text-expert mt-2">Enviamos a toda la República</h2>
                <p class="mt-3 text-charcoal/65">Confirmamos tiempos de entrega según tu ubicación antes de cerrar el pedido.</p>
            </article>
            <article class="elah-card bg-white p-7">
                <p class="eyebrow">Compra B2B</p>
                <h2 class="font-heading font-extrabold text-2xl text-expert mt-2">Precios base + IVA</h2>
                <p class="mt-3 text-charcoal/65">La cotización consolida productos, cantidades y datos fiscales de tu hotel.</p>
            </article>
            <article class="elah-card bg-expert text-white p-7">
                <p class="eyebrow text-aqua">¿Necesitas ayuda?</p>
                <h2 class="font-heading font-extrabold text-2xl mt-2">Diseñamos tu sistema</h2>
                <p class="mt-3 text-white/65">Un asesor te ayuda a elegir equipos y consumo según las áreas de tu hotel.</p>
                <a class="btn-primary mt-5" href="<?= e(url('contacto.php')) ?>">Hablar con ventas</a>
            </article>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
