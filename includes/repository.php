<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function productos_all(): array
{
    $rows = db()->query('SELECT slug, data FROM products ORDER BY sort_order, slug')->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $data = json_decode($row['data'], true);
        if (is_array($data)) {
            $out[$row['slug']] = $data;
        }
    }
    if ($out === []) {
        return require ROOT_PATH . '/data/productos.php';
    }
    return $out;
}

function producto_get(string $slug): ?array
{
    $stmt = db()->prepare('SELECT data FROM products WHERE slug = ?');
    $stmt->execute([$slug]);
    $row = $stmt->fetchColumn();
    if (!is_string($row)) {
        $all = require ROOT_PATH . '/data/productos.php';
        return $all[$slug] ?? null;
    }
    $data = json_decode($row, true);
    return is_array($data) ? $data : null;
}

function producto_save(string $slug, array $product, int $sortOrder = 0): void
{
    $product['slug'] = $slug;
    $stmt = db()->prepare('INSERT INTO products (slug, data, sort_order) VALUES (?, ?, ?)
        ON CONFLICT(slug) DO UPDATE SET data = excluded.data, sort_order = excluded.sort_order');
    $stmt->execute([$slug, json_encode($product, JSON_UNESCAPED_UNICODE), $sortOrder]);
}

function producto_delete(string $slug): void
{
    db()->prepare('DELETE FROM products WHERE slug = ?')->execute([$slug]);
}

function blog_all(): array
{
    $rows = db()->query('SELECT data FROM blog_posts ORDER BY sort_order DESC, slug')->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $data = json_decode($row['data'], true);
        if (is_array($data)) {
            $out[] = $data;
        }
    }
    if ($out === []) {
        return require ROOT_PATH . '/data/blog.php';
    }
    return $out;
}

function blog_get(string $slug): ?array
{
    $stmt = db()->prepare('SELECT data FROM blog_posts WHERE slug = ?');
    $stmt->execute([$slug]);
    $row = $stmt->fetchColumn();
    if (!is_string($row)) {
        foreach (require ROOT_PATH . '/data/blog.php' as $post) {
            if ($post['slug'] === $slug) {
                return $post;
            }
        }
        return null;
    }
    $data = json_decode($row, true);
    return is_array($data) ? $data : null;
}

function blog_save(string $slug, array $post, int $sortOrder = 0): void
{
    $post['slug'] = $slug;
    $stmt = db()->prepare('INSERT INTO blog_posts (slug, data, sort_order) VALUES (?, ?, ?)
        ON CONFLICT(slug) DO UPDATE SET data = excluded.data, sort_order = excluded.sort_order');
    $stmt->execute([$slug, json_encode($post, JSON_UNESCAPED_UNICODE), $sortOrder]);
}

function blog_delete(string $slug): void
{
    db()->prepare('DELETE FROM blog_posts WHERE slug = ?')->execute([$slug]);
}

function pedidos_all(): array
{
    $rows = db()->query('SELECT * FROM orders ORDER BY fecha DESC, id DESC')->fetchAll();
    if ($rows === []) {
        return require ROOT_PATH . '/data/pedidos.php';
    }
    return $rows;
}

function pedido_get(string $id, string $email): ?array
{
    $stmt = db()->prepare('SELECT * FROM orders WHERE id = ? AND lower(email) = lower(?)');
    $stmt->execute([$id, $email]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function pedido_save(array $order): void
{
    $stmt = db()->prepare('INSERT INTO orders (id, email, hotel, estado, fecha, eta, items, guia) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON CONFLICT(id) DO UPDATE SET email=excluded.email, hotel=excluded.hotel, estado=excluded.estado,
        fecha=excluded.fecha, eta=excluded.eta, items=excluded.items, guia=excluded.guia');
    $stmt->execute([
        $order['id'],
        $order['email'],
        $order['hotel'],
        $order['estado'],
        $order['fecha'] ?? '',
        $order['eta'] ?? '',
        $order['items'] ?? '',
        $order['guia'] ?? '',
    ]);
}

function pedido_delete(string $id): void
{
    db()->prepare('DELETE FROM orders WHERE id = ?')->execute([$id]);
}

function faqs_all(): array
{
    $rows = db()->query('SELECT pregunta, respuesta FROM faqs ORDER BY sort_order, id')->fetchAll();
    if ($rows === []) {
        return require ROOT_PATH . '/data/faq.php';
    }
    return $rows;
}

function faq_save(?int $id, string $pregunta, string $respuesta, int $sortOrder = 0): void
{
    if ($id) {
        db()->prepare('UPDATE faqs SET pregunta = ?, respuesta = ?, sort_order = ? WHERE id = ?')
            ->execute([$pregunta, $respuesta, $sortOrder, $id]);
        return;
    }
    db()->prepare('INSERT INTO faqs (pregunta, respuesta, sort_order) VALUES (?, ?, ?)')
        ->execute([$pregunta, $respuesta, $sortOrder]);
}

function faq_delete(int $id): void
{
    db()->prepare('DELETE FROM faqs WHERE id = ?')->execute([$id]);
}

function faqs_admin(): array
{
    return db()->query('SELECT * FROM faqs ORDER BY sort_order, id')->fetchAll();
}

function areas_all(): array
{
    $rows = db()->query('SELECT titulo, texto, href FROM areas ORDER BY sort_order, id')->fetchAll();
    if ($rows === []) {
        return require ROOT_PATH . '/data/areas.php';
    }
    return $rows;
}

function area_save(?int $id, string $titulo, string $texto, string $href, int $sortOrder = 0): void
{
    if ($id) {
        db()->prepare('UPDATE areas SET titulo = ?, texto = ?, href = ?, sort_order = ? WHERE id = ?')
            ->execute([$titulo, $texto, $href, $sortOrder, $id]);
        return;
    }
    db()->prepare('INSERT INTO areas (titulo, texto, href, sort_order) VALUES (?, ?, ?, ?)')
        ->execute([$titulo, $texto, $href, $sortOrder]);
}

function area_delete(int $id): void
{
    db()->prepare('DELETE FROM areas WHERE id = ?')->execute([$id]);
}

function areas_admin(): array
{
    return db()->query('SELECT * FROM areas ORDER BY sort_order, id')->fetchAll();
}

function leads_all(): array
{
    return db()->query('SELECT * FROM leads ORDER BY fecha DESC, id DESC')->fetchAll();
}

function lead_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM leads WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function lead_create(array $lead): int
{
    $stmt = db()->prepare('INSERT INTO leads (fecha, origen, nombre, cargo, hotel, ciudad, email, telefono, interes, tipo_propiedad, habitaciones, rfc, mensaje, carrito, subtotal_sin_iva, ip, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $lead['fecha'],
        $lead['origen'] ?? '',
        $lead['nombre'] ?? '',
        $lead['cargo'] ?? '',
        $lead['hotel'] ?? '',
        $lead['ciudad'] ?? '',
        $lead['email'] ?? '',
        $lead['telefono'] ?? '',
        $lead['interes'] ?? '',
        $lead['tipo_propiedad'] ?? '',
        $lead['habitaciones'] ?? '',
        $lead['rfc'] ?? '',
        $lead['mensaje'] ?? '',
        is_string($lead['carrito'] ?? null) ? $lead['carrito'] : json_encode($lead['carrito'] ?? [], JSON_UNESCAPED_UNICODE),
        (int) ($lead['subtotal_sin_iva'] ?? 0),
        $lead['ip'] ?? '',
        $lead['estado'] ?? 'nuevo',
    ]);
    return (int) db()->lastInsertId();
}

function lead_update(int $id, string $estado, string $notas): void
{
    db()->prepare('UPDATE leads SET estado = ?, notas = ? WHERE id = ?')->execute([$estado, $notas, $id]);
}

function lead_delete(int $id): void
{
    db()->prepare('DELETE FROM leads WHERE id = ?')->execute([$id]);
}

function settings_all(): array
{
    $rows = db()->query('SELECT key, value FROM settings')->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $out[$row['key']] = $row['value'];
    }
    return $out;
}

function setting_get(string $key, string $default = ''): string
{
    $stmt = db()->prepare('SELECT value FROM settings WHERE key = ?');
    $stmt->execute([$key]);
    $val = $stmt->fetchColumn();
    return is_string($val) ? $val : $default;
}

function settings_save(array $settings): void
{
    $stmt = db()->prepare('INSERT INTO settings (key, value) VALUES (?, ?) ON CONFLICT(key) DO UPDATE SET value = excluded.value');
    foreach ($settings as $key => $value) {
        $stmt->execute([$key, $value]);
    }
}

function admin_stats(): array
{
    $pdo = db();
    return [
        'leads_nuevos' => (int) $pdo->query("SELECT COUNT(*) FROM leads WHERE estado = 'nuevo'")->fetchColumn(),
        'leads_total' => (int) $pdo->query('SELECT COUNT(*) FROM leads')->fetchColumn(),
        'productos' => (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn(),
        'pedidos' => (int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn(),
        'blog' => (int) $pdo->query('SELECT COUNT(*) FROM blog_posts')->fetchColumn(),
    ];
}

function admin_verify(string $username, string $password): bool
{
    $stmt = db()->prepare('SELECT password_hash FROM admin_users WHERE username = ?');
    $stmt->execute([$username]);
    $hash = $stmt->fetchColumn();
    return is_string($hash) && password_verify($password, $hash);
}

function admin_change_password(string $username, string $newPassword): void
{
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    db()->prepare('UPDATE admin_users SET password_hash = ? WHERE username = ?')->execute([$hash, $username]);
}

function admin_slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-') ?: 'item';
}

function admin_flash(string $message, string $type = 'success'): void
{
    $_SESSION['admin_flash'] = ['message' => $message, 'type' => $type];
}

function admin_flash_consume(): ?array
{
    $flash = $_SESSION['admin_flash'] ?? null;
    unset($_SESSION['admin_flash']);
    return is_array($flash) ? $flash : null;
}
