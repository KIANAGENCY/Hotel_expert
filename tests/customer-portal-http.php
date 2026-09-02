<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/customer-auth.php';

function http_request(string $url, string $cookieJar, ?array $post = null): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIEJAR => $cookieJar,
        CURLOPT_COOKIEFILE => $cookieJar,
        CURLOPT_TIMEOUT => 15,
    ]);
    if ($post !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    $body = (string) curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    return [$status, $body];
}

function form_csrf(string $html): string
{
    if (!preg_match('/name="csrf" value="([^"]+)"/', $html, $match)) {
        throw new RuntimeException('No se encontró token CSRF en la respuesta.');
    }
    return html_entity_decode($match[1], ENT_QUOTES);
}

function portal_expect(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException('FALLÓ: ' . $message);
    }
    fwrite(STDOUT, "OK: {$message}\n");
}

$base = rtrim(env('TEST_BASE_URL', 'http://hotel_expert.test'), '/');
$suffix = bin2hex(random_bytes(4));
$email = "portal-{$suffix}@example.test";
$password = 'PortalSecure123';
$orderId = 'PORTAL-' . strtoupper($suffix);
$cookieJar = tempnam(sys_get_temp_dir(), 'elah-cookie-');
$customerId = 0;

try {
    [$status, $registrationHtml] = http_request($base . '/cuenta/registro/', $cookieJar);
    portal_expect($status === 200, 'la pantalla de registro responde');
    [$status] = http_request($base . '/cuenta/registro/', $cookieJar, [
        'csrf' => form_csrf($registrationHtml),
        'nombre' => 'Portal Prueba',
        'hotel' => 'Hotel Portal',
        'email' => $email,
        'telefono' => '8100000000',
        'rfc' => '',
        'password' => $password,
        'password_confirmation' => $password,
    ]);
    $registered = customer_by_email($email);
    portal_expect($status === 200 && $registered !== null, 'el registro crea la cuenta y redirige al login');
    portal_expect(password_verify($password, $registered['password_hash']), 'el registro HTTP guarda una contraseña hasheada');
    $rawRegistrationStmt = db()->prepare('SELECT email, telefono, rfc FROM customers WHERE id = ?');
    $rawRegistrationStmt->execute([(int) $registered['id']]);
    $rawRegistration = $rawRegistrationStmt->fetch();
    portal_expect(
        pii_is_encrypted((string) $rawRegistration['email'])
        && pii_is_encrypted((string) $rawRegistration['telefono'])
        && pii_is_encrypted((string) $rawRegistration['rfc'])
        && !str_contains(implode(' ', $rawRegistration), $email),
        'el registro HTTP cifra los datos sensibles antes de guardarlos'
    );
    $customerId = (int) $registered['id'];
    customer_mark_verified($customerId);
    pedido_save([
        'id' => $orderId,
        'customer_id' => $customerId,
        'email' => $email,
        'hotel' => 'Hotel Portal',
        'estado' => 'transito',
        'fecha' => date('Y-m-d'),
        'eta' => date('Y-m-d', time() + 86400),
        'items' => '2× Hotel Expert',
        'guia' => 'TEST-GUIA',
        'order_items' => [['slug' => 'estandar', 'name' => 'Hotel Expert', 'quantity' => 2, 'unit_price' => 100]],
    ]);

    [$status, $loginHtml] = http_request($base . '/cuenta/login/', $cookieJar);
    portal_expect($status === 200, 'la pantalla de login responde');
    portal_expect(
        str_contains($loginHtml, 'is-account-portal')
        && str_contains($loginHtml, 'Volver al sitio')
        && !preg_match('/class="header-nav\b/', $loginHtml)
        && !str_contains($loginHtml, 'header-sample-cta'),
        'el login usa header del portal sin navbar comercial'
    );
    [$status, $dashboard] = http_request($base . '/cuenta/login/', $cookieJar, [
        'csrf' => form_csrf($loginHtml),
        'email' => $email,
        'password' => $password,
    ]);
    portal_expect($status === 200 && str_contains($dashboard, $orderId), 'login redirige al historial propio');
    portal_expect(str_contains($dashboard, 'Recomprar productos'), 'el dashboard ofrece recompra');
    portal_expect(
        str_contains($dashboard, 'is-account-portal')
        && str_contains($dashboard, 'account-portal-nav')
        && !preg_match('/class="header-nav\b/', $dashboard)
        && !str_contains($dashboard, 'header-sample-cta')
        && !str_contains($dashboard, 'customer-context-bar'),
        'el panel usa header exclusivo del portal con navegación integrada'
    );
    portal_expect(
        str_contains($dashboard, 'account-portal-link is-active')
        && str_contains($dashboard, 'Cerrar sesión'),
        'el panel marca Mi cuenta y ofrece cerrar sesión en el header'
    );
    portal_expect(
        str_contains($dashboard, 'placeholder="Ej. María González"')
        && str_contains($dashboard, 'placeholder="Ej. Hotel Reforma"')
        && str_contains($dashboard, 'placeholder="Ej. 81 1234 5678"')
        && str_contains($dashboard, 'placeholder="Ej. HRE250101AB1"')
        && str_contains($dashboard, 'profile-rfc-help'),
        'el perfil muestra placeholders, ayudas y validaciones'
    );

    [$status, $orderPage] = http_request($base . '/cuenta/pedido/?id=' . rawurlencode($orderId), $cookieJar);
    portal_expect($status === 200 && str_contains($orderPage, $orderId), 'el detalle de pedido responde');
    portal_expect(
        str_contains($orderPage, 'is-account-portal')
        && str_contains($orderPage, 'account-portal-nav')
        && !str_contains($orderPage, 'account-order-toolbar'),
        'el detalle de pedido evita navegación duplicada'
    );

    [$status, $quote] = http_request($base . '/cuenta/action/', $cookieJar, [
        'csrf' => form_csrf($dashboard),
        'action' => 'reorder',
        'order_id' => $orderId,
    ]);
    portal_expect($status === 200 && str_contains($quote, 'ELAH_REORDER_CART'), 'recompra carga productos y abre cotización');
    portal_expect(
        str_contains($quote, 'is-account-portal')
        && str_contains($quote, 'account-portal-nav')
        && !preg_match('/class="header-nav\b/', $quote)
        && !str_contains($quote, 'customer-context-bar')
        && preg_match('/account-portal-link is-active[^>]*href="[^"]*cotizacion/', $quote) === 1,
        'cotización con sesión usa header exclusivo del portal'
    );
} finally {
    $pdo = db();
    $pdo->prepare('DELETE FROM orders WHERE id = ?')->execute([$orderId]);
    if ($customerId) {
        $pdo->prepare('DELETE FROM customers WHERE id = ?')->execute([$customerId]);
    }
    $pdo->exec("DELETE FROM login_attempts WHERE scope IN ('registration', 'email-verification', 'customer')");
    @unlink($cookieJar);
}

fwrite(STDOUT, "Flujo HTTP del portal completado.\n");
