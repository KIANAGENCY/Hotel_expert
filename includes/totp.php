<?php
declare(strict_types=1);

const TOTP_PERIOD = 30;
const TOTP_DIGITS = 6;
const TOTP_WINDOW = 1;
const TOTP_BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

function totp_secret_generate(int $bytes = 20): string
{
    return totp_base32_encode(random_bytes($bytes));
}

function totp_base32_encode(string $binary): string
{
    $alphabet = TOTP_BASE32_ALPHABET;
    $buffer = 0;
    $bits = 0;
    $out = '';
    $length = strlen($binary);
    for ($i = 0; $i < $length; $i++) {
        $buffer = ($buffer << 8) | ord($binary[$i]);
        $bits += 8;
        while ($bits >= 5) {
            $bits -= 5;
            $out .= $alphabet[($buffer >> $bits) & 31];
        }
    }
    if ($bits > 0) {
        $out .= $alphabet[($buffer << (5 - $bits)) & 31];
    }
    return $out;
}

function totp_base32_decode(string $encoded): string
{
    $encoded = strtoupper(preg_replace('/[^A-Z2-7]/', '', $encoded) ?? '');
    $map = array_flip(str_split(TOTP_BASE32_ALPHABET));
    $buffer = 0;
    $bits = 0;
    $out = '';
    $length = strlen($encoded);
    for ($i = 0; $i < $length; $i++) {
        if (!isset($map[$encoded[$i]])) {
            continue;
        }
        $buffer = ($buffer << 5) | $map[$encoded[$i]];
        $bits += 5;
        if ($bits >= 8) {
            $bits -= 8;
            $out .= chr(($buffer >> $bits) & 255);
        }
    }
    return $out;
}

function totp_hotp(string $binarySecret, int $counter, int $digits = TOTP_DIGITS): string
{
    $binCounter = pack('N2', 0, $counter);
    $hash = hash_hmac('sha1', $binCounter, $binarySecret, true);
    $offset = ord($hash[19]) & 0x0f;
    $truncated = (
        ((ord($hash[$offset]) & 0x7f) << 24)
        | ((ord($hash[$offset + 1]) & 0xff) << 16)
        | ((ord($hash[$offset + 2]) & 0xff) << 8)
        | (ord($hash[$offset + 3]) & 0xff)
    );
    $otp = $truncated % (10 ** $digits);
    return str_pad((string) $otp, $digits, '0', STR_PAD_LEFT);
}

function totp_code(string $secretBase32, ?int $timestamp = null, int $digits = TOTP_DIGITS, int $period = TOTP_PERIOD): string
{
    $timestamp ??= time();
    $counter = intdiv($timestamp, $period);
    return totp_hotp(totp_base32_decode($secretBase32), $counter, $digits);
}

function totp_verify(string $secretBase32, string $code, int $window = TOTP_WINDOW, ?int $timestamp = null, int $period = TOTP_PERIOD, ?int $minCounter = null): ?int
{
    $code = totp_normalize_code($code);
    if (!preg_match('/^\d{' . TOTP_DIGITS . '}$/', $code)) {
        return null;
    }
    $timestamp ??= time();
    $current = intdiv($timestamp, $period);
    $secret = totp_base32_decode($secretBase32);
    if ($secret === '') {
        return null;
    }
    for ($offset = -$window; $offset <= $window; $offset++) {
        $counter = $current + $offset;
        if ($counter < 0 || ($minCounter !== null && $counter <= $minCounter)) {
            continue;
        }
        if (hash_equals(totp_hotp($secret, $counter), $code)) {
            return $counter;
        }
    }
    return null;
}

function totp_normalize_code(string $code): string
{
    return strtoupper(preg_replace('/[\s\-]/', '', $code) ?? '');
}

function totp_otpauth_uri(string $secretBase32, string $account, string $issuer = 'Hotel Expert'): string
{
    $label = rawurlencode($issuer . ':' . $account);
    return 'otpauth://totp/' . $label . '?' . http_build_query([
        'secret' => $secretBase32,
        'issuer' => $issuer,
        'period' => TOTP_PERIOD,
        'digits' => TOTP_DIGITS,
        'algorithm' => 'SHA1',
    ], '', '&', PHP_QUERY_RFC3986);
}

function totp_recovery_codes(int $count = 8): array
{
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $codes = [];
    while (count($codes) < $count) {
        $raw = '';
        for ($i = 0; $i < 8; $i++) {
            $raw .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        $code = substr($raw, 0, 4) . '-' . substr($raw, 4, 4);
        $codes[$code] = true;
    }
    return array_keys($codes);
}

function totp_hash_recovery_codes(array $codes): string
{
    $hashes = [];
    foreach ($codes as $code) {
        $hashes[] = password_hash(totp_normalize_code((string) $code), PASSWORD_DEFAULT);
    }
    return json_encode($hashes, JSON_THROW_ON_ERROR);
}

function totp_consume_recovery_code(string $hashesJson, string $code): ?string
{
    $needle = totp_normalize_code($code);
    if ($needle === '' || strlen($needle) < 8) {
        return null;
    }
    $hashes = json_decode($hashesJson, true);
    if (!is_array($hashes)) {
        return null;
    }
    foreach ($hashes as $index => $hash) {
        if (!is_string($hash) || !password_verify($needle, $hash)) {
            continue;
        }
        unset($hashes[$index]);
        return json_encode(array_values($hashes), JSON_THROW_ON_ERROR);
    }
    return null;
}
