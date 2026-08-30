<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

$id = (int) ($_GET['id'] ?? 0);
$lead = lead_get($id);
if (!$lead) {
    admin_flash('Lead no encontrado.', 'error');
    header('Location: ' . admin_url('leads.php'));
    exit;
}

$carrito = json_decode($lead['carrito'], true);
if (!is_array($carrito)) {
    $carrito = [];
}

admin_layout_start('Lead #' . $id, 'leads');
?>
<a class="admin-back-link" href="<?= e(admin_url('leads.php')) ?>"><i class="fa-solid fa-arrow-left"></i> Volver a leads</a>
<?php admin_page_header('Pipeline B2B', 'Lead #' . $id, $lead['hotel']); ?>

<div class="admin-detail-grid">
    <div class="admin-card admin-detail-main">
        <p class="admin-meta-label">Hotel</p>
        <p class="admin-meta-value-lg"><?= e($lead['hotel']) ?></p>

        <div class="admin-field-grid">
            <div><p class="admin-meta-label">Nombre</p><p class="admin-meta-value"><?= e($lead['nombre']) ?></p></div>
            <div><p class="admin-meta-label">Cargo</p><p class="admin-meta-value"><?= e($lead['cargo'] ?: '—') ?></p></div>
            <div><p class="admin-meta-label">Email</p><p class="admin-meta-value"><a href="mailto:<?= e($lead['email']) ?>" style="color:var(--admin-accent)"><?= e($lead['email']) ?></a></p></div>
            <div><p class="admin-meta-label">Teléfono</p><p class="admin-meta-value"><?= e($lead['telefono'] ?: '—') ?></p></div>
            <div><p class="admin-meta-label">Ciudad</p><p class="admin-meta-value"><?= e($lead['ciudad'] ?: '—') ?></p></div>
            <div><p class="admin-meta-label">Tipo propiedad</p><p class="admin-meta-value"><?= e($lead['tipo_propiedad'] ?: '—') ?></p></div>
            <div><p class="admin-meta-label">Habitaciones</p><p class="admin-meta-value"><?= e($lead['habitaciones'] ?: '—') ?></p></div>
            <div><p class="admin-meta-label">RFC</p><p class="admin-meta-value"><?= e($lead['rfc'] ?: '—') ?></p></div>
        </div>

        <p class="admin-meta-label">Interés</p>
        <p class="admin-meta-value"><?= e($lead['interes'] ?: '—') ?></p>

        <p class="admin-meta-label">Mensaje</p>
        <p class="admin-meta-value" style="white-space:pre-wrap"><?= e($lead['mensaje'] ?: '—') ?></p>

        <?php if ($carrito): ?>
            <p class="admin-meta-label">Carrito cotizado</p>
            <ul style="margin:0 0 16px;padding-left:18px;color:var(--admin-text-secondary);font-size:14px">
                <?php foreach ($carrito as $item): ?>
                    <li><?= e((string) ($item['cantidad'] ?? $item['qty'] ?? 1)) ?> × <?= e($item['nombre'] ?? $item['slug'] ?? '') ?> — <?= e(admin_money((int) ($item['subtotal'] ?? 0))) ?></li>
                <?php endforeach; ?>
            </ul>
            <p class="admin-meta-value" style="font-family:var(--font-heading);font-weight:700">Subtotal: <?= e(admin_money((int) $lead['subtotal_sin_iva'])) ?> + IVA</p>
        <?php endif; ?>

        <p style="font-size:12px;color:var(--admin-text-muted);margin:16px 0 0">Origen: <?= e($lead['origen']) ?> · IP: <?= e($lead['ip']) ?> · <?= e($lead['fecha']) ?></p>
    </div>

    <div class="admin-card admin-detail-side">
        <form method="post" action="<?= e(admin_url('action.php')) ?>">
            <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
            <input type="hidden" name="action" value="lead_update">
            <input type="hidden" name="id" value="<?= (int) $lead['id'] ?>">
            <?php admin_select('Estado', 'estado', admin_estados_lead(), $lead['estado']); ?>
            <?php admin_textarea('Notas internas', 'notas', $lead['notas'], 6); ?>
            <button class="admin-btn admin-btn-primary" type="submit" style="width:100%;margin-top:8px">Guardar</button>
        </form>
        <form method="post" action="<?= e(admin_url('action.php')) ?>" style="margin-top:16px" onsubmit="return confirm('¿Eliminar este lead?')">
            <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
            <input type="hidden" name="action" value="lead_delete">
            <input type="hidden" name="id" value="<?= (int) $lead['id'] ?>">
            <button class="admin-btn admin-btn-danger" type="submit" style="width:100%">Eliminar lead</button>
        </form>
    </div>
</div>
<?php admin_layout_end(); ?>
