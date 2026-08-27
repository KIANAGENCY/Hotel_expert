<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ' . url('contacto/'));
    exit;
}

if (!csrf_ok($_POST['csrf'] ?? null)) {
    $_SESSION['form_error'] = 'Sesión expirada. Vuelve a enviar el formulario.';
    $returnPage = ($_POST['origen'] ?? '') === 'cotizacion' ? 'cotizacion.php' : 'contacto.php';
    header('Location: ' . url($returnPage));
    exit;
}

$productos = require ROOT_PATH . '/data/productos.php';
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

if ($lead['nombre'] === '' || $lead['hotel'] === '' || !filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['form_error'] = 'Revisa nombre, hotel y un correo válido.';
    $returnPage = $lead['origen'] === 'cotizacion' ? 'cotizacion.php' : 'contacto.php';
    header('Location: ' . url($returnPage));
    exit;
}

if ($lead['origen'] === 'cotizacion' && empty($carrito)) {
    $_SESSION['form_error'] = 'Agrega al menos un producto antes de solicitar la cotización.';
    header('Location: ' . url('cotizacion.php'));
    exit;
}

$file = ROOT_PATH . '/data/leads.json';
$all = [];
if (is_file($file)) {
    $decoded = json_decode((string) file_get_contents($file), true);
    if (is_array($decoded)) {
        $all = $decoded;
    }
}
$all[] = $lead;
file_put_contents($file, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

$subject = 'Solicitud Sistema ELAH — ' . $lead['hotel'];
$body = "Nuevo lead B2B\n\n" . json_encode($lead, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$headers = 'From: noreply@' . SITE_DOMAIN . "\r\nReply-To: " . $lead['email'];
@mail(EMAIL_VENTAS, $subject, $body, $headers);

/* HubSpot: POST al webhook de forms cuando exista portal
$hubspot = [
    'headers' => ['Content-Type: application/json'],
    ...
];
*/

$_SESSION['last_request_type'] = $lead['origen'];
header('Location: ' . url('gracias.php'));
exit;




