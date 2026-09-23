<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/totp.php';

$rfcSecret = '12345678901234567890';
$rfcBase32 = totp_base32_encode($rfcSecret);
assert($rfcBase32 === 'GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ');
assert(totp_base32_decode($rfcBase32) === $rfcSecret);

$rfcSha1 = [
    59 => '94287082',
    1111111109 => '07081804',
    1111111111 => '14050471',
    1234567890 => '89005924',
    2000000000 => '69279037',
    20000000000 => '65353130',
];
foreach ($rfcSha1 as $unix => $eight) {
    $counter = intdiv($unix, 30);
    assert(totp_hotp($rfcSecret, $counter, 8) === $eight);
    assert(totp_code($rfcBase32, $unix, 8) === $eight);
    assert(totp_code($rfcBase32, $unix) === substr($eight, -6));
}

$now = 1111111111;
$code = totp_code($rfcBase32, $now);
assert(totp_verify($rfcBase32, $code, 1, $now) === intdiv($now, 30));
assert(totp_verify($rfcBase32, $code, 1, $now, 30, intdiv($now, 30)) === null);
assert(totp_verify($rfcBase32, '000000', 1, $now) === null);

$codes = totp_recovery_codes(8);
assert(count($codes) === 8);
$json = totp_hash_recovery_codes($codes);
$remaining = totp_consume_recovery_code($json, $codes[0]);
assert(is_string($remaining));
$decoded = json_decode($remaining, true);
assert(is_array($decoded) && count($decoded) === 7);
assert(totp_consume_recovery_code($remaining, $codes[0]) === null);

$uri = totp_otpauth_uri($rfcBase32, 'admin');
assert(str_starts_with($uri, 'otpauth://totp/'));
assert(str_contains($uri, 'secret=' . $rfcBase32));

fwrite(STDOUT, "TOTP OK\n");
