<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$root = dirname(__DIR__);
require_once $root . '/includes/config.php';

$host = env('DB_HOST', '127.0.0.1');
$port = env('DB_PORT', '3306');
$database = env('DB_DATABASE', 'hotel_expert');
$username = env('DB_USERNAME', 'root');
$password = env('DB_PASSWORD');

if (!preg_match('/^[a-zA-Z0-9_]+$/', $database)) {
    throw new RuntimeException('DB_DATABASE solo puede contener letras, números y guion bajo.');
}

$server = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
$server->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
db();
fwrite(STDOUT, "Base de datos {$database} preparada.\n");
