<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$root = dirname(__DIR__);
require_once $root . '/includes/config.php';
require_once $root . '/includes/env-file.php';
require_once $root . '/includes/htaccess-deploy.php';

$current = env_file_all();
$values = array_merge($current, [
    'APP_URL' => 'https://www.hotelexpert.mx',
    'APP_ENV' => 'production',
    'TRUST_PROXY_HEADERS' => 'true',
    'DB_HOST' => $current['DB_HOST'] ?? '127.0.0.1',
    'DB_PORT' => $current['DB_PORT'] ?? '3306',
    'DB_DATABASE' => $current['DB_DATABASE'] ?? 'hotel_expert',
    'DB_USERNAME' => $current['DB_USERNAME'] ?? 'root',
    'DB_PASSWORD' => $current['DB_PASSWORD'] ?? '',
    'PII_ENCRYPTION_KEY' => $current['PII_ENCRYPTION_KEY'] ?? '',
    'PII_BLIND_INDEX_KEY' => $current['PII_BLIND_INDEX_KEY'] ?? '',
    'ADMIN_USERNAME' => $current['ADMIN_USERNAME'] ?? 'admin',
    'ADMIN_INITIAL_PASSWORD' => $current['ADMIN_INITIAL_PASSWORD'] ?? '',
    'SMTP_HOST' => $current['SMTP_HOST'] ?? '',
    'SMTP_PORT' => $current['SMTP_PORT'] ?? '587',
    'SMTP_USERNAME' => $current['SMTP_USERNAME'] ?? '',
    'SMTP_PASSWORD' => $current['SMTP_PASSWORD'] ?? '',
    'SMTP_ENCRYPTION' => $current['SMTP_ENCRYPTION'] ?? 'tls',
    'SMTP_FROM_EMAIL' => $current['SMTP_FROM_EMAIL'] ?? EMAIL_VENTAS,
    'SMTP_FROM_NAME' => $current['SMTP_FROM_NAME'] ?? SITE_NAME,
    'DEPLOY_FORCE_HTTPS' => 'true',
    'DEPLOY_CANONICAL_HOST' => 'www.hotelexpert.mx',
    'DEPLOY_REWRITE_BASE' => '',
    'STRIPE_ENABLED' => $current['STRIPE_ENABLED'] ?? 'false',
    'STRIPE_MODE' => $current['STRIPE_MODE'] ?? 'test',
    'STRIPE_CURRENCY' => $current['STRIPE_CURRENCY'] ?? 'mxn',
]);

if ($values['PII_ENCRYPTION_KEY'] === '') {
    $values['PII_ENCRYPTION_KEY'] = bin2hex(random_bytes(32));
}
if ($values['PII_BLIND_INDEX_KEY'] === '') {
    $values['PII_BLIND_INDEX_KEY'] = bin2hex(random_bytes(32));
}

$path = env_file_path();
if (is_file($path)) {
    env_file_backup($path);
}
if (file_put_contents($path, env_file_serialize($values), LOCK_EX) === false) {
    throw new RuntimeException('No se pudo escribir .env');
}

htaccess_deploy_apply([
    'DEPLOY_FORCE_HTTPS' => 'true',
    'DEPLOY_CANONICAL_HOST' => 'www.hotelexpert.mx',
    'DEPLOY_REWRITE_BASE' => '',
]);

settings_save(['site_domain' => 'www.hotelexpert.mx']);

fwrite(STDOUT, 'APP_URL=' . env_file_get('APP_URL') . PHP_EOL);
fwrite(STDOUT, 'HOST=' . env_file_get('DEPLOY_CANONICAL_HOST') . PHP_EOL);
fwrite(STDOUT, 'PII=' . (env_file_secret_is_set('PII_ENCRYPTION_KEY') ? 'ok' : 'missing') . PHP_EOL);
fwrite(STDOUT, 'SITE=' . setting_get('site_domain') . PHP_EOL);
