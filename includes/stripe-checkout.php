<?php
declare(strict_types=1);

require_once __DIR__ . '/stripe-config.php';
require_once __DIR__ . '/cart-pricing.php';

function stripe_amount_cents(int $mxnPesos): int
{
    return max(0, $mxnPesos) * 100;
}

function stripe_api_request(string $method, string $endpoint, array $params = []): array
{
    $secret = stripe_secret_key();
    if ($secret === '') {
        throw new RuntimeException('Stripe no está configurado.');
    }
    if (!function_exists('curl_init')) {
        throw new RuntimeException('cURL no está disponible en el servidor.');
    }

    $url = 'https://api.stripe.com/v1/' . ltrim($endpoint, '/');
    $ch = curl_init($url);
    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD => $secret . ':',
        CURLOPT_TIMEOUT => 25,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
    ];

    if (strtoupper($method) === 'GET') {
        if ($params !== []) {
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($params);
            curl_setopt($ch, CURLOPT_URL, $url);
        }
    } else {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    }

    curl_setopt_array($ch, $options);
    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if (!is_string($body)) {
        throw new RuntimeException('No hubo respuesta de Stripe.');
    }

    $json = json_decode($body, true);
    if (!is_array($json)) {
        throw new RuntimeException('Respuesta inválida de Stripe.');
    }
    if ($status < 200 || $status >= 300) {
        $message = (string) ($json['error']['message'] ?? 'Error al comunicarse con Stripe.');
        throw new RuntimeException($message);
    }

    return $json;
}

function stripe_checkout_create(array $totals, string $orderId, array $buyer): string
{
    $currency = stripe_currency();
    $params = [
        'mode' => 'payment',
        'client_reference_id' => $orderId,
        'success_url' => rtrim(env('APP_URL', SITE_ORIGIN), '/') . url('pago-exitoso.php') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => rtrim(env('APP_URL', SITE_ORIGIN), '/') . url('pago-cancelado.php'),
        'metadata[order_id]' => $orderId,
        'metadata[hotel]' => admin_clip_buyer((string) ($buyer['hotel'] ?? '')),
        'metadata[nombre]' => admin_clip_buyer((string) ($buyer['nombre'] ?? '')),
    ];

    $email = trim((string) ($buyer['email'] ?? ''));
    if ($email !== '') {
        $params['customer_email'] = $email;
        $params['metadata[email]'] = $email;
    }

    $index = 0;
    foreach ($totals['items'] as $item) {
        $params["line_items[{$index}][quantity]"] = (int) $item['qty'];
        $params["line_items[{$index}][price_data][currency]"] = $currency;
        $params["line_items[{$index}][price_data][unit_amount]"] = stripe_amount_cents((int) $item['unit_price']);
        $params["line_items[{$index}][price_data][product_data][name]"] = (string) $item['nombre'];
        $index++;
    }

    if ((int) $totals['iva'] > 0) {
        $params["line_items[{$index}][quantity]"] = 1;
        $params["line_items[{$index}][price_data][currency]"] = $currency;
        $params["line_items[{$index}][price_data][unit_amount]"] = stripe_amount_cents((int) $totals['iva']);
        $params["line_items[{$index}][price_data][product_data][name]"] = 'IVA (' . number_format((float) $totals['iva_rate'], 2, '.', '') . '%)';
        $index++;
    }

    if ($index === 0) {
        throw new RuntimeException('El carrito está vacío.');
    }

    $session = stripe_api_request('POST', 'checkout/sessions', $params);
    $url = (string) ($session['url'] ?? '');
    $id = (string) ($session['id'] ?? '');
    if ($url === '' || $id === '') {
        throw new RuntimeException('Stripe no devolvió una sesión de pago válida.');
    }

    return ['id' => $id, 'url' => $url];
}

function admin_clip_buyer(string $value): string
{
    return mb_substr(trim($value), 0, 180);
}

function stripe_checkout_store(string $sessionId, string $orderId, array $buyer, array $totals): void
{
    $email = pii_normalize_email((string) ($buyer['email'] ?? ''));
    $stmt = db()->prepare('INSERT INTO stripe_checkouts
        (session_id, order_id, customer_id, email_blind_index, hotel, nombre, cart_json, subtotal_sin_iva, iva_amount, total_amount, currency, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $sessionId,
        $orderId,
        !empty($buyer['customer_id']) ? (int) $buyer['customer_id'] : null,
        pii_email_blind_index($email),
        admin_clip_buyer((string) ($buyer['hotel'] ?? '')),
        admin_clip_buyer((string) ($buyer['nombre'] ?? '')),
        json_encode($totals['items'], JSON_UNESCAPED_UNICODE),
        (int) $totals['subtotal_sin_iva'],
        (int) $totals['iva'],
        (int) $totals['total'],
        stripe_currency(),
        'pending',
        date('Y-m-d H:i:s'),
    ]);
}

function stripe_checkout_get(string $sessionId): ?array
{
    $stmt = db()->prepare('SELECT * FROM stripe_checkouts WHERE session_id = ? LIMIT 1');
    $stmt->execute([$sessionId]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function stripe_checkout_mark(string $sessionId, string $status): void
{
    db()->prepare('UPDATE stripe_checkouts SET status = ? WHERE session_id = ?')->execute([$status, $sessionId]);
}

function stripe_session_retrieve(string $sessionId): array
{
    return stripe_api_request('GET', 'checkout/sessions/' . rawurlencode($sessionId));
}

function stripe_fulfill_checkout(string $sessionId): ?string
{
    $record = stripe_checkout_get($sessionId);
    if (!$record) {
        return null;
    }
    if (($record['status'] ?? '') === 'completed') {
        return (string) $record['order_id'];
    }

    $session = stripe_session_retrieve($sessionId);
    if (($session['payment_status'] ?? '') !== 'paid') {
        return null;
    }

    $orderId = (string) ($record['order_id'] ?? '');
    if ($orderId === '') {
        return null;
    }

    $items = json_decode((string) ($record['cart_json'] ?? '[]'), true);
    if (!is_array($items)) {
        $items = [];
    }

    $itemNames = [];
    $orderItems = [];
    foreach ($items as $item) {
        $qty = (int) ($item['qty'] ?? 1);
        $name = (string) ($item['nombre'] ?? '');
        $itemNames[] = $qty . '× ' . $name;
        $orderItems[] = [
            'slug' => (string) ($item['slug'] ?? ''),
            'name' => $name,
            'quantity' => $qty,
            'unit_price' => (int) ($item['unit_price'] ?? 0),
        ];
    }

    $email = '';
    if (!empty($session['customer_details']['email'])) {
        $email = (string) $session['customer_details']['email'];
    }
    if ($email === '' && !empty($session['metadata']['email'])) {
        $email = (string) $session['metadata']['email'];
    }
    if ($email === '' && !empty($record['email_blind_index'])) {
        $customer = customer_by_email_from_blind((string) $record['email_blind_index']);
        if ($customer) {
            $email = (string) $customer['email'];
        }
    }

    pedido_save([
        'id' => $orderId,
        'customer_id' => $record['customer_id'] ?? null,
        'email' => $email !== '' ? $email : 'pagos@hotelexpert.mx',
        'hotel' => (string) ($record['hotel'] ?? 'Hotel'),
        'estado' => 'procesando',
        'fecha' => date('Y-m-d'),
        'eta' => '',
        'items' => $itemNames !== [] ? implode(', ', $itemNames) : 'Pedido pagado en línea',
        'guia' => 'Stripe ' . $sessionId,
        'order_items' => $orderItems,
    ]);

    stripe_checkout_mark($sessionId, 'completed');
    return $orderId;
}

function customer_by_email_from_blind(string $blindIndex): ?array
{
    $stmt = db()->prepare('SELECT * FROM customers WHERE email_blind_index = ? LIMIT 1');
    $stmt->execute([$blindIndex]);
    $row = $stmt->fetch();
    return $row ? pii_decrypt_row($row, 'customers') : null;
}

function stripe_verify_webhook(string $payload, string $signatureHeader): bool
{
    $secret = stripe_webhook_secret();
    if ($secret === '' || $signatureHeader === '') {
        return false;
    }

    $timestamp = null;
    $signatures = [];
    foreach (explode(',', $signatureHeader) as $part) {
        [$key, $value] = array_map('trim', explode('=', $part, 2) + ['', '']);
        if ($key === 't') {
            $timestamp = $value;
        }
        if ($key === 'v1') {
            $signatures[] = $value;
        }
    }

    if ($timestamp === null || $signatures === []) {
        return false;
    }
    if (abs(time() - (int) $timestamp) > 300) {
        return false;
    }

    $signed = $timestamp . '.' . $payload;
    $expected = hash_hmac('sha256', $signed, $secret);
    foreach ($signatures as $signature) {
        if (hash_equals($expected, $signature)) {
            return true;
        }
    }

    return false;
}
