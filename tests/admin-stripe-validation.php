<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/env-file.php';
require_once dirname(__DIR__) . '/admin/includes/validation.php';

$valid = admin_validate_stripe_payload([
    'STRIPE_ENABLED' => '1',
    'STRIPE_MODE' => 'test',
    'STRIPE_PUBLISHABLE_KEY' => 'pk_test_abc123',
    'STRIPE_SECRET_KEY' => 'sk_test_xyz789',
    'STRIPE_WEBHOOK_SECRET' => 'whsec_testsecret',
    'STRIPE_CURRENCY' => 'mxn',
    'admin_password' => 'skip',
], false);
assert($valid['ok'] === true);
assert($valid['data']['STRIPE_ENABLED'] === 'true');
assert($valid['data']['STRIPE_CURRENCY'] === 'mxn');

$badMode = admin_validate_stripe_payload([
    'STRIPE_ENABLED' => '1',
    'STRIPE_MODE' => 'test',
    'STRIPE_PUBLISHABLE_KEY' => 'pk_live_wrong',
    'STRIPE_SECRET_KEY' => 'sk_test_xyz',
    'STRIPE_CURRENCY' => 'mxn',
], false);
assert($badMode['ok'] === false);

$disabled = admin_validate_stripe_payload([
    'STRIPE_ENABLED' => '0',
    'STRIPE_MODE' => 'test',
    'STRIPE_PUBLISHABLE_KEY' => '',
    'STRIPE_SECRET_KEY' => '',
    'STRIPE_CURRENCY' => 'mxn',
], false);
assert($disabled['ok'] === true);
assert($disabled['data']['STRIPE_ENABLED'] === 'false');

$badCurrency = admin_validate_stripe_payload([
    'STRIPE_ENABLED' => '0',
    'STRIPE_MODE' => 'test',
    'STRIPE_CURRENCY' => 'pesos',
], false);
assert($badCurrency['ok'] === false);

require_once dirname(__DIR__) . '/includes/stripe-config.php';
assert(stripe_key_prefix_for_mode('test', 'publishable') === 'pk_test_');
assert(stripe_currency() === 'mxn' || stripe_currency() === 'usd');

echo "admin-stripe-validation tests OK\n";
