<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/repository.php';

function admin_url(string $path = ''): string
{
    return url('admin/' . ltrim($path, '/'));
}

function admin_require_login(): void
{
    if (!admin_session_is_valid()) {
        admin_logout();
        header('Location: ' . admin_url('login.php'));
        exit;
    }
}

function admin_session_is_valid(): bool
{
    $username = (string) ($_SESSION['admin_user'] ?? '');
    if ($username === '') {
        return false;
    }
    $now = time();
    $idleTimeout = max(300, (int) env('ADMIN_SESSION_IDLE_SECONDS', '1800'));
    $absoluteTimeout = max($idleTimeout, (int) env('ADMIN_SESSION_ABSOLUTE_SECONDS', '43200'));
    if ($now - (int) ($_SESSION['admin_last_activity'] ?? 0) > $idleTimeout
        || $now - (int) ($_SESSION['admin_authenticated_at'] ?? 0) > $absoluteTimeout
        || (int) ($_SESSION['admin_session_version'] ?? 0) !== admin_session_version($username)) {
        return false;
    }
    $_SESSION['admin_last_activity'] = $now;
    return true;
}

function admin_user(): string
{
    return (string) ($_SESSION['admin_user'] ?? '');
}

function admin_logout(): void
{
    unset(
        $_SESSION['admin_user'],
        $_SESSION['admin_session_version'],
        $_SESSION['admin_authenticated_at'],
        $_SESSION['admin_last_activity'],
        $_SESSION['admin_csrf']
    );
    session_regenerate_id(true);
}

function admin_login(string $username, string $password): bool
{
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    if (login_is_limited('admin', $username, $ip)) {
        return false;
    }
    $valid = admin_verify($username, $password);
    login_record_attempt('admin', $username, $ip, $valid);
    if (!$valid) {
        return false;
    }
    session_regenerate_id(true);
    $_SESSION['admin_user'] = $username;
    $_SESSION['admin_session_version'] = admin_session_version($username);
    $_SESSION['admin_authenticated_at'] = time();
    $_SESSION['admin_last_activity'] = time();
    return true;
}

function admin_csrf(): string
{
    if (empty($_SESSION['admin_csrf'])) {
        $_SESSION['admin_csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['admin_csrf'];
}

function admin_csrf_ok(?string $token): bool
{
    return is_string($token) && hash_equals(admin_csrf(), $token);
}

function admin_money(int $amount): string
{
    return '$' . number_format($amount, 0, '.', ',');
}

function admin_estados_lead(): array
{
    return ['nuevo' => 'Nuevo', 'contactado' => 'Contactado', 'cerrado' => 'Cerrado'];
}

function admin_estados_pedido(): array
{
    return [
        'procesando' => 'Procesando',
        'preparacion' => 'En preparación',
        'transito' => 'En tránsito',
        'entregado' => 'Entregado',
    ];
}
