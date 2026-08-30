<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

admin_layout_start('Blog', 'blog');
$posts = blog_all();

$actions = '<a class="admin-btn admin-btn-primary" href="' . e(admin_url('post.php')) . '"><i class="fa-solid fa-plus"></i> Nuevo artículo</a>';
admin_page_header('Contenido', 'Blog', 'Crea, edita y publica artículos del Sistema ELAH.', $actions);

$categorias = ['all' => 'Todos'];
foreach ($posts as $post) {
    $cat = (string) ($post['categoria'] ?? '');
    if ($cat !== '') {
        $categorias[$cat] = $cat;
    }
}
admin_filter_bar($categorias);
?>
<div class="admin-card">
    <div class="admin-list-meta">
        <span><strong data-admin-list-count="blog-table"><?= count($posts) ?></strong> artículos</span>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table" id="blog-table" data-admin-list>
            <thead><tr><th>Artículo</th><th>Categoría</th><th>Fecha</th><th>Slug</th><th></th></tr></thead>
            <tbody>
            <?php if ($posts === []): ?>
                <tr class="admin-table-empty"><td colspan="5"><?php admin_empty_state('fa-newspaper', 'Sin artículos', 'Publica contenido sobre el Sistema ELAH y operación hotelera.', '<a class="admin-btn admin-btn-primary admin-btn-sm" href="' . e(admin_url('post.php')) . '"><i class="fa-solid fa-plus"></i> Nuevo artículo</a>'); ?></td></tr>
            <?php endif; ?>
            <?php foreach ($posts as $post): ?>
                <?php
                $cat = (string) ($post['categoria'] ?? '');
                $search = strtolower(implode(' ', [$post['titulo'], $cat, $post['slug'], $post['fecha']]));
                ?>
                <tr data-filter="<?= e($cat !== '' ? $cat : 'all') ?>" data-search="<?= e($search) ?>">
                    <td><strong><?= e($post['titulo']) ?></strong></td>
                    <td><?= e($cat) ?></td>
                    <td><?= e($post['fecha']) ?></td>
                    <td><code><?= e($post['slug']) ?></code></td>
                    <td>
                        <div class="admin-table-actions">
                            <a class="admin-btn-icon" href="<?= e(admin_url('post.php?slug=' . rawurlencode($post['slug']))) ?>" title="Editar"><i class="fa-solid fa-pen"></i></a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php admin_layout_end(); ?>
