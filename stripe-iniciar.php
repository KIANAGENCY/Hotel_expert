<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/cart-pricing.php';
require_once __DIR__ . '/includes/stripe-checkout.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ' . url('cotizacion/'));
    exit;
}

if (!csrf_ok($_POST['csrf'] ?? null)) {
    $_SESSION['form_error'] = 'La sesión expiró. Vuelve a intentar el pago.';
    header('Location: ' . url('cotizacion/'));
    exit;
}

if (!stripe_is_enabled() || !stripe_status_summary()['ready']) {
    $_SESSION['form_error'] = 'Los pagos en línea no están disponibles en este momento.';
    header('Location: ' . url('cotizacion/'));
    exit;
}

$totals = cart_totals_from_payload($_POST['carrito'] ?? '[]');
if ($totals['items'] === []) {
    $_SESSION['form_error'] = 'Agrega al menos un producto antes de pagar.';
    header('Location: ' . url('cotizacion/'));
    exit;
}

$customer = !empty($_SESSION['customer_id']) ? customer_get((int) $_SESSION['customer_id']) : null;
$nombre = trim((string) ($_POST['nombre'] ?? ($customer['nombre'] ?? '')));
$hotel = trim((string) ($_POST['hotel'] ?? ($customer['hotel'] ?? '')));
$email = trim((string) ($_POST['email'] ?? ($customer['email'] ?? '')));
$telefono = trim((string) ($_POST['telefono'] ?? ($customer['telefono'] ?? '')));

if ($nombre === '' || $hotel === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['form_error'] = 'Para pagar, completa nombre, hotel y un correo válido en el formulario.';
    header('Location: ' . url('cotizacion/'));
    exit;
}

if (rate_limit_exceeded('stripe-checkout', $email, (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'), 6, 30)) {
    $_SESSION['form_error'] = 'Demasiados intentos de pago. Espera unos minutos e inténtalo de nuevo.';
    header('Location: ' . url('cotizacion/'));
    exit;
}

$orderId = order_generate_id();
$buyer = [
    'nombre' => $nombre,
    'hotel' => $hotel,
    'email' => $email,
    'telefono' => $telefono,
    'customer_id' => $customer['id'] ?? null,
];

try {
    $session = stripe_checkout_create($totals, $orderId, $buyer);
    stripe_checkout_store($session['id'], $orderId, $buyer, $totals);
    header('Location: ' . $session['url']);
    exit;
} catch (Throwable $e) {
    $_SESSION['form_error'] = 'No se pudo iniciar el pago: ' . $e->getMessage();
    header('Location: ' . url('cotizacion/'));
    exit;
}
