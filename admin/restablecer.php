<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';

$token = (string) ($_GET['token'] ?? $_POST['token'] ?? '');
$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $password = (string) ($_POST['password'] ?? '');
    $confirm = (string) ($_POST['password_confirm'] ?? '');
    if (!admin_csrf_ok($_POST['csrf'] ?? null)) {
        $error = 'La sesión expiró. Inténtalo nuevamente.';
    } elseif ($password !== $confirm || mb_strlen($password) < 12) {
        $error = 'Usa una contraseña de al menos 12 caracteres y confírmala correctamente.';
    } elseif (admin_password_reset_consume($token, $password)) {
        admin_flash('Contraseña actualizada. Ya puedes iniciar sesión.');
        header('Location: ' . admin_url('login.php'));
        exit;
    } else {
        $error = 'El enlace es inválido o venció. Solicita uno nuevo.';
    }
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Nueva contraseña — Hotel Expert</title><link rel="stylesheet" href="<?= e(url('admin/assets/admin.css?v=2')) ?>"></head><body class="admin-login"><main class="admin-login-main"><div class="admin-login-form-card"><form method="post" class="admin-login-form"><input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>"><input type="hidden" name="token" value="<?= e($token) ?>"><h1 class="admin-login-form-title">Crea una nueva contraseña</h1><?php if ($error): ?><div class="admin-alert admin-alert-error"><?= e($error) ?></div><?php endif; ?><label class="admin-label"><span>Nueva contraseña</span><input class="admin-input" type="password" name="password" required minlength="12" autocomplete="new-password"></label><label class="admin-label"><span>Confirmar contraseña</span><input class="admin-input" type="password" name="password_confirm" required minlength="12" autocomplete="new-password"></label><button class="admin-btn admin-btn-primary admin-btn-block-lg" type="submit">Guardar contraseña</button></form></div></main></body></html>
