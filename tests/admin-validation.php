<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/admin-auth.php';
require_once dirname(__DIR__) . '/admin/includes/validation.php';

$area = admin_validate_area_payload([
    'titulo' => 'Lobby',
    'texto' => 'Descripción suficientemente larga.',
    'href' => 'para-tu-hotel.php#lobby',
    'sort_order' => '1',
]);
assert($area['ok'] === true);

$badHref = admin_validate_area_payload([
    'titulo' => 'Lobby',
    'texto' => 'Descripción suficientemente larga.',
    'href' => 'javascript:alert(1)',
    'sort_order' => '1',
]);
assert($badHref['ok'] === false);

$product = admin_validate_product_payload([
    'slug' => 'hotel-expert',
    'nombre' => 'Hotel Expert',
    'imagen' => 'producto.jpg',
    'precio' => '100',
]);
assert($product['ok'] === true);

$badImage = admin_validate_product_payload([
    'slug' => 'hotel-expert',
    'nombre' => 'Hotel Expert',
    'imagen' => '../secret.env',
]);
assert($badImage['ok'] === false);

fwrite(STDOUT, "Validaciones admin OK\n");
