<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

$slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) ($_GET['slug'] ?? '')));
$post = $slug ? blog_get($slug) : null;
$isNew = !$post;
if ($isNew) {
    $post = [
        'slug' => '', 'titulo' => '', 'seo_titulo' => '', 'meta_descripcion' => '', 'bajada' => '',
        'extracto' => '', 'categoria' => 'Sistema ELAH', 'fecha' => date('Y-m-d'), 'lectura' => '4 min',
        'cover' => '', 'cuerpo' => [],
    ];
}

admin_layout_start($isNew ? 'Nuevo artículo' : 'Editar artículo', 'blog');
admin_page_header('Contenido', $isNew ? 'Nuevo artículo' : 'Editar artículo', 'Redacta y publica contenido del blog ELAH.');
?>
<form method="post" action="<?= e(admin_url('action.php')) ?>" class="admin-card admin-form admin-form-grid">
    <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
    <input type="hidden" name="action" value="post_save">
    <?php admin_field('Slug', 'slug', $post['slug']); ?>
    <?php admin_field('Título', 'titulo', $post['titulo']); ?>
    <?php admin_field('SEO título', 'seo_titulo', $post['seo_titulo']); ?>
    <?php admin_textarea('Meta descripción', 'meta_descripcion', $post['meta_descripcion'], 2); ?>
    <?php admin_field('Bajada', 'bajada', $post['bajada']); ?>
    <?php admin_textarea('Extracto', 'extracto', $post['extracto'], 2); ?>
    <?php admin_field('Categoría', 'categoria', $post['categoria']); ?>
    <?php admin_field('Fecha', 'fecha', $post['fecha'], 'date'); ?>
    <?php admin_field('Tiempo lectura', 'lectura', $post['lectura']); ?>
    <?php admin_field('Cover URL', 'cover', $post['cover']); ?>
    <?php admin_textarea('Cuerpo (párrafos separados por línea en blanco)', 'cuerpo', implode("\n\n", $post['cuerpo']), 12); ?>
    <?php admin_field('Orden', 'sort_order', '0', 'number'); ?>
    <div class="admin-form-actions">
        <button class="admin-btn admin-btn-primary" type="submit">Guardar artículo</button>
        <a class="admin-btn admin-btn-outline" href="<?= e(admin_url('blog.php')) ?>">Cancelar</a>
    </div>
</form>
<?php if (!$isNew): ?>
<form method="post" action="<?= e(admin_url('action.php')) ?>" style="margin-top:16px" onsubmit="return confirm('¿Eliminar artículo?')">
    <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
    <input type="hidden" name="action" value="post_delete">
    <input type="hidden" name="slug" value="<?= e($slug) ?>">
    <button class="admin-btn admin-btn-danger" type="submit">Eliminar artículo</button>
</form>
<?php endif; ?>
<?php admin_layout_end(); ?>
