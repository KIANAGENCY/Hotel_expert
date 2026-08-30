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
    if (empty($_SESSION['admin_user'])) {
        header('Location: ' . admin_url('login.php'));
        exit;
    }
}

function admin_user(): string
{
    return (string) ($_SESSION['admin_user'] ?? '');
}

function admin_logout(): void
{
    unset($_SESSION['admin_user']);
}

function admin_login(string $username, string $password): bool
{
    if (!admin_verify($username, $password)) {
        return false;
    }
    $_SESSION['admin_user'] = $username;
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
