<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/customer-auth.php';

$sent = false;
$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!customer_csrf_ok($_POST['csrf'] ?? null)) {
        $error = 'La sesión expiró. Recarga la página.';
    } else {
        customer_request_password_reset(strtolower(trim((string) ($_POST['email'] ?? ''))));
        $sent = true;
    }
}

$page_title = 'Recuperar contraseña — Hotel Expert';
$page_description = 'Solicita un enlace seguro para restablecer tu contraseña.';
require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';
?>
<main id="contenido" class="account-single-page pt-28">
    <section class="account-single-card">
        <p class="eyebrow">Acceso de clientes</p>
        <h1>Recupera tu contraseña</h1>
        <?php if ($sent): ?>
            <p class="account-alert is-success">Si existe una cuenta verificada con ese correo, recibirás un enlace que expira en una hora.</p>
        <?php else: ?>
            <p class="account-lead">Escribe el correo de tu cuenta y te enviaremos un enlace de un solo uso.</p>
            <?php if ($error): ?><p class="account-alert is-error"><?= e($error) ?></p><?php endif; ?>
            <form method="post" class="account-form">
                <input type="hidden" name="csrf" value="<?= e(customer_csrf()) ?>">
                <label><span>Correo electrónico</span><input class="field" type="email" name="email" required autocomplete="email"></label>
                <button class="btn-primary justify-center" type="submit">Enviar enlace</button>
            </form>
        <?php endif; ?>
        <a class="account-back-link" href="<?= e(account_url('login/')) ?>">Volver a iniciar sesión</a>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
