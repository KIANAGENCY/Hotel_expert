<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

admin_layout_start('Áreas del hotel', 'areas');
$areas = areas_admin();

admin_page_header('Contenido', 'Áreas del hotel', 'Tarjetas de áreas operativas del hotel.');
?>
<div class="admin-two-col">
    <form method="post" action="<?= e(admin_url('action.php')) ?>" class="admin-card admin-form">
        <h2 class="admin-section-title">Nueva área</h2>
        <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
        <input type="hidden" name="action" value="area_save">
        <?php admin_field('Título', 'titulo', ''); ?>
        <?php admin_textarea('Texto', 'texto', '', 3); ?>
        <?php admin_field('Enlace (href)', 'href', 'para-tu-hotel.php#'); ?>
        <?php admin_field('Orden', 'sort_order', '0', 'number'); ?>
        <button class="admin-btn admin-btn-primary" type="submit">Agregar área</button>
    </form>
    <div class="admin-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Área</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($areas as $area): ?>
                    <tr>
                        <td>
                            <form method="post" action="<?= e(admin_url('action.php')) ?>">
                                <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
                                <input type="hidden" name="action" value="area_save">
                                <input type="hidden" name="id" value="<?= (int) $area['id'] ?>">
                                <input class="admin-input" name="titulo" value="<?= e($area['titulo']) ?>"<?= admin_field_attrs([], 'titulo') ?>>
                                <textarea class="admin-input" name="texto" rows="2" style="margin-top:8px"<?= admin_field_attrs([], 'texto') ?>><?= e($area['texto']) ?></textarea>
                                <input class="admin-input" name="href" value="<?= e($area['href']) ?>" style="margin-top:8px"<?= admin_field_attrs([], 'href') ?>>
                                <input class="admin-input" type="number" name="sort_order" value="<?= (int) $area['sort_order'] ?>" style="margin-top:8px"<?= admin_field_attrs([], 'sort_order') ?>>
                                <button class="admin-btn admin-btn-primary admin-btn-sm" type="submit" style="margin-top:8px">Actualizar</button>
                            </form>
                        </td>
                        <td>
                            <form method="post" action="<?= e(admin_url('action.php')) ?>" onsubmit="return confirm('¿Eliminar?')">
                                <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
                                <input type="hidden" name="action" value="area_delete">
                                <input type="hidden" name="id" value="<?= (int) $area['id'] ?>">
                                <button class="admin-btn-icon is-danger" type="submit" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php admin_layout_end(); ?>
