<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$productos = require __DIR__ . '/data/productos.php';
$page_title = 'Hotel Expert — Frescura que se siente. Marca que se recuerda.';
$page_description = 'Sistema B2B de limpieza, desinfección e identidad olfativa para hoteles boutique. Concentrado 100% biodegradable en envase de 2 litros retornable.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido">
    <section class="relative min-h-[100svh] flex items-end overflow-hidden bg-expert noise">
        <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=2000&q=80"
             alt="Lobby de hotel boutique"
             class="absolute inset-0 h-full w-full object-cover hero-ken opacity-60"
             width="2000" height="1333">
        <div class="absolute inset-0 bg-gradient-to-t from-expert via-expert/70 to-expert/30"></div>
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 pb-20 pt-36 w-full">
            <p class="eyebrow text-aqua reveal">Sistema profesional B2B · Hospitalidad</p>
            <h1 class="display mt-4 max-w-4xl text-4xl sm:text-6xl lg:text-[4.6rem] text-white reveal reveal-d1">
                Frescura que se siente.<br><span class="text-aqua">Marca que se recuerda.</span>
            </h1>
            <p class="mt-6 max-w-xl text-lg sm:text-xl text-white/80 reveal reveal-d2">
                Más que limpieza, estandarizamos experiencias. Un concentrado 100% biodegradable que limpia, desinfecta y refuerza el aroma insignia del hotel en una sola rutina.
            </p>
            <div class="mt-8 flex flex-wrap gap-3 reveal reveal-d3">
                <a class="btn-primary" href="<?= e(url('contacto.php')) ?>">Solicitar cotización</a>
                <a class="btn-ghost" href="<?= e(url('catalogo.php')) ?>">Ver catálogo</a>
                <a class="btn-ghost" href="<?= e(url('como-funciona.php')) ?>">Manual de dilución</a>
            </div>
            <dl class="mt-14 grid grid-cols-2 sm:grid-cols-4 gap-6 max-w-3xl text-white reveal reveal-d4">
                <div>
                    <dt class="text-3xl font-heading font-extrabold text-aqua" data-count="2" data-suffix=" L">2 L</dt>
                    <dd class="text-sm text-white/65 mt-1">Envase concentrado retornable</dd>
                </div>
                <div>
                    <dt class="text-3xl font-heading font-extrabold text-aqua" data-count="20" data-suffix=" L">20 L</dt>
                    <dd class="text-sm text-white/65 mt-1">Producto listo para usar</dd>
                </div>
                <div>
                    <dt class="text-3xl font-heading font-extrabold text-aqua">100%</dt>
                    <dd class="text-sm text-white/65 mt-1">Biodegradable</dd>
                </div>
                <div>
                    <dt class="text-3xl font-heading font-extrabold text-aqua">1</dt>
                    <dd class="text-sm text-white/65 mt-1">Rutina: limpia + aroma</dd>
                </div>
            </dl>
        </div>
    </section>

    <div class="bg-expert py-4 overflow-hidden border-y border-white/5">
        <div class="marquee">
            <div class="marquee-track text-aqua/90 font-heading font-semibold text-sm tracking-[0.18em] uppercase">
                <?php for ($i = 0; $i < 2; $i++): ?>
                    <span>100% biodegradable</span><span>·</span>
                    <span>Envase 2 L retornable</span><span>·</span>
                    <span>2 L ? 20 L</span><span>·</span>
                    <span>Aroma insignia</span><span>·</span>
                    <span>Nunca en vidrio ni espejos</span><span>·</span>
                    <span>Agua primero, concentrado después</span><span>·</span>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <section class="bg-hielo py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-2xl io-reveal">
                <p class="eyebrow">Propuesta de valor B2B</p>
                <h2 class="display mt-3 text-3xl sm:text-5xl">Limpieza + identidad olfativa, sin fragmentar el carrito.</h2>
                <p class="mt-4 text-lg text-charcoal/70">El huésped no separa higiene de marca. Housekeeping tampoco debería hacerlo. Hotel Expert concentra ambas en un solo protocolo medible.</p>
            </div>
            <div class="mt-12 grid lg:grid-cols-3 gap-5">
                <article class="bento lg:col-span-2 min-h-[280px] bg-expert text-white p-8 sm:p-10 io-reveal">
                    <p class="text-aqua text-xs font-heading font-bold tracking-[0.2em] uppercase">01 · Limpieza y desinfección</p>
                    <h3 class="mt-4 font-heading font-extrabold text-3xl max-w-md">Multitarget sobre pisos, mármol, acero, madera y baños.</h3>
                    <p class="mt-4 max-w-lg text-white/70">Una dilución. Un tiempo de contacto. Un estándar entre la 201 y la 412. Sin improvisar químicos en el turno de las 14:00.</p>
                </article>
                <article class="bento min-h-[280px] bg-white p-8 io-reveal border border-expert/5">
                    <p class="eyebrow">02 · Aroma insignia</p>
                    <h3 class="mt-4 font-heading font-extrabold text-2xl text-expert">Cada trapeado refuerza la firma, no la apaga.</h3>
                    <p class="mt-3 text-charcoal/70">El olfato es memoria. Si el ambientador pelea con el cloro, no hay marca: hay ruido.</p>
                </article>
                <article class="bento bg-arena p-8 io-reveal">
                    <p class="eyebrow">03 · Una sola rutina</p>
                    <h3 class="mt-3 font-heading font-extrabold text-2xl text-expert">Menos decisiones en el carrito.</h3>
                    <p class="mt-3 text-charcoal/70">Menos productos extra significan menos sobredosis, menos piso pegajoso y más consistencia operativa.</p>
                </article>
                <article class="bento lg:col-span-2 relative min-h-[240px] io-reveal">
                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=1400&q=80" alt="Habitación de hotel" class="absolute inset-0 h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-expert/90 to-expert/20"></div>
                    <div class="relative p-8 sm:p-10 text-white max-w-lg">
                        <p class="text-aqua text-xs font-heading font-bold tracking-[0.2em] uppercase">Hospitalidad boutique</p>
                        <h3 class="mt-3 font-heading font-extrabold text-2xl sm:text-3xl">Diseñado para propiedades que venden experiencia, no litros de químico.</h3>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <div class="io-reveal">
                    <p class="eyebrow">Catálogo insignia</p>
                    <h2 class="display mt-3 text-3xl sm:text-5xl">Dos concentrados.<br>Un sistema.</h2>
                </div>
                <a class="btn-outline io-reveal" href="<?= e(url('catalogo.php')) ?>">Ir al catálogo completo</a>
            </div>
            <div class="mt-12 grid md:grid-cols-2 gap-6">
                <?php foreach (['estandar', 'dual'] as $slug): $p = $productos[$slug]; ?>
                <article class="group rounded-[1.6rem] border border-expert/10 bg-hielo p-6 sm:p-8 io-reveal hover:shadow-lift transition">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="inline-flex rounded-full bg-eco/15 text-eco px-3 py-1 text-xs font-heading font-bold">100% biodegradable</span>
                            <h3 class="mt-4 font-heading font-extrabold text-2xl text-expert"><?= e($p['nombre']) ?></h3>
                            <p class="mt-1 text-turquesa font-heading font-semibold"><?= e($p['subtitulo']) ?></p>
                        </div>
                        <img src="<?= e(url('assets/img/bottle-' . ($slug === 'dual' ? 'dual' : 'std') . '.svg')) ?>" alt="" class="h-36 w-auto bottle-float">
                    </div>
                    <p class="mt-4 text-charcoal/70"><?= e($p['resumen']) ?></p>
                    <p class="mt-3 text-sm font-heading font-semibold text-expert"><?= e($p['rendimiento']) ?></p>
                    <a class="btn-primary mt-6" href="<?= e(url('producto.php?slug=' . $slug)) ?>">Ficha técnica</a>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="relative py-20 lg:py-28 bg-expert text-white overflow-hidden noise">
        <div class="absolute inset-0 grid-dots opacity-40"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6">
            <p class="eyebrow text-aqua io-reveal">Rendimiento</p>
            <h2 class="display mt-3 text-3xl sm:text-5xl text-white io-reveal">2 litros de concentrado.<br>20 litros de operación.</h2>
            <p class="mt-4 max-w-xl text-white/70 io-reveal">No transportamos agua. El hotel diluye en sitio y obtiene diez veces el volumen, con la proporción que conserva desinfección y aroma insignia.</p>
            <div class="mt-14 grid md:grid-cols-3 gap-6 items-stretch">
                <div class="rounded-3xl bg-white/5 border border-white/10 p-8 text-center io-reveal">
                    <img src="<?= e(url('assets/img/bottle-std.svg')) ?>" alt="Concentrado 2 litros" class="mx-auto h-40">
                    <p class="mt-4 font-heading font-extrabold text-xl">2 L concentrado</p>
                    <p class="text-white/60 text-sm">1 envase retornable</p>
                </div>
                <div class="rounded-3xl bg-white/5 border border-white/10 p-8 text-center io-reveal flex flex-col justify-center">
                    <p class="text-aqua font-heading font-extrabold text-5xl">+</p>
                    <p class="mt-2 font-heading font-extrabold text-xl">18 L de agua</p>
                    <p class="text-white/60 text-sm mt-2">Siempre el agua primero. Después el concentrado. Evita espuma excesiva.</p>
                </div>
                <div class="rounded-3xl bg-turquesa p-8 text-center io-reveal">
                    <img src="<?= e(url('assets/img/porron.svg')) ?>" alt="Porrón 20 litros" class="mx-auto h-40">
                    <p class="mt-4 font-heading font-extrabold text-xl">20 L listos</p>
                    <p class="text-white/80 text-sm">O 20 cargas de atomizador de 1 L (100 ml + 900 ml)</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-arena">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-2xl io-reveal">
                <p class="eyebrow">Ciclo sustentable</p>
                <h2 class="display mt-3 text-3xl sm:text-5xl">El bidón vuelve. El agua se queda.</h2>
                <p class="mt-4 text-lg text-charcoal/70">Sistema concentrado en envase de 2 litros retornable y reutilizable: menos plástico, menos flete de agua, misma ficha técnica.</p>
            </div>
            <ol class="mt-12 grid md:grid-cols-3 gap-6">
                <?php
                $ciclo = [
                    ['01', 'Usas', 'Diluyes en porrón o atomizador y operas el protocolo de housekeeping.'],
                    ['02', 'Recolectamos', 'En el siguiente pedido se recogen los bidones de 2 L vacíos.'],
                    ['03', 'Rehacemos', 'Se higienizan y rellenan. El envase sigue en ciclo; el residuo no.'],
                ];
                foreach ($ciclo as $i => $c): ?>
                <li class="rounded-[1.6rem] bg-white p-8 io-reveal border border-expert/5">
                    <span class="font-heading font-extrabold text-5xl text-aqua/80"><?= $c[0] ?></span>
                    <h3 class="mt-4 font-heading font-extrabold text-2xl text-expert"><?= e($c[1]) ?></h3>
                    <p class="mt-2 text-charcoal/70"><?= e($c[2]) ?></p>
                </li>
                <?php endforeach; ?>
            </ol>
            <div class="mt-8 inline-flex items-center gap-2 rounded-full bg-eco text-white px-4 py-2 text-sm font-heading font-semibold io-reveal">
                100% biodegradable · Envases retornables
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <p class="eyebrow io-reveal">Aplicación</p>
            <h2 class="display mt-3 text-3xl sm:text-5xl io-reveal">Donde el huésped siente el hotel.</h2>
            <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php
                $zonas = [
                    ['Lobbies', 'Primera impresión olfativa y piso de alto tráfico.', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=900&q=80'],
                    ['Cuartos', 'Estandariza la llegada a cada habitación.', 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=900&q=80'],
                    ['Baños', 'Desinfección y frescura sin improvisar químicos.', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=900&q=80'],
                    ['Textiles', 'Dual para cortinas, tapicería y alfombras.', 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?auto=format&fit=crop&w=900&q=80'],
                ];
                foreach ($zonas as $z): ?>
                <article class="group relative h-72 rounded-[1.4rem] overflow-hidden io-reveal">
                    <img src="<?= e($z[2]) ?>" alt="<?= e($z[0]) ?>" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-expert via-expert/30 to-transparent"></div>
                    <div class="absolute bottom-0 p-5 text-white">
                        <h3 class="font-heading font-extrabold text-xl"><?= e($z[0]) ?></h3>
                        <p class="text-sm text-white/75 mt-1"><?= e($z[1]) ?></p>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <p class="mt-6 text-sm text-charcoal/55 io-reveal">Excepción estricta: no usar en vidrios ni espejos.</p>
        </div>
    </section>

    <section class="py-20 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <p class="eyebrow io-reveal">Operación real</p>
            <h2 class="display mt-3 text-3xl sm:text-5xl io-reveal">Lo que gerencia y housekeeping alinean.</h2>
            <div class="mt-12 grid lg:grid-cols-3 gap-5">
                <?php
                $tes = [
                    ['Casa Luna Boutique · 28 llaves', 'El Dual cortó el olor a humedad en cortinas del patio interior. El Estándar unificó el lobby y los cuartos. Ya no compramos ambientador aparte.'],
                    ['Grand Plaza · Compras', 'El porrón de 20 L eliminó las mezclas “a ojo”. Bajaron las quejas de piso pegajoso en un mes.'],
                    ['Hotel Sierra · Ama de llaves', 'Rotular atomizadores por área fue el detalle que el equipo sí adoptó. El protocolo dejó de vivir en un PDF.'],
                ];
                foreach ($tes as $t): ?>
                <blockquote class="rounded-[1.6rem] bg-white p-8 border border-expert/5 io-reveal">
                    <p class="text-lg text-charcoal/80 leading-relaxed">“<?= e($t[1]) ?>”</p>
                    <footer class="mt-6 font-heading font-bold text-expert text-sm"><?= e($t[0]) ?></footer>
                </blockquote>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="relative py-20 overflow-hidden">
        <div class="absolute inset-0 bg-brand"></div>
        <div class="relative mx-auto max-w-4xl px-4 text-center text-white">
            <p class="eyebrow text-aqua io-reveal">Siguiente paso</p>
            <h2 class="display mt-3 text-3xl sm:text-5xl text-white io-reveal">¿Probamos en tu piso?</h2>
            <p class="mt-4 text-white/75 io-reveal">Cotización, muestra o demo operativa. WhatsApp Business o formulario: el mismo equipo B2B responde.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3 io-reveal">
                <a class="btn-light" href="<?= e(url('contacto.php')) ?>">Solicitar cotización</a>
                <a class="btn-ghost" href="https://wa.me/<?= WHATSAPP ?>?text=<?= WHATSAPP_MSG ?>" target="_blank" rel="noopener">WhatsApp <?= WHATSAPP_DISPLAY ?></a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
