<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

ob_start();

require_once dirname(__DIR__) . '/includes/customer-auth.php';

$email = 'demo.cliente@hotel.test';
$password = 'Demo2026';

echo "=== Diagnóstico login demo ===\n";

$customer = customer_by_email($email);
if (!$customer) {
    echo "ERROR: customer_by_email no encontró la cuenta\n";
    exit(1);
}
echo "ID: {$customer['id']}\n";
echo "Email descifrado: {$customer['email']}\n";
echo "Verificada: " . ($customer['email_verified_at'] ?? 'NO') . "\n";

$verified = customer_verify_credentials($email, $password);
echo "verify_credentials: " . ($verified ? 'OK' : 'FALLÓ') . "\n";

$raw = db()->prepare('SELECT password_hash FROM customers WHERE id = ?');
$raw->execute([(int) $customer['id']]);
$hash = (string) $raw->fetchColumn();
echo "password_verify directo: " . (password_verify($password, $hash) ? 'OK' : 'FALLÓ') . "\n";

$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
[$ok, $msg] = customer_login_account($email, $password);
echo "customer_login_account: " . ($ok ? 'OK' : 'FALLÓ') . " — {$msg}\n";

$limited = login_is_limited('customer', $email, '127.0.0.1');
echo "rate_limited: " . ($limited ? 'SÍ' : 'no') . "\n";

// HTTP test
$cookie = tempnam(sys_get_temp_dir(), 'demo-login-');
$base = rtrim(env('APP_URL', 'http://hotel_expert.test'), '/');
$ch = curl_init($base . '/cuenta/login/');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEJAR => $cookie,
    CURLOPT_COOKIEFILE => $cookie,
]);
$html = (string) curl_exec($ch);
curl_close($ch);
if (!preg_match('/name="csrf" value="([^"]+)"/', $html, $m)) {
    echo "HTTP: no CSRF\n";
    exit(1);
}
$csrf = html_entity_decode($m[1], ENT_QUOTES);
$ch = curl_init($base . '/cuenta/login/');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'csrf' => $csrf,
        'email' => $email,
        'password' => $password,
    ]),
    CURLOPT_COOKIEJAR => $cookie,
    CURLOPT_COOKIEFILE => $cookie,
]);
$body = (string) curl_exec($ch);
$code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
curl_close($ch);
echo "HTTP login status: {$code}\n";
echo "HTTP dashboard: " . (str_contains($body, 'HE-DEMO-0001') || str_contains($body, 'Historial') ? 'OK' : 'FALLÓ') . "\n";
if (!str_contains($body, 'HE-DEMO-0001') && !str_contains($body, 'Historial')) {
    if (preg_match('/account-alert is-error[^>]*>([^<]+)/', $body, $err)) {
        echo "HTTP error: " . trim($err[1]) . "\n";
    }
}
@unlink($cookie);
