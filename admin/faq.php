<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

admin_layout_start('FAQ', 'faq');
$faqs = faqs_admin();

admin_page_header('Contenido', 'FAQ', 'Preguntas frecuentes del sitio público.');
?>
<div class="admin-two-col">
    <form method="post" action="<?= e(admin_url('action.php')) ?>" class="admin-card admin-form">
        <h2 class="admin-section-title">Nueva pregunta</h2>
        <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
        <input type="hidden" name="action" value="faq_save">
        <?php admin_textarea('Pregunta', 'pregunta', '', 2); ?>
        <?php admin_textarea('Respuesta', 'respuesta', '', 4); ?>
        <?php admin_field('Orden', 'sort_order', '0', 'number'); ?>
        <button class="admin-btn admin-btn-primary" type="submit">Agregar FAQ</button>
    </form>
    <div class="admin-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Pregunta</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($faqs as $faq): ?>
                    <tr>
                        <td>
                            <details>
                                <summary><?= e($faq['pregunta']) ?></summary>
                                <p class="mt-2"><?= e($faq['respuesta']) ?></p>
                                <form method="post" action="<?= e(admin_url('action.php')) ?>" style="margin-top:12px">
                                    <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
                                    <input type="hidden" name="action" value="faq_save">
                                    <input type="hidden" name="id" value="<?= (int) $faq['id'] ?>">
                                    <textarea class="admin-input" name="pregunta" rows="2"<?= admin_field_attrs([], 'pregunta') ?>><?= e($faq['pregunta']) ?></textarea>
                                    <textarea class="admin-input" name="respuesta" rows="3" style="margin-top:8px"<?= admin_field_attrs([], 'respuesta') ?>><?= e($faq['respuesta']) ?></textarea>
                                    <input class="admin-input" type="number" name="sort_order" value="<?= (int) $faq['sort_order'] ?>" style="margin-top:8px"<?= admin_field_attrs([], 'sort_order') ?>>
                                    <button class="admin-btn admin-btn-primary admin-btn-sm" type="submit" style="margin-top:8px">Actualizar</button>
                                </form>
                            </details>
                        </td>
                        <td>
                            <form method="post" action="<?= e(admin_url('action.php')) ?>" onsubmit="return confirm('¿Eliminar?')">
                                <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
                                <input type="hidden" name="action" value="faq_delete">
                                <input type="hidden" name="id" value="<?= (int) $faq['id'] ?>">
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
