<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/repository.php';

$username = $argv[1] ?? env('ADMIN_USERNAME', 'admin');
$row = admin_totp_row($username);
if ($row === null) {
    fwrite(STDERR, "No existe el administrador {$username}.\n");
    exit(1);
}

admin_totp_disable($username);
fwrite(STDOUT, "2FA desactivado para {$username}. Ya puede entrar solo con usuario y contraseña.\n");
