<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/db.php';

$username = $argv[1] ?? env('ADMIN_USERNAME', 'admin');
$password = $argv[2] ?? '';

if ($password === '') {
    fwrite(STDERR, "Uso: php tools/ensure-admin.php [usuario] [contraseña]\n");
    exit(1);
}

$pdo = db();
$existing = $pdo->prepare('SELECT id FROM admin_users WHERE username = ?');
$existing->execute([$username]);

if (mb_strlen($password) < 12) {
    fwrite(STDERR, "La contraseña debe tener al menos 12 caracteres.\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
if (!password_verify($password, $hash)) {
    fwrite(STDERR, "No se pudo generar un hash de contraseña válido.\n");
    exit(1);
}

if ($existing->fetch()) {
    $pdo->prepare('UPDATE admin_users SET password_hash = ?, session_version = session_version + 1 WHERE username = ?')
        ->execute([$hash, $username]);
    fwrite(STDOUT, "Contraseña actualizada para el administrador \"{$username}\".\n");
} else {
    $pdo->prepare('INSERT INTO admin_users (username, password_hash, created_at) VALUES (?, ?, ?)')
        ->execute([$username, $hash, date('Y-m-d H:i:s')]);
    fwrite(STDOUT, "Administrador \"{$username}\" creado.\n");
}

$pdo->prepare('DELETE FROM login_attempts WHERE scope = ?')->execute(['admin']);

fwrite(STDOUT, "Intentos fallidos de acceso limpiados.\n");
fwrite(STDOUT, "Hash: " . substr($hash, 0, 7) . "… (bcrypt)\n");
fwrite(STDOUT, "URL: " . url('admin/login.php') . "\n");
