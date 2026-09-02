<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/customer-auth.php';
require_once __DIR__ . '/../includes/order-status.php';
customer_require_login();

$customer = current_customer();
$id = strtoupper(trim((string) ($_GET['id'] ?? '')));
$order = pedido_for_customer($id, (int) $customer['id']);
if (!$order) {
    http_response_code(404);
    $page_title = 'Pedido no encontrado — Hotel Expert';
} else {
    $page_title = 'Pedido ' . $order['id'] . ' — Hotel Expert';
}
$items = $order ? pedido_items((string) $order['id']) : [];
$statuses = order_statuses();
$page_description = 'Detalle y seguimiento de pedido Hotel Expert.';
require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
?>
<main id="contenido" class="account-order-page pt-28">
    <div class="account-order-shell">
        <?php if (!$order): ?>
            <section class="account-empty">
                <h1>Pedido no encontrado</h1>
                <p>Este pedido no pertenece a tu cuenta o no existe.</p>
                <div class="account-portal-cta-actions">
                    <a class="btn-outline" href="<?= e(account_url()) ?>">Volver a mi cuenta</a>
                    <a class="btn-primary" href="<?= e(url('/')) ?>">Ir al inicio</a>
                </div>
            </section>
        <?php else: ?>
            <section class="account-order-header">
                <div><p class="eyebrow">Seguimiento</p><h1><?= e($order['id']) ?></h1><p><?= e($order['hotel']) ?> · <?= e($order['fecha'] ?: 'Fecha pendiente') ?></p></div>
                <span class="account-status"><?= e($statuses[$order['estado']] ?? $order['estado']) ?></span>
            </section>
            <section class="account-current-order">
                <?php render_order_timeline((string) $order['estado']); ?>
                <div class="account-shipping-grid">
                    <div><span>Entrega estimada</span><strong><?= e($order['eta'] ?: 'Por confirmar') ?></strong></div>
                    <div><span>Guía / referencia</span><strong><?= e($order['guia'] ?: 'Por asignar') ?></strong></div>
                </div>
            </section>
            <section class="account-panel account-order-products">
                <div class="account-section-heading"><div><p class="eyebrow">Productos</p><h2>Detalle del pedido</h2></div></div>
                <?php if ($items): ?>
                    <ul>
                        <?php foreach ($items as $item): ?>
                            <li><span><strong><?= e($item['nombre']) ?></strong><small><?= e($item['slug']) ?></small></span><b><?= (int) $item['cantidad'] ?> × <?= e('$' . number_format((int) $item['precio_unitario'])) ?></b></li>
                        <?php endforeach; ?>
                    </ul>
                    <form method="post" action="<?= e(account_url('action/')) ?>">
                        <input type="hidden" name="csrf" value="<?= e(customer_csrf()) ?>">
                        <input type="hidden" name="action" value="reorder">
                        <input type="hidden" name="order_id" value="<?= e($order['id']) ?>">
                        <button class="btn-primary" type="submit">Recomprar estos productos</button>
                    </form>
                <?php else: ?>
                    <p class="account-muted"><?= e($order['items']) ?></p>
                    <p class="account-field-help">Este pedido es anterior al historial estructurado; contacta al equipo para repetirlo.</p>
                <?php endif; ?>
            </section>
            <section class="account-portal-cta account-portal-cta-compact">
                <div>
                    <p class="eyebrow">Seguir navegando</p>
                    <h2>¿Quieres revisar más productos?</h2>
                </div>
                <div class="account-portal-cta-actions">
                    <a class="btn-outline" href="<?= e(account_url()) ?>">Mi cuenta</a>
                    <a class="btn-outline" href="<?= e(url('catalogo/')) ?>">Catálogo</a>
                    <a class="btn-primary" href="<?= e(url('/')) ?>">Ir al inicio</a>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
