<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ' . url('contacto.php'));
    exit;
}

if (!csrf_ok($_POST['csrf'] ?? null)) {
    $_SESSION['form_error'] = 'Sesi�n expirada. Vuelve a enviar el formulario.';
    header('Location: ' . url('contacto.php'));
    exit;
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
    'habitaciones' => trim((string) ($_POST['habitaciones'] ?? '')),
    'mensaje' => trim((string) ($_POST['mensaje'] ?? '')),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
];

if ($lead['nombre'] === '' || $lead['hotel'] === '' || !filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['form_error'] = 'Revisa nombre, hotel y un correo v�lido.';
    header('Location: ' . url('contacto.php'));
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
file_put_contents($file, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

$subject = 'Lead Hotel Expert � ' . $lead['hotel'];
$body = "Nuevo lead B2B\n\n" . json_encode($lead, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$headers = 'From: noreply@' . SITE_DOMAIN . "\r\nReply-To: " . $lead['email'];
@mail(EMAIL_VENTAS, $subject, $body, $headers);

/* HubSpot: POST al webhook de forms cuando exista portal
$hubspot = [
    'headers' => ['Content-Type: application/json'],
    ...
];
*/

header('Location: ' . url('gracias.php'));
exit;
