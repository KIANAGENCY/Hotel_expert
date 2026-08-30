<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_logout();
header('Location: ' . admin_url('login.php'));
exit;
