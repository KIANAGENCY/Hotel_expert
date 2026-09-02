<?php
declare(strict_types=1);

require_once __DIR__ . '/env-file.php';

function htaccess_deploy_path(): string
{
    if (defined('HTACCESS_TEST_PATH')) {
        return HTACCESS_TEST_PATH;
    }
    return ROOT_PATH . '/.htaccess';
}

function htaccess_deploy_marker_begin(): string
{
    return '# BEGIN HOTEL_EXPERT_DEPLOY';
}

function htaccess_deploy_marker_end(): string
{
    return '# END HOTEL_EXPERT_DEPLOY';
}

function htaccess_deploy_rules(array $config): string
{
    $forceHttps = filter_var((string) ($config['DEPLOY_FORCE_HTTPS'] ?? 'false'), FILTER_VALIDATE_BOOLEAN);
    $canonicalHost = trim((string) ($config['DEPLOY_CANONICAL_HOST'] ?? ''));
    $rewriteBase = trim((string) ($config['DEPLOY_REWRITE_BASE'] ?? ''));
    $lines = [
        htaccess_deploy_marker_begin(),
        '# Generado por el panel — no editar a mano',
    ];

    if ($rewriteBase !== '') {
        $base = '/' . trim($rewriteBase, '/') . '/';
        $lines[] = 'RewriteBase ' . $base;
    }

    if ($canonicalHost !== '') {
        $host = preg_replace('/[^a-zA-Z0-9.\-]/', '', $canonicalHost);
        if ($host !== '') {
            $lines[] = 'RewriteCond %{HTTP_HOST} !^' . $host . '$ [NC]';
            $lines[] = 'RewriteRule ^ https://' . $host . '%{REQUEST_URI} [R=301,L,NE]';
        }
    }

    if ($forceHttps) {
        $lines[] = 'RewriteCond %{HTTPS} !=on';
        $lines[] = 'RewriteCond %{HTTP:X-Forwarded-Proto} !https [NC]';
        $lines[] = 'RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L,NE]';
    }

    if (count($lines) === 2) {
        $lines[] = '# Sin reglas adicionales de despliegue';
    }

    $lines[] = htaccess_deploy_marker_end();
    return implode(PHP_EOL, $lines) . PHP_EOL;
}

function htaccess_deploy_apply(array $config): string
{
    $path = htaccess_deploy_path();
    $content = is_file($path) ? (string) file_get_contents($path) : '';
    $block = htaccess_deploy_rules($config);
    $begin = htaccess_deploy_marker_begin();
    $end = htaccess_deploy_marker_end();

    if (str_contains($content, $begin) && str_contains($content, $end)) {
        $pattern = '/' . preg_quote($begin, '/') . '.*?' . preg_quote($end, '/') . '\R?/s';
        $updated = preg_replace($pattern, $block, $content, 1);
        if (!is_string($updated)) {
            throw new RuntimeException('No se pudo actualizar el bloque de despliegue en .htaccess.');
        }
    } else {
        $prefix = $content === '' ? '' : rtrim($content) . PHP_EOL . PHP_EOL;
        $updated = $prefix . $block;
    }

    $backup = is_file($path) ? env_file_backup($path) : null;
    $temp = $path . '.tmp.' . bin2hex(random_bytes(4));
    if (file_put_contents($temp, $updated, LOCK_EX) === false) {
        @unlink($temp);
        throw new RuntimeException('No se pudo escribir .htaccess.');
    }
    if (!rename($temp, $path)) {
        @unlink($temp);
        throw new RuntimeException('No se pudo actualizar .htaccess.');
    }

    return $backup ?? '';
}
