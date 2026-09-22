<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
require_once __DIR__ . '/../includes/mailer.php';

$message = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    if (!admin_csrf_ok($_POST['csrf'] ?? null)) {
        $message = 'La sesión expiró. Inténtalo nuevamente.';
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) || rate_limit_exceeded('admin-reset', $username, $ip, 3, 60)) {
        $message = 'Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.';
    } else {
        $token = admin_password_reset_create($username);
        if ($token !== null) {
            send_customer_email($username, 'Restablece tu contraseña de Hotel Expert', 'Restablece tu contraseña', 'Recibimos una solicitud para cambiar tu contraseña. Este enlace vence en 1 hora.', 'Restablecer contraseña', admin_password_reset_url($token));
        }
        $message = 'Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.';
    }
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Recuperar acceso — Hotel Expert</title><link rel="stylesheet" href="<?= e(url('admin/assets/admin.css?v=2')) ?>"></head><body class="admin-login"><main class="admin-login-main"><div class="admin-login-form-card"><form method="post" class="admin-login-form"><input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>"><h1 class="admin-login-form-title">Recupera tu acceso</h1><?php if ($message): ?><div class="admin-alert admin-alert-success"><?= e($message) ?></div><?php endif; ?><label class="admin-label"><span>Correo de administrador</span><input class="admin-input" type="email" name="username" required autocomplete="email"></label><button class="admin-btn admin-btn-primary admin-btn-block-lg" type="submit">Enviar enlace</button><p class="admin-login-note"><a href="<?= e(admin_url('login.php')) ?>">Volver a iniciar sesión</a></p></form></div></main></body></html>
