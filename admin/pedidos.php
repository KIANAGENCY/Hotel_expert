<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

admin_layout_start('Pedidos', 'pedidos');
$pedidos = pedidos_all();

$actions = '<a class="admin-btn admin-btn-primary" href="' . e(admin_url('pedido.php')) . '"><i class="fa-solid fa-plus"></i> Nuevo pedido</a>';
admin_page_header('Logística B2B', 'Pedidos', 'Rastreo de pedidos para hoteles.', $actions);

$filters = ['all' => 'Todos'];
foreach (admin_estados_pedido() as $key => $label) {
    $filters[$key] = $label;
}
admin_filter_bar($filters);
?>
<div class="admin-card">
    <div class="admin-list-meta">
        <span><strong data-admin-list-count="pedidos-table"><?= count($pedidos) ?></strong> pedidos</span>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table" id="pedidos-table" data-admin-list>
            <thead><tr><th>Folio</th><th>Hotel</th><th>Email</th><th>Estado</th><th>ETA</th><th></th></tr></thead>
            <tbody>
            <?php if ($pedidos === []): ?>
                <tr class="admin-table-empty"><td colspan="6"><?php admin_empty_state('fa-truck-fast', 'Sin pedidos', 'Registra pedidos B2B o espera solicitudes desde el catálogo.', '<a class="admin-btn admin-btn-primary admin-btn-sm" href="' . e(admin_url('pedido.php')) . '"><i class="fa-solid fa-plus"></i> Nuevo pedido</a>'); ?></td></tr>
            <?php endif; ?>
            <?php foreach ($pedidos as $row): ?>
                <?php
                $search = strtolower(implode(' ', [$row['id'], $row['hotel'], $row['email'], $row['estado'], $row['eta'] ?? '']));
                ?>
                <tr data-filter="<?= e($row['estado']) ?>" data-search="<?= e($search) ?>">
                    <td><strong><?= e($row['id']) ?></strong></td>
                    <td><?= e($row['hotel']) ?></td>
                    <td><?= e($row['email']) ?></td>
                    <td><span class="badge badge-<?= e($row['estado']) ?>"><?= e(admin_estados_pedido()[$row['estado']] ?? $row['estado']) ?></span></td>
                    <td><?= e($row['eta']) ?></td>
                    <td>
                        <div class="admin-table-actions">
                            <a class="admin-btn-icon" href="<?= e(admin_url('pedido.php?id=' . rawurlencode($row['id']))) ?>" title="Editar"><i class="fa-solid fa-pen"></i></a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php admin_layout_end(); ?>
