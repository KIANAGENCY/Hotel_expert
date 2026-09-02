<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

try {
    pii_key('PII_ENCRYPTION_KEY');
    pii_key('PII_BLIND_INDEX_KEY');
    $pdo = db();
    $tables = [
        'customers' => ['email', 'telefono', 'rfc'],
        'orders' => ['email'],
        'leads' => ['email', 'telefono', 'rfc'],
    ];
    $checked = 0;

    foreach ($tables as $table => $fields) {
        $columns = implode(', ', array_map(static fn (string $field): string => "`{$field}`", $fields));
        $rows = $pdo->query("SELECT {$columns}, email_blind_index FROM `{$table}`")->fetchAll();
        foreach ($rows as $row) {
            foreach ($fields as $field) {
                $stored = (string) $row[$field];
                if (!pii_is_encrypted($stored)) {
                    throw new RuntimeException("{$table}.{$field} contiene un valor sin cifrar.");
                }
                pii_decrypt($stored, $table . '.' . $field);
                $checked++;
            }
            $email = pii_decrypt((string) $row['email'], $table . '.email');
            if (!hash_equals(pii_email_blind_index($email), (string) $row['email_blind_index'])) {
                throw new RuntimeException("El blind index de {$table}.email no coincide.");
            }
        }
    }

    fwrite(STDOUT, "Migración PII aplicada y verificada. Campos comprobados: {$checked}.\n");
} catch (Throwable $e) {
    fwrite(STDERR, "Error al cifrar o verificar PII: {$e->getMessage()}\n");
    exit(1);
}
