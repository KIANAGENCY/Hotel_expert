<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mailer.php';

function account_url(string $path = ''): string
{
    return url('cuenta/' . ltrim($path, '/'));
}

function account_absolute_url(string $path = ''): string
{
    $origin = rtrim(env('APP_URL', SITE_ORIGIN . BASE_URL), '/');
    return $origin . '/cuenta/' . ltrim($path, '/');
}

function is_customer_portal_route(): bool
{
    $script = str_replace('\\', '/', (string) ($_SERVER['PHP_SELF'] ?? ''));
    return str_contains($script, '/cuenta/')
        && !str_ends_with($script, '/cuenta/action.php')
        && !str_ends_with($script, '/cuenta/logout.php');
}

function is_customer_portal_surface(): bool
{
    $script = str_replace('\\', '/', (string) ($_SERVER['PHP_SELF'] ?? ''));
    if (str_contains($script, '/cuenta/')) {
        return false;
    }
    $route = basename($script, '.php');
    if ($route === 'index') {
        return true;
    }
    return in_array($route, ['catalogo', 'productos', 'producto', 'cotizacion', 'contacto'], true);
}

function should_use_customer_portal_header(?array $customer = null): bool
{
    if (is_customer_portal_route()) {
        return true;
    }
    return $customer !== null && is_customer_portal_surface();
}

function customer_session_id(): int
{
    return (int) ($_SESSION['customer_id'] ?? 0);
}

function current_customer(): ?array
{
    $id = customer_session_id();
    $customer = $id > 0 ? customer_get($id) : null;
    $now = time();
    $idleTimeout = max(300, (int) env('CUSTOMER_SESSION_IDLE_SECONDS', '1800'));
    $absoluteTimeout = max($idleTimeout, (int) env('CUSTOMER_SESSION_ABSOLUTE_SECONDS', '43200'));
    $expired = $now - (int) ($_SESSION['customer_last_activity'] ?? 0) > $idleTimeout
        || $now - (int) ($_SESSION['customer_authenticated_at'] ?? 0) > $absoluteTimeout;
    if (!$customer || $expired || (int) ($_SESSION['customer_session_version'] ?? 0) !== (int) $customer['session_version']) {
        unset(
            $_SESSION['customer_id'],
            $_SESSION['customer_session_version'],
            $_SESSION['customer_authenticated_at'],
            $_SESSION['customer_last_activity']
        );
        return null;
    }
    $_SESSION['customer_last_activity'] = $now;
    return $customer;
}

function customer_require_login(): void
{
    if (!current_customer()) {
        $_SESSION['account_intended'] = (string) ($_SERVER['REQUEST_URI'] ?? account_url());
        account_flash('Inicia sesión para acceder a tu cuenta.', 'info');
        header('Location: ' . account_url('login/'));
        exit;
    }
}

function customer_logout(): void
{
    unset(
        $_SESSION['customer_id'],
        $_SESSION['customer_session_version'],
        $_SESSION['customer_authenticated_at'],
        $_SESSION['customer_last_activity'],
        $_SESSION['customer_csrf']
    );
    session_regenerate_id(true);
}

function customer_csrf(): string
{
    if (empty($_SESSION['customer_csrf'])) {
        $_SESSION['customer_csrf'] = bin2hex(random_bytes(32));
    }
    return (string) $_SESSION['customer_csrf'];
}

function customer_csrf_ok(?string $token): bool
{
    return is_string($token) && hash_equals(customer_csrf(), $token);
}

function customer_password_valid(string $password): bool
{
    return strlen($password) >= 8
        && preg_match('/[A-Z]/', $password) === 1
        && preg_match('/[a-z]/', $password) === 1
        && preg_match('/\d/', $password) === 1;
}

function customer_identity_valid(string $name, string $hotel): bool
{
    $name = trim((string) preg_replace('/\s+/u', ' ', $name));
    $hotel = trim((string) preg_replace('/\s+/u', ' ', $hotel));
    $nameLetters = preg_match_all('/\p{L}/u', $name);
    $hotelCharacters = preg_match_all('/[\p{L}\p{N}]/u', $hotel);
    $nameParts = preg_split('/\s+/u', $name, -1, PREG_SPLIT_NO_EMPTY);
    $firstNameLetters = is_array($nameParts) && $nameParts !== []
        ? preg_match_all('/\p{L}/u', $nameParts[0])
        : 0;
    $lastNameLetters = is_array($nameParts) && count($nameParts) >= 2
        ? preg_match_all('/\p{L}/u', $nameParts[array_key_last($nameParts)])
        : 0;

    return mb_strlen($name) >= 3
        && mb_strlen($name) <= 190
        && $nameLetters !== false
        && $nameLetters >= 2
        && is_array($nameParts)
        && count($nameParts) >= 2
        && $firstNameLetters !== false
        && $firstNameLetters >= 2
        && $lastNameLetters !== false
        && $lastNameLetters >= 2
        && mb_strlen($hotel) >= 3
        && mb_strlen($hotel) <= 190
        && $hotelCharacters !== false
        && $hotelCharacters >= 3;
}

function customer_phone_valid(string $phone): bool
{
    $phone = trim($phone);
    if ($phone === '') {
        return true;
    }
    $digitCount = preg_match_all('/\d/', $phone);
    return mb_strlen($phone) <= 60
        && preg_match('/^[0-9+() .-]+$/', $phone) === 1
        && $digitCount !== false
        && $digitCount >= 7
        && $digitCount <= 15;
}

function customer_rfc_valid(string $rfc): bool
{
    $rfc = mb_strtoupper(trim($rfc), 'UTF-8');
    return $rfc === '' || preg_match('/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/u', $rfc) === 1;
}

function customer_register_account(array $input): array
{
    $email = strtolower(trim((string) ($input['email'] ?? '')));
    $password = (string) ($input['password'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [false, 'Ingresa un correo electrónico válido.'];
    }
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    if (rate_limit_exceeded('registration', $email, $ip, 5, 60)) {
        return [false, 'Se alcanzó el límite temporal de registros. Intenta nuevamente más tarde.'];
    }
    if (customer_by_email($email)) {
        return [false, 'No fue posible crear la cuenta con esos datos. Puedes iniciar sesión o recuperar tu contraseña.'];
    }
    if (!customer_identity_valid((string) ($input['nombre'] ?? ''), (string) ($input['hotel'] ?? ''))) {
        return [false, 'Ingresa al menos un nombre y un apellido válidos, además del hotel o empresa.'];
    }
    if (!customer_phone_valid((string) ($input['telefono'] ?? ''))) {
        return [false, 'Ingresa un teléfono válido con lada o déjalo vacío.'];
    }
    if (!customer_rfc_valid((string) ($input['rfc'] ?? ''))) {
        return [false, 'Ingresa un RFC válido de 12 o 13 caracteres o déjalo vacío.'];
    }
    if (!customer_password_valid($password)) {
        return [false, 'Usa al menos 8 caracteres, una mayúscula, una minúscula y un número.'];
    }
    if (!hash_equals($password, (string) ($input['password_confirmation'] ?? ''))) {
        return [false, 'Las contraseñas no coinciden.'];
    }

    try {
        $id = customer_create($input);
    } catch (PDOException $e) {
        if ((string) $e->getCode() === '23000') {
            return [false, 'No fue posible crear la cuenta con esos datos.'];
        }
        throw $e;
    }
    $sent = customer_send_verification($id);
    return [true, $sent
        ? 'Cuenta creada. Revisa tu correo para verificarla.'
        : 'Cuenta creada, pero no pudimos enviar el correo. Revisa la configuración SMTP o solicita otro enlace.'];
}

function customer_send_verification(int $customerId): bool
{
    $customer = customer_get($customerId);
    if (!$customer || !empty($customer['email_verified_at'])) {
        return false;
    }
    if (rate_limit_exceeded(
        'email-verification',
        (string) $customerId,
        (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'),
        3,
        60
    )) {
        return false;
    }
    $token = bin2hex(random_bytes(32));
    customer_store_token('email_verification_tokens', $customerId, $token, date('Y-m-d H:i:s', time() + 86400));
    return send_customer_email(
        (string) $customer['email'],
        'Verifica tu cuenta — Hotel Expert',
        'Verifica tu correo',
        'Confirma tu correo para acceder a tus pedidos, rastreo y recompra.',
        'Verificar mi cuenta',
        account_absolute_url('verificar/?token=' . rawurlencode($token))
    );
}

function customer_verify_email_token(string $token): bool
{
    $id = $token !== '' ? customer_consume_token('email_verification_tokens', $token) : null;
    if (!$id) {
        return false;
    }
    customer_mark_verified($id);
    return true;
}

function customer_login_account(string $email, string $password): array
{
    $email = strtolower(trim($email));
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    if (login_is_limited('customer', $email, $ip)) {
        return [false, 'Demasiados intentos. Espera 15 minutos antes de volver a intentar.'];
    }
    $customer = customer_verify_credentials($email, $password);
    login_record_attempt('customer', $email, $ip, (bool) $customer);
    if (!$customer) {
        return [false, 'Correo o contraseña incorrectos.'];
    }
    if (empty($customer['email_verified_at'])) {
        $_SESSION['unverified_customer_id'] = (int) $customer['id'];
        return [false, 'Debes verificar tu correo antes de iniciar sesión.'];
    }
    session_regenerate_id(true);
    $_SESSION['customer_id'] = (int) $customer['id'];
    $_SESSION['customer_session_version'] = (int) $customer['session_version'];
    $_SESSION['customer_authenticated_at'] = time();
    $_SESSION['customer_last_activity'] = time();
    unset($_SESSION['unverified_customer_id']);
    customer_mark_login((int) $customer['id']);
    return [true, 'Bienvenido de vuelta.'];
}

function customer_request_password_reset(string $email): void
{
    $email = strtolower(trim($email));
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    if (login_is_limited('password-reset', $email, $ip, 3, 60)) {
        return;
    }
    login_record_attempt('password-reset', $email, $ip, false);
    $customer = customer_by_email($email);
    if (!$customer || empty($customer['email_verified_at'])) {
        return;
    }
    $token = bin2hex(random_bytes(32));
    customer_store_token('password_reset_tokens', (int) $customer['id'], $token, date('Y-m-d H:i:s', time() + 3600));
    send_customer_email(
        (string) $customer['email'],
        'Restablece tu contraseña — Hotel Expert',
        'Restablece tu contraseña',
        'Este enlace es de un solo uso y expirará en una hora.',
        'Crear nueva contraseña',
        account_absolute_url('restablecer/?token=' . rawurlencode($token))
    );
}

function customer_reset_password(string $token, string $password, string $confirmation): array
{
    if (!customer_password_valid($password)) {
        return [false, 'Usa al menos 8 caracteres, una mayúscula, una minúscula y un número.'];
    }
    if (!hash_equals($password, $confirmation)) {
        return [false, 'Las contraseñas no coinciden.'];
    }
    $id = customer_consume_token('password_reset_tokens', $token);
    if (!$id) {
        return [false, 'El enlace es inválido, ya fue utilizado o expiró.'];
    }
    customer_update_password($id, $password);
    customer_revoke_sessions($id);
    return [true, 'Contraseña actualizada. Ya puedes iniciar sesión.'];
}

function account_flash(string $message, string $type = 'success'): void
{
    $_SESSION['account_flash'] = ['message' => $message, 'type' => $type];
}

function account_flash_consume(): ?array
{
    $flash = $_SESSION['account_flash'] ?? null;
    unset($_SESSION['account_flash']);
    return is_array($flash) ? $flash : null;
}
