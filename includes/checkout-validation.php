<?php
declare(strict_types=1);

function checkout_form_validation_error(array $input, bool $requireConsent = false): ?string
{
    $fields = [
        'nombre' => 'nombre',
        'hotel' => 'hotel o empresa',
        'email' => 'correo electrónico',
        'telefono' => 'teléfono',
        'ciudad' => 'ciudad y estado',
    ];
    $values = [];
    foreach ($fields as $key => $label) {
        $values[$key] = trim((string) ($input[$key] ?? ''));
        if ($values[$key] === '') {
            return 'Completa el campo obligatorio: ' . $label . '.';
        }
    }

    if (mb_strlen($values['nombre']) > 190 || mb_strlen($values['hotel']) > 190 || mb_strlen($values['ciudad']) > 190) {
        return 'El nombre, hotel o ciudad y estado exceden la longitud permitida.';
    }
    if (mb_strlen($values['email']) > 254 || filter_var($values['email'], FILTER_VALIDATE_EMAIL) === false) {
        return 'Ingresa un correo electrónico válido.';
    }

    $phone = $values['telefono'];
    $digitCount = preg_match_all('/\d/', $phone);
    if (mb_strlen($phone) > 60
        || preg_match('/^[0-9+() .-]+$/', $phone) !== 1
        || $digitCount === false
        || $digitCount < 7
        || $digitCount > 15) {
        return 'Ingresa un teléfono válido con lada (de 7 a 15 dígitos).';
    }

    $cargo = trim((string) ($input['cargo'] ?? ''));
    $rooms = trim((string) ($input['habitaciones'] ?? ''));
    $rfc = mb_strtoupper(trim((string) ($input['rfc'] ?? '')), 'UTF-8');
    $message = trim((string) ($input['mensaje'] ?? ''));
    if (mb_strlen($cargo) > 190 || mb_strlen($message) > 5000) {
        return 'El cargo o los comentarios exceden la longitud permitida.';
    }
    if ($rooms !== '' && (!preg_match('/^[1-9][0-9]{0,5}$/', $rooms) || (int) $rooms > 999999)) {
        return 'Habitaciones debe ser un número entero mayor que cero.';
    }
    if ($rfc !== '' && preg_match('/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/u', $rfc) !== 1) {
        return 'Ingresa un RFC válido de 12 o 13 caracteres, o déjalo vacío.';
    }
    if ($requireConsent && empty($input['contact_consent'])) {
        return 'Acepta que Hotel Expert te contacte para atender la solicitud de cotización.';
    }

    return null;
}
