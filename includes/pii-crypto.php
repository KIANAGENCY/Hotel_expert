<?php
declare(strict_types=1);

const PII_CIPHER = 'aes-256-gcm';
const PII_PREFIX = 'pii:v1:';

function pii_key(string $environmentVariable): string
{
    static $keys = [];
    if (isset($keys[$environmentVariable])) {
        return $keys[$environmentVariable];
    }

    $hex = env($environmentVariable);
    if (!preg_match('/^[a-f0-9]{64}$/i', $hex)) {
        throw new RuntimeException("{$environmentVariable} debe contener exactamente 64 caracteres hexadecimales.");
    }
    $key = hex2bin($hex);
    if ($key === false || strlen($key) !== 32) {
        throw new RuntimeException("{$environmentVariable} no contiene una clave válida de 32 bytes.");
    }
    $keys[$environmentVariable] = $key;
    return $key;
}

function pii_is_encrypted(string $value): bool
{
    return str_starts_with($value, PII_PREFIX);
}

function pii_encrypt(string $plaintext, string $context): string
{
    $nonce = random_bytes(12);
    $tag = '';
    $ciphertext = openssl_encrypt(
        $plaintext,
        PII_CIPHER,
        pii_key('PII_ENCRYPTION_KEY'),
        OPENSSL_RAW_DATA,
        $nonce,
        $tag,
        pii_aad($context),
        16
    );
    if ($ciphertext === false || strlen($tag) !== 16) {
        throw new RuntimeException('No fue posible cifrar los datos sensibles.');
    }

    return PII_PREFIX . bin2hex($nonce) . ':' . bin2hex($tag) . ':' . bin2hex($ciphertext);
}

function pii_decrypt(string $payload, string $context): string
{
    if (!pii_is_encrypted($payload)) {
        throw new UnexpectedValueException('Se encontró un dato sensible sin cifrar.');
    }
    $parts = explode(':', $payload, 5);
    if (count($parts) !== 5 || $parts[0] !== 'pii' || $parts[1] !== 'v1') {
        throw new UnexpectedValueException('El formato del dato cifrado no es válido.');
    }
    [, , $nonceHex, $tagHex, $ciphertextHex] = $parts;
    $nonce = hex2bin($nonceHex);
    $tag = hex2bin($tagHex);
    $ciphertext = hex2bin($ciphertextHex);
    if ($nonce === false || strlen($nonce) !== 12 || $tag === false || strlen($tag) !== 16 || $ciphertext === false) {
        throw new UnexpectedValueException('El contenido hexadecimal del dato cifrado no es válido.');
    }
    $plaintext = openssl_decrypt(
        $ciphertext,
        PII_CIPHER,
        pii_key('PII_ENCRYPTION_KEY'),
        OPENSSL_RAW_DATA,
        $nonce,
        $tag,
        pii_aad($context)
    );
    if ($plaintext === false) {
        throw new UnexpectedValueException('No fue posible autenticar o descifrar el dato sensible.');
    }
    return $plaintext;
}

function pii_aad(string $context): string
{
    if ($context === '') {
        throw new InvalidArgumentException('El contexto de cifrado no puede estar vacío.');
    }
    return 'hotel-expert:pii:v1:' . $context;
}

function pii_normalize_email(string $email): string
{
    return mb_strtolower(trim($email), 'UTF-8');
}

function pii_normalize_phone(string $phone): string
{
    return trim($phone);
}

function pii_normalize_rfc(string $rfc): string
{
    return mb_strtoupper(trim($rfc), 'UTF-8');
}

function pii_blind_index(string $value, string $context = 'email'): string
{
    return hash_hmac('sha256', $context . "\0" . $value, pii_key('PII_BLIND_INDEX_KEY'));
}

function pii_email_blind_index(string $email): string
{
    return pii_blind_index(pii_normalize_email($email), 'email');
}

function pii_decrypt_row(array $row, string $table): array
{
    $fields = match ($table) {
        'customers' => ['email', 'telefono', 'rfc'],
        'orders' => ['email'],
        'leads' => ['email', 'telefono', 'rfc'],
        default => throw new InvalidArgumentException('Tabla PII no permitida.'),
    };
    foreach ($fields as $field) {
        if (array_key_exists($field, $row)) {
            $row[$field] = pii_decrypt((string) $row[$field], $table . '.' . $field);
        }
    }
    return $row;
}

function pii_decrypt_rows(array $rows, string $table): array
{
    return array_map(static fn (array $row): array => pii_decrypt_row($row, $table), $rows);
}
