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
                <label>
                    <span>Contraseña nueva</span>
                    <span class="account-password-field">
                        <input class="field" id="account-reset-password" type="password" name="password" required minlength="8" autocomplete="new-password" placeholder="Mínimo 8 caracteres" aria-describedby="password-help">
                        <button class="account-password-toggle" type="button" data-password-toggle aria-controls="account-reset-password" aria-label="Mostrar contraseña" aria-pressed="false">
                            <svg class="password-eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            <svg class="password-eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m3 3 18 18M10.6 6.2A10.7 10.7 0 0 1 12 6c6 0 9.5 6 9.5 6a16.6 16.6 0 0 1-2.2 2.9M6.3 6.3C3.9 8 2.5 12 2.5 12s3.5 6 9.5 6c1.5 0 2.8-.4 4-1"/><path d="M9.8 9.8a3.1 3.1 0 0 0-.3 1.2 2.5 2.5 0 0 0 3.7 2.2"/></svg>
                        </button>
                    </span>
                </label>
                <p id="password-help" class="account-field-help">Mínimo 8 caracteres, una mayúscula, una minúscula y un número.</p>
                <label>
                    <span>Confirmar contraseña</span>
                    <span class="account-password-field">
                        <input class="field" id="account-reset-password-confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Repite tu contraseña">
                        <button class="account-password-toggle" type="button" data-password-toggle aria-controls="account-reset-password-confirmation" aria-label="Mostrar contraseña" aria-pressed="false">
                            <svg class="password-eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            <svg class="password-eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m3 3 18 18M10.6 6.2A10.7 10.7 0 0 1 12 6c6 0 9.5 6 9.5 6a16.6 16.6 0 0 1-2.2 2.9M6.3 6.3C3.9 8 2.5 12 2.5 12s3.5 6 9.5 6c1.5 0 2.8-.4 4-1"/><path d="M9.8 9.8a3.1 3.1 0 0 0-.3 1.2 2.5 2.5 0 0 0 3.7 2.2"/></svg>
                        </button>
                    </span>
                </label>
                <button class="btn-primary justify-center" type="submit">Guardar contraseña</button>
            </form>
        <?php endif; ?>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
