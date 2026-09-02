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
        [$ok, $message] = customer_register_account($_POST);
        if ($ok) {
            account_flash($message, str_contains($message, 'no pudimos') ? 'warning' : 'success');
            header('Location: ' . account_url('login/'));
            exit;
        }
        $error = $message;
    }
}

$page_title = 'Crear cuenta de cliente — Hotel Expert';
$page_description = 'Crea tu acceso para consultar pedidos, rastreo y recompra.';
require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
?>
<main id="contenido" class="account-auth-page account-register-page pt-28">
    <section class="account-auth-shell account-register-shell">
        <div class="account-auth-story">
            <p class="eyebrow text-aqua">Portal de clientes</p>
            <h1>Menos pasos para tu siguiente pedido.</h1>
            <p>Tu cuenta reúne el historial de la propiedad y facilita repetir una cotización con cantidades anteriores.</p>
        </div>
        <div class="account-auth-card">
            <p class="eyebrow">Registro seguro</p>
            <h2>Crea tu cuenta</h2>
            <?php if ($error): ?><p class="account-alert is-error"><?= e($error) ?></p><?php endif; ?>
            <form method="post" class="account-form">
                <input type="hidden" name="csrf" value="<?= e(customer_csrf()) ?>">
                <div class="account-form-grid">
                    <label><span>Nombre y apellido</span><input class="field" name="nombre" required minlength="5" maxlength="190" pattern=".*\S+\s+\S+.*" title="Escribe por lo menos un nombre y un apellido" autocomplete="name" placeholder="Ej. María González" value="<?= e($_POST['nombre'] ?? '') ?>"></label>
                    <label><span>Hotel o empresa</span><input class="field" name="hotel" required minlength="3" maxlength="190" title="Escribe el nombre completo del hotel o empresa" autocomplete="organization" placeholder="Ej. Hotel Costa Azul" value="<?= e($_POST['hotel'] ?? '') ?>"></label>
                </div>
                <label><span>Correo electrónico</span><input class="field" type="email" name="email" required autocomplete="email" placeholder="correo@gmail.com" value="<?= e($_POST['email'] ?? '') ?>"></label>
                <div class="account-form-grid">
                    <label><span>Teléfono</span><input class="field" type="tel" name="telefono" autocomplete="tel" placeholder="Ej. 81 0000 0000" value="<?= e($_POST['telefono'] ?? '') ?>"></label>
                    <label><span>RFC (opcional)</span><input class="field" name="rfc" placeholder="Ej. HEM010101ABC" value="<?= e($_POST['rfc'] ?? '') ?>"></label>
                </div>
                <div class="account-form-grid account-password-grid">
                    <label>
                        <span>Contraseña</span>
                        <span class="account-password-field">
                            <input class="field" id="account-register-password" type="password" name="password" required minlength="8" autocomplete="new-password" placeholder="Mínimo 8 caracteres" aria-describedby="password-help">
                            <button class="account-password-toggle" type="button" data-password-toggle aria-controls="account-register-password" aria-label="Mostrar contraseña" aria-pressed="false">
                                <svg class="password-eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                                <svg class="password-eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m3 3 18 18M10.6 6.2A10.7 10.7 0 0 1 12 6c6 0 9.5 6 9.5 6a16.6 16.6 0 0 1-2.2 2.9M6.3 6.3C3.9 8 2.5 12 2.5 12s3.5 6 9.5 6c1.5 0 2.8-.4 4-1"/><path d="M9.8 9.8a3.1 3.1 0 0 0-.3 1.2 2.5 2.5 0 0 0 3.7 2.2"/></svg>
                            </button>
                        </span>
                    </label>
                    <label>
                        <span>Confirmar contraseña</span>
                        <span class="account-password-field">
                            <input class="field" id="account-register-password-confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Repite tu contraseña">
                            <button class="account-password-toggle" type="button" data-password-toggle aria-controls="account-register-password-confirmation" aria-label="Mostrar contraseña" aria-pressed="false">
                                <svg class="password-eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                                <svg class="password-eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m3 3 18 18M10.6 6.2A10.7 10.7 0 0 1 12 6c6 0 9.5 6 9.5 6a16.6 16.6 0 0 1-2.2 2.9M6.3 6.3C3.9 8 2.5 12 2.5 12s3.5 6 9.5 6c1.5 0 2.8-.4 4-1"/><path d="M9.8 9.8a3.1 3.1 0 0 0-.3 1.2 2.5 2.5 0 0 0 3.7 2.2"/></svg>
                            </button>
                        </span>
                    </label>
                </div>
                <p id="password-help" class="account-field-help">Mínimo 8 caracteres, una mayúscula, una minúscula y un número.</p>
                <button class="btn-primary justify-center" type="submit">Crear cuenta</button>
            </form>
            <p class="account-auth-switch">¿Ya tienes cuenta? <a href="<?= e(account_url('login/')) ?>">Iniciar sesión</a></p>
        </div>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
