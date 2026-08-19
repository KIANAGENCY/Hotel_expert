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
define('SITE_TAGLINE', 'Más que limpieza, estandarizamos experiencias.');
define('SITE_CLAIM', 'Frescura que se siente. Marca que se recuerda.');
define('SITE_DOMAIN', 'www.hotelexpert.mx');
define('WHATSAPP', '528112497481');
define('WHATSAPP_DISPLAY', '+52 81 1249 7481');
define('EMAIL_VENTAS', 'ventas@hotelexpert.mx');
define('WHATSAPP_MSG', rawurlencode('Hola Hotel Expert, quiero una cotización B2B para mi hotel.'));

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
    ['Inicio', 'index.php', 'index'],
    ['Catálogo', 'catalogo.php', 'catalogo'],
    ['Cómo funciona', 'como-funciona.php', 'como-funciona'],
    ['Rastreo', 'rastreo.php', 'rastreo'],
    ['Nosotros', 'nosotros.php', 'nosotros'],
    ['Blog', 'blog.php', 'blog'],
    ['Contacto', 'contacto.php', 'contacto'],
];
