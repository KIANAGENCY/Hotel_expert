<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/customer-auth.php';

if (current_customer()) {
    header('Location: ' . account_url());
    exit;
}

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!customer_csrf_ok($_POST['csrf'] ?? null)) {
        $error = 'La sesión expiró. Recarga la página.';
    } else {
        [$ok, $message] = customer_login_account((string) ($_POST['email'] ?? ''), (string) ($_POST['password'] ?? ''));
        if ($ok) {
            account_flash($message);
            $target = (string) ($_SESSION['account_intended'] ?? account_url());
            unset($_SESSION['account_intended']);
            $accountPrefix = rtrim(BASE_URL, '/') . '/cuenta/';
            if (!str_starts_with($target, $accountPrefix)) {
                $target = account_url();
            }
            header('Location: ' . $target);
            exit;
        }
        $error = $message;
    }
}

$flash = account_flash_consume();
$page_title = 'Iniciar sesión — Hotel Expert';
$page_description = 'Accede a tus pedidos, seguimiento y recompra de productos Hotel Expert.';
require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
?>
<main id="contenido" class="account-auth-page pt-28">
    <section class="account-auth-shell">
        <div class="account-auth-story">
            <p class="eyebrow text-aqua">Clientes ELAH</p>
            <h1>Tu operación, siempre a la vista.</h1>
            <p>Consulta el estado de tus pedidos y prepara una recompra sin capturar nuevamente cada producto.</p>
            <ul><li>Historial centralizado</li><li>Seguimiento de envío</li><li>Recompra por cotización</li></ul>
        </div>
        <div class="account-auth-card">
            <p class="eyebrow">Bienvenido de vuelta</p>
            <h2>Inicia sesión</h2>
            <?php if ($flash): ?><p class="account-alert is-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></p><?php endif; ?>
            <?php if ($error): ?><p class="account-alert is-error"><?= e($error) ?></p><?php endif; ?>
            <?php if (!empty($_SESSION['unverified_customer_id'])): ?>
                <form method="post" action="<?= e(account_url('reenviar/')) ?>" class="account-inline-form">
                    <input type="hidden" name="csrf" value="<?= e(customer_csrf()) ?>">
                    <button type="submit">Reenviar correo de verificación</button>
                </form>
            <?php endif; ?>
            <form method="post" class="account-form">
                <input type="hidden" name="csrf" value="<?= e(customer_csrf()) ?>">
                <label><span>Correo electrónico</span><input class="field" type="email" name="email" required autocomplete="email" placeholder="correo@gmail.com" value="<?= e($_POST['email'] ?? '') ?>"></label>
                <label>
                    <span>Contraseña</span>
                    <span class="account-password-field">
                        <input class="field" id="account-login-password" type="password" name="password" required minlength="8" autocomplete="current-password" placeholder="Ingresa tu contraseña">
                        <button class="account-password-toggle" type="button" data-password-toggle aria-controls="account-login-password" aria-label="Mostrar contraseña" aria-pressed="false">
                            <svg class="password-eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/>
                                <circle cx="12" cy="12" r="2.5"/>
                            </svg>
                            <svg class="password-eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="m3 3 18 18M10.6 6.2A10.7 10.7 0 0 1 12 6c6 0 9.5 6 9.5 6a16.6 16.6 0 0 1-2.2 2.9M6.3 6.3C3.9 8 2.5 12 2.5 12s3.5 6 9.5 6c1.5 0 2.8-.4 4-1"/>
                                <path d="M9.8 9.8a3.1 3.1 0 0 0-.3 1.2 2.5 2.5 0 0 0 3.7 2.2"/>
                            </svg>
                        </button>
                    </span>
                </label>
                <div class="account-form-meta"><a href="<?= e(account_url('recuperar/')) ?>">¿Olvidaste tu contraseña?</a></div>
                <button class="btn-primary justify-center" type="submit">Entrar a mi cuenta</button>
            </form>
            <p class="account-auth-switch">¿Aún no tienes cuenta? <a href="<?= e(account_url('registro/')) ?>">Crear cuenta</a></p>
        </div>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
