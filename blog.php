<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$posts = require __DIR__ . '/data/blog.php';
$page_title = 'Recursos Sistema ELAH — Hotel Expert';
$page_description = 'Conoce el Sistema ELAH, su tecnología de eliminación de olores y la eficiencia del concentrado para hoteles.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="bg-expert text-white py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <p class="eyebrow text-aqua">Conoce ELAH</p>
            <h1 class="display mt-3 text-4xl sm:text-6xl text-white">Limpieza y aroma como un solo sistema.</h1>
            <p class="mt-4 max-w-xl text-white/70">Información para gerencia, compras y housekeeping sobre la operación y el valor de ELAH.</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-3 gap-6">
            <?php foreach ($posts as $i => $post): ?>
            <article class="rounded-[1.6rem] bg-white overflow-hidden border border-expert/5 io-reveal <?= $i === 0 ? 'lg:col-span-2 lg:grid lg:grid-cols-2' : '' ?>">
                <img src="<?= e($post['cover']) ?>" alt="" class="h-52 w-full object-cover <?= $i === 0 ? 'lg:h-full' : '' ?>">
                <div class="p-7 flex flex-col">
                    <p class="text-xs font-heading font-bold uppercase tracking-wider text-turquesa"><?= e($post['categoria']) ?> · <?= e($post['lectura']) ?></p>
                    <h2 class="mt-2 font-heading font-extrabold text-xl text-expert"><?= e($post['titulo']) ?></h2>
                    <p class="mt-2 text-charcoal/70 flex-1"><?= e($post['extracto']) ?></p>
                    <a class="btn-outline mt-5 self-start" href="<?= e(url('articulo.php?slug=' . $post['slug'])) ?>">Leer</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 mt-10">
            <div class="rounded-[1.6rem] bg-arena p-8 sm:p-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6 io-reveal">
                <div>
                    <h2 class="font-heading font-extrabold text-2xl text-expert">Cómo funciona el sistema completo</h2>
                    <p class="mt-2 text-charcoal/70">Conoce las tres capas de ELAH, sus proporciones y la cobertura dentro del hotel.</p>
                </div>
                <a class="btn-primary" href="<?= e(url('como-funciona.php')) ?>">Entender ELAH</a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
