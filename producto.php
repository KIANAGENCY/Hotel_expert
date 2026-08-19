<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$productos = require __DIR__ . '/data/productos.php';
$slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) ($_GET['slug'] ?? '')));
if (!isset($productos[$slug])) {
    http_response_code(404);
    $page_title = 'Producto no encontrado — Hotel Expert';
    require __DIR__ . '/includes/head.php';
    require __DIR__ . '/includes/header.php';
    echo '<main id="contenido" class="pt-40 pb-24 px-4 text-center"><h1 class="display text-4xl">Producto no encontrado</h1><a class="btn-primary mt-6" href="' . e(url('catalogo.php')) . '">Volver al catálogo</a></main>';
    require __DIR__ . '/includes/footer.php';
    exit;
}
$p = $productos[$slug];
$page = 'catalogo';
$img = match ($slug) {
    'dual' => 'bottle-dual.svg',
    'porron' => 'porron.svg',
    'atomizador' => 'atomizador.svg',
    default => 'bottle-std.svg',
};
$page_title = $p['nombre'] . ' — Hotel Expert';
$page_description = $p['resumen'];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="bg-hielo py-16 lg:py-22">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div class="rounded-[2rem] bg-white p-10 grid place-items-center shadow-glass">
                <img src="<?= e(url('assets/img/' . $img)) ?>" alt="<?= e($p['nombre']) ?>" class="h-80 bottle-float">
            </div>
            <div>
                <p class="eyebrow"><?= e($p['linea']) ?> · <?= e($p['sku']) ?></p>
                <h1 class="display mt-3 text-4xl sm:text-5xl"><?= e($p['nombre']) ?></h1>
                <p class="mt-3 text-xl text-turquesa font-heading font-semibold"><?= e($p['subtitulo']) ?></p>
                <p class="mt-4 text-lg text-charcoal/75"><?= e($p['resumen']) ?></p>
                <div class="mt-6 flex flex-wrap gap-2">
                    <?php foreach ($p['claims'] as $c): ?>
                        <span class="rounded-full bg-eco/15 text-eco px-3 py-1 text-xs font-heading font-bold"><?= e($c) ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a class="btn-primary" href="<?= e(url('contacto.php')) ?>">Cotizar mayoreo</a>
                    <a class="btn-outline" href="<?= e(url('como-funciona.php')) ?>">Ver dilución</a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid md:grid-cols-2 gap-6">
            <div class="rounded-[1.4rem] border border-expert/10 p-8">
                <h2 class="font-heading font-extrabold text-xl text-expert">Ficha</h2>
                <dl class="mt-4 space-y-3 text-charcoal/80">
                    <div><dt class="text-xs uppercase tracking-wider text-charcoal/40">Función</dt><dd><?= e($p['funcion']) ?></dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-charcoal/40">Propiedad olfativa</dt><dd><?= e($p['olfativa']) ?></dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-charcoal/40">Presentación</dt><dd><?= e($p['presentacion']) ?></dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-charcoal/40">Rendimiento</dt><dd><?= e($p['rendimiento']) ?></dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-charcoal/40">Especialidad</dt><dd><?= e($p['especialidad']) ?></dd></div>
                </dl>
            </div>
            <div class="rounded-[1.4rem] bg-arena p-8">
                <h2 class="font-heading font-extrabold text-xl text-expert">Ideal para</h2>
                <p class="mt-3 text-charcoal/75"><?= e($p['ideal']) ?></p>
                <?php if ($p['superficies']): ?>
                    <h3 class="mt-6 font-heading font-bold text-sm uppercase tracking-wider text-turquesa">Superficies aptas</h3>
                    <ul class="mt-3 flex flex-wrap gap-2">
                        <?php foreach ($p['superficies'] as $s): ?>
                            <li class="rounded-lg bg-white px-3 py-1.5 text-sm"><?= e($s) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php if ($p['no_usar']): ?>
                    <div class="mt-6 rounded-xl bg-expert text-white p-4">
                        <p class="font-heading font-bold text-sm">Excepción estricta</p>
                        <p class="text-white/80 mt-1">NO usar en <?= e(implode(' ni ', $p['no_usar'])) ?>.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
