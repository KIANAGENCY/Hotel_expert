<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Cómo Funciona el Marketing Olfativo en Hoteles | Sistema ELAH';
$page_description = 'Descubre cómo el Sistema ELAH integra limpieza biodegradable, aromatización y presencia continua en un solo sistema de marketing olfativo para hoteles en México.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="relative py-20 lg:py-28 bg-expert text-white overflow-hidden noise">
        <div class="absolute inset-0 grid-dots opacity-30"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6">
            <p class="eyebrow text-aqua">Cómo funciona</p>
            <h1 class="display mt-3 text-4xl sm:text-6xl text-white max-w-4xl">Una identidad olfativa en cada punto de contacto.</h1>
            <p class="mt-6 max-w-2xl text-xl text-white/70">ELAH integra la limpieza operativa, el refuerzo ambiental y la presencia continua bajo el mismo aroma insignia.</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <h2 class="display text-3xl sm:text-5xl max-w-4xl">¿Cómo funciona el marketing olfativo en un hotel?</h2>
            <p class="mt-5 max-w-4xl text-lg text-charcoal/70 leading-relaxed">El marketing olfativo hotelero combina limpieza biodegradable con un aroma de marca (odotipo) que se repite en cada punto de contacto del huésped. El Sistema ELAH de Hotel Expert integra estas tres capas —limpieza, refuerzo ambiental y presencia continua— bajo un mismo aroma insignia, sin que tu equipo de operaciones tenga que gestionarlo por separado.</p>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <p class="eyebrow">El sistema completo</p>
            <h2 class="display mt-3 text-3xl sm:text-5xl">Tres capas. Un solo aroma.</h2>
            <div class="mt-12 grid lg:grid-cols-3 gap-6">
                <?php
                $capas = [
                    ['01', 'Motor diario', '¿Qué hace el multiusos concentrado?', 'Limpia, desinfecta y aromatiza en cada uso. Es el motor del sistema para áreas abiertas y de alto tránsito.'],
                    ['02', 'Refuerzo puntual', '¿Para qué sirve el spray ambiental?', 'Refuerza el aroma insignia en momentos y zonas específicas, como el check-in o una habitación recién liberada.'],
                    ['03', 'Presencia constante', '¿Cómo mantiene el difusor la presencia constante?', 'Difunde el aroma de marca de forma pasiva y continua en lobby, pasillos y espacios compactos, sin intervención diaria del personal.'],
                ];
                foreach ($capas as $c): ?>
                <article class="elah-card bg-white p-8 io-reveal">
                    <span class="font-heading font-extrabold text-5xl text-aqua"><?= e($c[0]) ?></span>
                    <p class="eyebrow mt-6"><?= e($c[1]) ?></p>
                    <h3 class="mt-2 font-heading font-extrabold text-2xl text-expert"><?= e($c[2]) ?></h3>
                    <p class="mt-3 text-charcoal/70"><?= e($c[3]) ?></p>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <p class="eyebrow">Multiusos concentrado</p>
                <h2 class="display mt-3 text-3xl sm:text-5xl">Más rendimiento. Menos agua transportada.</h2>
                <p class="mt-5 text-lg text-charcoal/70">Los concentrados se mezclan en el hotel. Esto reduce plástico, transporte de agua y espacio de almacenamiento.</p>
                <div class="mt-8 grid sm:grid-cols-2 gap-5">
                    <div class="elah-card bg-hielo p-6">
                        <p class="font-heading font-extrabold text-3xl text-turquesa">1 L</p>
                        <p class="mt-2 font-heading font-bold text-expert">100 ml + 900 ml</p>
                        <p class="mt-2 text-sm text-charcoal/60">Para una carga lista para usar.</p>
                    </div>
                    <div class="elah-card bg-hielo p-6">
                        <p class="font-heading font-extrabold text-3xl text-turquesa">20 L</p>
                        <p class="mt-2 font-heading font-bold text-expert">2 L + 18 L</p>
                        <p class="mt-2 text-sm text-charcoal/60">Un bidón completo rinde 20 litros.</p>
                    </div>
                </div>
            </div>
            <div class="rounded-[1.75rem] bg-expert text-white p-8 sm:p-10">
                <p class="eyebrow text-aqua">Cobertura operativa</p>
                <h2 class="font-heading font-extrabold text-3xl mt-3">Prácticamente todo el hotel.</h2>
                <div class="mt-7 flex flex-wrap gap-2">
                    <?php foreach (['Pisos', 'Mármol', 'Granito', 'Acero', 'Baños', 'Cromo', 'Madera', 'Tela', 'Sillones', 'Alfombras'] as $s): ?>
                        <span class="rounded-full bg-white/10 px-3 py-1.5 text-sm"><?= e($s) ?></span>
                    <?php endforeach; ?>
                </div>
                <p class="mt-8 rounded-2xl border border-aqua/30 bg-aqua/10 p-5 text-lg"><strong class="text-aqua">Única excepción:</strong> no usar en vidrio.</p>
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-arena">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <p class="eyebrow">El producto correcto en cada zona</p>
            <h2 class="display mt-3 text-3xl sm:text-5xl">Estándar en áreas abiertas. Dual en habitaciones.</h2>
            <div class="mt-12 grid lg:grid-cols-2 gap-6">
                <article class="elah-card bg-white p-8 sm:p-10">
                    <p class="eyebrow">Hotel Expert Estándar</p>
                    <h3 class="font-heading font-extrabold text-3xl text-expert mt-3">Alto tránsito</h3>
                    <p class="mt-4 text-charcoal/70">Limpia, desinfecta y deja el aroma insignia sin neutralizador. Ideal para lobby, pasillos, restaurante, gimnasio y oficinas.</p>
                    <a class="btn-outline mt-7" href="<?= e(url('producto.php?slug=estandar')) ?>">Ver Estándar</a>
                </article>
                <article class="elah-card bg-expert text-white p-8 sm:p-10">
                    <p class="eyebrow text-aqua">Hotel Expert Dual</p>
                    <h3 class="font-heading font-extrabold text-3xl mt-3">Espacios cerrados</h3>
                    <p class="mt-4 text-white/70">Elimina malos olores en su origen. Ideal para habitaciones, baños, textiles y alfombras que retienen humedad.</p>
                    <a class="btn-primary mt-7" href="<?= e(url('producto.php?slug=dual')) ?>">Ver Dual</a>
                </article>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <p class="eyebrow">Dónde actúa ELAH</p>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach (['Habitaciones y baños', 'Pasillos', 'Lobby', 'Restaurante y mesas', 'Gimnasio', 'Oficinas administrativas'] as $i => $zona): ?>
                    <div class="rounded-3xl bg-hielo p-6 flex items-center gap-4">
                        <span class="font-heading font-extrabold text-aqua text-2xl">0<?= $i + 1 ?></span>
                        <span class="font-heading font-bold text-expert"><?= e($zona) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-12 text-center">
                <a class="btn-primary btn-lg" href="<?= e(url('producto.php?slug=paquete-entrada')) ?>">Instalar el sistema completo</a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
