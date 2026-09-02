<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/env-file.php';
require_once dirname(__DIR__) . '/includes/htaccess-deploy.php';

$tmpDir = sys_get_temp_dir() . '/hotel-expert-env-test-' . bin2hex(random_bytes(4));
mkdir($tmpDir);
$envPath = $tmpDir . '/.env';
$htPath = $tmpDir . '/.htaccess';

define('ENV_FILE_TEST_PATH', $envPath);
define('HTACCESS_TEST_PATH', $htPath);

file_put_contents($envPath, implode(PHP_EOL, [
    'APP_URL=http://local.test',
    'APP_ENV=local',
    'DB_HOST=127.0.0.1',
    'DB_PASSWORD=secret',
    'SMTP_PASSWORD=mail-secret',
    'PII_ENCRYPTION_KEY=abc',
]) . PHP_EOL);

$all = env_file_all();
assert($all['APP_URL'] === 'http://local.test');
assert($all['DB_PASSWORD'] === 'secret');

assert(env_file_secret_is_set('DB_PASSWORD') === true);
assert(env_file_secret_is_set('SMTP_HOST') === false);

$backup = env_file_update([
    'APP_URL' => 'https://www.example.com',
    'APP_ENV' => 'production',
    'DB_PASSWORD' => '',
    'SMTP_PASSWORD' => 'new-mail',
], ['DB_PASSWORD', 'SMTP_PASSWORD']);
assert($backup !== '');
assert(is_file($backup));
assert(env_file_get('APP_URL') === 'https://www.example.com');
assert(env_file_get('DB_PASSWORD') === 'secret');
assert(env_file_get('SMTP_PASSWORD') === 'new-mail');

$updated = file_get_contents($envPath);
assert(is_string($updated));
assert(str_contains($updated, 'APP_URL=https://www.example.com'));
assert(str_contains($updated, 'PII_ENCRYPTION_KEY=abc'));

file_put_contents($htPath, "RewriteEngine On\n# BEGIN HOTEL_EXPERT_DEPLOY\n# END HOTEL_EXPERT_DEPLOY\n");
$htBackup = htaccess_deploy_apply([
    'DEPLOY_FORCE_HTTPS' => 'true',
    'DEPLOY_CANONICAL_HOST' => 'www.example.com',
    'DEPLOY_REWRITE_BASE' => '',
]);
assert($htBackup !== '');
$htContent = file_get_contents($htPath);
assert(is_string($htContent));
assert(str_contains($htContent, 'RewriteCond %{HTTPS} !=on'));
assert(str_contains($htContent, 'www.example.com'));

@unlink($envPath);
@unlink($backup);
@unlink($htPath);
@unlink($htBackup);
@rmdir($tmpDir);

echo "env-file tests OK\n";
