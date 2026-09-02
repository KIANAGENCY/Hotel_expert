<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/install.php';

$database = 'hotel_expert_pii_test_' . bin2hex(random_bytes(4));
putenv('ADMIN_INITIAL_PASSWORD=' . bin2hex(random_bytes(16)));
$host = env('DB_HOST', '127.0.0.1');
$port = env('DB_PORT', '3306');
$user = env('DB_USERNAME', 'root');
$password = env('DB_PASSWORD');
$server = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $user, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

try {
    $server->exec("CREATE DATABASE {$database} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    db_install_if_needed($pdo);

    $version = (int) $pdo->query('SELECT MAX(version) FROM schema_migrations')->fetchColumn();
    $seededOrder = $pdo->query('SELECT email, email_blind_index FROM orders LIMIT 1')->fetch();
    if ($version !== 5 || !$seededOrder || !pii_is_encrypted((string) $seededOrder['email'])) {
        throw new RuntimeException('La instalación limpia no creó el esquema PII cifrado.');
    }
    $email = pii_decrypt((string) $seededOrder['email'], 'orders.email');
    if (!hash_equals(pii_email_blind_index($email), (string) $seededOrder['email_blind_index'])) {
        throw new RuntimeException('El blind index del pedido inicial no coincide.');
    }
    fwrite(STDOUT, "Instalación limpia PII verificada.\n");
} finally {
    $server->exec("DROP DATABASE IF EXISTS {$database}");
}
