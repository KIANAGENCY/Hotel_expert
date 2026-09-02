<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/pii-crypto.php';

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
        ON DUPLICATE KEY UPDATE data = VALUES(data), sort_order = VALUES(sort_order)');
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
        ON DUPLICATE KEY UPDATE data = VALUES(data), sort_order = VALUES(sort_order)');
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
    return pii_decrypt_rows($rows, 'orders');
}

function pedido_get(string $id, string $email): ?array
{
    $stmt = db()->prepare('SELECT * FROM orders WHERE id = ? AND email_blind_index = ?');
    $stmt->execute([$id, pii_email_blind_index($email)]);
    $row = $stmt->fetch();
    return $row ? pii_decrypt_row($row, 'orders') : null;
}

function pedido_save(array $order): void
{
    $pdo = db();
    $ownsTransaction = !$pdo->inTransaction();
    if ($ownsTransaction) {
        $pdo->beginTransaction();
    }
    try {
        $email = pii_normalize_email((string) $order['email']);
        $customerId = $order['customer_id'] ?? customer_id_by_email($email);
        $stmt = $pdo->prepare('INSERT INTO orders (id, customer_id, email, email_blind_index, hotel, estado, fecha, eta, items, guia)
            VALUES (?, ?, ?, ?, ?, ?, NULLIF(?, ""), NULLIF(?, ""), ?, ?)
            ON DUPLICATE KEY UPDATE customer_id=VALUES(customer_id), email=VALUES(email),
            email_blind_index=VALUES(email_blind_index), hotel=VALUES(hotel),
            estado=VALUES(estado), fecha=VALUES(fecha), eta=VALUES(eta), items=VALUES(items), guia=VALUES(guia)');
        $stmt->execute([
            $order['id'],
            $customerId,
            pii_encrypt($email, 'orders.email'),
            pii_email_blind_index($email),
            $order['hotel'],
            $order['estado'],
            $order['fecha'] ?? '',
            $order['eta'] ?? '',
            $order['items'] ?? '',
            $order['guia'] ?? '',
        ]);

        if (isset($order['order_items']) && is_array($order['order_items'])) {
            $pdo->prepare('DELETE FROM order_items WHERE order_id = ?')->execute([$order['id']]);
            $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_slug, product_name, quantity, unit_price) VALUES (?, ?, ?, ?, ?)');
            foreach ($order['order_items'] as $item) {
                if (empty($item['slug']) || (int) ($item['quantity'] ?? 0) < 1) {
                    continue;
                }
                $itemStmt->execute([
                    $order['id'],
                    (string) $item['slug'],
                    (string) ($item['name'] ?? $item['slug']),
                    min(99, (int) $item['quantity']),
                    max(0, (int) ($item['unit_price'] ?? 0)),
                ]);
            }
        }
        if ($ownsTransaction) {
            $pdo->commit();
        }
    } catch (Throwable $e) {
        if ($ownsTransaction && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function pedido_items(string $orderId): array
{
    $stmt = db()->prepare('SELECT product_slug AS slug, product_name AS nombre, quantity AS cantidad, unit_price AS precio_unitario
        FROM order_items WHERE order_id = ? ORDER BY id');
    $stmt->execute([$orderId]);
    return $stmt->fetchAll();
}

function pedidos_by_customer(int $customerId): array
{
    $stmt = db()->prepare('SELECT * FROM orders WHERE customer_id = ? ORDER BY fecha DESC, id DESC');
    $stmt->execute([$customerId]);
    return pii_decrypt_rows($stmt->fetchAll(), 'orders');
}

function pedido_for_customer(string $id, int $customerId): ?array
{
    $stmt = db()->prepare('SELECT * FROM orders WHERE id = ? AND customer_id = ?');
    $stmt->execute([$id, $customerId]);
    $order = $stmt->fetch();
    return $order ? pii_decrypt_row($order, 'orders') : null;
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
    return pii_decrypt_rows(db()->query('SELECT * FROM leads ORDER BY fecha DESC, id DESC')->fetchAll(), 'leads');
}

function lead_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM leads WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ? pii_decrypt_row($row, 'leads') : null;
}

function lead_create(array $lead): int
{
    $fecha = (string) ($lead['fecha'] ?? '');
    $timestamp = strtotime($fecha);
    $fecha = $timestamp === false ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $timestamp);
    $email = pii_normalize_email((string) ($lead['email'] ?? ''));
    $phone = pii_normalize_phone((string) ($lead['telefono'] ?? ''));
    $rfc = pii_normalize_rfc((string) ($lead['rfc'] ?? ''));
    $stmt = db()->prepare('INSERT INTO leads (customer_id, fecha, origen, nombre, cargo, hotel, ciudad, email, email_blind_index, telefono, interes, tipo_propiedad, habitaciones, rfc, mensaje, carrito, subtotal_sin_iva, ip, estado, notas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $lead['customer_id'] ?? null,
        $fecha,
        $lead['origen'] ?? '',
        $lead['nombre'] ?? '',
        $lead['cargo'] ?? '',
        $lead['hotel'] ?? '',
        $lead['ciudad'] ?? '',
        pii_encrypt($email, 'leads.email'),
        pii_email_blind_index($email),
        pii_encrypt($phone, 'leads.telefono'),
        $lead['interes'] ?? '',
        $lead['tipo_propiedad'] ?? '',
        $lead['habitaciones'] ?? '',
        pii_encrypt($rfc, 'leads.rfc'),
        $lead['mensaje'] ?? '',
        is_string($lead['carrito'] ?? null) ? $lead['carrito'] : json_encode($lead['carrito'] ?? [], JSON_UNESCAPED_UNICODE),
        (int) ($lead['subtotal_sin_iva'] ?? 0),
        $lead['ip'] ?? '',
        $lead['estado'] ?? 'nuevo',
        $lead['notas'] ?? '',
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
    $rows = db()->query('SELECT setting_key, value FROM settings')->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $out[$row['setting_key']] = $row['value'];
    }
    return $out;
}

function setting_get(string $key, string $default = ''): string
{
    $stmt = db()->prepare('SELECT value FROM settings WHERE setting_key = ?');
    $stmt->execute([$key]);
    $val = $stmt->fetchColumn();
    return is_string($val) ? $val : $default;
}

function settings_save(array $settings): void
{
    $stmt = db()->prepare('INSERT INTO settings (setting_key, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = VALUES(value)');
    foreach ($settings as $key => $value) {
        $stmt->execute([$key, $value]);
    }
}

function customer_by_email(string $email): ?array
{
    $stmt = db()->prepare('SELECT * FROM customers WHERE email_blind_index = ? LIMIT 1');
    $stmt->execute([pii_email_blind_index($email)]);
    $row = $stmt->fetch();
    return $row ? pii_decrypt_row($row, 'customers') : null;
}

function customer_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM customers WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ? pii_decrypt_row($row, 'customers') : null;
}

function customer_id_by_email(string $email): ?int
{
    $customer = customer_by_email($email);
    return $customer && !empty($customer['email_verified_at']) ? (int) $customer['id'] : null;
}

function customer_create(array $data): int
{
    $now = date('Y-m-d H:i:s');
    $email = pii_normalize_email((string) $data['email']);
    $phone = pii_normalize_phone(mb_substr(trim((string) ($data['telefono'] ?? '')), 0, 60));
    $rfc = pii_normalize_rfc(mb_substr(trim((string) ($data['rfc'] ?? '')), 0, 20));
    $stmt = db()->prepare('INSERT INTO customers (email, email_blind_index, password_hash, nombre, hotel, telefono, rfc, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        pii_encrypt($email, 'customers.email'),
        pii_email_blind_index($email),
        password_hash((string) $data['password'], PASSWORD_DEFAULT),
        mb_substr(trim((string) $data['nombre']), 0, 190),
        mb_substr(trim((string) $data['hotel']), 0, 190),
        pii_encrypt($phone, 'customers.telefono'),
        pii_encrypt($rfc, 'customers.rfc'),
        $now,
        $now,
    ]);
    return (int) db()->lastInsertId();
}

function customer_verify_credentials(string $email, string $password): ?array
{
    $customer = customer_by_email($email);
    if (!$customer || !password_verify($password, (string) $customer['password_hash'])) {
        return null;
    }
    if (password_needs_rehash((string) $customer['password_hash'], PASSWORD_DEFAULT)) {
        customer_update_password((int) $customer['id'], $password);
        $customer = customer_get((int) $customer['id']);
    }
    return $customer;
}

function customer_mark_login(int $id): void
{
    db()->prepare('UPDATE customers SET last_login_at = ?, updated_at = ? WHERE id = ?')
        ->execute([date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $id]);
}

function customer_update_password(int $id, string $password): void
{
    db()->prepare('UPDATE customers SET password_hash = ?, updated_at = ? WHERE id = ?')
        ->execute([password_hash($password, PASSWORD_DEFAULT), date('Y-m-d H:i:s'), $id]);
}

function customer_revoke_sessions(int $id): void
{
    db()->prepare('UPDATE customers SET session_version = session_version + 1, updated_at = ? WHERE id = ?')
        ->execute([date('Y-m-d H:i:s'), $id]);
}

function customer_update_profile(int $id, array $data): void
{
    db()->prepare('UPDATE customers SET nombre = ?, hotel = ?, telefono = ?, rfc = ?, updated_at = ? WHERE id = ?')
        ->execute([
            mb_substr(trim((string) $data['nombre']), 0, 190),
            mb_substr(trim((string) $data['hotel']), 0, 190),
            pii_encrypt(pii_normalize_phone(mb_substr((string) ($data['telefono'] ?? ''), 0, 60)), 'customers.telefono'),
            pii_encrypt(pii_normalize_rfc(mb_substr((string) ($data['rfc'] ?? ''), 0, 20)), 'customers.rfc'),
            date('Y-m-d H:i:s'),
            $id,
        ]);
}

function customer_store_token(string $table, int $customerId, string $rawToken, string $expiresAt): void
{
    if (!in_array($table, ['email_verification_tokens', 'password_reset_tokens'], true)) {
        throw new InvalidArgumentException('Token table not allowed.');
    }
    $pdo = db();
    $pdo->prepare("DELETE FROM {$table} WHERE customer_id = ? OR expires_at < NOW()")->execute([$customerId]);
    $pdo->prepare("INSERT INTO {$table} (customer_id, token_hash, expires_at, created_at) VALUES (?, ?, ?, ?)")
        ->execute([$customerId, hash('sha256', $rawToken), $expiresAt, date('Y-m-d H:i:s')]);
}

function customer_consume_token(string $table, string $rawToken): ?int
{
    if (!in_array($table, ['email_verification_tokens', 'password_reset_tokens'], true)) {
        throw new InvalidArgumentException('Token table not allowed.');
    }
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("SELECT id, customer_id FROM {$table}
            WHERE token_hash = ? AND used_at IS NULL AND expires_at >= NOW() FOR UPDATE");
        $stmt->execute([hash('sha256', $rawToken)]);
        $row = $stmt->fetch();
        if (!$row) {
            $pdo->rollBack();
            return null;
        }
        $pdo->prepare("UPDATE {$table} SET used_at = ? WHERE id = ?")
            ->execute([date('Y-m-d H:i:s'), $row['id']]);
        $pdo->commit();
        return (int) $row['customer_id'];
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function customer_mark_verified(int $id): void
{
    $pdo = db();
    $now = date('Y-m-d H:i:s');
    $pdo->prepare('UPDATE customers SET email_verified_at = COALESCE(email_verified_at, ?), updated_at = ? WHERE id = ?')
        ->execute([$now, $now, $id]);
    $customer = customer_get($id);
    if ($customer) {
        $emailBlindIndex = pii_email_blind_index((string) $customer['email']);
        $pdo->prepare('UPDATE orders SET customer_id = ? WHERE customer_id IS NULL AND email_blind_index = ?')
            ->execute([$id, $emailBlindIndex]);
        $pdo->prepare('UPDATE leads SET customer_id = ? WHERE customer_id IS NULL AND email_blind_index = ?')
            ->execute([$id, $emailBlindIndex]);
    }
}

function login_is_limited(string $scope, string $identifier, string $ip, int $limit = 5, int $minutes = 15): bool
{
    $since = date('Y-m-d H:i:s', time() - ($minutes * 60));
    $stmt = db()->prepare('SELECT
        (SELECT COUNT(*) FROM login_attempts WHERE scope = ? AND identifier_hash = ? AND success = 0 AND attempted_at >= ?) AS identifier_attempts,
        (SELECT COUNT(*) FROM login_attempts WHERE scope = ? AND ip_hash = ? AND success = 0 AND attempted_at >= ?) AS ip_attempts');
    $stmt->execute([
        $scope,
        pii_blind_index(mb_strtolower(trim($identifier), 'UTF-8'), 'login:' . $scope),
        $since,
        $scope,
        hash('sha256', $ip),
        $since,
    ]);
    $counts = $stmt->fetch() ?: [];
    return (int) ($counts['identifier_attempts'] ?? 0) >= $limit
        || (int) ($counts['ip_attempts'] ?? 0) >= $limit;
}

function login_record_attempt(string $scope, string $identifier, string $ip, bool $success): void
{
    $identifierHash = pii_blind_index(mb_strtolower(trim($identifier), 'UTF-8'), 'login:' . $scope);
    $ipHash = hash('sha256', $ip);
    if ($success) {
        db()->prepare('DELETE FROM login_attempts WHERE scope = ? AND identifier_hash = ?')
            ->execute([$scope, $identifierHash]);
        return;
    }
    db()->prepare('INSERT INTO login_attempts (scope, identifier_hash, ip_hash, attempted_at, success) VALUES (?, ?, ?, ?, 0)')
        ->execute([$scope, $identifierHash, $ipHash, date('Y-m-d H:i:s')]);
}

function rate_limit_exceeded(string $scope, string $identifier, string $ip, int $limit, int $minutes): bool
{
    if (login_is_limited($scope, $identifier, $ip, $limit, $minutes)) {
        return true;
    }
    login_record_attempt($scope, $identifier, $ip, false);
    return false;
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
    if (!is_string($hash) || !password_verify($password, $hash)) {
        return false;
    }
    if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
        admin_change_password($username, $password);
    }
    return true;
}

function admin_session_version(string $username): ?int
{
    $stmt = db()->prepare('SELECT session_version FROM admin_users WHERE username = ?');
    $stmt->execute([$username]);
    $version = $stmt->fetchColumn();
    return $version === false ? null : (int) $version;
}

function admin_change_password(string $username, string $newPassword): void
{
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    db()->prepare('UPDATE admin_users SET password_hash = ?, session_version = session_version + 1 WHERE username = ?')
        ->execute([$hash, $username]);
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
