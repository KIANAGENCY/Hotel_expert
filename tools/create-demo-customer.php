<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/customer-auth.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$email = 'demo.cliente@hotel.test';
$password = 'Demo2026';

$existing = customer_by_email($email);
if ($existing) {
    customer_mark_verified((int) $existing['id']);
    customer_update_password((int) $existing['id'], $password);
    $id = (int) $existing['id'];
    fwrite(STDOUT, "Cuenta existente actualizada (id {$id}).\n");
} else {
    $id = customer_create([
        'email' => $email,
        'password' => $password,
        'nombre' => 'Demo Cliente',
        'hotel' => 'Hotel Demo Expert',
        'telefono' => '8112345678',
        'rfc' => 'DEM010101AA1',
    ]);
    customer_mark_verified($id);
    fwrite(STDOUT, "Cuenta creada (id {$id}).\n");
}

pedido_save([
    'id' => 'HE-DEMO-0001',
    'customer_id' => $id,
    'email' => $email,
    'hotel' => 'Hotel Demo Expert',
    'estado' => 'transito',
    'fecha' => date('Y-m-d'),
    'eta' => date('Y-m-d', strtotime('+5 days')),
    'items' => '2 × Hotel Expert Estándar 2L · 1 × Dual 2L',
    'guia' => 'DEMO-77421903',
    'order_items' => [
        ['slug' => 'estandar', 'name' => 'Hotel Expert Estándar', 'quantity' => 2, 'unit_price' => 100],
        ['slug' => 'dual', 'name' => 'Hotel Expert Dual', 'quantity' => 1, 'unit_price' => 120],
    ],
]);

$login = customer_verify_credentials($email, $password);
if (!$login || empty($login['email_verified_at'])) {
    fwrite(STDERR, "Error: la cuenta no quedó lista para iniciar sesión.\n");
    exit(1);
}

fwrite(STDOUT, "Pedidos vinculados: " . count(pedidos_by_customer($id)) . "\n");
fwrite(STDOUT, "Login: http://hotel_expert.test/cuenta/login/\n");
fwrite(STDOUT, "Correo: {$email}\n");
fwrite(STDOUT, "Contraseña: {$password}\n");
