<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/customer-auth.php';
require_once __DIR__ . '/../includes/order-status.php';
customer_require_login();

$customer = current_customer();
$orders = pedidos_by_customer((int) $customer['id']);
$recent = $orders[0] ?? null;
$reorderable = [];
foreach ($orders as $order) {
    $reorderable[(string) $order['id']] = pedido_items((string) $order['id']) !== [];
}
$flash = account_flash_consume();
$statuses = order_statuses();
$page_title = 'Mi cuenta — Hotel Expert';
$page_description = 'Consulta pedidos, rastreo y recompra de productos Hotel Expert.';
require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
?>
<main id="contenido" class="account-dashboard pt-28">
    <section class="account-dashboard-hero">
        <div>
            <p class="eyebrow text-aqua">Portal de clientes</p>
            <h1>Hola, <?= e(explode(' ', (string) $customer['nombre'])[0]) ?>.</h1>
            <p><?= e($customer['hotel']) ?> · Panel de operaciones</p>
        </div>
        <div class="account-dashboard-summary" aria-label="Resumen de la cuenta">
            <span><?= count($orders) ?></span>
            <small><?= count($orders) === 1 ? 'pedido registrado' : 'pedidos registrados' ?></small>
        </div>
    </section>

    <div class="account-dashboard-shell">
        <?php if ($flash): ?><p class="account-alert is-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></p><?php endif; ?>

        <?php if ($recent): ?>
            <div class="account-operations-grid">
                <section class="account-current-order account-active-order">
                    <div class="account-section-heading">
                        <div>
                            <p class="eyebrow">Estado actual</p>
                            <h2>Pedido <?= e($recent['id']) ?></h2>
                            <p class="account-order-date">Realizado el <?= e($recent['fecha'] ?: 'fecha pendiente') ?></p>
                        </div>
                        <span class="account-status"><?= e($statuses[$recent['estado']] ?? $recent['estado']) ?></span>
                    </div>
                    <?php render_order_timeline((string) $recent['estado']); ?>
                    <div class="account-shipping-grid account-shipping-grid-compact">
                        <div>
                            <span>Entrega estimada</span>
                            <strong><?= e($recent['eta'] ?: 'Por confirmar') ?></strong>
                        </div>
                        <div>
                            <span>Guía / referencia</span>
                            <strong><?= e($recent['guia'] ?: 'Por asignar') ?></strong>
                        </div>
                    </div>
                    <a class="account-detail-link" href="<?= e(account_url('pedido/?id=' . rawurlencode($recent['id']))) ?>">
                        Ver detalle completo <span aria-hidden="true">→</span>
                    </a>
                </section>

                <aside class="account-panel account-quick-actions">
                    <p class="eyebrow">Acciones rápidas</p>
                    <h2>Gestionar pedido</h2>
                    <?php if ($reorderable[(string) $recent['id']] ?? false): ?>
                        <form method="post" action="<?= e(account_url('action/')) ?>">
                            <input type="hidden" name="csrf" value="<?= e(customer_csrf()) ?>">
                            <input type="hidden" name="action" value="reorder">
                            <input type="hidden" name="order_id" value="<?= e($recent['id']) ?>">
                            <button class="btn-primary account-reorder-primary" type="submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h2l2.2 10.1a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L20.5 8H6.1M10 20h.01M17 20h.01"/></svg>
                                Recomprar productos
                            </button>
                        </form>
                    <?php endif; ?>
                    <p class="account-quick-label">Otras opciones</p>
                    <div class="account-quick-links">
                        <a href="<?= e(account_url('pedido/?id=' . rawurlencode($recent['id']))) ?>">Ver seguimiento <span aria-hidden="true">→</span></a>
                        <a href="<?= e(url('cotizacion/')) ?>">Nueva cotización <span aria-hidden="true">→</span></a>
                        <a href="<?= e(url('catalogo/')) ?>">Explorar catálogo <span aria-hidden="true">→</span></a>
                    </div>
                </aside>
            </div>
        <?php else: ?>
            <section class="account-empty">
                <p class="eyebrow">Tu historial</p>
                <h2>Aún no hay pedidos vinculados.</h2>
                <p>Cuando un pedido use el correo <?= e($customer['email']) ?> aparecerá aquí automáticamente.</p>
                <a class="btn-primary" href="<?= e(url('catalogo/')) ?>">Preparar una cotización</a>
            </section>
        <?php endif; ?>

        <section class="account-panel account-history">
            <div class="account-section-heading">
                <div><p class="eyebrow">Historial</p><h2>Historial de pedidos</h2></div>
                <span class="account-history-count"><?= count($orders) ?></span>
            </div>
            <?php if ($orders): ?>
                <div class="account-order-list" role="table" aria-label="Historial de pedidos">
                    <div class="account-order-list-head" role="row">
                        <span role="columnheader">Pedido</span><span role="columnheader">Fecha</span><span role="columnheader">Estado</span><span role="columnheader">Acciones</span>
                    </div>
                    <?php foreach ($orders as $order): ?>
                        <div class="account-order-row" role="row">
                            <a class="account-order-id" role="cell" href="<?= e(account_url('pedido/?id=' . rawurlencode($order['id']))) ?>"><?= e($order['id']) ?></a>
                            <span role="cell"><?= e($order['fecha'] ?: 'Sin fecha') ?></span>
                            <span role="cell"><em class="account-status"><?= e($statuses[$order['estado']] ?? $order['estado']) ?></em></span>
                            <div class="account-order-row-actions" role="cell">
                                <a href="<?= e(account_url('pedido/?id=' . rawurlencode($order['id']))) ?>" aria-label="Ver pedido <?= e($order['id']) ?>">Ver detalle</a>
                                <?php if ($reorderable[(string) $order['id']] ?? false): ?>
                                    <form method="post" action="<?= e(account_url('action/')) ?>">
                                        <input type="hidden" name="csrf" value="<?= e(customer_csrf()) ?>">
                                        <input type="hidden" name="action" value="reorder">
                                        <input type="hidden" name="order_id" value="<?= e($order['id']) ?>">
                                        <button type="submit" aria-label="Recomprar pedido <?= e($order['id']) ?>">Recomprar</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?><p class="account-muted">Sin pedidos todavía.</p><?php endif; ?>
        </section>

        <section class="account-panel account-profile-panel">
            <div class="account-profile-intro">
                <p class="eyebrow">Datos de la cuenta</p>
                <h2>Perfil del hotel</h2>
                <p>Actualiza los datos que usamos para identificar tu operación y dar seguimiento a tus solicitudes.</p>
            </div>
            <form method="post" action="<?= e(account_url('action/')) ?>" class="account-form compact account-profile-form">
                <input type="hidden" name="csrf" value="<?= e(customer_csrf()) ?>">
                <input type="hidden" name="action" value="profile">
                <label>
                    <span>Nombre y apellido</span>
                    <input class="field" name="nombre" required minlength="5" maxlength="190" pattern=".*\S+\s+\S+.*" title="Escribe por lo menos un nombre y un apellido" placeholder="Ej. María González" autocomplete="name" aria-describedby="profile-name-help" value="<?= e($customer['nombre']) ?>">
                    <small id="profile-name-help" class="account-profile-help">Escribe nombre y apellido, sin dejar el campo vacío.</small>
                </label>
                <label>
                    <span>Hotel / empresa</span>
                    <input class="field" name="hotel" required minlength="3" maxlength="190" title="Escribe el nombre completo del hotel o empresa" placeholder="Ej. Hotel Reforma" autocomplete="organization" aria-describedby="profile-hotel-help" value="<?= e($customer['hotel']) ?>">
                    <small id="profile-hotel-help" class="account-profile-help">Mínimo 3 caracteres.</small>
                </label>
                <label>
                    <span>Teléfono</span>
                    <input class="field" type="tel" name="telefono" maxlength="60" inputmode="tel" pattern="[0-9+() .-]{7,60}" title="Usa entre 7 y 60 caracteres: números, espacios, paréntesis, guiones o signo +" placeholder="Ej. 81 1234 5678" autocomplete="tel" aria-describedby="profile-phone-help" value="<?= e($customer['telefono']) ?>">
                    <small id="profile-phone-help" class="account-profile-help">Opcional. Incluye lada; acepta números, espacios, +, paréntesis y guiones.</small>
                </label>
                <label>
                    <span>RFC</span>
                    <input class="field" name="rfc" minlength="12" maxlength="13" pattern="[A-Za-zÑñ&amp;]{3,4}[0-9]{6}[A-Za-z0-9]{3}" title="Escribe un RFC válido de 12 o 13 caracteres" placeholder="Ej. HRE250101AB1" autocapitalize="characters" aria-describedby="profile-rfc-help" value="<?= e($customer['rfc']) ?>">
                    <small id="profile-rfc-help" class="account-profile-help">Opcional. RFC de persona moral (12) o física (13 caracteres).</small>
                </label>
                <button class="btn-outline justify-center" type="submit">Guardar datos</button>
            </form>
        </section>
    </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
