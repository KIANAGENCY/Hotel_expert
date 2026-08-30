<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$posts = blog_all();
$slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) ($_GET['slug'] ?? '')));
$post = null;
foreach ($posts as $row) {
    if ($row['slug'] === $slug) {
        $post = $row;
        break;
    }
}
if (!$post) {
    http_response_code(404);
    $page_title = 'Artículo no encontrado — Hotel Expert';
    require __DIR__ . '/includes/head.php';
    require __DIR__ . '/includes/header.php';
    echo '<main id="contenido" class="pt-40 pb-24 px-4 text-center"><h1 class="display text-4xl">Artículo no encontrado</h1><a class="btn-primary mt-6" href="' . e(url('blog/')) . '">Volver al blog</a></main>';
    require __DIR__ . '/includes/footer.php';
    exit;
}
$page = 'recursos';
$page_title = $post['seo_titulo'] ?? ($post['titulo'] . ' — Hotel Expert');
$page_description = $post['meta_descripcion'] ?? $post['extracto'];
$page_og = $post['cover'];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <article>
        <header class="relative min-h-[46vh] flex items-end overflow-hidden bg-expert">
            <img src="<?= e($post['cover']) ?>" alt="" class="absolute inset-0 h-full w-full object-cover opacity-45">
            <div class="absolute inset-0 bg-gradient-to-t from-expert to-transparent"></div>
            <div class="relative mx-auto max-w-3xl px-4 sm:px-6 pb-12 w-full">
                <p class="eyebrow text-aqua"><?= e($post['categoria']) ?> · <?= e(date('d M Y', strtotime($post['fecha']))) ?> · <?= e($post['lectura']) ?></p>
                <h1 class="display mt-3 text-3xl sm:text-5xl text-white"><?= e($post['titulo']) ?></h1>
                <?php if (!empty($post['bajada'])): ?>
                    <p class="mt-4 text-xl text-white/75"><?= e($post['bajada']) ?></p>
                <?php endif; ?>
            </div>
        </header>
        <div class="mx-auto max-w-3xl px-4 sm:px-6 py-14 space-y-5 text-lg text-charcoal/80 leading-relaxed">
            <?php foreach ($post['cuerpo'] as $p): ?>
                <p><?= e($p) ?></p>
            <?php endforeach; ?>
            <div class="pt-8 flex flex-wrap gap-3">
                <a class="btn-primary" href="<?= e(url('contacto/')) ?>">Cotizar el sistema</a>
                <a class="btn-outline" href="<?= e(url('blog/')) ?>">Más artículos</a>
            </div>
        </div>
    </article>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>




