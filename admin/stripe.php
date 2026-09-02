<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
require_once __DIR__ . '/../includes/stripe-config.php';
require_once __DIR__ . '/../includes/cart-pricing.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

$s = stripe_config_defaults();
$status = stripe_status_summary();
$ivaRate = checkout_iva_rate();
$envBackup = env_file_latest_backup_basename(env_file_path());
$webhookUrl = stripe_webhook_url();

admin_layout_start('Pagos Stripe', 'stripe');

admin_page_header('Sistema', 'Pagos Stripe', 'Configura la pasarela de pago Stripe para cobros B2B en el portal de clientes.');
?>
<form method="post" action="<?= e(admin_url('action.php')) ?>" class="admin-card admin-form" style="max-width:760px">
    <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">

    <h2 class="admin-section-title">Estado</h2>
    <div class="admin-deploy-status">
        <p>
            <span class="admin-badge <?= $status['enabled'] ? 'admin-badge-success' : 'admin-badge-warning' ?>">
                Pasarela: <?= $status['enabled'] ? 'activa' : 'inactiva' ?>
            </span>
            <span class="admin-badge <?= $status['ready'] ? 'admin-badge-success' : 'admin-badge-warning' ?>">
                Modo <?= e($status['mode']) ?>: <?= $status['ready'] ? 'listo' : 'incompleto' ?>
            </span>
        </p>
        <p style="font-size:13px;color:var(--admin-text-muted);margin:8px 0 0">
            Clave publicable: <?= $status['publishable'] ? 'configurada' : 'pendiente' ?> ·
            Clave secreta: <?= $status['secret'] ? 'configurada' : 'pendiente' ?> ·
            Webhook: <?= $status['webhook'] ? 'configurado' : 'opcional' ?>
        </p>
    </div>

    <h2 class="admin-section-title">Pasarela</h2>
    <label class="admin-label admin-checkbox-row">
        <input type="checkbox" name="STRIPE_ENABLED" value="1" <?= filter_var($s['STRIPE_ENABLED'], FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' ?>>
        <span>Activar pagos con Stripe</span>
    </label>
    <?php admin_select('Modo', 'STRIPE_MODE', ['test' => 'Pruebas (test)', 'live' => 'Producción (live)'], $s['STRIPE_MODE']); ?>
    <?php admin_field('Moneda', 'STRIPE_CURRENCY', $s['STRIPE_CURRENCY']); ?>

    <h2 class="admin-section-title">Carrito y facturación</h2>
    <?php admin_field('Tasa de IVA (%)', 'checkout_iva_rate', (string) $ivaRate, 'number', ['min' => '0', 'max' => '100', 'step' => '0.01', 'inputmode' => 'decimal']); ?>
    <p style="font-size:13px;color:var(--admin-text-muted);margin:0 0 16px">Se aplica a productos marcados con IVA en el catálogo. El carrito muestra subtotal, IVA y total antes del pago.</p>

    <h2 class="admin-section-title">Claves API</h2>
    <p style="font-size:13px;color:var(--admin-text-muted);margin:0 0 16px">
        Obtén las claves en el <a href="https://dashboard.stripe.com/apikeys" target="_blank" rel="noopener noreferrer">panel de Stripe</a>.
        Usa <strong>test</strong> hasta validar el flujo de cobro.
    </p>
    <?php admin_field('Clave publicable', 'STRIPE_PUBLISHABLE_KEY', $s['STRIPE_PUBLISHABLE_KEY']); ?>
    <?php admin_field(
        'Clave secreta' . (env_file_secret_is_set('STRIPE_SECRET_KEY') ? ' (configurada — vacío = mantener)' : ''),
        'STRIPE_SECRET_KEY',
        '',
        'password'
    ); ?>

    <h2 class="admin-section-title">Webhook</h2>
    <p style="font-size:13px;color:var(--admin-text-muted);margin:0 0 12px">
        URL para registrar en Stripe → Developers → Webhooks:
    </p>
    <p style="margin:0 0 16px"><code><?= e($webhookUrl) ?></code></p>
    <?php admin_field(
        'Secreto de firma (whsec_)' . (env_file_secret_is_set('STRIPE_WEBHOOK_SECRET') ? ' (configurado — vacío = mantener)' : ''),
        'STRIPE_WEBHOOK_SECRET',
        '',
        'password'
    ); ?>
    <p style="font-size:13px;color:var(--admin-text-muted);margin:0 0 16px">
        Recomendado para confirmar pagos de forma segura cuando conectes el checkout.
    </p>

    <?php if ($envBackup !== ''): ?>
        <p style="font-size:13px;color:var(--admin-text-muted);margin:0 0 16px">Último respaldo de <code>.env</code>: <code><?= e($envBackup) ?></code></p>
    <?php endif; ?>

    <h2 class="admin-section-title">Confirmar cambios</h2>
    <?php admin_field('Contraseña de administrador', 'admin_password', '', 'password'); ?>

    <div class="admin-form-actions" style="display:flex;gap:12px;flex-wrap:wrap">
        <button class="admin-btn admin-btn-secondary" type="submit" name="action" value="stripe_test">Probar conexión Stripe</button>
        <button class="admin-btn admin-btn-primary" type="submit" name="action" value="stripe_save">Guardar Stripe</button>
    </div>
</form>
<?php admin_layout_end(); ?>
