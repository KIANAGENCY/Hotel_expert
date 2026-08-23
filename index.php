<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$productos = require __DIR__ . '/data/productos.php';
$posts = require __DIR__ . '/data/blog.php';
$page_title = 'Sistema ELAH — Limpieza y aroma estandarizado para hoteles';
$page_description = 'Sistema integral de limpieza, desinfección y aroma insignia para hoteles. Multiusos, sprays y difusores bajo una sola identidad olfativa.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido">
    <section id="inicio" data-nav-section="inicio" class="elah-hero nav-section relative min-h-[100svh] flex items-center overflow-hidden bg-expert noise">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2200&q=85"
             alt="Lobby de hotel con identidad y ambiente distintivo"
             class="absolute inset-0 h-full w-full object-cover hero-ken opacity-45">
        <div class="absolute inset-0 bg-gradient-to-r from-expert via-expert/90 to-expert/20"></div>
        <div class="absolute inset-0 grid-dots opacity-20"></div>
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 py-44 w-full">
            <div class="max-w-4xl">
                <p class="eyebrow reveal">Sistema ELAH de Hotel Expert</p>
                <h1 class="display mt-5 text-4xl sm:text-6xl lg:text-[4.8rem] text-white reveal reveal-d1">
                    Estandarización de<br><span class="text-aqua">Limpieza y Aroma</span><br>de Hoteles.
                </h1>
                <p class="mt-7 max-w-2xl text-lg sm:text-2xl text-white/80 reveal reveal-d2">
                    Unimos dos mundos: limpieza y aromatización. Vendemos la identidad olfativa completa de tu hotel.
                </p>
                <div class="mt-9 flex flex-wrap gap-3 reveal reveal-d3">
                    <a class="btn-primary btn-lg" href="#tienda">Conocer los paquetes</a>
                    <a class="btn-ghost btn-lg" href="#como-funciona">Cómo funciona</a>
                </div>
                <div class="mt-12 flex flex-wrap gap-x-8 gap-y-3 text-sm text-white/65 font-heading reveal reveal-d4">
                    <span>Envío nacional</span>
                    <span>100% biodegradable</span>
                    <span>Precios + IVA</span>
                </div>
            </div>
        </div>
    </section>

    <section id="tienda" data-nav-section="tienda" class="nav-section py-20 lg:py-28 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <div class="max-w-3xl io-reveal">
                    <p class="eyebrow">Tienda ELAH</p>
                    <h2 class="display mt-3 text-3xl sm:text-5xl">Prueba. Instala. Reabastece.</h2>
                    <p class="mt-5 text-lg text-charcoal/70">Comienza con una prueba en tu hotel o instala el sistema completo con multiusos, difusores, spray y aroma.</p>
                </div>
                <a class="btn-outline io-reveal" href="<?= e(url('catalogo.php')) ?>">Ver toda la tienda</a>
            </div>
            <div class="mt-12 grid lg:grid-cols-3 gap-6">
                <article class="elah-offer bg-white p-8 io-reveal">
                    <p class="eyebrow">Paquete Muestra</p>
                    <h3 class="font-heading font-extrabold text-2xl text-expert mt-3">Pruébalo en tu hotel</h3>
                    <p class="price-display mt-5">$1,999 <small>+ IVA</small></p>
                    <p class="mt-4 text-charcoal/70">1 Dual + 1 Estándar + 1 caja de aromas.</p>
                    <button class="btn-primary mt-7" type="button" data-cart-add="paquete-muestra">Agregar</button>
                </article>
                <article class="elah-offer bg-expert text-white p-8 ring-4 ring-aqua/30 io-reveal">
                    <span class="inline-flex rounded-full bg-aqua text-expert px-3 py-1 text-xs font-heading font-bold">SISTEMA COMPLETO</span>
                    <h3 class="font-heading font-extrabold text-2xl mt-4">Paquete de Entrada ELAH</h3>
                    <p class="price-display text-white mt-5">$8,999 <small>+ IVA</small></p>
                    <p class="mt-2 text-white/45 line-through">Precio de lista $10,496</p>
                    <p class="mt-4 text-white/70">2 Dual + 2 Estándar, difusores, spray, aroma y envío.</p>
                    <button class="btn-primary mt-7" type="button" data-cart-add="paquete-entrada">Agregar</button>
                </article>
                <article class="elah-offer bg-white p-8 io-reveal">
                    <p class="eyebrow">Reabasto</p>
                    <h3 class="font-heading font-extrabold text-2xl text-expert mt-3">Compra según tu consumo</h3>
                    <p class="mt-5 text-xl font-heading font-extrabold text-turquesa">A precio de lista</p>
                    <p class="mt-4 text-charcoal/70">Concentrados, aromas y equipos según el tamaño de tu hotel.</p>
                    <a class="btn-outline mt-7" href="<?= e(url('catalogo.php#productos')) ?>">Ver productos</a>
                </article>
            </div>
        </div>
    </section>

    <section id="como-funciona" data-nav-section="como-funciona" class="nav-section py-20 lg:py-28 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-3xl io-reveal">
                <p class="eyebrow">Cómo funciona</p>
                <h2 class="display mt-3 text-3xl sm:text-5xl">Tres capas. Un solo aroma.</h2>
                <p class="mt-5 text-lg text-charcoal/70">ELAH integra limpieza operativa, refuerzo ambiental y presencia continua bajo la misma identidad olfativa.</p>
            </div>
            <div class="mt-12 grid md:grid-cols-3 gap-5">
                <?php
                $capas = [
                    ['01', 'Motor diario', 'Multiusos concentrado', 'Limpia, desinfecta y aromatiza en cada uso.'],
                    ['02', 'Refuerzo puntual', 'Spray ambiental', 'Refuerza el aroma en zonas específicas.'],
                    ['03', 'Presencia constante', 'Difusor eléctrico', 'Aromatización pasiva y continua.'],
                ];
                foreach ($capas as $c): ?>
                    <article class="elah-card bg-hielo p-8 io-reveal">
                        <span class="text-aqua font-heading font-extrabold text-4xl"><?= e($c[0]) ?></span>
                        <p class="eyebrow mt-6"><?= e($c[1]) ?></p>
                        <h3 class="mt-2 font-heading font-extrabold text-2xl text-expert"><?= e($c[2]) ?></h3>
                        <p class="mt-3 text-charcoal/70"><?= e($c[3]) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="mt-12 rounded-[1.75rem] bg-expert text-white p-8 sm:p-10 grid md:grid-cols-3 gap-8 items-center io-reveal">
                <div>
                    <p class="font-heading font-extrabold text-4xl text-aqua">2 L → 20 L</p>
                    <p class="mt-2 text-white/65">2 L de concentrado + 18 L de agua.</p>
                </div>
                <div>
                    <p class="font-heading font-extrabold text-4xl text-aqua">100%</p>
                    <p class="mt-2 text-white/65">Biodegradable y eficiente.</p>
                </div>
                <a class="btn-primary justify-center" href="<?= e(url('como-funciona.php')) ?>">Ver funcionamiento completo</a>
            </div>
        </div>
    </section>

    <section id="nosotros" data-nav-section="nosotros" class="nav-section py-20 lg:py-28 bg-expert text-white relative overflow-hidden">
        <div class="absolute inset-0 grid-dots opacity-35"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div class="io-reveal">
                <p class="eyebrow text-aqua">Nosotros</p>
                <h2 class="display mt-3 text-3xl sm:text-5xl text-white">No son productos separados. Es el Sistema ELAH.</h2>
                <p class="mt-6 text-lg text-white/70">Resolvemos bajo una sola oferta dos necesidades del hotel: la limpieza de sus instalaciones y la ambientación aromática de su marca.</p>
                <p class="mt-4 text-lg text-white/70">Funciona para hoteles independientes y cadenas que buscan una experiencia consistente entre habitaciones, áreas y propiedades.</p>
                <a class="btn-ghost mt-8" href="<?= e(url('nosotros.php')) ?>">Conocer Hotel Expert</a>
            </div>
            <div class="grid sm:grid-cols-2 gap-5 io-reveal">
                <article class="elah-compare bg-white text-charcoal p-7">
                    <p class="eyebrow">Estándar</p>
                    <h3 class="font-heading font-extrabold text-xl text-expert mt-2">Áreas abiertas</h3>
                    <p class="mt-4 text-charcoal/70">Limpia, desinfecta y deja el aroma insignia en zonas de alto tránsito.</p>
                </article>
                <article class="elah-compare bg-aqua text-expert p-7">
                    <p class="eyebrow !text-expert">Dual</p>
                    <h3 class="font-heading font-extrabold text-xl mt-2">Habitaciones</h3>
                    <p class="mt-4 text-expert/75">Elimina malos olores en su origen, especialmente en textiles y espacios cerrados.</p>
                </article>
            </div>
        </div>
    </section>

    <section id="blog" data-nav-section="blog" class="nav-section py-20 lg:py-28 bg-arena">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <div class="io-reveal">
                    <p class="eyebrow">Blog y recursos</p>
                    <h2 class="display mt-3 text-3xl sm:text-5xl">Conoce mejor el Sistema ELAH.</h2>
                </div>
                <a class="btn-outline io-reveal" href="<?= e(url('blog.php')) ?>">Ver todos los recursos</a>
            </div>
            <div class="mt-12 grid md:grid-cols-3 gap-6">
                <?php foreach (array_slice($posts, 0, 3) as $post): ?>
                    <article class="product-card bg-white overflow-hidden io-reveal">
                        <img src="<?= e($post['cover']) ?>" alt="" class="h-48 w-full object-cover">
                        <div class="p-6">
                            <p class="eyebrow"><?= e($post['categoria']) ?></p>
                            <h3 class="mt-3 font-heading font-extrabold text-xl text-expert"><?= e($post['titulo']) ?></h3>
                            <p class="mt-3 text-charcoal/65"><?= e($post['extracto']) ?></p>
                            <a class="btn-outline mt-6" href="<?= e(url('articulo.php?slug=' . $post['slug'])) ?>">Leer artículo</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="contacto" data-nav-section="contacto" class="nav-section relative py-24 overflow-hidden bg-brand">
        <div class="mx-auto max-w-4xl px-4 text-center text-white">
            <p class="eyebrow text-aqua io-reveal">Contacto</p>
            <h2 class="display mt-3 text-3xl sm:text-5xl text-white io-reveal">Instala la identidad olfativa completa.</h2>
            <p class="mt-5 text-xl text-white/70 io-reveal">Diseñamos el sistema según las áreas, habitaciones y necesidades de tu hotel.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3 io-reveal">
                <a class="btn-light btn-lg" href="<?= e(url('contacto.php')) ?>">Hablar con un asesor</a>
                <a class="btn-ghost btn-lg" href="https://wa.me/<?= WHATSAPP ?>?text=<?= WHATSAPP_MSG ?>" target="_blank" rel="noopener">WhatsApp <?= WHATSAPP_DISPLAY ?></a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
