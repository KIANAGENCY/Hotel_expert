<?php
declare(strict_types=1);

function db_install_if_needed(PDO $pdo): void
{
    $exists = (bool) $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='settings'")->fetchColumn();
    if ($exists) {
        return;
    }

    $pdo->exec(<<<'SQL'
CREATE TABLE settings (
    key TEXT PRIMARY KEY,
    value TEXT NOT NULL
);
CREATE TABLE admin_users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    created_at TEXT NOT NULL
);
CREATE TABLE products (
    slug TEXT PRIMARY KEY,
    data TEXT NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0
);
CREATE TABLE blog_posts (
    slug TEXT PRIMARY KEY,
    data TEXT NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0
);
CREATE TABLE orders (
    id TEXT PRIMARY KEY,
    email TEXT NOT NULL,
    hotel TEXT NOT NULL,
    estado TEXT NOT NULL,
    fecha TEXT NOT NULL DEFAULT '',
    eta TEXT NOT NULL DEFAULT '',
    items TEXT NOT NULL DEFAULT '',
    guia TEXT NOT NULL DEFAULT ''
);
CREATE TABLE faqs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    pregunta TEXT NOT NULL,
    respuesta TEXT NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0
);
CREATE TABLE areas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo TEXT NOT NULL,
    texto TEXT NOT NULL,
    href TEXT NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0
);
CREATE TABLE leads (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    fecha TEXT NOT NULL,
    origen TEXT NOT NULL DEFAULT '',
    nombre TEXT NOT NULL DEFAULT '',
    cargo TEXT NOT NULL DEFAULT '',
    hotel TEXT NOT NULL DEFAULT '',
    ciudad TEXT NOT NULL DEFAULT '',
    email TEXT NOT NULL DEFAULT '',
    telefono TEXT NOT NULL DEFAULT '',
    interes TEXT NOT NULL DEFAULT '',
    tipo_propiedad TEXT NOT NULL DEFAULT '',
    habitaciones TEXT NOT NULL DEFAULT '',
    rfc TEXT NOT NULL DEFAULT '',
    mensaje TEXT NOT NULL DEFAULT '',
    carrito TEXT NOT NULL DEFAULT '[]',
    subtotal_sin_iva INTEGER NOT NULL DEFAULT 0,
    ip TEXT NOT NULL DEFAULT '',
    estado TEXT NOT NULL DEFAULT 'nuevo',
    notas TEXT NOT NULL DEFAULT ''
);
SQL);

    db_seed($pdo);

    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO admin_users (username, password_hash, created_at) VALUES (?, ?, ?)');
    $stmt->execute(['admin', $hash, date('c')]);
}

function db_seed(PDO $pdo): void
{
    $productos = require ROOT_PATH . '/data/productos.php';
    $i = 0;
    $stmt = $pdo->prepare('INSERT INTO products (slug, data, sort_order) VALUES (?, ?, ?)');
    foreach ($productos as $slug => $product) {
        $stmt->execute([$slug, json_encode($product, JSON_UNESCAPED_UNICODE), $i++]);
    }

    $posts = require ROOT_PATH . '/data/blog.php';
    $stmt = $pdo->prepare('INSERT INTO blog_posts (slug, data, sort_order) VALUES (?, ?, ?)');
    foreach ($posts as $i => $post) {
        $stmt->execute([$post['slug'], json_encode($post, JSON_UNESCAPED_UNICODE), $i]);
    }

    $pedidos = require ROOT_PATH . '/data/pedidos.php';
    $stmt = $pdo->prepare('INSERT INTO orders (id, email, hotel, estado, fecha, eta, items, guia) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    foreach ($pedidos as $row) {
        $stmt->execute([$row['id'], $row['email'], $row['hotel'], $row['estado'], $row['fecha'], $row['eta'], $row['items'], $row['guia']]);
    }

    $faqs = require ROOT_PATH . '/data/faq.php';
    $stmt = $pdo->prepare('INSERT INTO faqs (pregunta, respuesta, sort_order) VALUES (?, ?, ?)');
    foreach ($faqs as $i => $faq) {
        $stmt->execute([$faq['pregunta'], $faq['respuesta'], $i]);
    }

    $areas = require ROOT_PATH . '/data/areas.php';
    $stmt = $pdo->prepare('INSERT INTO areas (titulo, texto, href, sort_order) VALUES (?, ?, ?, ?)');
    foreach ($areas as $i => $area) {
        $stmt->execute([$area['titulo'], $area['texto'], $area['href'], $i]);
    }

    $settings = [
        'site_name' => SITE_NAME,
        'site_tagline' => SITE_TAGLINE,
        'site_claim' => SITE_CLAIM,
        'site_domain' => SITE_DOMAIN,
        'whatsapp' => WHATSAPP,
        'whatsapp_display' => WHATSAPP_DISPLAY,
        'email_ventas' => EMAIL_VENTAS,
        'social_facebook' => '',
        'social_instagram' => '',
    ];
    $stmt = $pdo->prepare('INSERT INTO settings (key, value) VALUES (?, ?)');
    foreach ($settings as $key => $value) {
        $stmt->execute([$key, $value]);
    }

    $leadsFile = ROOT_PATH . '/data/leads.json';
    if (is_file($leadsFile)) {
        $leads = json_decode((string) file_get_contents($leadsFile), true);
        if (is_array($leads)) {
            $stmt = $pdo->prepare('INSERT INTO leads (fecha, origen, nombre, cargo, hotel, ciudad, email, telefono, interes, tipo_propiedad, habitaciones, rfc, mensaje, carrito, subtotal_sin_iva, ip, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            foreach ($leads as $lead) {
                $stmt->execute([
                    $lead['fecha'] ?? date('c'),
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
                    json_encode($lead['carrito'] ?? [], JSON_UNESCAPED_UNICODE),
                    (int) ($lead['subtotal_sin_iva'] ?? 0),
                    $lead['ip'] ?? '',
                    'nuevo',
                ]);
            }
        }
    }
}
