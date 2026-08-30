<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Sistema ELAH | Limpieza + Aroma para hoteles — Hotel Expert';
$page_description = 'ELAH significa Estandarización de Limpieza y Aroma en Hoteles. Integra limpieza cotidiana, refuerzo y difusión bajo una misma identidad olfativa.';
$home_faq = [
    ['¿Qué significa ELAH?', 'ELAH significa Estandarización de Limpieza y Aroma en Hoteles.'],
    ['¿Qué es el Sistema ELAH?', 'Es el sistema de Hotel Expert para integrar limpieza y aromatización bajo una misma identidad olfativa.'],
    ['¿Hotel Expert es solamente un aromatizante?', 'No. Hotel Expert es un producto profesional concentrado que limpia, desinfecta y deja el aroma insignia del hotel.'],
    ['¿Cuál es la diferencia entre Hotel Expert y Hotel Expert Dual?', 'Ambos limpian, desinfectan y llevan el aroma insignia. Hotel Expert Dual añade neutralización de malos olores.'],
    ['¿Cómo se diluye Hotel Expert?', '100 ml de concentrado + 900 ml de agua para preparar un litro listo para usar.'],
    ['¿Se puede utilizar en vidrio?', 'No utilizar Hotel Expert en vidrio o espejos.'],
];
$structured_data = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(static fn(array $item): array => [
        '@type' => 'Question', 'name' => $item[0],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item[1]],
    ], $home_faq),
];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" data-scroll-flow>
    <section class="home-hero home-scroll-step" data-nav-section="sistema-elah">
        <div class="hero-hotel-image" role="img" aria-label="Interior contemporáneo de un hotel"></div>
        <div class="home-hero-overlay"></div>
        <div class="home-hero-content relative mx-auto grid min-h-[100svh] max-w-7xl items-start gap-12 px-4 pb-20 sm:px-6 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <h1 class="elah-hero-lockup">
                    <span class="elah-hero-sistema">Sistema</span>
                    <span class="elah-hero-elah">ELAH</span>
                </h1>
                <p class="mt-6 max-w-2xl font-heading text-xl font-bold leading-snug text-white sm:text-3xl">Estandarización de Limpieza y Aroma en Hoteles</p>
                <p class="elah-equation mt-8">Limpieza <span>+</span> Aroma <span>=</span> ELAH</p>
                <p class="mt-7 max-w-2xl text-lg leading-relaxed text-white/75 sm:text-xl">Hotel Expert integra la limpieza profesional y el aroma insignia de tu hotel dentro de un mismo sistema.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a class="btn-primary" href="<?= e(url('sistema-elah/')) ?>">Conoce el Sistema ELAH</a>
                    <a class="btn-ghost" href="<?= e(url('contacto/?tipo=muestra')) ?>">Solicita una muestra</a>
                </div>
                <p class="mt-7 text-sm font-semibold tracking-wide text-white/55">Limpieza · Aroma insignia · Estandarización</p>
            </div>
            <div class="hidden lg:col-span-5 lg:block" aria-hidden="true">
                <div class="scent-orbit"><span></span><span></span><span></span></div>
            </div>
        </div>
    </section>

    <section class="section-space bg-white home-scroll-step" data-nav-section="sistema-elah">
        <div class="section-shell">
            <div class="max-w-3xl">
                <p class="eyebrow">Sistema ELAH</p>
                <h2 class="section-title">Unimos dos mundos.</h2>
                <p class="section-lead">La limpieza y la aromatización suelen resolverse como procesos separados. ELAH los integra dentro de un mismo sistema.</p>
            </div>
            <div class="equation-grid mt-12">
                <article><p class="equation-label">Limpieza</p><p>Operación cotidiana del hotel.</p></article>
                <div class="equation-symbol" aria-hidden="true">+</div>
                <article><p class="equation-label">Aroma</p><p>Identidad y experiencia sensorial.</p></article>
                <div class="equation-symbol" aria-hidden="true">=</div>
                <article class="equation-result"><p class="equation-label">ELAH</p><p>Estandarización de Limpieza y Aroma en Hoteles.</p></article>
            </div>
        </div>
    </section>

    <section class="section-space bg-hielo home-scroll-step" data-nav-section="sistema-elah">
        <div class="section-shell">
            <h2 class="section-title max-w-4xl">Una misma identidad, no aromas desconectados.</h2>
            <div class="identity-compare mt-12">
                <article class="identity-before">
                    <p class="eyebrow text-charcoal/50">Antes</p>
                    <div class="identity-row"><span>Limpieza</span><strong>Aroma A</strong></div>
                    <div class="identity-row"><span>Spray</span><strong>Aroma B</strong></div>
                    <div class="identity-row"><span>Difusor</span><strong>Aroma C</strong></div>
                </article>
                <article class="identity-after">
                    <p class="eyebrow text-aqua">Con ELAH</p>
                    <div class="identity-row"><span>Limpieza</span><strong>Aroma insignia</strong></div>
                    <div class="identity-row"><span>Spray</span><strong>Aroma insignia</strong></div>
                    <div class="identity-row"><span>Difusor</span><strong>Aroma insignia</strong></div>
                </article>
            </div>
            <p class="statement mt-10">Una misma identidad olfativa en diferentes puntos de contacto.</p>
        </div>
    </section>

    <section class="section-space bg-expert text-white home-scroll-step" data-nav-section="sistema-elah">
        <div class="section-shell">
            <p class="eyebrow text-aqua">Cómo funciona</p>
            <h2 class="section-title text-white">Un sistema. Tres formas de llevar el aroma insignia.</h2>
            <div class="process-line mt-12">
                <article><span>01</span><p class="process-kicker">Limpieza</p><h3>Hotel Expert</h3><p>El multiusos concentrado limpia, desinfecta y deja el aroma insignia durante la operación cotidiana.</p><strong>Limpieza + aroma en cada aplicación.</strong></article>
                <article><span>02</span><p class="process-kicker">Refuerzo</p><h3>Spray ambiental</h3><p>Permite reforzar el aroma de manera puntual en áreas o momentos específicos.</p><strong>Aroma donde y cuando se necesita.</strong></article>
                <article><span>03</span><p class="process-kicker">Presencia continua</p><h3>Difusores</h3><p>Complementan el sistema mediante aromatización continua en espacios seleccionados.</p><strong>Presencia aromática continua.</strong></article>
            </div>
            <div class="mt-10 flex flex-col gap-5 border-t border-white/15 pt-8 sm:flex-row sm:items-center sm:justify-between">
                <p class="font-heading text-xl font-bold text-aqua">Limpieza + Refuerzo + Difusión → Aroma insignia</p>
                <a class="btn-ghost" href="<?= e(url('sistema-elah/')) ?>">Conoce cómo funciona ELAH</a>
            </div>
        </div>
    </section>

    <section class="section-space bg-white home-scroll-step" data-nav-section="sistema-elah">
        <div class="section-shell grid gap-10 lg:grid-cols-12 lg:items-center">
            <div class="lg:col-span-8">
                <h2 class="section-title">La limpieza deja de ser solo una operación.</h2>
                <h3 class="mt-5 font-heading text-2xl font-bold text-turquesa sm:text-3xl">También puede reforzar la identidad de tu hotel.</h3>
                <p class="section-lead">Housekeeping limpia habitaciones, baños y diferentes áreas todos los días. Con Hotel Expert, esa misma aplicación también lleva el aroma insignia.</p>
                <p class="mt-4 max-w-3xl text-lg text-charcoal/70">Así, una actividad indispensable de la operación se convierte también en un punto de contacto sensorial.</p>
            </div>
            <p class="feature-statement lg:col-span-4">Limpieza <span>+</span> identidad olfativa</p>
        </div>
    </section>

    <section id="productos" class="section-space bg-arena scroll-mt-28 home-scroll-step" data-nav-section="productos">
        <div class="section-shell">
            <p class="eyebrow">Sistema ELAH</p><h2 class="section-title">El motor del sistema</h2>
            <div class="product-duo mt-12">
                <article class="product-panel">
                    <div><p class="eyebrow">Hotel Expert</p><h3>Limpia + Desinfecta + Aroma insignia</h3><p>Multiusos concentrado para la operación cotidiana del hotel.</p><a class="btn-outline mt-7" href="<?= e(url('productos/hotel-expert/')) ?>">Conocer Hotel Expert</a></div>
                    <img src="<?= e(url('assets/img/bottle-std.svg')) ?>" alt="Presentación de Hotel Expert" loading="lazy" width="260" height="420">
                </article>
                <article class="product-panel product-panel-dual">
                    <div><p class="eyebrow text-aqua">Hotel Expert Dual</p><h3>Limpia + Desinfecta + Aroma insignia + Neutraliza malos olores</h3><p>Añade neutralización de malos olores para áreas donde esta necesidad requiere mayor atención.</p><a class="btn-ghost mt-7" href="<?= e(url('productos/hotel-expert-dual/')) ?>">Conocer Hotel Expert Dual</a></div>
                    <img src="<?= e(url('assets/img/bottle-dual.svg')) ?>" alt="Presentación de Hotel Expert Dual" loading="lazy" width="260" height="420">
                </article>
            </div>
            <p class="mt-8 text-center font-heading font-semibold text-charcoal/55">Aromas · Spray · Difusores</p>
        </div>
    </section>

    <section class="section-space bg-white home-scroll-step" data-nav-section="productos">
        <div class="section-shell">
            <h2 class="section-title">Dos versiones. Una misma identidad.</h2>
            <div class="comparison-cards mt-12">
                <?php foreach ([
                    ['Hotel Expert', ['Limpia', 'Desinfecta', 'Aroma insignia', 'Multiuso']],
                    ['Hotel Expert Dual', ['Limpia', 'Desinfecta', 'Aroma insignia', 'Multiuso', 'Neutraliza malos olores']]
                ] as [$name, $features]): ?>
                <article><h3><?= e($name) ?></h3><ul><?php foreach ($features as $feature): ?><li><span aria-hidden="true">✓</span><?= e($feature) ?></li><?php endforeach; ?></ul></article>
                <?php endforeach; ?>
            </div>
            <a class="btn-outline mt-8" href="<?= e(url('productos/#comparacion')) ?>">Comparar productos</a>
        </div>
    </section>

    <section class="section-space hotel-map home-scroll-step" data-nav-section="aroma-insignia">
        <div class="section-shell">
            <h2 class="section-title max-w-4xl">Una misma experiencia, de la habitación al lobby.</h2>
            <div class="space-chips mt-10"><?php foreach (['Habitaciones', 'Baños', 'Pasillos', 'Lobby', 'Restaurante', 'Gimnasio', 'Áreas administrativas'] as $space): ?><span><?= e($space) ?></span><?php endforeach; ?></div>
            <p class="statement mt-12">Un hotel. Diferentes espacios. Una misma identidad olfativa.</p>
        </div>
    </section>

    <section class="section-space aroma-section text-white home-scroll-step" data-nav-section="aroma-insignia">
        <div class="section-shell grid gap-10 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-8"><p class="eyebrow text-aqua">Aroma insignia</p><h2 class="section-title text-white">Tu hotel se reconoce por cómo se ve. También puede recordarse por cómo huele.</h2></div>
            <div class="lg:col-span-4"><p class="text-lg leading-relaxed text-white/75">El aroma puede formar parte de la identidad sensorial del establecimiento. ELAH permite incorporarlo desde la limpieza cotidiana y reforzarlo mediante diferentes formas de aromatización.</p><p class="mt-4 font-semibold">No se trata únicamente de que el hotel huela bien. Se trata de mantener una experiencia coherente con su identidad.</p><a class="btn-ghost mt-7" href="<?= e(url('aroma-insignia/')) ?>">Descubre el aroma insignia</a></div>
        </div>
    </section>

    <section class="section-space bg-white home-scroll-step" data-nav-section="recursos">
        <div class="section-shell"><h2 class="section-title max-w-4xl">Diseñado para integrarse a la operación del hotel.</h2>
            <div class="operations-grid mt-12">
                <article><p>Concentrado</p><strong>2 L → 20 L</strong><span>Un bidón de 2 litros permite preparar 20 litros de producto listo para usar.</span></article>
                <article><p>Dilución</p><strong>1:9</strong><span>100 ml de concentrado + 900 ml de agua.</span></article>
                <article><p>Multiuso</p><strong>Diferentes áreas</strong><span>Para diferentes superficies y áreas del hotel.</span></article>
                <article><p>Retornable</p><strong>2 litros</strong><span>Presentación concentrada en envase retornable de 2 litros.</span></article>
            </div>
            <p class="mt-7 text-sm font-semibold text-charcoal/60">No utilizar en vidrio o espejos.</p>
        </div>
    </section>

    <section class="section-space bg-hielo home-scroll-step" data-nav-section="recursos">
        <div class="section-shell">
            <h2 class="section-title">De la operación a la experiencia del huésped.</h2>
            <ol class="experience-flow mt-12" aria-label="Secuencia de experiencia"><li>Limpieza</li><li>Frescura</li><li>Aroma insignia</li><li>Consistencia</li><li>Experiencia</li></ol>
            <p class="section-lead">Cada espacio limpio forma parte de la experiencia del huésped. ELAH busca que limpieza y aroma trabajen bajo una misma lógica para mantener una experiencia consistente en diferentes momentos de la estancia.</p>
            <p class="statement mt-8"><?= e(SITE_CLAIM) ?></p>
        </div>
    </section>

    <section class="sample-section home-scroll-step" id="muestra" data-nav-section="contacto">
        <div class="section-shell py-20 text-center sm:py-28">
            <h2 class="section-title text-white">Pruébalo en tu propio hotel.</h2>
            <p class="mx-auto mt-6 max-w-2xl text-lg text-white/70">Conoce Hotel Expert dentro de tu operación y descubre cómo limpieza y aroma pueden trabajar juntos bajo una misma identidad.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3"><a class="btn-primary" href="<?= e(url('contacto/?tipo=muestra')) ?>">Solicitar una muestra</a><a class="btn-ghost" href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener noreferrer">Hablar con un asesor</a></div>
        </div>
    </section>

    <section class="section-space bg-white home-scroll-step" data-nav-section="recursos">
        <div class="mx-auto max-w-3xl px-4 sm:px-6">
            <h2 class="section-title">Preguntas frecuentes</h2>
            <div class="faq-list mt-10">
                <?php foreach ($home_faq as $i => [$question, $answer]): ?>
                <details class="faq-item"><summary><span><?= e($question) ?></span><span class="faq-icon" aria-hidden="true">+</span></summary><div class="faq-answer"><p><?= e($answer) ?></p></div></details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="elah-close home-scroll-step" data-nav-section="contacto">
        <div class="section-shell py-24 text-center sm:py-32">
            <p class="eyebrow text-aqua">Sistema</p><p class="elah-close-word">ELAH</p>
            <p class="mx-auto mt-5 max-w-2xl font-heading text-xl font-bold text-white">Estandarización de Limpieza y Aroma en Hoteles</p>
            <p class="elah-equation mx-auto mt-8">Limpieza <span>+</span> Aroma <span>=</span> ELAH</p>
            <p class="mt-8 text-white/65"><?= e(SITE_CLAIM) ?></p>
            <div class="mt-8 flex flex-wrap justify-center gap-3"><a class="btn-primary" href="<?= e(url('sistema-elah/')) ?>">Conoce el Sistema ELAH</a><a class="btn-ghost" href="<?= e(url('contacto/?tipo=muestra')) ?>">Solicita una muestra</a></div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
