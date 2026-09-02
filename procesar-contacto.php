<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/mailer.php';

$returnPath = static function (string $origin): string {
    return match ($origin) {
        'cotizacion' => 'cotizacion/',
        'muestra' => 'muestra/',
        default => 'contacto/',
    };
};

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ' . url('contacto/'));
    exit;
}

if (!csrf_ok($_POST['csrf'] ?? null)) {
    $_SESSION['form_error'] = 'Sesión expirada. Vuelve a enviar el formulario.';
    header('Location: ' . url($returnPath((string) ($_POST['origen'] ?? 'contacto'))));
    exit;
}

$productos = productos_all();
$carritoRaw = json_decode((string) ($_POST['carrito'] ?? '[]'), true);
$carrito = [];
$subtotal = 0;
if (is_array($carritoRaw)) {
    foreach ($carritoRaw as $item) {
        $slug = preg_replace('/[^a-z0-9-]/', '', (string) ($item['slug'] ?? ''));
        $cantidad = max(1, min(99, (int) ($item['qty'] ?? 1)));
        if (!isset($productos[$slug])) {
            continue;
        }
        $producto = $productos[$slug];
        $lineTotal = $producto['precio'] * $cantidad;
        $subtotal += $lineTotal;
        $carrito[] = [
            'slug' => $slug,
            'nombre' => $producto['nombre'],
            'cantidad' => $cantidad,
            'precio_unitario' => $producto['precio'],
            'subtotal' => $lineTotal,
        ];
    }
}

$lead = [
    'customer_id' => !empty($_SESSION['customer_id']) ? (int) $_SESSION['customer_id'] : null,
    'fecha' => date('c'),
    'origen' => substr(preg_replace('/[^a-z0-9-]/', '', (string) ($_POST['origen'] ?? 'contacto')), 0, 40),
    'nombre' => trim((string) ($_POST['nombre'] ?? '')),
    'cargo' => trim((string) ($_POST['cargo'] ?? '')),
    'hotel' => trim((string) ($_POST['hotel'] ?? '')),
    'ciudad' => trim((string) ($_POST['ciudad'] ?? '')),
    'email' => trim((string) ($_POST['email'] ?? '')),
    'telefono' => trim((string) ($_POST['telefono'] ?? '')),
    'interes' => trim((string) ($_POST['interes'] ?? '')),
    'tipo_propiedad' => trim((string) ($_POST['tipo_propiedad'] ?? '')),
    'habitaciones' => trim((string) ($_POST['habitaciones'] ?? '')),
    'rfc' => strtoupper(trim((string) ($_POST['rfc'] ?? ''))),
    'mensaje' => trim((string) ($_POST['mensaje'] ?? '')),
    'carrito' => $carrito,
    'subtotal_sin_iva' => $subtotal,
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
];

$authenticatedCustomer = $lead['customer_id'] ? customer_get((int) $lead['customer_id']) : null;
if ($authenticatedCustomer) {
    $lead['email'] = (string) $authenticatedCustomer['email'];
}

if ($lead['nombre'] === '' || $lead['hotel'] === '' || !filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['form_error'] = 'Revisa nombre, hotel y un correo válido.';
    header('Location: ' . url($returnPath((string) $lead['origen'])));
    exit;
}

$tooLong = mb_strlen($lead['nombre']) > 190
    || mb_strlen($lead['cargo']) > 190
    || mb_strlen($lead['hotel']) > 190
    || mb_strlen($lead['ciudad']) > 190
    || mb_strlen($lead['email']) > 254
    || mb_strlen($lead['telefono']) > 60
    || mb_strlen($lead['interes']) > 255
    || mb_strlen($lead['tipo_propiedad']) > 100
    || mb_strlen($lead['habitaciones']) > 30
    || mb_strlen($lead['rfc']) > 20
    || mb_strlen($lead['mensaje']) > 5000;
if ($tooLong) {
    $_SESSION['form_error'] = 'Uno de los datos excede la longitud permitida.';
    header('Location: ' . url($returnPath((string) $lead['origen'])));
    exit;
}

if (rate_limit_exceeded('public-form', $lead['email'], (string) $lead['ip'], 8, 60)) {
    $_SESSION['form_error'] = 'Alcanzaste el límite temporal de solicitudes. Intenta nuevamente más tarde.';
    header('Location: ' . url($returnPath((string) $lead['origen'])));
    exit;
}

if ($lead['origen'] === 'cotizacion' && empty($carrito)) {
    $_SESSION['form_error'] = 'Agrega al menos un producto antes de solicitar la cotización.';
    header('Location: ' . url('cotizacion.php'));
    exit;
}

$leadId = lead_create(array_merge($lead, ['estado' => 'nuevo']));
send_lead_notification($leadId, (string) $lead['origen']);

/* HubSpot: POST al webhook de forms cuando exista portal
$hubspot = [
    'headers' => ['Content-Type: application/json'],
    ...
];
*/

$_SESSION['last_request_type'] = $lead['origen'];
header('Location: ' . url('gracias.php'));
exit;

