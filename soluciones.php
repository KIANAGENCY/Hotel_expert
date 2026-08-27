<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Soluciones de limpieza, aroma e identidad olfativa para hoteles | Hotel Expert';
$page_description = 'Hotel Expert aborda limpieza profesional, aromatización, aroma insignia, neutralización de olores y estandarización con el Sistema ELAH.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$soluciones = [
    [
        'titulo' => 'Limpieza profesional para hoteles',
        'texto' => 'Simplifica la operación mediante una solución concentrada y multiuso.',
        'href' => 'limpieza-para-hoteles.php',
        'cta' => 'Conocer limpieza profesional',
    ],
    [
        'titulo' => 'Aromatización para hoteles',
        'texto' => 'Mantén presencia aromática en diferentes espacios y momentos.',
        'href' => 'aromatizacion-para-hoteles.php',
        'cta' => 'Conocer aromatización',
    ],
    [
        'titulo' => 'Identidad olfativa y aroma insignia',
        'texto' => 'Convierte el aroma en parte de la identidad del establecimiento.',
        'href' => 'aroma-insignia.php',
        'cta' => 'Conocer aroma insignia',
    ],
    [
        'titulo' => 'Neutralización de malos olores',
        'texto' => 'Hotel Expert Dual incorpora una solución específica para necesidades de control de olores.',
        'href' => 'neutralizacion-malos-olores.php',
        'cta' => 'Conocer neutralización',
    ],
    [
        'titulo' => 'Estandarización de limpieza y aroma',
        'texto' => 'Integra procesos y experiencia mediante el Sistema ELAH.',
        'href' => 'sistema-elah.php',
        'cta' => 'Conocer el Sistema ELAH',
    ],
];
?>
<main id="contenido" class="pt-28">
    <section class="bg-expert text-white py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <nav class="text-sm text-white/50 mb-8" aria-label="Ruta de navegación">
                <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <li><a class="hover:text-aqua" href="<?= e(url('')) ?>">Inicio</a></li>
                    <li aria-hidden="true">/</li>
                    <li><span class="text-white/80">Soluciones</span></li>
                </ol>
            </nav>
            <h1 class="display text-4xl sm:text-6xl text-white max-w-4xl">Soluciones de limpieza, aroma e identidad olfativa para hoteles</h1>
            <p class="mt-5 max-w-2xl text-xl text-white/70">Hotel Expert aborda diferentes necesidades de la operación hotelera desde una propuesta integrada.</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid md:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php foreach ($soluciones as $s): ?>
                <article class="elah-card bg-white p-8 io-reveal flex flex-col">
                    <h2 class="font-heading font-extrabold text-2xl text-expert"><?= e($s['titulo']) ?></h2>
                    <p class="mt-4 text-charcoal/70 flex-1"><?= e($s['texto']) ?></p>
                    <a class="btn-outline mt-6 self-start" href="<?= e(url($s['href'])) ?>"><?= e($s['cta']) ?></a>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <?php
    $cta_title = 'Diseñemos la solución para tu hotel';
    $cta_text = 'Cuéntanos tu operación y objetivos de aroma para identificar la combinación adecuada del Sistema ELAH.';
    $cta_primary_label = 'Solicitar información';
    $cta_primary_href = 'contacto.php';
    require __DIR__ . '/includes/partials/cta-band.php';
    ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>





