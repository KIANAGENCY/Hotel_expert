<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

$s = settings_all();
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
    <p style="font-size:13px;color:var(--admin-text-muted);margin:0 0 16px">Para cambiarla, usa al menos 10 caracteres, mayúscula, minúscula y número. Se cerrarán todas las sesiones administrativas.</p>
    <p style="font-size:13px;color:var(--admin-text-muted);margin:0 0 16px">Los cambios de contacto se guardan en la base de datos y se reflejan en el sitio público.</p>

    <button class="admin-btn admin-btn-primary" type="submit">Guardar configuración</button>
</form>
<?php admin_layout_end(); ?>
