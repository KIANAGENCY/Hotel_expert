<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Cómo funciona — Manual de dilución Hotel Expert';
$page_description = 'Guía ilustrada de dilución 1 L y 20 L, superficies aptas, incompatibilidad con vidrio y FAQ operativo. Agua primero, concentrado después.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="bg-arena py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <p class="eyebrow">Manual digital de uso</p>
            <h1 class="display mt-3 text-4xl sm:text-6xl max-w-3xl">Dilución exacta. Experiencia repetible.</h1>
            <p class="mt-4 max-w-xl text-lg text-charcoal/70">El aroma insignia y la desinfección dependen de la proporción. Este módulo es el protocolo que housekeeping puede colgar en el cuarto de servicio.</p>
        </div>
    </section>

    <section class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="rounded-[1.6rem] bg-expert text-white p-8 sm:p-10 flex flex-col md:flex-row gap-6 items-start io-reveal">
                <span class="text-4xl" aria-hidden="true">??</span>
                <div>
                    <h2 class="font-heading font-extrabold text-2xl">Regla de oro</h2>
                    <p class="mt-2 text-lg text-white/80">Siempre verter primero el agua en el contenedor y después el producto concentrado. Así se evita la generación excesiva de espuma.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <h2 class="display text-3xl sm:text-4xl io-reveal">Proporciones oficiales</h2>
            <div class="mt-10 grid md:grid-cols-2 gap-6">
                <article class="rounded-[1.6rem] border border-expert/10 p-8 io-reveal">
                    <img src="<?= e(url('assets/img/atomizador.svg')) ?>" alt="" class="h-28">
                    <h3 class="mt-4 font-heading font-extrabold text-2xl text-expert">Atomizador 1 litro</h3>
                    <p class="mt-3 text-3xl font-heading font-extrabold text-turquesa">100 ml + 900 ml</p>
                    <p class="mt-2 text-charcoal/70">100 ml de concentrado + 900 ml de agua. Agua primero.</p>
                </article>
                <article class="rounded-[1.6rem] bg-hielo p-8 io-reveal">
                    <img src="<?= e(url('assets/img/porron.svg')) ?>" alt="" class="h-28">
                    <h3 class="mt-4 font-heading font-extrabold text-2xl text-expert">Porrón 20 litros</h3>
                    <p class="mt-3 text-3xl font-heading font-extrabold text-turquesa">2 L + 18 L</p>
                    <p class="mt-2 text-charcoal/70">1 envase completo de concentrado + 18 litros de agua. Agua primero.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="py-16 bg-hielo">
        <div class="mx-auto max-w-3xl px-4 sm:px-6">
            <h2 class="display text-3xl io-reveal">Calculadora de mezcla</h2>
            <p class="mt-2 text-charcoal/70 io-reveal">Apoyo de piso. No sustituye la ficha; replica la razón 2:18.</p>
            <div class="mt-8 rounded-[1.6rem] bg-white p-8 shadow-glass io-reveal">
                <label class="block mb-4">
                    <span class="text-sm font-heading font-semibold">Modo</span>
                    <select id="dil-mode" class="field mt-1">
                        <option value="20l">Porrón 20 L (oficial)</option>
                        <option value="1l">Atomizador 1 L (oficial)</option>
                        <option value="custom">Ajustar litros de agua</option>
                    </select>
                </label>
                <label class="block mb-4">
                    <span class="text-sm font-heading font-semibold">Litros de agua (modo libre)</span>
                    <input id="dil-water" class="field mt-1" type="number" min="1" max="200" step="0.5" value="18">
                </label>
                <p id="dil-out" class="rounded-xl bg-hielo p-4 text-expert"></p>
                <input type="hidden" id="dil-conc">
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <h2 class="display text-3xl sm:text-4xl io-reveal">Paso a paso de aplicación</h2>
            <ol class="mt-10 grid md:grid-cols-3 gap-6">
                <?php
                $pasos = [
                    ['Rocía / Atomiza', 'Aplica directamente en la superficie, trapo o tela.'],
                    ['Talla', 'Talla suavemente con cepillo o trapo en caso de manchas en textiles.'],
                    ['Limpia', 'Retira el exceso con trapo o trapeador húmedo.'],
                ];
                foreach ($pasos as $i => $paso): ?>
                <li class="rounded-[1.4rem] bg-arena p-8 io-reveal">
                    <span class="font-heading font-extrabold text-aqua text-4xl">0<?= $i + 1 ?></span>
                    <h3 class="mt-3 font-heading font-extrabold text-xl text-expert"><?= e($paso[0]) ?></h3>
                    <p class="mt-2 text-charcoal/70"><?= e($paso[1]) ?></p>
                </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-10">
            <div class="io-reveal">
                <h2 class="display text-3xl">Superficies aptas</h2>
                <ul class="mt-6 grid grid-cols-2 gap-2">
                    <?php foreach (['Pisos', 'Mármol', 'Granito', 'Acero inoxidable', 'Cromo', 'Madera', 'Azulejos', 'Baños', 'Alfombras*', 'Cortinas*', 'Tapicería*'] as $s): ?>
                        <li class="rounded-xl bg-hielo px-4 py-3 font-heading font-semibold text-expert"><?= e($s) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p class="mt-3 text-sm text-charcoal/55">* Especialidad Dual en textiles y alto tráfico.</p>
            </div>
            <div class="rounded-[1.6rem] bg-expert text-white p-8 io-reveal">
                <h2 class="font-heading font-extrabold text-3xl">Incompatibilidad</h2>
                <p class="mt-4 text-xl text-aqua font-heading font-bold">Nunca usar en vidrio ni espejos.</p>
                <p class="mt-3 text-white/70">Es una excepción estricta del sistema. El resto de superficies listadas en ficha están diseñadas para la dilución oficial.</p>
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-hielo">
        <div class="mx-auto max-w-3xl px-4 sm:px-6">
            <h2 class="display text-3xl io-reveal">FAQ de aplicación operativa</h2>
            <div class="mt-8 space-y-3">
                <details class="faq-item rounded-2xl bg-white p-5 border border-expert/10 io-reveal" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between font-heading font-bold text-expert">
                        ¿Piso pegajoso al trapear?
                        <span class="faq-icon text-turquesa text-2xl leading-none transition">+</span>
                    </summary>
                    <p class="mt-3 text-charcoal/70">Se está utilizando demasiado producto concentrado en la mezcla. Ajustar la dosis de agua. No agregar otro químico encima.</p>
                </details>
                <details class="faq-item rounded-2xl bg-white p-5 border border-expert/10 io-reveal">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-heading font-bold text-expert">
                        ¿Por qué el Dual y no solo el Estándar?
                        <span class="faq-icon text-turquesa text-2xl leading-none transition">+</span>
                    </summary>
                    <p class="mt-3 text-charcoal/70">El Dual neutraliza olores de raíz (humedad, humo, baños, mascotas, alimentos) de forma constante y creciente con el uso. El Estándar impregna el aroma insignia en la rutina de limpieza y desinfección. Muchos hoteles usan ambos por zona.</p>
                </details>
                <details class="faq-item rounded-2xl bg-white p-5 border border-expert/10 io-reveal">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-heading font-bold text-expert">
                        Los trapos huelen a humedad después de limpiar
                        <span class="faq-icon text-turquesa text-2xl leading-none transition">+</span>
                    </summary>
                    <p class="mt-3 text-charcoal/70">Es un caso Dual: evita que trapos y trapeadores conserven olor a humedad. Revisa también que la dilución no esté sobrecargada.</p>
                </details>
                <details class="faq-item rounded-2xl bg-white p-5 border border-expert/10 io-reveal">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-heading font-bold text-expert">
                        ¿Dónde descargo el manual ilustrado?
                        <span class="faq-icon text-turquesa text-2xl leading-none transition">+</span>
                    </summary>
                    <p class="mt-3 text-charcoal/70">Esta página es el manual digital. El PDF ilustrado se comparte con clientes activos; solicítalo en contacto o por WhatsApp.</p>
                </details>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
