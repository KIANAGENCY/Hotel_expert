<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Cómo usar Hotel Expert correctamente | Manual de uso';
$page_description = 'Dilución 1:9, preparación de 20 litros, aplicación y superficies compatibles del concentrado Hotel Expert.';
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
                    <li><a class="hover:text-aqua" href="<?= e(url('recursos/')) ?>">Recursos</a></li>
                    <li aria-hidden="true">/</li>
                    <li><span class="text-white/80">Manual de uso</span></li>
                </ol>
            </nav>
            <h1 class="display text-4xl sm:text-6xl text-white max-w-4xl">Cómo usar Hotel Expert correctamente</h1>
            <p class="mt-5 max-w-2xl text-xl text-white/70">Hotel Expert es un producto concentrado. Debe diluirse antes de utilizarse.</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 space-y-16">
            <div class="grid lg:grid-cols-2 gap-8">
                <article class="elah-card bg-hielo p-8 sm:p-10">
                    <h2 class="display text-3xl">Dilución para 1 litro</h2>
                    <p class="mt-5 text-lg text-charcoal/70">100 ml de concentrado + 900 ml de agua.</p>
                </article>
                <article class="elah-card bg-expert text-white p-8 sm:p-10">
                    <h2 class="font-heading font-extrabold text-3xl">Preparación de 20 litros</h2>
                    <p class="mt-5 text-lg text-white/70">18 litros de agua + bidón completo de 2 litros.</p>
                    <p class="mt-6 rounded-2xl border border-aqua/30 bg-aqua/10 p-5 text-aqua font-heading font-bold">Siempre agua primero y producto después.</p>
                </article>
            </div>

            <article>
                <h2 class="display text-3xl sm:text-4xl mb-8">Cómo aplicarlo</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="elah-card bg-hielo p-8">
                        <span class="font-heading font-extrabold text-4xl text-aqua">1</span>
                        <h3 class="mt-4 font-heading font-extrabold text-xl text-expert">Rocía</h3>
                        <p class="mt-3 text-charcoal/70">Aplica el producto diluido sobre la superficie.</p>
                    </div>
                    <div class="elah-card bg-hielo p-8">
                        <span class="font-heading font-extrabold text-4xl text-aqua">2</span>
                        <h3 class="mt-4 font-heading font-extrabold text-xl text-expert">Talla cuando sea necesario</h3>
                        <p class="mt-3 text-charcoal/70">Interviene con acción mecánica si la superficie lo requiere.</p>
                    </div>
                    <div class="elah-card bg-hielo p-8">
                        <span class="font-heading font-extrabold text-4xl text-aqua">3</span>
                        <h3 class="mt-4 font-heading font-extrabold text-xl text-expert">Retira con paño o trapeador húmedo</h3>
                        <p class="mt-3 text-charcoal/70">Finaliza la limpieza retirando el producto.</p>
                    </div>
                </div>
            </article>

            <article class="max-w-3xl">
                <h2 class="display text-3xl sm:text-4xl">Superficies compatibles</h2>
                <div class="mt-6 flex flex-wrap gap-2">
                    <?php foreach (['Pisos', 'Mármol', 'Granito', 'Acero inoxidable', 'Baños', 'Cromo', 'Madera', 'Telas', 'Sillones', 'Alfombras'] as $s): ?>
                        <span class="rounded-full bg-hielo px-3 py-1.5 text-sm font-heading font-semibold text-expert"><?= e($s) ?></span>
                    <?php endforeach; ?>
                </div>
            </article>

            <article class="max-w-3xl">
                <h2 class="display text-3xl sm:text-4xl">Importante: no utilizar en vidrio</h2>
                <p class="mt-5 rounded-2xl border border-aqua/30 bg-hielo p-6 text-lg text-charcoal/80">No debe utilizarse en vidrio o espejos.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a class="btn-primary" href="<?= e(url('productos/hotel-expert/')) ?>">Ver Hotel Expert</a>
                    <a class="btn-outline" href="<?= e(url('contacto/?tipo=muestra')) ?>">Solicitar muestra</a>
                </div>
            </article>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>





