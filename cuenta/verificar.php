<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/customer-auth.php';

$ok = customer_verify_email_token(trim((string) ($_GET['token'] ?? '')));
account_flash(
    $ok ? 'Correo verificado. Ya puedes iniciar sesión.' : 'El enlace de verificación es inválido, ya fue utilizado o expiró.',
    $ok ? 'success' : 'error'
);
header('Location: ' . account_url('login/'));
exit;
