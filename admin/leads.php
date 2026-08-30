<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

admin_layout_start('Leads', 'leads');
$leads = leads_all();

admin_page_header('Pipeline B2B', 'Leads', 'Solicitudes de contacto y cotización del Sistema ELAH.');

$filters = ['all' => 'Todos'];
foreach (admin_estados_lead() as $key => $label) {
    $filters[$key] = $label;
}
admin_filter_bar($filters);
?>
<div class="admin-card">
    <div class="admin-list-meta">
        <span><strong data-admin-list-count="leads-table"><?= count($leads) ?></strong> resultados</span>
        <span>Usa ⌘K para buscar en el header</span>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table" id="leads-table" data-admin-list>
            <thead>
                <tr><th>Fecha</th><th>Origen</th><th>Hotel</th><th>Contacto</th><th>Interés</th><th>Subtotal</th><th>Estado</th><th></th></tr>
            </thead>
            <tbody>
            <?php if ($leads === []): ?>
                <tr class="admin-table-empty"><td colspan="8"><?php admin_empty_state('fa-inbox', 'No hay leads', 'Cuando un hotel envíe contacto o cotización desde el sitio, lo verás aquí.', '<a class="admin-btn admin-btn-outline admin-btn-sm" href="' . e(url('contacto.php')) . '" target="_blank" rel="noopener">Ver formulario público</a>'); ?></td></tr>
            <?php endif; ?>
            <?php foreach ($leads as $lead): ?>
                <?php
                $search = strtolower(implode(' ', [
                    $lead['hotel'], $lead['nombre'], $lead['email'], $lead['interes'], $lead['origen'], $lead['estado'],
                ]));
                ?>
                <tr data-filter="<?= e($lead['estado']) ?>" data-search="<?= e($search) ?>">
                    <td><?= e(date('d/m/Y H:i', strtotime($lead['fecha']))) ?></td>
                    <td><?= e($lead['origen']) ?></td>
                    <td><a href="<?= e(admin_url('lead.php?id=' . $lead['id'])) ?>"><?= e($lead['hotel']) ?></a></td>
                    <td><?= e($lead['nombre']) ?><div class="admin-table-meta"><?= e($lead['email']) ?></div></td>
                    <td><?= e($lead['interes']) ?></td>
                    <td><?= $lead['subtotal_sin_iva'] ? e(admin_money((int) $lead['subtotal_sin_iva'])) : '—' ?></td>
                    <td><span class="badge badge-<?= e($lead['estado']) ?>"><?= e(admin_estados_lead()[$lead['estado']] ?? $lead['estado']) ?></span></td>
                    <td>
                        <div class="admin-table-actions">
                            <a class="admin-btn-icon" href="<?= e(admin_url('lead.php?id=' . $lead['id'])) ?>" title="Ver"><i class="fa-solid fa-pen"></i></a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php admin_layout_end(); ?>
