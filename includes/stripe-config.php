<?php
declare(strict_types=1);

require_once __DIR__ . '/env-file.php';

function stripe_env_keys(): array
{
    return [
        'STRIPE_ENABLED',
        'STRIPE_MODE',
        'STRIPE_PUBLISHABLE_KEY',
        'STRIPE_SECRET_KEY',
        'STRIPE_WEBHOOK_SECRET',
        'STRIPE_CURRENCY',
    ];
}

function stripe_config_defaults(): array
{
    $env = env_file_all();
    $mode = strtolower((string) ($env['STRIPE_MODE'] ?? 'test'));
    if (!in_array($mode, ['test', 'live'], true)) {
        $mode = 'test';
    }
    return [
        'STRIPE_ENABLED' => $env['STRIPE_ENABLED'] ?? 'false',
        'STRIPE_MODE' => $mode,
        'STRIPE_PUBLISHABLE_KEY' => $env['STRIPE_PUBLISHABLE_KEY'] ?? '',
        'STRIPE_SECRET_KEY' => '',
        'STRIPE_WEBHOOK_SECRET' => '',
        'STRIPE_CURRENCY' => strtolower((string) ($env['STRIPE_CURRENCY'] ?? 'mxn')),
    ];
}

function stripe_is_enabled(): bool
{
    return filter_var(env('STRIPE_ENABLED', 'false'), FILTER_VALIDATE_BOOLEAN);
}

function stripe_mode(): string
{
    $mode = strtolower(env('STRIPE_MODE', 'test'));
    return in_array($mode, ['test', 'live'], true) ? $mode : 'test';
}

function stripe_publishable_key(): string
{
    return env('STRIPE_PUBLISHABLE_KEY', '');
}

function stripe_secret_key(): string
{
    return env('STRIPE_SECRET_KEY', '');
}

function stripe_webhook_secret(): string
{
    return env('STRIPE_WEBHOOK_SECRET', '');
}

function stripe_currency(): string
{
    $currency = strtolower(env('STRIPE_CURRENCY', 'mxn'));
    return preg_match('/^[a-z]{3}$/', $currency) === 1 ? $currency : 'mxn';
}

function stripe_webhook_url(): string
{
    $base = rtrim(env('APP_URL', SITE_ORIGIN), '/');
    return $base . url('cuenta/stripe-webhook.php');
}

function stripe_key_prefix_for_mode(string $mode, string $kind): string
{
    $suffix = $mode === 'live' ? 'live' : 'test';
    return match ($kind) {
        'publishable' => 'pk_' . $suffix . '_',
        'secret' => 'sk_' . $suffix . '_',
        default => '',
    };
}

function stripe_test_connection(?array $config = null): bool
{
    $config ??= stripe_config_defaults();
    $secret = trim((string) ($config['STRIPE_SECRET_KEY'] ?? ''));
    if ($secret === '' && env_file_secret_is_set('STRIPE_SECRET_KEY')) {
        $secret = env_file_get('STRIPE_SECRET_KEY');
    }
    if ($secret === '' || !str_starts_with($secret, 'sk_')) {
        return false;
    }

    if (!function_exists('curl_init')) {
        return false;
    }

    $ch = curl_init('https://api.stripe.com/v1/balance');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD => $secret . ':',
        CURLOPT_TIMEOUT => 12,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
    ]);
    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if (!is_string($body) || $status < 200 || $status >= 300) {
        return false;
    }

    $json = json_decode($body, true);
    return is_array($json) && !isset($json['error']);
}

function stripe_status_summary(): array
{
    $enabled = stripe_is_enabled();
    $hasPublishable = stripe_publishable_key() !== '';
    $hasSecret = env_file_secret_is_set('STRIPE_SECRET_KEY');
    $hasWebhook = env_file_secret_is_set('STRIPE_WEBHOOK_SECRET');
    $mode = stripe_mode();

    return [
        'enabled' => $enabled,
        'mode' => $mode,
        'publishable' => $hasPublishable,
        'secret' => $hasSecret,
        'webhook' => $hasWebhook,
        'ready' => $enabled && $hasPublishable && $hasSecret,
    ];
}
