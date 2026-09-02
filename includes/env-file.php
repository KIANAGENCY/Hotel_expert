<?php
declare(strict_types=1);

function env_file_path(): string
{
    if (defined('ENV_FILE_TEST_PATH')) {
        return ENV_FILE_TEST_PATH;
    }
    return ROOT_PATH . '/.env';
}

function env_file_latest_backup_basename(string $targetPath): string
{
    $pattern = $targetPath . '.bak.*';
    $files = glob($pattern) ?: [];
    if ($files === []) {
        return '';
    }
    usort($files, static fn(string $a, string $b): int => filemtime($b) <=> filemtime($a));
    return basename($files[0]);
}

function env_file_editable_keys(): array
{
    return [
        'APP_URL',
        'APP_ENV',
        'TRUST_PROXY_HEADERS',
        'DB_HOST',
        'DB_PORT',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD',
        'SMTP_HOST',
        'SMTP_PORT',
        'SMTP_USERNAME',
        'SMTP_PASSWORD',
        'SMTP_ENCRYPTION',
        'SMTP_FROM_EMAIL',
        'SMTP_FROM_NAME',
        'ADMIN_SESSION_IDLE_SECONDS',
        'ADMIN_SESSION_ABSOLUTE_SECONDS',
        'CUSTOMER_SESSION_IDLE_SECONDS',
        'CUSTOMER_SESSION_ABSOLUTE_SECONDS',
        'DEPLOY_FORCE_HTTPS',
        'DEPLOY_CANONICAL_HOST',
        'DEPLOY_REWRITE_BASE',
        'STRIPE_ENABLED',
        'STRIPE_MODE',
        'STRIPE_PUBLISHABLE_KEY',
        'STRIPE_SECRET_KEY',
        'STRIPE_WEBHOOK_SECRET',
        'STRIPE_CURRENCY',
    ];
}

function env_file_readonly_secret_keys(): array
{
    return ['PII_ENCRYPTION_KEY', 'PII_BLIND_INDEX_KEY'];
}

function env_file_parse(string $content): array
{
    $values = [];
    foreach (preg_split('/\R/', $content) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = array_map('trim', explode('=', $line, 2));
        if ($key !== '') {
            $values[$key] = trim($value, "\"'");
        }
    }
    return $values;
}

function env_file_all(): array
{
    $path = env_file_path();
    if (!is_file($path)) {
        return [];
    }
    $content = file_get_contents($path);
    return is_string($content) ? env_file_parse($content) : [];
}

function env_file_get(string $key, string $default = ''): string
{
    $values = env_file_all();
    return array_key_exists($key, $values) ? (string) $values[$key] : $default;
}

function env_file_secret_is_set(string $key): bool
{
    return env_file_get($key) !== '';
}

function env_file_backup(string $path): ?string
{
    if (!is_file($path)) {
        return null;
    }
    $backup = $path . '.bak.' . date('Ymd-His');
    if (!copy($path, $backup)) {
        throw new RuntimeException('No se pudo crear el respaldo de ' . basename($path) . '.');
    }
    return $backup;
}

function env_file_serialize(array $values): string
{
    $editable = env_file_editable_keys();
    $readonlySecrets = env_file_readonly_secret_keys();
    $allowed = array_merge($editable, $readonlySecrets, ['ADMIN_USERNAME', 'ADMIN_INITIAL_PASSWORD']);
    $lines = [
        '# Hotel Expert — configuración de entorno',
        '# Las claves editables desde el panel se actualizan en Despliegue y servidor / Pagos Stripe.',
        '',
    ];

    $groups = [
        'Dominio y entorno' => ['APP_URL', 'APP_ENV', 'TRUST_PROXY_HEADERS'],
        'Base de datos' => ['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'],
        'Seguridad PII' => ['PII_ENCRYPTION_KEY', 'PII_BLIND_INDEX_KEY'],
        'Administrador inicial' => ['ADMIN_USERNAME', 'ADMIN_INITIAL_PASSWORD'],
        'Correo SMTP' => ['SMTP_HOST', 'SMTP_PORT', 'SMTP_USERNAME', 'SMTP_PASSWORD', 'SMTP_ENCRYPTION', 'SMTP_FROM_EMAIL', 'SMTP_FROM_NAME'],
        'Sesiones' => ['ADMIN_SESSION_IDLE_SECONDS', 'ADMIN_SESSION_ABSOLUTE_SECONDS', 'CUSTOMER_SESSION_IDLE_SECONDS', 'CUSTOMER_SESSION_ABSOLUTE_SECONDS'],
        'Despliegue Apache' => ['DEPLOY_FORCE_HTTPS', 'DEPLOY_CANONICAL_HOST', 'DEPLOY_REWRITE_BASE'],
        'Pagos Stripe' => ['STRIPE_ENABLED', 'STRIPE_MODE', 'STRIPE_PUBLISHABLE_KEY', 'STRIPE_SECRET_KEY', 'STRIPE_WEBHOOK_SECRET', 'STRIPE_CURRENCY'],
    ];

    $written = [];
    foreach ($groups as $label => $keys) {
        $sectionLines = [];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $values)) {
                continue;
            }
            $sectionLines[] = $key . '=' . env_file_format_value((string) $values[$key]);
            $written[$key] = true;
        }
        if ($sectionLines !== []) {
            $lines[] = '# ' . $label;
            $lines = array_merge($lines, $sectionLines);
            $lines[] = '';
        }
    }

    foreach ($values as $key => $value) {
        if (isset($written[$key]) || !in_array($key, $allowed, true)) {
            continue;
        }
        $lines[] = $key . '=' . env_file_format_value((string) $value);
    }

    return rtrim(implode(PHP_EOL, $lines)) . PHP_EOL;
}

function env_file_format_value(string $value): string
{
    if ($value === '') {
        return '';
    }
    if (preg_match('/[\s#="\'\\\\]/', $value)) {
        return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"';
    }
    return $value;
}

function env_file_update(array $changes, array $preserveIfEmpty = ['DB_PASSWORD', 'SMTP_PASSWORD']): string
{
    $allowed = array_flip(env_file_editable_keys());
    $current = env_file_all();

    foreach ($changes as $key => $value) {
        if (!isset($allowed[$key])) {
            continue;
        }
        $value = trim((string) $value);
        if ($value === '' && in_array($key, $preserveIfEmpty, true) && env_file_secret_is_set($key)) {
            continue;
        }
        $current[$key] = $value;
    }

    $path = env_file_path();
    $backup = is_file($path) ? env_file_backup($path) : null;
    $temp = $path . '.tmp.' . bin2hex(random_bytes(4));
    $written = file_put_contents($temp, env_file_serialize($current), LOCK_EX);
    if ($written === false) {
        @unlink($temp);
        throw new RuntimeException('No se pudo escribir la configuración de entorno.');
    }
    if (!rename($temp, $path)) {
        @unlink($temp);
        throw new RuntimeException('No se pudo actualizar el archivo .env.');
    }

    foreach ($current as $key => $value) {
        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
    }

    return $backup ?? '';
}

function env_file_test_database(array $db): bool
{
    $host = trim((string) ($db['DB_HOST'] ?? ''));
    $port = trim((string) ($db['DB_PORT'] ?? '3306'));
    $name = trim((string) ($db['DB_DATABASE'] ?? ''));
    $user = trim((string) ($db['DB_USERNAME'] ?? ''));
    $password = (string) ($db['DB_PASSWORD'] ?? '');

    if ($host === '' || $name === '' || $user === '') {
        return false;
    }
    if ($password === '' && env_file_secret_is_set('DB_PASSWORD')) {
        $password = env_file_get('DB_PASSWORD');
    }

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $name);
    try {
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
        ]);
        $pdo->query('SELECT 1');
        return true;
    } catch (Throwable) {
        return false;
    }
}

function env_file_deploy_defaults(): array
{
    $env = env_file_all();
    return [
        'APP_URL' => $env['APP_URL'] ?? (SITE_ORIGIN . BASE_URL),
        'APP_ENV' => $env['APP_ENV'] ?? 'local',
        'TRUST_PROXY_HEADERS' => $env['TRUST_PROXY_HEADERS'] ?? 'false',
        'DB_HOST' => $env['DB_HOST'] ?? '127.0.0.1',
        'DB_PORT' => $env['DB_PORT'] ?? '3306',
        'DB_DATABASE' => $env['DB_DATABASE'] ?? 'hotel_expert',
        'DB_USERNAME' => $env['DB_USERNAME'] ?? 'root',
        'DB_PASSWORD' => '',
        'SMTP_HOST' => $env['SMTP_HOST'] ?? '',
        'SMTP_PORT' => $env['SMTP_PORT'] ?? '587',
        'SMTP_USERNAME' => $env['SMTP_USERNAME'] ?? '',
        'SMTP_PASSWORD' => '',
        'SMTP_ENCRYPTION' => $env['SMTP_ENCRYPTION'] ?? 'tls',
        'SMTP_FROM_EMAIL' => $env['SMTP_FROM_EMAIL'] ?? EMAIL_VENTAS,
        'SMTP_FROM_NAME' => $env['SMTP_FROM_NAME'] ?? SITE_NAME,
        'ADMIN_SESSION_IDLE_SECONDS' => $env['ADMIN_SESSION_IDLE_SECONDS'] ?? '1800',
        'ADMIN_SESSION_ABSOLUTE_SECONDS' => $env['ADMIN_SESSION_ABSOLUTE_SECONDS'] ?? '43200',
        'CUSTOMER_SESSION_IDLE_SECONDS' => $env['CUSTOMER_SESSION_IDLE_SECONDS'] ?? '1800',
        'CUSTOMER_SESSION_ABSOLUTE_SECONDS' => $env['CUSTOMER_SESSION_ABSOLUTE_SECONDS'] ?? '43200',
        'DEPLOY_FORCE_HTTPS' => $env['DEPLOY_FORCE_HTTPS'] ?? 'false',
        'DEPLOY_CANONICAL_HOST' => $env['DEPLOY_CANONICAL_HOST'] ?? SITE_DOMAIN,
        'DEPLOY_REWRITE_BASE' => $env['DEPLOY_REWRITE_BASE'] ?? '',
    ];
}
