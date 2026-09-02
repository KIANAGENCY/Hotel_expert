<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
require_once __DIR__ . '/includes/layout.php';

if (admin_session_is_valid()) {
    header('Location: ' . admin_url('index.php'));
    exit;
}

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!admin_csrf_ok($_POST['csrf'] ?? null)) {
        $error = 'La sesión expiró. Recarga la página.';
    } else {
        $user = trim((string) ($_POST['username'] ?? ''));
        $pass = (string) ($_POST['password'] ?? '');
        if (admin_login($user, $pass)) {
            header('Location: ' . admin_url('index.php'));
            exit;
        }
        $error = 'Usuario o contraseña incorrectos, o acceso temporalmente limitado.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso — Panel Hotel Expert</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800;900&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= e(url('admin/assets/admin.css')) ?>">
</head>
<body class="admin-login">
<div class="admin-login-mobile-brand">
    <?php admin_brand_markup(); ?>
</div>
<div class="admin-login-shell">
    <aside class="admin-login-aside">
        <?php admin_brand_markup(); ?>

        <div>
            <p class="admin-login-hero-tag">Panel de control</p>
            <h1 class="admin-login-hero-title">Tu espacio para gestionar, publicar y medir.</h1>
            <p class="admin-login-hero-text">Acceso privado para la administración de Hotel Expert: leads B2B, catálogo, pedidos y contenido del Sistema ELAH.</p>
        </div>

        <div class="admin-login-features">
            <span class="admin-login-feature"><i class="fa-solid fa-shield-halved"></i>Acceso protegido con sesión segura</span>
            <span class="admin-login-feature"><i class="fa-solid fa-circle-check"></i>Leads, productos y blog en un mismo lugar</span>
        </div>
    </aside>

    <main class="admin-login-main">
        <div class="admin-login-form-card">
        <form method="post" class="admin-login-form">
            <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">
            <div>
                <p class="admin-login-form-tag">Bienvenido de vuelta</p>
                <h2 class="admin-login-form-title">Inicia sesión</h2>
            </div>

            <?php if ($error): ?>
                <div class="admin-alert admin-alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <label class="admin-label">
                <span>Usuario</span>
                <input class="admin-input" name="username" required autocomplete="username">
            </label>
            <label class="admin-label">
                <span>Contraseña</span>
                <input class="admin-input" type="password" name="password" required autocomplete="current-password">
            </label>

            <button class="admin-btn admin-btn-primary admin-btn-block-lg" type="submit">
                Entrar al panel <i class="fa-solid fa-arrow-right"></i>
            </button>

            <p class="admin-login-note">Acceso exclusivo para personal autorizado de Hotel Expert.</p>
        </form>
        </div>
    </main>
</div>
</body>
</html>
