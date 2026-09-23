<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
require_once __DIR__ . '/includes/layout.php';

if (admin_session_is_valid()) {
    header('Location: ' . admin_url('index.php'));
    exit;
}

$pendingUser = admin_2fa_pending_user();
if ($pendingUser === '') {
    header('Location: ' . admin_url('login.php'));
    exit;
}

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!admin_csrf_ok($_POST['csrf'] ?? null)) {
        $error = 'La sesión expiró. Recarga la página e inténtalo de nuevo.';
    } elseif (!empty($_POST['cancel'])) {
        unset($_SESSION['admin_2fa_pending'], $_SESSION['admin_2fa_pending_until']);
        header('Location: ' . admin_url('login.php'));
        exit;
    } elseif (login_is_limited('admin-2fa', $pendingUser, (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'))) {
        $error = 'Demasiados intentos fallidos. Espera 15 minutos o usa un código de recuperación.';
    } elseif (admin_complete_2fa((string) ($_POST['totp_code'] ?? ''))) {
        header('Location: ' . admin_url('index.php'));
        exit;
    } else {
        $error = 'Código incorrecto o ya usado. Prueba el de la app o un código de recuperación.';
        $pendingUser = admin_2fa_pending_user();
        if ($pendingUser === '') {
            header('Location: ' . admin_url('login.php'));
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación — Panel Hotel Expert</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800;900&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= e(url('admin/assets/admin.css?v=3')) ?>">
</head>
<body class="admin-login">
<div class="admin-login-mobile-brand">
    <?php admin_brand_markup(); ?>
</div>
<div class="admin-login-shell">
    <aside class="admin-login-aside">
        <?php admin_brand_markup(); ?>

        <div>
            <p class="admin-login-hero-tag">Segundo factor</p>
            <h1 class="admin-login-hero-title">Confirma que eres tú.</h1>
            <p class="admin-login-hero-text">Abre Google Authenticator, Microsoft Authenticator o Authy e ingresa el código de 6 dígitos. También puedes usar un código de recuperación.</p>
        </div>

        <div class="admin-login-features">
            <span class="admin-login-feature"><i class="fa-solid fa-shield-halved"></i>El código cambia cada 30 segundos</span>
            <span class="admin-login-feature"><i class="fa-solid fa-key"></i>Los códigos de recuperación son de un solo uso</span>
        </div>
    </aside>

    <main class="admin-login-main">
        <div class="admin-login-form-card">
        <form method="post" class="admin-login-form">
            <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
            <div>
                <p class="admin-login-form-tag">Verificación en dos pasos</p>
                <h2 class="admin-login-form-title">Ingresa tu código</h2>
            </div>

            <?php if ($error): ?>
                <div class="admin-alert admin-alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <label class="admin-label">
                <span>Código</span>
                <input class="admin-input admin-2fa-code" name="totp_code" required autofocus autocomplete="one-time-code" autocapitalize="characters" spellcheck="false" maxlength="9" placeholder="000000" inputmode="text">
            </label>

            <button class="admin-btn admin-btn-primary admin-btn-block-lg" type="submit">
                Verificar y entrar <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
        <form method="post" class="admin-login-form" style="margin-top:8px">
            <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
            <input type="hidden" name="cancel" value="1">
            <button class="admin-btn admin-btn-block-lg" type="submit">Volver al inicio de sesión</button>
            <p class="admin-login-note">Tendrás que escribir usuario y contraseña otra vez.</p>
        </form>
        </div>
    </main>
</div>
</body>
</html>
