<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/config.php';

function http_request(string $url, string $cookieJar, ?array $post = null): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_COOKIEJAR => $cookieJar,
        CURLOPT_COOKIEFILE => $cookieJar,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HEADER => true,
    ]);
    if ($post !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    $raw = (string) curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    return [$status, $raw];
}

$base = rtrim(env('TEST_BASE_URL', 'http://hotel_expert.test'), '/');
$cookieJar = tempnam(sys_get_temp_dir(), 'admin-cookie-');

[$status, $html] = http_request($base . '/admin/login.php', $cookieJar);
if (!preg_match('/name="csrf" value="([^"]+)"/', $html, $match)) {
    fwrite(STDERR, "No CSRF token found. Status={$status}\n");
    exit(1);
}
$csrf = html_entity_decode($match[1], ENT_QUOTES);

[$postStatus, $postRaw] = http_request($base . '/admin/login.php', $cookieJar, [
    'csrf' => $csrf,
    'username' => 'admin',
    'password' => 'HotelExpert#2026',
]);

$location = '';
if (preg_match('/^Location:\s*(.+)$/mi', $postRaw, $loc)) {
    $location = trim($loc[1]);
}

fwrite(STDOUT, "GET status={$status}\n");
fwrite(STDOUT, "POST status={$postStatus}\n");
fwrite(STDOUT, "Location={$location}\n");
fwrite(STDOUT, str_contains($postRaw, 'incorrectos') ? "RESULT=failed\n" : "RESULT=ok_or_redirect\n");

@unlink($cookieJar);
