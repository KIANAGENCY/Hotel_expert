<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/customer-auth.php';
customer_require_login();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !customer_csrf_ok($_POST['csrf'] ?? null)) {
    account_flash('La sesión expiró. Intenta nuevamente.', 'error');
    header('Location: ' . account_url());
    exit;
}

$customer = current_customer();
$action = (string) ($_POST['action'] ?? '');

if ($action === 'profile') {
    if (!customer_identity_valid((string) ($_POST['nombre'] ?? ''), (string) ($_POST['hotel'] ?? ''))) {
        account_flash('Ingresa al menos un nombre y un apellido válidos, además del hotel o empresa.', 'error');
        header('Location: ' . account_url());
        exit;
    }
    if (!customer_phone_valid((string) ($_POST['telefono'] ?? ''))) {
        account_flash('Ingresa un teléfono válido con lada o déjalo vacío.', 'error');
        header('Location: ' . account_url());
        exit;
    }
    if (!customer_rfc_valid((string) ($_POST['rfc'] ?? ''))) {
        account_flash('Ingresa un RFC válido de 12 o 13 caracteres o déjalo vacío.', 'error');
        header('Location: ' . account_url());
        exit;
    }
    customer_update_profile((int) $customer['id'], $_POST);
    account_flash('Datos del perfil actualizados.');
    header('Location: ' . account_url());
    exit;
}

if ($action === 'reorder') {
    $order = pedido_for_customer(strtoupper(trim((string) ($_POST['order_id'] ?? ''))), (int) $customer['id']);
    if (!$order) {
        account_flash('No encontramos ese pedido en tu cuenta.', 'error');
        header('Location: ' . account_url());
        exit;
    }
    $cart = [];
    $products = productos_all();
    foreach (pedido_items((string) $order['id']) as $item) {
        if (isset($products[$item['slug']])) {
            $cart[$item['slug']] = min(99, max(1, (int) $item['cantidad']));
        }
    }
    if ($cart === []) {
        account_flash('Este pedido no tiene productos disponibles para recompra.', 'warning');
        header('Location: ' . account_url('pedido/?id=' . rawurlencode($order['id'])));
        exit;
    }
    $_SESSION['reorder_cart'] = $cart;
    header('Location: ' . url('cotizacion/'));
    exit;
}

account_flash('Acción no reconocida.', 'error');
header('Location: ' . account_url());
exit;
