<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Hotel Expert en cada área de tu hotel | Para tu hotel';
$page_description = 'Descubre cómo aplicar Hotel Expert y el Sistema ELAH en habitaciones, lobby, restaurantes y textiles.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$areas_detalle = [
    [
        'id' => 'habitaciones-banos',
        'titulo' => 'Habitaciones y baños',
        'texto' => 'Limpieza, aroma y control de olores donde la percepción del huésped es especialmente importante. Hotel Expert Dual resulta especialmente útil en espacios cerrados y textiles.',
    ],
    [
        'id' => 'lobby-areas-comunes',
        'titulo' => 'Lobby y áreas comunes',
        'texto' => 'Refuerza la identidad desde los primeros puntos de contacto. La limpieza con aroma insignia y los difusores ayudan a mantener presencia en zonas de alto tránsito.',
    ],
    [
        'id' => 'restaurantes-servicio',
        'titulo' => 'Restaurantes y áreas de servicio',
        'texto' => 'Integra la limpieza dentro de una experiencia coherente con el resto de la propiedad, con la misma identidad olfativa del hotel.',
    ],
    [
        'id' => 'textiles-alfombras',
        'titulo' => 'Textiles, alfombras y tapicería',
        'texto' => 'Limpieza de superficies textiles y, con Hotel Expert Dual, apoyo específico para el control de malos olores en alfombras, tapicería y textiles.',
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
                    <li><span class="text-white/80">Para tu hotel</span></li>
                </ol>
            </nav>
            <h1 class="display text-4xl sm:text-6xl text-white max-w-4xl">Hotel Expert en cada área de tu hotel</h1>
            <p class="mt-5 max-w-2xl text-xl text-white/70">La experiencia del huésped no sucede únicamente en la habitación.</p>
            <p class="mt-3 max-w-2xl text-lg text-white/65">Descubre cómo aplicar Hotel Expert y el Sistema ELAH en diferentes puntos de contacto.</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 space-y-8">
            <?php foreach ($areas_detalle as $area): ?>
                <article id="<?= e($area['id']) ?>" class="elah-card bg-white p-8 sm:p-10 scroll-mt-32 io-reveal">
                    <h2 class="font-heading font-extrabold text-3xl text-expert"><?= e($area['titulo']) ?></h2>
                    <p class="mt-4 text-lg text-charcoal/70 max-w-3xl"><?= e($area['texto']) ?></p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a class="btn-outline" href="<?= e(url('sistema-elah/')) ?>">Conocer el Sistema ELAH</a>
                        <a class="btn-outline" href="<?= e(url('muestra/')) ?>">Solicitar muestra</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>




