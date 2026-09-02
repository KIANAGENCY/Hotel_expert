<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;

function send_customer_email(string $to, string $subject, string $heading, string $message, string $actionLabel, string $actionUrl): bool
{
    $autoload = ROOT_PATH . '/vendor/autoload.php';
    if (!is_file($autoload)) {
        error_log('PHPMailer no está instalado. Ejecuta composer install.');
        return false;
    }
    require_once $autoload;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = env('SMTP_HOST');
        $mail->Port = (int) env('SMTP_PORT', '587');
        $mail->SMTPAuth = env('SMTP_USERNAME') !== '';
        $mail->Username = env('SMTP_USERNAME');
        $mail->Password = env('SMTP_PASSWORD');
        $encryption = strtolower(env('SMTP_ENCRYPTION', 'tls'));
        if ($encryption === 'ssl' || $encryption === 'smtps') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($encryption !== 'none') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        $from = env('SMTP_FROM_EMAIL', EMAIL_VENTAS);
        $mail->setFrom($from, env('SMTP_FROM_NAME', SITE_NAME));
        $mail->addAddress($to);
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $safeHeading = e($heading);
        $safeMessage = nl2br(e($message));
        $safeLabel = e($actionLabel);
        $safeUrl = e($actionUrl);
        $mail->Body = <<<HTML
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
        $mail->AltBody = $heading . "\n\n" . $message . "\n\n" . $actionLabel . ': ' . $actionUrl;
        return $mail->send();
    } catch (MailException $e) {
        error_log('SMTP Hotel Expert: ' . $e->getMessage());
        return false;
    }
}

function send_lead_notification(int $leadId, string $origin): bool
{
    $autoload = ROOT_PATH . '/vendor/autoload.php';
    if (!is_file($autoload)) {
        error_log('PHPMailer no está instalado. Ejecuta composer install.');
        return false;
    }
    require_once $autoload;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = env('SMTP_HOST');
        $mail->Port = (int) env('SMTP_PORT', '587');
        $mail->SMTPAuth = env('SMTP_USERNAME') !== '';
        $mail->Username = env('SMTP_USERNAME');
        $mail->Password = env('SMTP_PASSWORD');
        $encryption = strtolower(env('SMTP_ENCRYPTION', 'tls'));
        if ($encryption === 'ssl' || $encryption === 'smtps') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($encryption !== 'none') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        $mail->setFrom(env('SMTP_FROM_EMAIL', EMAIL_VENTAS), env('SMTP_FROM_NAME', SITE_NAME));
        $mail->addAddress(site_email());
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = 'Nueva solicitud Hotel Expert #' . $leadId;
        $base = rtrim(env('APP_URL', SITE_ORIGIN . BASE_URL), '/');
        $adminLink = $base . '/admin/lead.php?id=' . $leadId;
        $safeOrigin = e($origin);
        $safeLink = e($adminLink);
        $mail->Body = "<p>Hay una nueva solicitud #{$leadId} ({$safeOrigin}).</p><p><a href=\"{$safeLink}\">Revisarla en el panel seguro</a></p>";
        $mail->AltBody = "Hay una nueva solicitud #{$leadId} ({$origin}). Revísala en el panel: {$adminLink}";
        return $mail->send();
    } catch (MailException $e) {
        error_log('SMTP Hotel Expert: ' . $e->getMessage());
        return false;
    }
}
