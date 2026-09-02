<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/admin-auth.php';
require_once dirname(__DIR__) . '/admin/includes/validation.php';

$valid = admin_validate_deploy_payload([
    'APP_URL' => 'https://www.hotelexpert.mx',
    'APP_ENV' => 'production',
    'TRUST_PROXY_HEADERS' => '1',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'hotel_expert',
    'DB_USERNAME' => 'root',
    'SMTP_PORT' => '587',
    'SMTP_ENCRYPTION' => 'tls',
    'ADMIN_SESSION_IDLE_SECONDS' => '1800',
    'ADMIN_SESSION_ABSOLUTE_SECONDS' => '43200',
    'CUSTOMER_SESSION_IDLE_SECONDS' => '1800',
    'CUSTOMER_SESSION_ABSOLUTE_SECONDS' => '43200',
    'DEPLOY_FORCE_HTTPS' => '1',
    'DEPLOY_CANONICAL_HOST' => 'www.hotelexpert.mx',
    'admin_password' => 'skip',
], false);
assert($valid['ok'] === true);
assert($valid['data']['APP_URL'] === 'https://www.hotelexpert.mx');
assert($valid['data']['TRUST_PROXY_HEADERS'] === 'true');
assert($valid['data']['DEPLOY_FORCE_HTTPS'] === 'true');

$syncUrl = admin_validate_deploy_payload([
    'APP_URL' => 'https://old.example.com',
    'APP_ENV' => 'production',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'hotel_expert',
    'DB_USERNAME' => 'root',
    'DEPLOY_CANONICAL_HOST' => 'www.example.com',
    'ADMIN_SESSION_IDLE_SECONDS' => '1800',
    'ADMIN_SESSION_ABSOLUTE_SECONDS' => '43200',
    'CUSTOMER_SESSION_IDLE_SECONDS' => '1800',
    'CUSTOMER_SESSION_ABSOLUTE_SECONDS' => '43200',
], false);
assert($syncUrl['ok'] === true);
assert($syncUrl['data']['APP_URL'] === 'https://www.example.com');
assert($syncUrl['data']['site_domain'] === 'www.example.com');

$badUrl = admin_validate_deploy_payload([
    'APP_URL' => 'http://insecure.test',
    'APP_ENV' => 'production',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'hotel_expert',
    'DB_USERNAME' => 'root',
    'ADMIN_SESSION_IDLE_SECONDS' => '1800',
    'ADMIN_SESSION_ABSOLUTE_SECONDS' => '43200',
    'CUSTOMER_SESSION_IDLE_SECONDS' => '1800',
    'CUSTOMER_SESSION_ABSOLUTE_SECONDS' => '43200',
], false);
assert($badUrl['ok'] === false);

$badHost = admin_validate_deploy_payload([
    'APP_URL' => 'https://www.example.com',
    'APP_ENV' => 'production',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'hotel_expert',
    'DB_USERNAME' => 'root',
    'DEPLOY_CANONICAL_HOST' => 'bad host!',
    'ADMIN_SESSION_IDLE_SECONDS' => '1800',
    'ADMIN_SESSION_ABSOLUTE_SECONDS' => '43200',
    'CUSTOMER_SESSION_IDLE_SECONDS' => '1800',
    'CUSTOMER_SESSION_ABSOLUTE_SECONDS' => '43200',
], false);
assert($badHost['ok'] === false);

$db = admin_validate_deploy_db_payload([
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'hotel_expert',
    'DB_USERNAME' => 'root',
]);
assert($db['ok'] === true);

$badDb = admin_validate_deploy_db_payload([
    'DB_HOST' => '',
    'DB_DATABASE' => 'hotel_expert',
    'DB_USERNAME' => 'root',
]);
assert($badDb['ok'] === false);

echo "admin-deploy-validation tests OK\n";
