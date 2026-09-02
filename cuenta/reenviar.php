<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/customer-auth.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !customer_csrf_ok($_POST['csrf'] ?? null)) {
    http_response_code(405);
    exit('Solicitud no permitida.');
}

$id = (int) ($_SESSION['unverified_customer_id'] ?? 0);
$sent = $id > 0 && customer_send_verification($id);
account_flash(
    $sent ? 'Enviamos un nuevo enlace de verificación.' : 'No fue posible enviar otro enlace en este momento. Intenta más tarde.',
    $sent ? 'success' : 'warning'
);
header('Location: ' . account_url('login/'));
exit;
