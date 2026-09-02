<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/customer-auth.php';

function test_expect(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException('FALLÓ: ' . $message);
    }
    fwrite(STDOUT, "OK: {$message}\n");
}

$suffix = bin2hex(random_bytes(5));
$emailA = "test-a-{$suffix}@example.test";
$emailB = "test-b-{$suffix}@example.test";
$orderId = 'TEST-' . strtoupper($suffix);
$leadId = 0;
$customerA = 0;
$customerB = 0;

try {
    test_expect(db()->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql', 'la aplicación usa el controlador MySQL');
    $cryptoProbe = pii_encrypt('dato-sensible', 'tests.probe');
    test_expect(
        pii_is_encrypted($cryptoProbe)
        && $cryptoProbe !== 'dato-sensible'
        && pii_decrypt($cryptoProbe, 'tests.probe') === 'dato-sensible',
        'AES-256-GCM cifra y descifra con contexto autenticado'
    );
    $tamperedProbe = substr($cryptoProbe, 0, -1) . (str_ends_with($cryptoProbe, '0') ? '1' : '0');
    try {
        pii_decrypt($tamperedProbe, 'tests.probe');
        $tamperRejected = false;
    } catch (UnexpectedValueException) {
        $tamperRejected = true;
    }
    test_expect($tamperRejected, 'AES-256-GCM rechaza ciphertext manipulado');
    $customerA = customer_create([
        'email' => $emailA,
        'password' => 'SecureTest123',
        'nombre' => 'Cliente Prueba A',
        'hotel' => 'Hotel A',
        'telefono' => '8112345678',
        'rfc' => 'TEST010101AA1',
    ]);
    $customerB = customer_create([
        'email' => $emailB,
        'password' => 'SecureTest456',
        'nombre' => 'Cliente Prueba B',
        'hotel' => 'Hotel B',
    ]);

    $stored = customer_get($customerA);
    test_expect($stored !== null, 'el cliente se persiste');
    test_expect($stored['email'] === $emailA && $stored['telefono'] === '8112345678' && $stored['rfc'] === 'TEST010101AA1', 'los datos sensibles se descifran al leerlos');
    test_expect($stored['password_hash'] !== 'SecureTest123', 'la contraseña no se almacena en texto plano');
    test_expect(password_verify('SecureTest123', $stored['password_hash']), 'el hash verifica la contraseña correcta');
    $rawCustomerStmt = db()->prepare('SELECT email, email_blind_index, telefono, rfc, password_hash FROM customers WHERE id = ?');
    $rawCustomerStmt->execute([$customerA]);
    $rawCustomer = $rawCustomerStmt->fetch();
    test_expect(
        pii_is_encrypted((string) $rawCustomer['email'])
        && pii_is_encrypted((string) $rawCustomer['telefono'])
        && pii_is_encrypted((string) $rawCustomer['rfc'])
        && !str_contains(implode(' ', $rawCustomer), $emailA)
        && !str_contains(implode(' ', $rawCustomer), '8112345678')
        && !str_contains(implode(' ', $rawCustomer), 'TEST010101AA1')
        && !str_contains(implode(' ', $rawCustomer), 'SecureTest123'),
        'la base no contiene PII ni contraseña en texto plano'
    );
    test_expect(hash_equals(pii_email_blind_index($emailA), $rawCustomer['email_blind_index']), 'el blind index permite buscar el correo sin exponerlo');
    test_expect(customer_verify_credentials($emailA, 'incorrecta') === null, 'credenciales incorrectas se rechazan');
    test_expect(customer_verify_credentials($emailA, 'SecureTest123') !== null, 'credenciales correctas se aceptan');

    $verification = bin2hex(random_bytes(32));
    customer_store_token('email_verification_tokens', $customerA, $verification, date('Y-m-d H:i:s', time() + 300));
    $tokenStmt = db()->prepare('SELECT token_hash FROM email_verification_tokens WHERE customer_id = ?');
    $tokenStmt->execute([$customerA]);
    $storedToken = (string) $tokenStmt->fetchColumn();
    test_expect($storedToken !== $verification && hash_equals(hash('sha256', $verification), $storedToken), 'el token se almacena únicamente como hash');
    test_expect(customer_verify_email_token($verification), 'token de verificación válido se acepta');
    test_expect(!customer_verify_email_token($verification), 'token de verificación es de un solo uso');

    $reset = bin2hex(random_bytes(32));
    customer_store_token('password_reset_tokens', $customerA, $reset, date('Y-m-d H:i:s', time() + 300));
    [$resetOk] = customer_reset_password($reset, 'UpdatedPass123', 'UpdatedPass123');
    test_expect($resetOk, 'restablecimiento con token válido funciona');
    test_expect((int) customer_get($customerA)['session_version'] > 1, 'restablecer contraseña revoca sesiones anteriores');
    test_expect(customer_verify_credentials($emailA, 'UpdatedPass123') !== null, 'la contraseña nueva funciona');
    [$reused] = customer_reset_password($reset, 'AnotherPass123', 'AnotherPass123');
    test_expect(!$reused, 'token de restablecimiento no puede reutilizarse');

    pedido_save([
        'id' => $orderId,
        'customer_id' => $customerA,
        'email' => $emailA,
        'hotel' => 'Hotel A',
        'estado' => 'procesando',
        'fecha' => date('Y-m-d'),
        'eta' => date('Y-m-d', time() + 86400),
        'items' => '1× Producto prueba',
        'guia' => '',
        'order_items' => [[
            'slug' => 'estandar',
            'name' => 'Hotel Expert',
            'quantity' => 1,
            'unit_price' => 100,
        ]],
    ]);
    $rawOrderStmt = db()->prepare('SELECT email, email_blind_index FROM orders WHERE id = ?');
    $rawOrderStmt->execute([$orderId]);
    $rawOrder = $rawOrderStmt->fetch();
    test_expect(pii_is_encrypted((string) $rawOrder['email']) && !str_contains((string) $rawOrder['email'], $emailA), 'el correo del pedido se almacena cifrado');
    test_expect(pedido_get($orderId, strtoupper($emailA)) !== null, 'el rastreo encuentra el pedido mediante blind index normalizado');
    test_expect(pedido_for_customer($orderId, $customerA) !== null, 'el dueño puede consultar su pedido');
    test_expect(pedido_for_customer($orderId, $customerB) === null, 'otro cliente no puede consultar el pedido');
    test_expect(count(pedido_items($orderId)) === 1, 'las líneas estructuradas permiten recompra');

    $leadId = lead_create([
        'customer_id' => $customerA,
        'fecha' => date('c'),
        'origen' => 'cotizacion',
        'nombre' => 'Cliente Prueba A',
        'hotel' => 'Hotel A',
        'email' => $emailA,
        'telefono' => '8187654321',
        'rfc' => 'TEST010101AA1',
        'carrito' => [],
    ]);
    test_expect($leadId > 0 && (int) lead_get($leadId)['customer_id'] === $customerA, 'la cotización se vincula al cliente en MySQL');
    $rawLeadStmt = db()->prepare('SELECT email, telefono, rfc FROM leads WHERE id = ?');
    $rawLeadStmt->execute([$leadId]);
    $rawLead = $rawLeadStmt->fetch();
    test_expect(
        pii_is_encrypted((string) $rawLead['email'])
        && pii_is_encrypted((string) $rawLead['telefono'])
        && pii_is_encrypted((string) $rawLead['rfc'])
        && !str_contains(implode(' ', $rawLead), $emailA),
        'la cotización almacena correo, teléfono y RFC cifrados'
    );

    $csrf = customer_csrf();
    test_expect(customer_csrf_ok($csrf), 'CSRF válido se acepta');
    test_expect(!customer_csrf_ok('token-invalido'), 'CSRF inválido se rechaza');
    test_expect(customer_phone_valid('81 1234 5678') && customer_phone_valid('') && !customer_phone_valid('teléfono falso'), 'teléfono de perfil valida formato y permite vacío');
    test_expect(customer_rfc_valid('HEM010101ABC') && customer_rfc_valid('GODE561231GR8') && customer_rfc_valid('') && !customer_rfc_valid('RFC123'), 'RFC valida personas morales, físicas y permite vacío');

    for ($i = 0; $i < 5; $i++) {
        login_record_attempt('test-' . $suffix, $emailA, '127.0.0.1', false);
    }
    test_expect(login_is_limited('test-' . $suffix, $emailA, '127.0.0.1'), 'rate limit bloquea tras cinco fallos');
    for ($i = 0; $i < 5; $i++) {
        login_record_attempt('account-' . $suffix, $emailA, '10.0.0.' . $i, false);
    }
    test_expect(login_is_limited('account-' . $suffix, $emailA, '192.0.2.10'), 'rate limit protege una cuenta entre distintas IP');
    for ($i = 0; $i < 5; $i++) {
        login_record_attempt('ip-' . $suffix, "other-{$i}@example.test", '192.0.2.20', false);
    }
    test_expect(login_is_limited('ip-' . $suffix, 'new@example.test', '192.0.2.20'), 'rate limit protege una IP entre distintas cuentas');
} finally {
    $pdo = db();
    if ($leadId) {
        $pdo->prepare('DELETE FROM leads WHERE id = ?')->execute([$leadId]);
    }
    $pdo->prepare('DELETE FROM orders WHERE id = ?')->execute([$orderId]);
    $pdo->prepare('DELETE FROM login_attempts WHERE scope = ?')->execute(['test-' . $suffix]);
    $pdo->prepare('DELETE FROM login_attempts WHERE scope IN (?, ?)')->execute(['account-' . $suffix, 'ip-' . $suffix]);
    if ($customerA || $customerB) {
        $ids = array_values(array_filter([$customerA, $customerB]));
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $pdo->prepare("DELETE FROM customers WHERE id IN ({$placeholders})")->execute($ids);
    }
}

fwrite(STDOUT, "Todas las pruebas de autenticación pasaron.\n");
