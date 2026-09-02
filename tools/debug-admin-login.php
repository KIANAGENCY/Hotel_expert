<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/repository.php';

$username = 'admin';
$password = 'HotelExpert#2026';

$stmt = db()->prepare('SELECT username, password_hash, created_at, session_version FROM admin_users WHERE username = ?');
$stmt->execute([$username]);
$row = $stmt->fetch();

if (!$row) {
    fwrite(STDOUT, "NO_ADMIN\n");
    exit(1);
}

$hash = (string) $row['password_hash'];
$verify = password_verify($password, $hash);
$limited = login_is_limited('admin', $username, '127.0.0.1');

fwrite(STDOUT, "username={$row['username']}\n");
fwrite(STDOUT, "hash_prefix=" . substr($hash, 0, 7) . "\n");
fwrite(STDOUT, "hash_algo=" . (str_starts_with($hash, '$2y$') || str_starts_with($hash, '$argon2') ? 'secure' : 'unknown') . "\n");
fwrite(STDOUT, "password_verify=" . ($verify ? 'yes' : 'no') . "\n");
fwrite(STDOUT, "rate_limited=" . ($limited ? 'yes' : 'no') . "\n");

$attempts = db()->prepare("SELECT COUNT(*) FROM login_attempts WHERE scope = 'admin' AND success = 0 AND attempted_at >= ?");
$attempts->execute([date('Y-m-d H:i:s', time() - 900)]);
fwrite(STDOUT, "failed_attempts_15m=" . $attempts->fetchColumn() . "\n");
