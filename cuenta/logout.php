<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/customer-auth.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !customer_csrf_ok($_POST['csrf'] ?? null)) {
    http_response_code(405);
    exit('Solicitud no permitida.');
}

customer_logout();
account_flash('Tu sesión se cerró correctamente.', 'info');
header('Location: ' . account_url('login/'));
exit;
