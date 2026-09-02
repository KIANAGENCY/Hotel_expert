<?php
declare(strict_types=1);

function checkout_iva_rate(): float
{
    $rate = site_setting('checkout_iva_rate', '16');
    return max(0.0, min(100.0, (float) $rate));
}

function cart_parse_client_items(mixed $raw): array
{
    if (is_string($raw)) {
        $raw = json_decode($raw, true);
    }
    if (!is_array($raw)) {
        return [];
    }

    $products = productos_all();
    $items = [];
    foreach ($raw as $entry) {
        if (!is_array($entry)) {
            continue;
        }
        $slug = preg_replace('/[^a-z0-9-]/', '', (string) ($entry['slug'] ?? ''));
        $qty = max(1, min(99, (int) ($entry['qty'] ?? $entry['cantidad'] ?? 1)));
        if ($slug === '' || !isset($products[$slug])) {
            continue;
        }
        $product = $products[$slug];
        $unit = max(0, (int) ($product['precio'] ?? 0));
        $appliesIva = !empty($product['iva']);
        $lineSubtotal = $unit * $qty;
        $lineIva = $appliesIva ? (int) round($lineSubtotal * checkout_iva_rate() / 100) : 0;
        $items[] = [
            'slug' => $slug,
            'nombre' => (string) ($product['nombre'] ?? $slug),
            'presentacion' => (string) ($product['presentacion'] ?? ''),
            'precio_texto' => (string) ($product['precio_texto'] ?? ''),
            'qty' => $qty,
            'unit_price' => $unit,
            'iva' => $appliesIva,
            'line_subtotal' => $lineSubtotal,
            'line_iva' => $lineIva,
            'line_total' => $lineSubtotal + $lineIva,
        ];
    }

    return $items;
}

function cart_calculate_totals(array $items): array
{
    $subtotal = 0;
    $iva = 0;
    foreach ($items as $item) {
        $subtotal += (int) $item['line_subtotal'];
        $iva += (int) $item['line_iva'];
    }

    return [
        'items' => $items,
        'subtotal_sin_iva' => $subtotal,
        'iva' => $iva,
        'total' => $subtotal + $iva,
        'iva_rate' => checkout_iva_rate(),
        'item_count' => array_sum(array_map(static fn(array $item): int => (int) $item['qty'], $items)),
    ];
}

function cart_totals_from_payload(mixed $raw): array
{
    return cart_calculate_totals(cart_parse_client_items($raw));
}

function order_generate_id(): string
{
    return 'HE-' . date('Y') . '-' . str_pad((string) random_int(10000, 99999), 5, '0', STR_PAD_LEFT);
}

function cart_money_mxn(int $amount): string
{
    return '$' . number_format($amount, 0, '.', ',');
}
