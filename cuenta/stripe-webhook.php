<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/stripe-checkout.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit;
}

$payload = (string) file_get_contents('php://input');
$signature = (string) ($_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '');

if (!stripe_verify_webhook($payload, $signature)) {
    http_response_code(400);
    echo 'invalid signature';
    exit;
}

$event = json_decode($payload, true);
if (!is_array($event)) {
    http_response_code(400);
    exit;
}

$type = (string) ($event['type'] ?? '');
if ($type === 'checkout.session.completed') {
    $session = $event['data']['object'] ?? [];
    $sessionId = (string) ($session['id'] ?? '');
    if ($sessionId !== '') {
        try {
            stripe_fulfill_checkout($sessionId);
        } catch (Throwable) {
            http_response_code(500);
            exit;
        }
    }
}

http_response_code(200);
echo 'ok';
