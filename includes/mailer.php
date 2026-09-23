<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;

function send_customer_email(string $to, string $subject, string $heading, string $message, string $actionLabel, string $actionUrl): bool
{
    $safeHeading = e($heading);
    $safeMessage = nl2br(e($message));
    $safeLabel = e($actionLabel);
    $safeUrl = e($actionUrl);
    $html = <<<HTML
<!doctype html><html lang="es"><body style="margin:0;background:#EAF5F5;font-family:Arial,sans-serif;color:#222326">
<div style="max-width:620px;margin:32px auto;background:#fff;border-radius:24px;overflow:hidden">
<div style="background:#0B2345;padding:26px 32px;color:#52C8C8;font-weight:800;letter-spacing:2px">HOTEL EXPERT</div>
<div style="padding:36px 32px">
<h1 style="margin:0;color:#0B2345;font-size:28px">{$safeHeading}</h1>
<p style="line-height:1.65;margin:18px 0 28px">{$safeMessage}</p>
<a href="{$safeUrl}" style="display:inline-block;background:#52C8C8;color:#0B2345;text-decoration:none;padding:14px 22px;border-radius:999px;font-weight:800">{$safeLabel}</a>
<p style="font-size:12px;color:#666;margin-top:28px">Si no solicitaste esta acción, ignora este correo.</p>
</div></div></body></html>
HTML;
    $text = $heading . "\n\n" . $message . "\n\n" . $actionLabel . ': ' . $actionUrl;
    return mailer_deliver($to, $subject, $html, $text);
}

function send_lead_notification(int $leadId, string $origin): bool
{
    $base = rtrim(env('APP_URL', SITE_ORIGIN . BASE_URL), '/');
    $adminLink = $base . '/admin/lead.php?id=' . $leadId;
    $safeOrigin = e($origin);
    $safeLink = e($adminLink);
    $html = "<p>Hay una nueva solicitud #{$leadId} ({$safeOrigin}).</p><p><a href=\"{$safeLink}\">Revisarla en el panel seguro</a></p>";
    $text = "Hay una nueva solicitud #{$leadId} ({$origin}). Revísala en el panel: {$adminLink}";
    return mailer_deliver(site_email(), 'Nueva solicitud Hotel Expert #' . $leadId, $html, $text);
}

function mailer_host_is_usable(string $host): bool
{
    $host = strtolower(trim($host));
    if ($host === '' || str_contains($host, '@') || str_contains($host, ' ') || str_contains($host, '/')) {
        return false;
    }
    return (bool) preg_match('/^(?:[a-z0-9-]+\.)*[a-z0-9-]+$/', $host);
}

function mailer_from(): array
{
    $email = trim(env('SMTP_FROM_EMAIL', EMAIL_VENTAS));
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $email = EMAIL_VENTAS;
    }
    $name = trim(env('SMTP_FROM_NAME', SITE_NAME));
    if ($name === '') {
        $name = SITE_NAME;
    }
    return [$email, $name];
}

function mailer_deliver(string $to, string $subject, string $html, string $text): bool
{
    $to = trim($to);
    if (filter_var($to, FILTER_VALIDATE_EMAIL) === false) {
        error_log('SMTP Hotel Expert: destinatario inválido.');
        return false;
    }

    if (mailer_bootstrap()) {
        foreach (mailer_phpmailer_transports() as $transport) {
            if (mailer_try_phpmailer($transport, $to, $subject, $html, $text)) {
                return true;
            }
        }
    }

    return mailer_native_send($to, $subject, $html);
}

function mailer_bootstrap(): bool
{
    static $ready = null;
    if ($ready !== null) {
        return $ready;
    }
    if (class_exists(PHPMailer::class, false)) {
        return $ready = true;
    }
    $vendor = ROOT_PATH . '/vendor/autoload.php';
    if (is_file($vendor)) {
        require_once $vendor;
        if (class_exists(PHPMailer::class)) {
            return $ready = true;
        }
    }
    $dir = __DIR__ . '/phpmailer';
    if (is_file($dir . '/PHPMailer.php')) {
        require_once $dir . '/Exception.php';
        require_once $dir . '/SMTP.php';
        require_once $dir . '/PHPMailer.php';
        return $ready = class_exists(PHPMailer::class);
    }
    error_log('SMTP Hotel Expert: PHPMailer no está disponible.');
    return $ready = false;
}

function mailer_phpmailer_transports(): array
{
    $transports = [];
    $host = trim(env('SMTP_HOST'));
    $hasAuth = trim(env('SMTP_USERNAME')) !== '' && env('SMTP_PASSWORD') !== '';
    if ($hasAuth && mailer_host_is_usable($host)) {
        $transports[] = 'configured';
    } elseif ($hasAuth) {
        $transports[] = 'localhost';
    }
    $transports[] = 'mail';
    return $transports;
}

function mailer_try_phpmailer(string $transport, string $to, string $subject, string $html, string $text): bool
{
    $mail = new PHPMailer(true);
    try {
        mailer_configure_phpmailer($mail, $transport);
        [$fromEmail, $fromName] = mailer_from();
        $mail->setFrom($fromEmail, $fromName);
        $mail->Sender = $fromEmail;
        $mail->addAddress($to);
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $html;
        $mail->AltBody = $text;
        return $mail->send();
    } catch (Throwable $e) {
        error_log('SMTP Hotel Expert [' . $transport . ']: ' . $e->getMessage());
        return false;
    }
}

function mailer_configure_phpmailer(PHPMailer $mail, string $transport): void
{
    $mail->Timeout = 8;
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ];

    if ($transport === 'mail') {
        $mail->isMail();
        return;
    }

    $mail->isSMTP();
    $username = trim(env('SMTP_USERNAME'));
    $password = env('SMTP_PASSWORD');
    $hasAuth = $username !== '' && $password !== '';

    if ($transport === 'localhost') {
        $mail->Host = 'localhost';
        $mail->Port = 25;
        $mail->SMTPAuth = $hasAuth;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = '';
        $mail->SMTPAutoTLS = false;
        return;
    }

    $host = trim(env('SMTP_HOST'));
    $port = (int) env('SMTP_PORT', '587');
    if ($port < 1 || $port > 65535) {
        $port = 587;
    }
    $encryption = strtolower(env('SMTP_ENCRYPTION', 'tls'));
    $mail->Host = $host;
    $mail->Port = $port;
    $mail->SMTPAuth = $hasAuth;
    $mail->Username = $username;
    $mail->Password = $password;

    $local = in_array(strtolower($host), ['localhost', '127.0.0.1'], true);
    if ($local || $encryption === 'none' || $port === 25) {
        $mail->SMTPSecure = '';
        $mail->SMTPAutoTLS = false;
        return;
    }
    if ($encryption === 'ssl' || $encryption === 'smtps' || $port === 465) {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAutoTLS = false;
        return;
    }
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
}

function mailer_native_send(string $to, string $subject, string $html): bool
{
    [$fromEmail, $fromName] = mailer_from();
    $encodedName = '=?UTF-8?B?' . base64_encode($fromName) . '?=';
    $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $encodedName . ' <' . $fromEmail . '>',
        'Reply-To: ' . $fromEmail,
        'X-Mailer: Hotel Expert',
    ];
    $params = PHP_OS_FAMILY === 'Windows' ? '' : '-f' . $fromEmail;
    $sent = $params === ''
        ? @mail($to, $encodedSubject, $html, implode("\r\n", $headers))
        : @mail($to, $encodedSubject, $html, implode("\r\n", $headers), $params);
    if (!$sent) {
        error_log('SMTP Hotel Expert [native]: mail() rechazó el envío.');
    }
    return (bool) $sent;
}
