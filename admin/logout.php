<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !admin_csrf_ok($_POST['csrf'] ?? null)) {
    http_response_code(405);
    exit('Solicitud no permitida.');
}

admin_logout();
header('Location: ' . admin_url('login.php'));
exit;
