<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

$customers = [];
$customersLoadError = false;
try {
    $customers = customers_all();
} catch (Throwable $error) {
    $customersLoadError = true;
    error_log('Hotel Expert admin/clientes: error al cargar o descifrar clientes [' . get_class($error) . ']: ' . $error->getMessage());
}
admin_layout_start('Clientes', 'clientes');
admin_page_header('Portal', 'Clientes', 'Cuentas del portal. Si el correo de verificación no llega, puedes activar la cuenta aquí.');
?>
<?php if ($customersLoadError): ?>
    <div class="admin-alert admin-alert-error" role="alert">
        No se pudo cargar la lista de clientes. Verifica que el archivo <code>includes/repository.php</code> esté actualizado en el hosting y consulta el registro de errores PHP.
    </div>
<?php endif; ?>
<div class="admin-card">
    <div class="admin-list-meta">
        <span><strong data-admin-list-count="clientes-table"><?= count($customers) ?></strong> cuentas</span>
        <span>Usa ⌘K para buscar</span>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table" id="clientes-table" data-admin-list>
            <thead>
                <tr><th>Alta</th><th>Nombre</th><th>Hotel</th><th>Correo</th><th>Estado</th><th></th></tr>
            </thead>
            <tbody>
            <?php if ($customers === [] && !$customersLoadError): ?>
                <tr class="admin-table-empty"><td colspan="6"><?php admin_empty_state('fa-users', 'No hay clientes', 'Cuando un hotel cree cuenta en el portal, aparecerá aquí.'); ?></td></tr>
            <?php endif; ?>
            <?php foreach ($customers as $customer): ?>
                <?php
                $customerId = (int) ($customer['id'] ?? 0);
                $name = (string) ($customer['nombre'] ?? '');
                $hotel = (string) ($customer['hotel'] ?? '');
                $email = (string) ($customer['email'] ?? '');
                $verified = !empty($customer['email_verified_at']);
                $createdAt = strtotime((string) ($customer['created_at'] ?? ''));
                $createdLabel = $createdAt === false ? '—' : date('d/m/Y H:i', $createdAt);
                $search = strtolower(implode(' ', [
                    $name,
                    $hotel,
                    $email,
                    $verified ? 'verificado' : 'pendiente',
                ]));
                ?>
                <tr data-filter="<?= $verified ? 'verificado' : 'pendiente' ?>" data-search="<?= e($search) ?>">
                    <td><?= e($createdLabel) ?></td>
                    <td><?= e($name) ?></td>
                    <td><?= e($hotel) ?></td>
                    <td><?= e($email) ?></td>
                    <td>
                        <?php if ($verified): ?>
                            <span class="admin-badge admin-badge-success">Verificado</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge-warning">Pendiente</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$verified && $customerId > 0): ?>
                            <div class="admin-table-actions">
                                <form method="post" action="<?= e(admin_url('action.php')) ?>">
                                    <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
                                    <input type="hidden" name="action" value="customer_verify">
                                    <input type="hidden" name="id" value="<?= $customerId ?>">
                                    <button class="admin-btn admin-btn-primary admin-btn-sm" type="submit">Activar cuenta</button>
                                </form>
                                <form method="post" action="<?= e(admin_url('action.php')) ?>">
                                    <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
                                    <input type="hidden" name="action" value="customer_resend">
                                    <input type="hidden" name="id" value="<?= $customerId ?>">
                                    <button class="admin-btn admin-btn-sm" type="submit">Reenviar correo</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php admin_layout_end(); ?>
