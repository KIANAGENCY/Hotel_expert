<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$sqliteFile = ROOT_PATH . '/data/hotel_expert.sqlite';
if (!is_file($sqliteFile)) {
    fwrite(STDERR, "No existe {$sqliteFile}\n");
    exit(1);
}

$source = new PDO('sqlite:' . $sqliteFile, null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
pii_key('PII_ENCRYPTION_KEY');
pii_key('PII_BLIND_INDEX_KEY');
$target = db();

$target->beginTransaction();
try {
    $target->exec('DELETE FROM order_items');
    $target->exec('DELETE FROM orders');
    $target->exec('DELETE FROM leads');
    $target->exec('DELETE FROM faqs');
    $target->exec('DELETE FROM areas');
    $target->exec('DELETE FROM products');
    $target->exec('DELETE FROM blog_posts');
    $target->exec('DELETE FROM settings');
    $target->exec('DELETE FROM admin_users');
    foreach ($source->query('SELECT slug, data, sort_order FROM products') as $row) {
        $target->prepare('INSERT INTO products (slug, data, sort_order) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE data=VALUES(data), sort_order=VALUES(sort_order)')
            ->execute([$row['slug'], $row['data'], $row['sort_order']]);
    }
    foreach ($source->query('SELECT slug, data, sort_order FROM blog_posts') as $row) {
        $target->prepare('INSERT INTO blog_posts (slug, data, sort_order) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE data=VALUES(data), sort_order=VALUES(sort_order)')
            ->execute([$row['slug'], $row['data'], $row['sort_order']]);
    }
    foreach ($source->query('SELECT * FROM orders') as $row) {
        pedido_save($row);
    }
    foreach ($source->query('SELECT pregunta, respuesta, sort_order FROM faqs') as $row) {
        $target->prepare('INSERT INTO faqs (pregunta, respuesta, sort_order) VALUES (?, ?, ?)')
            ->execute([$row['pregunta'], $row['respuesta'], $row['sort_order']]);
    }
    foreach ($source->query('SELECT titulo, texto, href, sort_order FROM areas') as $row) {
        $target->prepare('INSERT INTO areas (titulo, texto, href, sort_order) VALUES (?, ?, ?, ?)')
            ->execute([$row['titulo'], $row['texto'], $row['href'], $row['sort_order']]);
    }
    foreach ($source->query('SELECT * FROM leads') as $row) {
        lead_create($row);
    }
    foreach ($source->query('SELECT key, value FROM settings') as $row) {
        $target->prepare('INSERT INTO settings (setting_key, value) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE value=VALUES(value)')->execute([$row['key'], $row['value']]);
    }
    foreach ($source->query('SELECT username, password_hash, created_at FROM admin_users') as $row) {
        $target->prepare('INSERT INTO admin_users (username, password_hash, created_at) VALUES (?, ?, ?)')
            ->execute([$row['username'], $row['password_hash'], date('Y-m-d H:i:s', strtotime($row['created_at']))]);
    }
    db_seed_mysql($target);
    $target->commit();
    fwrite(STDOUT, "Migración SQLite → MySQL completada.\n");
} catch (Throwable $e) {
    if ($target->inTransaction()) {
        $target->rollBack();
    }
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}
