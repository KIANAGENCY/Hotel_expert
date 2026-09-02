<?php
declare(strict_types=1);

require_once __DIR__ . '/pii-crypto.php';

function db_install_if_needed(PDO $pdo): void
{
    $pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS schema_migrations (
    version INT UNSIGNED PRIMARY KEY,
    applied_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    value TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admin_users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    session_version INT UNSIGNED NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS customers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email TEXT NOT NULL,
    email_blind_index CHAR(64) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nombre VARCHAR(190) NOT NULL,
    hotel VARCHAR(190) NOT NULL,
    telefono TEXT NOT NULL,
    rfc TEXT NOT NULL,
    email_verified_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    last_login_at DATETIME NULL,
    session_version INT UNSIGNED NOT NULL DEFAULT 1,
    UNIQUE INDEX uq_customers_email_blind (email_blind_index),
    INDEX idx_customers_verified (email_verified_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS products (
    slug VARCHAR(190) PRIMARY KEY,
    data JSON NOT NULL,
    sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS blog_posts (
    slug VARCHAR(190) PRIMARY KEY,
    data JSON NOT NULL,
    sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS orders (
    id VARCHAR(80) PRIMARY KEY,
    customer_id BIGINT UNSIGNED NULL,
    email TEXT NOT NULL,
    email_blind_index CHAR(64) NOT NULL,
    hotel VARCHAR(190) NOT NULL,
    estado VARCHAR(30) NOT NULL,
    fecha DATE NULL,
    eta DATE NULL,
    items TEXT NOT NULL,
    guia VARCHAR(255) NOT NULL DEFAULT '',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    INDEX idx_orders_customer (customer_id),
    INDEX idx_orders_email_blind (email_blind_index)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(80) NOT NULL,
    product_slug VARCHAR(190) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    unit_price INT UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX idx_order_items_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS faqs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pregunta TEXT NOT NULL,
    respuesta TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS areas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    texto TEXT NOT NULL,
    href VARCHAR(500) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS leads (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT UNSIGNED NULL,
    fecha DATETIME NOT NULL,
    origen VARCHAR(40) NOT NULL DEFAULT '',
    nombre VARCHAR(190) NOT NULL DEFAULT '',
    cargo VARCHAR(190) NOT NULL DEFAULT '',
    hotel VARCHAR(190) NOT NULL DEFAULT '',
    ciudad VARCHAR(190) NOT NULL DEFAULT '',
    email TEXT NOT NULL,
    email_blind_index CHAR(64) NOT NULL,
    telefono TEXT NOT NULL,
    interes VARCHAR(255) NOT NULL DEFAULT '',
    tipo_propiedad VARCHAR(100) NOT NULL DEFAULT '',
    habitaciones VARCHAR(30) NOT NULL DEFAULT '',
    rfc TEXT NOT NULL,
    mensaje TEXT NOT NULL,
    carrito JSON NOT NULL,
    subtotal_sin_iva INT UNSIGNED NOT NULL DEFAULT 0,
    ip VARCHAR(45) NOT NULL DEFAULT '',
    estado VARCHAR(30) NOT NULL DEFAULT 'nuevo',
    notas TEXT NOT NULL,
    CONSTRAINT fk_leads_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    INDEX idx_leads_customer (customer_id),
    INDEX idx_leads_email_blind (email_blind_index),
    INDEX idx_leads_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS email_verification_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    CONSTRAINT fk_email_tokens_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    INDEX idx_email_tokens_customer (customer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    CONSTRAINT fk_reset_tokens_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    INDEX idx_reset_tokens_customer (customer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS login_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    scope VARCHAR(30) NOT NULL,
    identifier_hash CHAR(64) NOT NULL,
    ip_hash CHAR(64) NOT NULL,
    attempted_at DATETIME NOT NULL,
    success TINYINT(1) NOT NULL DEFAULT 0,
    INDEX idx_login_attempts_lookup (scope, identifier_hash, ip_hash, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

    $pdo->exec("INSERT IGNORE INTO schema_migrations (version, applied_at) VALUES (1, NOW())");
    $sessionVersionExists = (bool) $pdo->query("SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'customers' AND COLUMN_NAME = 'session_version'")->fetchColumn();
    if (!$sessionVersionExists) {
        $pdo->exec('ALTER TABLE customers ADD session_version INT UNSIGNED NOT NULL DEFAULT 1 AFTER last_login_at');
    }
    $pdo->exec("INSERT IGNORE INTO schema_migrations (version, applied_at) VALUES (2, NOW())");
    $piiMigrationApplied = (bool) $pdo->query('SELECT 1 FROM schema_migrations WHERE version = 3')->fetchColumn();
    if (!$piiMigrationApplied) {
        db_migrate_pii_encryption($pdo);
        $pdo->exec("INSERT INTO schema_migrations (version, applied_at) VALUES (3, NOW())");
    }
    $rateLimitMigrationApplied = (bool) $pdo->query('SELECT 1 FROM schema_migrations WHERE version = 4')->fetchColumn();
    if (!$rateLimitMigrationApplied) {
        $pdo->exec('DELETE FROM login_attempts');
        $pdo->exec("INSERT INTO schema_migrations (version, applied_at) VALUES (4, NOW())");
    }
    $adminSessionVersionExists = (bool) $pdo->query("SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'admin_users' AND COLUMN_NAME = 'session_version'")->fetchColumn();
    if (!$adminSessionVersionExists) {
        $pdo->exec('ALTER TABLE admin_users ADD session_version INT UNSIGNED NOT NULL DEFAULT 1 AFTER password_hash');
    }
    $pdo->exec("INSERT IGNORE INTO schema_migrations (version, applied_at) VALUES (5, NOW())");
    $stripeCheckoutMigration = (bool) $pdo->query('SELECT 1 FROM schema_migrations WHERE version = 6')->fetchColumn();
    if (!$stripeCheckoutMigration) {
        $pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS stripe_checkouts (
    session_id VARCHAR(255) PRIMARY KEY,
    order_id VARCHAR(80) NOT NULL,
    customer_id BIGINT UNSIGNED NULL,
    email_blind_index CHAR(64) NOT NULL,
    hotel VARCHAR(190) NOT NULL,
    nombre VARCHAR(190) NOT NULL DEFAULT '',
    cart_json JSON NOT NULL,
    subtotal_sin_iva INT UNSIGNED NOT NULL DEFAULT 0,
    iva_amount INT UNSIGNED NOT NULL DEFAULT 0,
    total_amount INT UNSIGNED NOT NULL DEFAULT 0,
    currency CHAR(3) NOT NULL DEFAULT 'mxn',
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_stripe_checkouts_order (order_id),
    INDEX idx_stripe_checkouts_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
        $pdo->prepare('INSERT INTO settings (setting_key, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_key = setting_key')
            ->execute(['checkout_iva_rate', '16']);
        $pdo->exec("INSERT INTO schema_migrations (version, applied_at) VALUES (6, NOW())");
    }
    db_seed_mysql($pdo);
}

function db_migrate_pii_encryption(PDO $pdo): void
{
    foreach (['customers', 'orders', 'leads'] as $table) {
        if (!db_column_exists($pdo, $table, 'email_blind_index')) {
            $pdo->exec("ALTER TABLE `{$table}` ADD `email_blind_index` CHAR(64) NULL AFTER `email`");
        }
        db_drop_indexes_for_column($pdo, $table, 'email');
        $pdo->exec("ALTER TABLE `{$table}` MODIFY `email` TEXT NOT NULL");
    }
    $pdo->exec('ALTER TABLE customers MODIFY telefono TEXT NOT NULL, MODIFY rfc TEXT NOT NULL');
    $pdo->exec('ALTER TABLE leads MODIFY telefono TEXT NOT NULL, MODIFY rfc TEXT NOT NULL');

    db_encrypt_pii_rows($pdo, 'customers', ['email', 'telefono', 'rfc']);
    db_encrypt_pii_rows($pdo, 'orders', ['email']);
    db_encrypt_pii_rows($pdo, 'leads', ['email', 'telefono', 'rfc']);

    foreach (['customers', 'orders', 'leads'] as $table) {
        $pdo->exec("ALTER TABLE `{$table}` MODIFY `email_blind_index` CHAR(64) NOT NULL");
    }
    if (!db_index_exists($pdo, 'customers', 'uq_customers_email_blind')) {
        $pdo->exec('ALTER TABLE customers ADD UNIQUE INDEX uq_customers_email_blind (email_blind_index)');
    }
    if (!db_index_exists($pdo, 'orders', 'idx_orders_email_blind')) {
        $pdo->exec('ALTER TABLE orders ADD INDEX idx_orders_email_blind (email_blind_index)');
    }
    if (!db_index_exists($pdo, 'leads', 'idx_leads_email_blind')) {
        $pdo->exec('ALTER TABLE leads ADD INDEX idx_leads_email_blind (email_blind_index)');
    }
}

function db_encrypt_pii_rows(PDO $pdo, string $table, array $fields): void
{
    if (!in_array($table, ['customers', 'orders', 'leads'], true)) {
        throw new InvalidArgumentException('Tabla PII no permitida para migración.');
    }
    $primaryKey = $table === 'orders' ? 'id' : 'id';
    $columns = implode(', ', array_map(static fn (string $field): string => "`{$field}`", $fields));
    $rows = $pdo->query("SELECT `{$primaryKey}`, {$columns} FROM `{$table}`")->fetchAll();
    $assignments = array_map(static fn (string $field): string => "`{$field}` = ?", $fields);
    $assignments[] = '`email_blind_index` = ?';
    $update = $pdo->prepare("UPDATE `{$table}` SET " . implode(', ', $assignments) . " WHERE `{$primaryKey}` = ?");

    foreach ($rows as $row) {
        $plaintext = [];
        foreach ($fields as $field) {
            $stored = (string) $row[$field];
            $plaintext[$field] = pii_is_encrypted($stored)
                ? pii_decrypt($stored, $table . '.' . $field)
                : $stored;
        }
        $plaintext['email'] = pii_normalize_email($plaintext['email']);
        if (isset($plaintext['telefono'])) {
            $plaintext['telefono'] = pii_normalize_phone($plaintext['telefono']);
        }
        if (isset($plaintext['rfc'])) {
            $plaintext['rfc'] = pii_normalize_rfc($plaintext['rfc']);
        }
        $parameters = [];
        foreach ($fields as $field) {
            $stored = (string) $row[$field];
            if (pii_is_encrypted($stored)) {
                $parameters[] = $stored;
                continue;
            }
            $encrypted = pii_encrypt($plaintext[$field], $table . '.' . $field);
            if (!hash_equals($plaintext[$field], pii_decrypt($encrypted, $table . '.' . $field))) {
                throw new RuntimeException("Falló la verificación del cifrado para {$table}.{$field}.");
            }
            $parameters[] = $encrypted;
        }
        $parameters[] = pii_email_blind_index($plaintext['email']);
        $parameters[] = $row[$primaryKey];
        $update->execute($parameters);
    }
}

function db_column_exists(PDO $pdo, string $table, string $column): bool
{
    $stmt = $pdo->prepare('SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?');
    $stmt->execute([$table, $column]);
    return (bool) $stmt->fetchColumn();
}

function db_index_exists(PDO $pdo, string $table, string $index): bool
{
    $stmt = $pdo->prepare('SELECT 1 FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?');
    $stmt->execute([$table, $index]);
    return (bool) $stmt->fetchColumn();
}

function db_drop_indexes_for_column(PDO $pdo, string $table, string $column): void
{
    $stmt = $pdo->prepare('SELECT DISTINCT INDEX_NAME FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? AND INDEX_NAME <> "PRIMARY"');
    $stmt->execute([$table, $column]);
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $index) {
        $safeIndex = str_replace('`', '``', (string) $index);
        $pdo->exec("ALTER TABLE `{$table}` DROP INDEX `{$safeIndex}`");
    }
}

function db_seed_mysql(PDO $pdo): void
{
    if ((int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn() === 0) {
        $stmt = $pdo->prepare('INSERT INTO products (slug, data, sort_order) VALUES (?, ?, ?)');
        foreach (array_values(array_keys(require ROOT_PATH . '/data/productos.php')) as $i => $slug) {
            $product = (require ROOT_PATH . '/data/productos.php')[$slug];
            $stmt->execute([$slug, json_encode($product, JSON_UNESCAPED_UNICODE), $i]);
        }
    }

    if ((int) $pdo->query('SELECT COUNT(*) FROM blog_posts')->fetchColumn() === 0) {
        $stmt = $pdo->prepare('INSERT INTO blog_posts (slug, data, sort_order) VALUES (?, ?, ?)');
        foreach (require ROOT_PATH . '/data/blog.php' as $i => $post) {
            $stmt->execute([$post['slug'], json_encode($post, JSON_UNESCAPED_UNICODE), $i]);
        }
    }

    if ((int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn() === 0) {
        $stmt = $pdo->prepare('INSERT INTO orders (id, email, email_blind_index, hotel, estado, fecha, eta, items, guia) VALUES (?, ?, ?, ?, ?, NULLIF(?, ""), NULLIF(?, ""), ?, ?)');
        foreach (require ROOT_PATH . '/data/pedidos.php' as $order) {
            $email = pii_normalize_email((string) $order['email']);
            $stmt->execute([
                $order['id'],
                pii_encrypt($email, 'orders.email'),
                pii_email_blind_index($email),
                $order['hotel'],
                $order['estado'],
                $order['fecha'],
                $order['eta'],
                $order['items'],
                $order['guia'],
            ]);
        }
    }

    if ((int) $pdo->query('SELECT COUNT(*) FROM faqs')->fetchColumn() === 0) {
        $stmt = $pdo->prepare('INSERT INTO faqs (pregunta, respuesta, sort_order) VALUES (?, ?, ?)');
        foreach (require ROOT_PATH . '/data/faq.php' as $i => $faq) {
            $stmt->execute([$faq['pregunta'], $faq['respuesta'], $i]);
        }
    }

    if ((int) $pdo->query('SELECT COUNT(*) FROM areas')->fetchColumn() === 0) {
        $stmt = $pdo->prepare('INSERT INTO areas (titulo, texto, href, sort_order) VALUES (?, ?, ?, ?)');
        foreach (require ROOT_PATH . '/data/areas.php' as $i => $area) {
            $stmt->execute([$area['titulo'], $area['texto'], $area['href'], $i]);
        }
    }

    if ((int) $pdo->query('SELECT COUNT(*) FROM settings')->fetchColumn() === 0) {
        $stmt = $pdo->prepare('INSERT INTO settings (setting_key, value) VALUES (?, ?)');
        foreach ([
            'site_name' => SITE_NAME,
            'site_tagline' => SITE_TAGLINE,
            'site_claim' => SITE_CLAIM,
            'site_domain' => SITE_DOMAIN,
            'whatsapp' => WHATSAPP,
            'whatsapp_display' => WHATSAPP_DISPLAY,
            'email_ventas' => EMAIL_VENTAS,
            'social_facebook' => '',
            'social_instagram' => '',
            'checkout_iva_rate' => '16',
        ] as $key => $value) {
            $stmt->execute([$key, $value]);
        }
    }

    if ((int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn() === 0) {
        $username = env('ADMIN_USERNAME', 'admin');
        $password = env('ADMIN_INITIAL_PASSWORD');
        if ($password === '' || mb_strlen($password) < 12) {
            throw new RuntimeException('Define ADMIN_INITIAL_PASSWORD con al menos 12 caracteres antes de crear el primer administrador.');
        }
        $stmt = $pdo->prepare('INSERT INTO admin_users (username, password_hash, created_at) VALUES (?, ?, ?)');
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), date('Y-m-d H:i:s')]);
    }
}
