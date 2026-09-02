<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/customer-auth.php';

$token = trim((string) ($_GET['token'] ?? $_POST['token'] ?? ''));
$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!customer_csrf_ok($_POST['csrf'] ?? null)) {
        $error = 'La sesión expiró. Recarga la página.';
    } else {
        [$ok, $message] = customer_reset_password($token, (string) ($_POST['password'] ?? ''), (string) ($_POST['password_confirmation'] ?? ''));
        if ($ok) {
            account_flash($message);
            header('Location: ' . account_url('login/'));
            exit;
        }
        $error = $message;
    }
}

$page_title = 'Nueva contraseña — Hotel Expert';
$page_description = 'Crea una nueva contraseña para tu cuenta.';
require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
?>
<main id="contenido" class="account-single-page pt-28">
    <section class="account-single-card">
        <p class="eyebrow">Acceso seguro</p>
        <h1>Nueva contraseña</h1>
        <?php if ($error): ?><p class="account-alert is-error"><?= e($error) ?></p><?php endif; ?>
        <?php if ($token === ''): ?>
            <p class="account-alert is-error">El enlace no contiene un token válido.</p>
        <?php else: ?>
            <form method="post" class="account-form">
                <input type="hidden" name="csrf" value="<?= e(customer_csrf()) ?>">
                <input type="hidden" name="token" value="<?= e($token) ?>">
                <label><span>Contraseña nueva</span><input class="field" type="password" name="password" required minlength="8" autocomplete="new-password"></label>
                <p class="account-field-help">Mínimo 8 caracteres, una mayúscula, una minúscula y un número.</p>
                <label><span>Confirmar contraseña</span><input class="field" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"></label>
                <button class="btn-primary justify-center" type="submit">Guardar contraseña</button>
            </form>
        <?php endif; ?>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
