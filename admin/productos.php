<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

admin_layout_start('Productos', 'productos');
$productos = productos_all();

$actions = '<a class="admin-btn admin-btn-primary" href="' . e(admin_url('producto.php')) . '"><i class="fa-solid fa-plus"></i> Nuevo producto</a>';
admin_page_header('Catálogo ELAH', 'Productos', 'Gestiona el catálogo del Sistema ELAH.', $actions);

$categorias = ['all' => 'Todos'];
foreach ($productos as $p) {
    $cat = (string) ($p['categoria'] ?? '');
    if ($cat !== '') {
        $categorias[$cat] = $cat;
    }
}
admin_filter_bar($categorias);
?>
<div class="admin-card">
    <div class="admin-list-meta">
        <span><strong data-admin-list-count="productos-table"><?= count($productos) ?></strong> productos</span>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table" id="productos-table" data-admin-list>
            <thead><tr><th>Producto</th><th>SKU</th><th>Precio</th><th>Slug</th><th></th></tr></thead>
            <tbody>
            <?php if ($productos === []): ?>
                <tr class="admin-table-empty"><td colspan="5"><?php admin_empty_state('fa-box', 'Catálogo vacío', 'Agrega el primer producto del Sistema ELAH.', '<a class="admin-btn admin-btn-primary admin-btn-sm" href="' . e(admin_url('producto.php')) . '"><i class="fa-solid fa-plus"></i> Nuevo producto</a>'); ?></td></tr>
            <?php endif; ?>
            <?php foreach ($productos as $slug => $p): ?>
                <?php
                $cat = (string) ($p['categoria'] ?? '');
                $search = strtolower(implode(' ', [$p['nombre'], $p['sku'], $slug, $cat, $p['precio_texto'] ?? '']));
                ?>
                <tr data-filter="<?= e($cat !== '' ? $cat : 'all') ?>" data-search="<?= e($search) ?>">
                    <td><strong><?= e($p['nombre']) ?></strong><div class="admin-table-meta"><?= e($cat) ?></div></td>
                    <td><?= e($p['sku']) ?></td>
                    <td><?= e($p['precio_texto']) ?></td>
                    <td><code><?= e($slug) ?></code></td>
                    <td>
                        <div class="admin-table-actions">
                            <a class="admin-btn-icon" href="<?= e(admin_url('producto.php?slug=' . rawurlencode($slug))) ?>" title="Editar"><i class="fa-solid fa-pen"></i></a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php admin_layout_end(); ?>
