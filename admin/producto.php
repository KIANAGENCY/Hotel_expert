<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

$slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) ($_GET['slug'] ?? '')));
$p = $slug ? producto_get($slug) : null;
$isNew = !$p;
if ($isNew) {
    $p = [
        'slug' => '', 'sku' => '', 'nombre' => '', 'categoria' => '', 'subtitulo' => '', 'resumen' => '',
        'presentacion' => '', 'rendimiento' => '', 'precio' => 0, 'precio_texto' => '', 'precio_lista' => '',
        'iva' => true, 'imagen' => '', 'alt' => '', 'icono' => '', 'funcion' => '', 'especialidad' => '',
        'claims' => [], 'superficies' => [], 'no_usar' => ['Vidrio'],
    ];
}

admin_layout_start($isNew ? 'Nuevo producto' : 'Editar producto', 'productos');
admin_page_header('Catálogo ELAH', $isNew ? 'Nuevo producto' : 'Editar producto', 'Completa la ficha del producto para el sitio público.');
?>
<form method="post" action="<?= e(admin_url('action.php')) ?>" class="admin-card admin-form admin-form-grid">
    <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
    <input type="hidden" name="action" value="product_save">
    <?php admin_field('Slug (URL)', 'slug', $p['slug']); ?>
    <?php admin_field('SKU', 'sku', $p['sku']); ?>
    <?php admin_field('Nombre', 'nombre', $p['nombre']); ?>
    <?php admin_field('Categoría', 'categoria', $p['categoria']); ?>
    <?php admin_field('Subtítulo', 'subtitulo', $p['subtitulo']); ?>
    <?php admin_textarea('Resumen', 'resumen', $p['resumen'], 3); ?>
    <?php admin_field('Presentación', 'presentacion', $p['presentacion']); ?>
    <?php admin_field('Rendimiento', 'rendimiento', $p['rendimiento']); ?>
    <?php admin_field('Precio (número)', 'precio', (string) $p['precio'], 'number'); ?>
    <?php admin_field('Precio texto', 'precio_texto', $p['precio_texto']); ?>
    <?php admin_field('Precio lista (opcional)', 'precio_lista', (string) ($p['precio_lista'] ?? '')); ?>
    <?php admin_field('Imagen (archivo en assets/img/)', 'imagen', (string) ($p['imagen'] ?? '')); ?>
    <?php admin_field('Alt imagen', 'alt', $p['alt'] ?? ''); ?>
    <?php admin_field('Icono monograma', 'icono', $p['icono']); ?>
    <?php admin_textarea('Función', 'funcion', $p['funcion'], 2); ?>
    <?php admin_textarea('Especialidad', 'especialidad', $p['especialidad'], 2); ?>
    <?php admin_textarea('Claims (uno por línea)', 'claims', implode("\n", $p['claims']), 4); ?>
    <?php admin_textarea('Superficies (una por línea)', 'superficies', implode("\n", $p['superficies']), 4); ?>
    <?php admin_textarea('No usar (una por línea)', 'no_usar', implode("\n", $p['no_usar']), 2); ?>
    <?php admin_field('Orden', 'sort_order', '0', 'number'); ?>
    <label class="admin-label admin-form-full" style="display:flex;align-items:center;gap:8px">
        <input type="checkbox" name="iva" value="1" <?= !empty($p['iva']) ? 'checked' : '' ?>>
        <span>Precio + IVA</span>
    </label>
    <div class="admin-form-actions">
        <button class="admin-btn admin-btn-primary" type="submit">Guardar producto</button>
        <a class="admin-btn admin-btn-outline" href="<?= e(admin_url('productos.php')) ?>">Cancelar</a>
    </div>
</form>
<?php if (!$isNew): ?>
<form method="post" action="<?= e(admin_url('action.php')) ?>" style="margin-top:16px" onsubmit="return confirm('¿Eliminar producto?')">
    <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
    <input type="hidden" name="action" value="product_delete">
    <input type="hidden" name="slug" value="<?= e($slug) ?>">
    <button class="admin-btn admin-btn-danger" type="submit">Eliminar producto</button>
</form>
<?php endif; ?>
<?php admin_layout_end(); ?>
