<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

$id = strtoupper(trim((string) ($_GET['id'] ?? '')));
$row = null;
if ($id !== '') {
    foreach (pedidos_all() as $item) {
        if (strcasecmp($item['id'], $id) === 0) {
            $row = $item;
            break;
        }
    }
}
$isNew = !$row;
if ($isNew) {
    $row = ['id' => '', 'email' => '', 'hotel' => '', 'estado' => 'procesando', 'fecha' => date('Y-m-d'), 'eta' => '', 'items' => '', 'guia' => ''];
}
$products = productos_all();
$orderItems = $isNew ? [] : pedido_items((string) $row['id']);

admin_layout_start($isNew ? 'Nuevo pedido' : 'Editar pedido', 'pedidos');
admin_page_header('Logística B2B', $isNew ? 'Nuevo pedido' : 'Editar pedido', 'Datos de rastreo visibles en el sitio público.');
?>
<form method="post" action="<?= e(admin_url('action.php')) ?>" class="admin-card admin-form" style="max-width:640px">
    <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
    <input type="hidden" name="action" value="order_save">
    <?php admin_field('Folio (ID)', 'id', $row['id']); ?>
    <?php admin_field('Email cliente', 'email', $row['email'], 'email'); ?>
    <?php admin_field('Hotel', 'hotel', $row['hotel']); ?>
    <?php admin_select('Estado', 'estado', admin_estados_pedido(), $row['estado']); ?>
    <?php admin_field('Fecha pedido', 'fecha', (string) ($row['fecha'] ?? ''), 'date'); ?>
    <?php admin_field('ETA entrega', 'eta', (string) ($row['eta'] ?? ''), 'date'); ?>
    <fieldset class="admin-label">
        <span>Productos del pedido</span>
        <div class="admin-order-items">
            <?php for ($i = 0; $i < max(5, count($orderItems) + 1); $i++):
                $item = $orderItems[$i] ?? [];
                ?>
                <div class="admin-order-item-row">
                    <select class="admin-input" name="product_slug[]">
                        <option value="">Selecciona un producto</option>
                        <?php foreach ($products as $slug => $product): ?>
                            <option value="<?= e($slug) ?>" <?= ($item['slug'] ?? '') === $slug ? 'selected' : '' ?>><?= e($product['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input class="admin-input" type="number" min="1" max="99" name="product_qty[]" value="<?= e($item['cantidad'] ?? 1) ?>" aria-label="Cantidad"<?= admin_field_attrs([], 'product_qty') ?>>
                </div>
            <?php endfor; ?>
        </div>
    </fieldset>
    <?php admin_textarea('Descripción legada / notas de productos', 'items', $row['items'], 3); ?>
    <?php admin_field('Guía / notas envío', 'guia', $row['guia']); ?>
    <div class="admin-form-actions" style="border-top:none;padding-top:0">
        <button class="admin-btn admin-btn-primary" type="submit">Guardar pedido</button>
        <a class="admin-btn admin-btn-outline" href="<?= e(admin_url('pedidos.php')) ?>">Cancelar</a>
    </div>
</form>
<?php if (!$isNew): ?>
<form method="post" action="<?= e(admin_url('action.php')) ?>" style="margin-top:16px" onsubmit="return confirm('¿Eliminar pedido?')">
    <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
    <input type="hidden" name="action" value="order_delete">
    <input type="hidden" name="id" value="<?= e($row['id']) ?>">
    <button class="admin-btn admin-btn-danger" type="submit">Eliminar pedido</button>
</form>
<?php endif; ?>
<?php admin_layout_end(); ?>
