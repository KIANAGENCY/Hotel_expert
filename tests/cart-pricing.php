<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/cart-pricing.php';

$totals = cart_totals_from_payload([
    ['slug' => 'estandar', 'qty' => 2],
    ['slug' => 'dual', 'qty' => 1],
]);

assert($totals['subtotal_sin_iva'] > 0);
assert($totals['iva'] > 0);
assert($totals['total'] === $totals['subtotal_sin_iva'] + $totals['iva']);

echo "cart-pricing tests OK\n";
