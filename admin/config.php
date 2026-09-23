<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

$s = settings_all();
$totpEnabled = admin_totp_is_enabled(admin_user());
$setupSecret = admin_totp_setup_secret();
$recoveryCodes = $_SESSION['admin_totp_recovery_plain'] ?? [];
if (!is_array($recoveryCodes)) {
    $recoveryCodes = [];
}
$otpauth = $setupSecret !== ''
    ? totp_otpauth_uri($setupSecret, admin_user(), SITE_NAME)
    : '';
admin_layout_start('Configuración', 'config');

admin_page_header('Ajustes', 'Configuración', 'Datos del sitio, contacto y seguridad del panel.');
?>
<form method="post" action="<?= e(admin_url('action.php')) ?>" class="admin-card admin-form" style="max-width:640px">
    <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
    <input type="hidden" name="action" value="settings_save">

    <h2 class="admin-section-title">Sitio web</h2>
    <?php admin_field('Nombre del sitio', 'site_name', $s['site_name'] ?? SITE_NAME); ?>
    <?php admin_field('Tagline', 'site_tagline', $s['site_tagline'] ?? SITE_TAGLINE); ?>
    <?php admin_field('Claim', 'site_claim', $s['site_claim'] ?? SITE_CLAIM); ?>
    <?php admin_field('Dominio', 'site_domain', $s['site_domain'] ?? SITE_DOMAIN); ?>
    <?php admin_field('WhatsApp (número)', 'whatsapp', $s['whatsapp'] ?? WHATSAPP); ?>
    <?php admin_field('WhatsApp (display)', 'whatsapp_display', $s['whatsapp_display'] ?? WHATSAPP_DISPLAY); ?>
    <?php admin_field('Email ventas', 'email_ventas', $s['email_ventas'] ?? EMAIL_VENTAS, 'email'); ?>
    <?php admin_field('Facebook URL', 'social_facebook', $s['social_facebook'] ?? ''); ?>
    <?php admin_field('Instagram URL', 'social_instagram', $s['social_instagram'] ?? ''); ?>

    <h2 class="admin-section-title">Seguridad</h2>
    <?php admin_field('Contraseña actual', 'current_password', '', 'password', ['autocomplete' => 'current-password']); ?>
    <?php admin_field('Nueva contraseña admin (opcional)', 'new_password', '', 'password', ['autocomplete' => 'new-password']); ?>
    <?php admin_field('Confirmar nueva contraseña', 'new_password_confirmation', '', 'password', ['autocomplete' => 'new-password']); ?>
    <p style="font-size:13px;color:var(--admin-text-muted);margin:0 0 16px">Para cambiarla, usa al menos 12 caracteres, mayúscula, minúscula y número. Se cerrarán todas las sesiones administrativas.</p>
    <p style="font-size:13px;color:var(--admin-text-muted);margin:0 0 16px">Los cambios de contacto se guardan en la base de datos y se reflejan en el sitio público.</p>

    <button class="admin-btn admin-btn-primary" type="submit">Guardar configuración</button>
</form>

<section class="admin-card admin-form" style="max-width:640px;margin-top:24px">
    <h2 class="admin-section-title">Verificación en dos pasos</h2>
    <?php if ($recoveryCodes !== []): ?>
        <div class="admin-alert admin-alert-success">Guarda estos códigos de recuperación ahora. No se volverán a mostrar.</div>
        <ul class="admin-recovery-codes">
            <?php foreach ($recoveryCodes as $code): ?>
                <li><code><?= e((string) $code) ?></code></li>
            <?php endforeach; ?>
        </ul>
        <form method="post" action="<?= e(admin_url('action.php')) ?>">
            <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
            <input type="hidden" name="action" value="totp_ack_recovery">
            <button class="admin-btn admin-btn-primary" type="submit">Ya los guardé</button>
        </form>
    <?php elseif ($totpEnabled): ?>
        <p class="admin-2fa-status"><span class="admin-badge admin-badge-success">Activo</span> El inicio de sesión pide un código de la app autenticadora.</p>
        <p style="font-size:13px;color:var(--admin-text-muted);margin:0 0 16px">Códigos de recuperación restantes: <?= (int) admin_totp_remaining_recovery(admin_user()) ?>.</p>
        <form method="post" action="<?= e(admin_url('action.php')) ?>" class="admin-form">
            <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
            <input type="hidden" name="action" value="totp_recovery_regenerate">
            <?php admin_field('Contraseña actual', 'recovery_password', '', 'password', ['autocomplete' => 'current-password']); ?>
            <?php admin_field('Código de la app o de recuperación', 'totp_code', '', 'text', ['autocomplete' => 'one-time-code', 'maxlength' => '9']); ?>
            <button class="admin-btn" type="submit">Generar códigos de recuperación nuevos</button>
        </form>
        <form method="post" action="<?= e(admin_url('action.php')) ?>" class="admin-form" style="margin-top:24px">
            <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
            <input type="hidden" name="action" value="totp_disable">
            <?php admin_field('Contraseña actual', 'disable_password', '', 'password', ['autocomplete' => 'current-password']); ?>
            <?php admin_field('Código de la app o de recuperación', 'disable_code', '', 'text', ['autocomplete' => 'one-time-code', 'maxlength' => '9']); ?>
            <button class="admin-btn admin-btn-danger" type="submit">Desactivar 2FA</button>
        </form>
    <?php elseif ($setupSecret !== ''): ?>
        <p style="font-size:14px;color:var(--admin-text-secondary);margin:0 0 16px">Escanea el código con Google Authenticator, Microsoft Authenticator o Authy. Si no puedes escanear, escribe la clave manualmente.</p>
        <div class="admin-totp-setup">
            <div id="admin-totp-qr" class="admin-totp-qr" data-otpauth="<?= e($otpauth) ?>"></div>
            <div>
                <p class="admin-totp-secret-label">Clave manual</p>
                <code class="admin-totp-secret"><?= e($setupSecret) ?></code>
            </div>
        </div>
        <form method="post" action="<?= e(admin_url('action.php')) ?>" class="admin-form">
            <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
            <input type="hidden" name="action" value="totp_confirm">
            <?php admin_field('Contraseña actual', 'confirm_password', '', 'password', ['autocomplete' => 'current-password']); ?>
            <?php admin_field('Código de 6 dígitos', 'totp_code', '', 'text', ['autocomplete' => 'one-time-code', 'inputmode' => 'numeric', 'maxlength' => '6']); ?>
            <button class="admin-btn admin-btn-primary" type="submit">Confirmar y activar</button>
        </form>
        <form method="post" action="<?= e(admin_url('action.php')) ?>" style="margin-top:12px">
            <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
            <input type="hidden" name="action" value="totp_cancel">
            <button class="admin-btn" type="submit">Cancelar activación</button>
        </form>
    <?php else: ?>
        <p style="font-size:14px;color:var(--admin-text-secondary);margin:0 0 16px">Añade una capa extra: después de la contraseña, el panel pedirá un código de tu teléfono. Recomendado en producción.</p>
        <form method="post" action="<?= e(admin_url('action.php')) ?>">
            <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
            <input type="hidden" name="action" value="totp_start">
            <button class="admin-btn admin-btn-primary" type="submit">Activar 2FA</button>
        </form>
    <?php endif; ?>
</section>
<?php admin_layout_end(); ?>
