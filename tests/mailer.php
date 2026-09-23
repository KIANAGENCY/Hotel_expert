<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

assert(mailer_host_is_usable('mail.hotelexpert.mx') === true);
assert(mailer_host_is_usable('localhost') === true);
assert(mailer_host_is_usable('127.0.0.1') === true);
assert(mailer_host_is_usable('') === false);
assert(mailer_host_is_usable('ventas@hotelexpert.mx') === false);
assert(mailer_host_is_usable('smtp.example.com') === true);
assert(mailer_host_is_usable('smtp.example.com/evil') === false);

[$email, $name] = mailer_from();
assert(filter_var($email, FILTER_VALIDATE_EMAIL) !== false);
assert($name !== '');

$transports = mailer_phpmailer_transports();
assert(in_array('mail', $transports, true));

fwrite(STDOUT, "Mailer helpers OK\n");
