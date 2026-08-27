<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Neutralización de malos olores para hoteles | Hotel Expert Dual';
$page_description = 'Hotel Expert Dual limpia, desinfecta, aromatiza y neutraliza malos olores en habitaciones, textiles, alfombras y tapicería.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="bg-expert text-white py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <nav class="text-sm text-white/50 mb-8" aria-label="Ruta de navegación">
                <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <li><a class="hover:text-aqua" href="<?= e(url('')) ?>">Inicio</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a class="hover:text-aqua" href="<?= e(url('soluciones.php')) ?>">Soluciones</a></li>
                    <li aria-hidden="true">/</li>
                    <li><span class="text-white/80">Neutralización de malos olores</span></li>
                </ol>
            </nav>
            <h1 class="display text-4xl sm:text-6xl text-white max-w-4xl">Neutralización de malos olores para habitaciones y espacios del hotel</h1>
            <p class="mt-5 max-w-2xl text-xl text-white/70">Cuando el problema no es la falta de fragancia sino la presencia de malos olores, cubrirlos con un aroma más intenso no resuelve la necesidad operativa.</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 space-y-16">
            <article class="max-w-3xl">
                <h2 class="display text-3xl sm:text-4xl">Neutralizar no es lo mismo que perfumar</h2>
                <p class="mt-5 text-lg text-charcoal/70">Hotel Expert Dual incorpora un ingrediente activo orientado a neutralizar malos olores mientras limpia, desinfecta y deja el aroma insignia.</p>
            </article>
            <article>
                <h2 class="display text-3xl sm:text-4xl mb-8">Especialmente relevante en espacios cerrados y textiles</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <?php foreach (['Habitaciones', 'Alfombras', 'Tapicería', 'Textiles'] as $item): ?>
                        <div class="elah-card bg-hielo p-7">
                            <h3 class="font-heading font-extrabold text-xl text-expert"><?= e($item) ?></h3>
                            <p class="mt-3 text-charcoal/70">Espacios donde los olores pueden retenerse y la percepción del huésped es crítica.</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </article>
            <article class="max-w-3xl">
                <h2 class="display text-3xl sm:text-4xl">Limpieza y control de olores en una misma rutina</h2>
                <p class="mt-5 text-lg text-charcoal/70">Dual se integra en la operación cotidiana del housekeeping, sin separar limpieza y control de olores en procesos distintos.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a class="btn-primary" href="<?= e(url('productos/hotel-expert-dual/')) ?>">Conocer Hotel Expert Dual</a>
                    <a class="btn-outline" href="<?= e(url('sistema-elah/')) ?>">Ver el Sistema ELAH</a>
                </div>
            </article>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>





