<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$rootFs = dirname(__DIR__);
$docRoot = rtrim(str_replace('\\', '/', (string) ($_SERVER['DOCUMENT_ROOT'] ?? '')), '/');
$rootNorm = rtrim(str_replace('\\', '/', $rootFs), '/');
$base = '';
if ($docRoot !== '' && str_starts_with($rootNorm, $docRoot)) {
    $base = substr($rootNorm, strlen($docRoot));
}
define('BASE_URL', $base === '' || $base === false ? '' : $base);
define('ROOT_PATH', $rootFs);

define('SITE_NAME', 'Hotel Expert');
define('SITE_TAGLINE', 'Estandarización de Limpieza y Aroma en Hoteles');
define('SITE_CLAIM', 'Frescura que se siente. Marca que se recuerda.');
define('SITE_DOMAIN', 'www.hotelexpert.mx');
define('SITE_ORIGIN', 'https://' . SITE_DOMAIN);
define('WHATSAPP', '528112497481');
define('WHATSAPP_DISPLAY', '+52 81 1249 7481');
define('EMAIL_VENTAS', 'ventas@hotelexpert.mx');
define('WHATSAPP_MSG', rawurlencode('Hola Hotel Expert, quiero información del Sistema ELAH para mi hotel.'));
define('WHATSAPP_MSG_MUESTRA', rawurlencode('Hola Hotel Expert, quiero solicitar una muestra del Sistema ELAH para mi hotel.'));

$page = basename((string) ($_SERVER['PHP_SELF'] ?? 'index.php'), '.php');

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return BASE_URL . '/' . $path;
}

function whatsapp_url(string $message = WHATSAPP_MSG): string
{
    return 'https://wa.me/' . site_whatsapp() . '?text=' . $message;
}

function is_active(string $slug): bool
{
    global $page;
    return $page === $slug;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}

function csrf_ok(?string $token): bool
{
    return is_string($token) && hash_equals(csrf_token(), $token);
}

$nav = [
    ['Sistema ELAH', 'sistema-elah/', 'sistema-elah'],
    ['Productos', 'productos/', 'productos'],
    ['Aroma insignia', 'aroma-insignia/', 'aroma-insignia'],
    ['Recursos', 'recursos/', 'recursos'],
    ['Nosotros', 'nosotros/', 'nosotros'],
    ['Contacto', 'contacto/', 'contacto'],
];

require_once __DIR__ . '/repository.php';

function site_setting(string $key, string $fallback = ''): string
{
    try {
        $cache = settings_all();
    } catch (Throwable) {
        $cache = [];
    }
    return $cache[$key] ?? $fallback;
}

function site_email(): string
{
    return site_setting('email_ventas', EMAIL_VENTAS);
}

function site_whatsapp(): string
{
    return site_setting('whatsapp', WHATSAPP);
}

function site_whatsapp_display(): string
{
    return site_setting('whatsapp_display', WHATSAPP_DISPLAY);
}

$social = [
    'facebook' => site_setting('social_facebook') ?: null,
    'instagram' => site_setting('social_instagram') ?: null,
];
