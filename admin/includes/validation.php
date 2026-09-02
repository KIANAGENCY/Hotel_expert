<?php
declare(strict_types=1);

function admin_clip(string $value, int $max): string
{
    return mb_substr(trim($value), 0, $max);
}

function admin_sort_order(mixed $value): int
{
    return max(0, min(9999, (int) $value));
}

function admin_field_rules(string $name): array
{
    $key = rtrim($name, '[]');
    $rules = [
        'titulo' => ['required' => 'required', 'minlength' => '3', 'maxlength' => '190'],
        'texto' => ['required' => 'required', 'minlength' => '10', 'maxlength' => '2000'],
        'href' => ['required' => 'required', 'maxlength' => '255', 'pattern' => '[a-zA-Z0-9._\\-/#]+', 'title' => 'Ruta relativa o ancla interna, sin espacios'],
        'pregunta' => ['required' => 'required', 'minlength' => '5', 'maxlength' => '500'],
        'respuesta' => ['required' => 'required', 'minlength' => '10', 'maxlength' => '5000'],
        'sort_order' => ['min' => '0', 'max' => '9999', 'inputmode' => 'numeric'],
        'slug' => ['required' => 'required', 'pattern' => '[a-z0-9\\-]+', 'minlength' => '2', 'maxlength' => '80', 'title' => 'Solo minúsculas, números y guiones'],
        'sku' => ['maxlength' => '40', 'pattern' => '[A-Za-z0-9\\-]+', 'title' => 'Solo letras, números y guiones'],
        'nombre' => ['required' => 'required', 'minlength' => '2', 'maxlength' => '190'],
        'categoria' => ['maxlength' => '80'],
        'subtitulo' => ['maxlength' => '190'],
        'resumen' => ['maxlength' => '1000'],
        'presentacion' => ['maxlength' => '120'],
        'rendimiento' => ['maxlength' => '120'],
        'precio' => ['min' => '0', 'max' => '9999999', 'inputmode' => 'numeric'],
        'precio_texto' => ['maxlength' => '40'],
        'precio_lista' => ['min' => '0', 'max' => '9999999', 'inputmode' => 'numeric'],
        'imagen' => ['maxlength' => '120', 'pattern' => '[a-zA-Z0-9._\\-]+', 'title' => 'Solo nombre de archivo, sin carpetas'],
        'alt' => ['maxlength' => '190'],
        'icono' => ['maxlength' => '8', 'pattern' => '[A-Za-z0-9]+', 'title' => 'Monograma corto, sin espacios'],
        'funcion' => ['maxlength' => '1000'],
        'especialidad' => ['maxlength' => '1000'],
        'claims' => ['maxlength' => '4000'],
        'superficies' => ['maxlength' => '4000'],
        'no_usar' => ['maxlength' => '2000'],
        'seo_titulo' => ['maxlength' => '190'],
        'meta_descripcion' => ['maxlength' => '320'],
        'bajada' => ['maxlength' => '240'],
        'extracto' => ['maxlength' => '500'],
        'lectura' => ['maxlength' => '40'],
        'cover' => ['maxlength' => '500', 'pattern' => 'https://.*', 'title' => 'URL segura que comience con https://'],
        'cuerpo' => ['maxlength' => '50000'],
        'site_name' => ['required' => 'required', 'minlength' => '2', 'maxlength' => '120'],
        'site_tagline' => ['required' => 'required', 'minlength' => '5', 'maxlength' => '190'],
        'site_claim' => ['maxlength' => '190'],
        'site_domain' => ['required' => 'required', 'maxlength' => '120', 'pattern' => '[a-zA-Z0-9.\\-]+', 'title' => 'Dominio sin protocolo'],
        'whatsapp' => ['required' => 'required', 'pattern' => '[0-9]{10,15}', 'title' => 'Solo dígitos, entre 10 y 15'],
        'whatsapp_display' => ['maxlength' => '40'],
        'email_ventas' => ['required' => 'required', 'type' => 'email', 'maxlength' => '190'],
        'social_facebook' => ['maxlength' => '500', 'pattern' => 'https://.*', 'title' => 'URL que comience con https:// o déjalo vacío'],
        'social_instagram' => ['maxlength' => '500', 'pattern' => 'https://.*', 'title' => 'URL que comience con https:// o déjalo vacío'],
        'current_password' => ['minlength' => '8', 'autocomplete' => 'current-password'],
        'new_password' => ['minlength' => '12', 'autocomplete' => 'new-password', 'pattern' => '(?=.*[a-z])(?=.*[A-Z])(?=.*\\d).{12,}', 'title' => 'Mínimo 12 caracteres, con mayúscula, minúscula y número'],
        'new_password_confirmation' => ['minlength' => '12', 'autocomplete' => 'new-password'],
        'id' => ['required' => 'required', 'pattern' => '[A-Z0-9\\-]+', 'minlength' => '3', 'maxlength' => '40', 'title' => 'Folio en mayúsculas, números y guiones'],
        'email' => ['required' => 'required', 'type' => 'email', 'maxlength' => '190'],
        'hotel' => ['required' => 'required', 'minlength' => '2', 'maxlength' => '190'],
        'fecha' => ['type' => 'date'],
        'eta' => ['type' => 'date'],
        'items' => ['maxlength' => '2000'],
        'guia' => ['maxlength' => '120'],
        'product_qty' => ['min' => '1', 'max' => '99', 'inputmode' => 'numeric'],
        'notas' => ['maxlength' => '5000'],
        'APP_URL' => ['required' => 'required', 'maxlength' => '500', 'pattern' => 'https://.*', 'title' => 'URL pública con https://'],
        'APP_ENV' => ['required' => 'required'],
        'DB_HOST' => ['required' => 'required', 'maxlength' => '120'],
        'DB_PORT' => ['required' => 'required', 'min' => '1', 'max' => '65535', 'inputmode' => 'numeric'],
        'DB_DATABASE' => ['required' => 'required', 'maxlength' => '120'],
        'DB_USERNAME' => ['required' => 'required', 'maxlength' => '120'],
        'DB_PASSWORD' => ['autocomplete' => 'new-password'],
        'SMTP_HOST' => ['maxlength' => '190'],
        'SMTP_PORT' => ['min' => '1', 'max' => '65535', 'inputmode' => 'numeric'],
        'SMTP_USERNAME' => ['maxlength' => '190'],
        'SMTP_PASSWORD' => ['autocomplete' => 'new-password'],
        'SMTP_FROM_EMAIL' => ['type' => 'email', 'maxlength' => '190'],
        'SMTP_FROM_NAME' => ['maxlength' => '120'],
        'ADMIN_SESSION_IDLE_SECONDS' => ['required' => 'required', 'min' => '300', 'max' => '86400', 'inputmode' => 'numeric'],
        'ADMIN_SESSION_ABSOLUTE_SECONDS' => ['required' => 'required', 'min' => '600', 'max' => '604800', 'inputmode' => 'numeric'],
        'CUSTOMER_SESSION_IDLE_SECONDS' => ['required' => 'required', 'min' => '300', 'max' => '86400', 'inputmode' => 'numeric'],
        'CUSTOMER_SESSION_ABSOLUTE_SECONDS' => ['required' => 'required', 'min' => '600', 'max' => '604800', 'inputmode' => 'numeric'],
        'DEPLOY_CANONICAL_HOST' => ['maxlength' => '120', 'pattern' => '[a-zA-Z0-9.\\-]+', 'title' => 'Dominio sin protocolo'],
        'DEPLOY_REWRITE_BASE' => ['maxlength' => '80', 'pattern' => '[a-zA-Z0-9_\\-/]*', 'title' => 'Subcarpeta opcional, sin barras iniciales'],
        'admin_password' => ['minlength' => '1', 'autocomplete' => 'current-password'],
        'STRIPE_PUBLISHABLE_KEY' => ['maxlength' => '190', 'pattern' => 'pk_(test|live)_.+', 'title' => 'Debe comenzar con pk_test_ o pk_live_'],
        'STRIPE_SECRET_KEY' => ['maxlength' => '190', 'autocomplete' => 'new-password'],
        'STRIPE_WEBHOOK_SECRET' => ['maxlength' => '190', 'autocomplete' => 'new-password'],
        'STRIPE_CURRENCY' => ['required' => 'required', 'pattern' => '[a-z]{3}', 'title' => 'Código ISO de 3 letras, ej. mxn'],
    ];

    return $rules[$key] ?? [];
}

function admin_merge_field_attrs(array $attrs, string $name): array
{
    foreach (admin_field_rules($name) as $key => $value) {
        if (!isset($attrs[$key])) {
            $attrs[$key] = $value;
        }
    }
    return $attrs;
}

function admin_redirect_error(string $message, string $url): never
{
    admin_flash($message, 'error');
    header('Location: ' . $url);
    exit;
}

function admin_href_is_safe(string $href): bool
{
    if ($href === '' || preg_match('/[\s<>"\']/', $href)) {
        return false;
    }
    if (preg_match('/^(javascript|data|vbscript):/i', $href)) {
        return false;
    }
    if (preg_match('#^https?://#i', $href)) {
        return filter_var($href, FILTER_VALIDATE_URL) !== false;
    }
    return (bool) preg_match('/^[a-z0-9._\-\/]+(?:#[\w\-]+)?$/i', $href);
}

function admin_optional_https_url(string $url): bool
{
    return $url === '' || (str_starts_with($url, 'https://') && filter_var($url, FILTER_VALIDATE_URL) !== false);
}

function admin_asset_filename_is_safe(string $name): bool
{
    if ($name === '') {
        return true;
    }
    if (str_contains($name, '/') || str_contains($name, '\\') || str_contains($name, '..')) {
        return false;
    }
    return (bool) preg_match('/^[a-zA-Z0-9._-]+$/', $name);
}

function admin_date_or_empty(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }
    $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);
    return ($date && $date->format('Y-m-d') === $value) ? $value : '';
}

function admin_email_is_valid(string $email): bool
{
    return $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function admin_password_is_strong(string $password): bool
{
    return strlen($password) >= 12
        && preg_match('/[A-Z]/', $password) === 1
        && preg_match('/[a-z]/', $password) === 1
        && preg_match('/\d/', $password) === 1;
}

function admin_lines(string $value, int $maxLines = 50, int $maxLineLength = 240): array
{
    $lines = [];
    foreach (preg_split('/\R/', $value) ?: [] as $line) {
        $line = admin_clip((string) $line, $maxLineLength);
        if ($line !== '') {
            $lines[] = $line;
        }
        if (count($lines) >= $maxLines) {
            break;
        }
    }
    return $lines;
}

function admin_validate_area_payload(array $post): array
{
    $titulo = admin_clip((string) ($post['titulo'] ?? ''), 190);
    $texto = admin_clip((string) ($post['texto'] ?? ''), 2000);
    $href = admin_clip((string) ($post['href'] ?? ''), 255);
    if (mb_strlen($titulo) < 3) {
        return ['ok' => false, 'message' => 'El título debe tener al menos 3 caracteres.'];
    }
    if (mb_strlen($texto) < 10) {
        return ['ok' => false, 'message' => 'El texto debe tener al menos 10 caracteres.'];
    }
    if (!admin_href_is_safe($href)) {
        return ['ok' => false, 'message' => 'El enlace no es válido. Usa una ruta interna segura o https://'];
    }
    return [
        'ok' => true,
        'data' => [
            'id' => max(0, (int) ($post['id'] ?? 0)) ?: null,
            'titulo' => $titulo,
            'texto' => $texto,
            'href' => $href,
            'sort_order' => admin_sort_order($post['sort_order'] ?? 0),
        ],
    ];
}

function admin_validate_faq_payload(array $post): array
{
    $pregunta = admin_clip((string) ($post['pregunta'] ?? ''), 500);
    $respuesta = admin_clip((string) ($post['respuesta'] ?? ''), 5000);
    if (mb_strlen($pregunta) < 5) {
        return ['ok' => false, 'message' => 'La pregunta debe tener al menos 5 caracteres.'];
    }
    if (mb_strlen($respuesta) < 10) {
        return ['ok' => false, 'message' => 'La respuesta debe tener al menos 10 caracteres.'];
    }
    return [
        'ok' => true,
        'data' => [
            'id' => max(0, (int) ($post['id'] ?? 0)) ?: null,
            'pregunta' => $pregunta,
            'respuesta' => $respuesta,
            'sort_order' => admin_sort_order($post['sort_order'] ?? 0),
        ],
    ];
}

function admin_validate_product_payload(array $post): array
{
    $slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) ($post['slug'] ?? '')));
    if ($slug === '' || mb_strlen($slug) < 2) {
        return ['ok' => false, 'message' => 'El slug es obligatorio y solo puede usar minúsculas, números y guiones.'];
    }
    $nombre = admin_clip((string) ($post['nombre'] ?? ''), 190);
    if (mb_strlen($nombre) < 2) {
        return ['ok' => false, 'message' => 'El nombre del producto es obligatorio.'];
    }
    $imagen = admin_clip((string) ($post['imagen'] ?? ''), 120);
    if (!admin_asset_filename_is_safe($imagen)) {
        return ['ok' => false, 'message' => 'La imagen solo puede ser un nombre de archivo seguro, sin rutas.'];
    }
    $icono = admin_clip((string) ($post['icono'] ?? ''), 8);
    if ($icono !== '' && !preg_match('/^[A-Za-z0-9]+$/', $icono)) {
        return ['ok' => false, 'message' => 'El icono solo puede contener letras y números.'];
    }
    return [
        'ok' => true,
        'data' => [
            'slug' => $slug,
            'sku' => admin_clip((string) ($post['sku'] ?? ''), 40),
            'nombre' => $nombre,
            'categoria' => admin_clip((string) ($post['categoria'] ?? ''), 80),
            'subtitulo' => admin_clip((string) ($post['subtitulo'] ?? ''), 190),
            'resumen' => admin_clip((string) ($post['resumen'] ?? ''), 1000),
            'presentacion' => admin_clip((string) ($post['presentacion'] ?? ''), 120),
            'rendimiento' => admin_clip((string) ($post['rendimiento'] ?? ''), 120),
            'precio' => max(0, min(9999999, (int) ($post['precio'] ?? 0))),
            'precio_texto' => admin_clip((string) ($post['precio_texto'] ?? ''), 40),
            'precio_lista' => admin_clip((string) ($post['precio_lista'] ?? ''), 20) ?: null,
            'iva' => !empty($post['iva']),
            'imagen' => $imagen ?: null,
            'alt' => admin_clip((string) ($post['alt'] ?? ''), 190),
            'icono' => $icono,
            'funcion' => admin_clip((string) ($post['funcion'] ?? ''), 1000),
            'especialidad' => admin_clip((string) ($post['especialidad'] ?? ''), 1000),
            'claims' => admin_lines((string) ($post['claims'] ?? '')),
            'superficies' => admin_lines((string) ($post['superficies'] ?? '')),
            'no_usar' => admin_lines((string) ($post['no_usar'] ?? '')),
            'sort_order' => admin_sort_order($post['sort_order'] ?? 0),
        ],
    ];
}

function admin_validate_post_payload(array $post): array
{
    $slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) ($post['slug'] ?? '')));
    if ($slug === '' || mb_strlen($slug) < 2) {
        return ['ok' => false, 'message' => 'El slug del artículo no es válido.'];
    }
    $titulo = admin_clip((string) ($post['titulo'] ?? ''), 190);
    if (mb_strlen($titulo) < 3) {
        return ['ok' => false, 'message' => 'El título del artículo es obligatorio.'];
    }
    $cover = admin_clip((string) ($post['cover'] ?? ''), 500);
    if (!admin_optional_https_url($cover)) {
        return ['ok' => false, 'message' => 'La imagen de portada debe ser una URL https:// válida o quedar vacía.'];
    }
    $fecha = admin_date_or_empty((string) ($post['fecha'] ?? ''));
    if ($fecha === '' && trim((string) ($post['fecha'] ?? '')) !== '') {
        return ['ok' => false, 'message' => 'La fecha del artículo no es válida.'];
    }
    $cuerpo = array_values(array_filter(array_map(
        static fn(string $part): string => admin_clip($part, 8000),
        preg_split('/\R{2,}/', (string) ($post['cuerpo'] ?? '')) ?: []
    ), static fn(string $part): bool => $part !== ''));
    if ($cuerpo === []) {
        return ['ok' => false, 'message' => 'El cuerpo del artículo no puede quedar vacío.'];
    }
    return [
        'ok' => true,
        'data' => [
            'slug' => $slug,
            'titulo' => $titulo,
            'seo_titulo' => admin_clip((string) ($post['seo_titulo'] ?? ''), 190),
            'meta_descripcion' => admin_clip((string) ($post['meta_descripcion'] ?? ''), 320),
            'bajada' => admin_clip((string) ($post['bajada'] ?? ''), 240),
            'extracto' => admin_clip((string) ($post['extracto'] ?? ''), 500),
            'categoria' => admin_clip((string) ($post['categoria'] ?? ''), 80),
            'fecha' => $fecha !== '' ? $fecha : date('Y-m-d'),
            'lectura' => admin_clip((string) ($post['lectura'] ?? '4 min'), 40),
            'cover' => $cover,
            'cuerpo' => $cuerpo,
            'sort_order' => admin_sort_order($post['sort_order'] ?? 0),
        ],
    ];
}

function admin_validate_order_payload(array $post): array
{
    $id = strtoupper(admin_clip((string) ($post['id'] ?? ''), 40));
    if ($id === '' || !preg_match('/^[A-Z0-9\-]+$/', $id)) {
        return ['ok' => false, 'message' => 'El folio del pedido no es válido.'];
    }
    $email = admin_clip((string) ($post['email'] ?? ''), 190);
    if (!admin_email_is_valid($email)) {
        return ['ok' => false, 'message' => 'El correo del cliente no es válido.'];
    }
    $hotel = admin_clip((string) ($post['hotel'] ?? ''), 190);
    if (mb_strlen($hotel) < 2) {
        return ['ok' => false, 'message' => 'El hotel es obligatorio.'];
    }
    $estado = (string) ($post['estado'] ?? 'procesando');
    if (!array_key_exists($estado, admin_estados_pedido())) {
        return ['ok' => false, 'message' => 'El estado del pedido no es válido.'];
    }
    $fecha = admin_date_or_empty((string) ($post['fecha'] ?? ''));
    $eta = admin_date_or_empty((string) ($post['eta'] ?? ''));
    if (trim((string) ($post['fecha'] ?? '')) !== '' && $fecha === '') {
        return ['ok' => false, 'message' => 'La fecha del pedido no es válida.'];
    }
    if (trim((string) ($post['eta'] ?? '')) !== '' && $eta === '') {
        return ['ok' => false, 'message' => 'La fecha estimada de entrega no es válida.'];
    }
    return [
        'ok' => true,
        'data' => [
            'id' => $id,
            'email' => $email,
            'hotel' => $hotel,
            'estado' => $estado,
            'fecha' => $fecha,
            'eta' => $eta,
            'items' => admin_clip((string) ($post['items'] ?? ''), 2000),
            'guia' => admin_clip((string) ($post['guia'] ?? ''), 120),
        ],
    ];
}

function admin_validate_lead_payload(array $post): array
{
    $id = max(0, (int) ($post['id'] ?? 0));
    if ($id <= 0) {
        return ['ok' => false, 'message' => 'Lead no válido.'];
    }
    $estado = (string) ($post['estado'] ?? 'nuevo');
    if (!array_key_exists($estado, admin_estados_lead())) {
        return ['ok' => false, 'message' => 'Estado de lead no válido.'];
    }
    return [
        'ok' => true,
        'data' => [
            'id' => $id,
            'estado' => $estado,
            'notas' => admin_clip((string) ($post['notas'] ?? ''), 5000),
        ],
    ];
}

function admin_validate_settings_payload(array $post): array
{
    $siteName = admin_clip((string) ($post['site_name'] ?? ''), 120);
    $siteTagline = admin_clip((string) ($post['site_tagline'] ?? ''), 190);
    if (mb_strlen($siteName) < 2 || mb_strlen($siteTagline) < 5) {
        return ['ok' => false, 'message' => 'Nombre y tagline del sitio son obligatorios.'];
    }
    $domain = admin_clip((string) ($post['site_domain'] ?? ''), 120);
    if ($domain === '' || !preg_match('/^[a-zA-Z0-9.\-]+$/', $domain)) {
        return ['ok' => false, 'message' => 'El dominio del sitio no es válido.'];
    }
    $whatsapp = preg_replace('/\D/', '', (string) ($post['whatsapp'] ?? ''));
    if ($whatsapp === '' || strlen($whatsapp) < 10 || strlen($whatsapp) > 15) {
        return ['ok' => false, 'message' => 'El número de WhatsApp debe tener entre 10 y 15 dígitos.'];
    }
    $emailVentas = admin_clip((string) ($post['email_ventas'] ?? ''), 190);
    if (!admin_email_is_valid($emailVentas)) {
        return ['ok' => false, 'message' => 'El correo de ventas no es válido.'];
    }
    $facebook = admin_clip((string) ($post['social_facebook'] ?? ''), 500);
    $instagram = admin_clip((string) ($post['social_instagram'] ?? ''), 500);
    if (!admin_optional_https_url($facebook) || !admin_optional_https_url($instagram)) {
        return ['ok' => false, 'message' => 'Las URLs sociales deben comenzar con https:// o quedar vacías.'];
    }
    $newPass = (string) ($post['new_password'] ?? '');
    $currentPass = (string) ($post['current_password'] ?? '');
    $confirmation = (string) ($post['new_password_confirmation'] ?? '');
    if ($newPass !== '') {
        if (!admin_password_is_strong($newPass) || !hash_equals($newPass, $confirmation) || !admin_verify(admin_user(), $currentPass)) {
            return ['ok' => false, 'message' => 'No se cambió la contraseña. Verifica la actual, la confirmación y usa al menos 12 caracteres con mayúscula, minúscula y número.'];
        }
    }
    return [
        'ok' => true,
        'data' => [
            'settings' => [
                'site_name' => $siteName,
                'site_tagline' => $siteTagline,
                'site_claim' => admin_clip((string) ($post['site_claim'] ?? ''), 190),
                'site_domain' => $domain,
                'whatsapp' => $whatsapp,
                'whatsapp_display' => admin_clip((string) ($post['whatsapp_display'] ?? ''), 40),
                'email_ventas' => $emailVentas,
                'social_facebook' => $facebook,
                'social_instagram' => $instagram,
            ],
            'new_password' => $newPass,
        ],
    ];
}

function admin_bool_env(mixed $value): string
{
    return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
}

function admin_session_timeout(mixed $value, int $min, int $max): int
{
    return max($min, min($max, (int) $value));
}

function admin_validate_deploy_db_payload(array $post): array
{
    $host = admin_clip((string) ($post['DB_HOST'] ?? ''), 120);
    $port = (int) ($post['DB_PORT'] ?? 3306);
    $database = admin_clip((string) ($post['DB_DATABASE'] ?? ''), 120);
    $username = admin_clip((string) ($post['DB_USERNAME'] ?? ''), 120);
    if ($host === '' || $database === '' || $username === '') {
        return ['ok' => false, 'message' => 'Host, base de datos y usuario son obligatorios para probar la conexión.'];
    }
    if ($port < 1 || $port > 65535) {
        return ['ok' => false, 'message' => 'El puerto de MySQL no es válido.'];
    }
    return [
        'ok' => true,
        'data' => [
            'DB_HOST' => $host,
            'DB_PORT' => (string) $port,
            'DB_DATABASE' => $database,
            'DB_USERNAME' => $username,
            'DB_PASSWORD' => (string) ($post['DB_PASSWORD'] ?? ''),
        ],
    ];
}

function admin_validate_deploy_payload(array $post, bool $requireAdminPassword = true): array
{
    $appUrl = admin_clip((string) ($post['APP_URL'] ?? ''), 500);
    if ($appUrl === '' || !str_starts_with($appUrl, 'https://') || filter_var($appUrl, FILTER_VALIDATE_URL) === false) {
        return ['ok' => false, 'message' => 'La URL pública debe ser una dirección https:// válida.'];
    }
    $appEnv = admin_clip((string) ($post['APP_ENV'] ?? ''), 20);
    if (!in_array($appEnv, ['local', 'production'], true)) {
        return ['ok' => false, 'message' => 'El entorno debe ser local o production.'];
    }

    $db = admin_validate_deploy_db_payload($post);
    if (!$db['ok']) {
        return $db;
    }

    $smtpHost = admin_clip((string) ($post['SMTP_HOST'] ?? ''), 190);
    $smtpPort = (int) ($post['SMTP_PORT'] ?? 587);
    if ($smtpHost !== '' && ($smtpPort < 1 || $smtpPort > 65535)) {
        return ['ok' => false, 'message' => 'El puerto SMTP no es válido.'];
    }
    $smtpEncryption = strtolower(admin_clip((string) ($post['SMTP_ENCRYPTION'] ?? 'tls'), 10));
    if (!in_array($smtpEncryption, ['tls', 'ssl', 'none', ''], true)) {
        return ['ok' => false, 'message' => 'El cifrado SMTP debe ser tls, ssl o none.'];
    }
    $smtpFrom = admin_clip((string) ($post['SMTP_FROM_EMAIL'] ?? ''), 190);
    if ($smtpFrom !== '' && !admin_email_is_valid($smtpFrom)) {
        return ['ok' => false, 'message' => 'El correo remitente SMTP no es válido.'];
    }

    $canonical = admin_clip((string) ($post['DEPLOY_CANONICAL_HOST'] ?? ''), 120);
    if ($canonical !== '' && !preg_match('/^[a-zA-Z0-9.\-]+$/', $canonical)) {
        return ['ok' => false, 'message' => 'El host canónico no es válido.'];
    }
    $rewriteBase = trim(admin_clip((string) ($post['DEPLOY_REWRITE_BASE'] ?? ''), 80), '/');
    if ($rewriteBase !== '' && !preg_match('/^[a-zA-Z0-9_\-\/]+$/', $rewriteBase)) {
        return ['ok' => false, 'message' => 'La subcarpeta de despliegue contiene caracteres no permitidos.'];
    }

    if ($canonical !== '' && !str_contains($appUrl, $canonical)) {
        $appUrl = 'https://' . $canonical;
    }

    if ($requireAdminPassword) {
        $adminPassword = (string) ($post['admin_password'] ?? '');
        if ($adminPassword === '' || !admin_verify(admin_user(), $adminPassword)) {
            return ['ok' => false, 'message' => 'Confirma tu contraseña de administrador para guardar el despliegue.'];
        }
    }

    return [
        'ok' => true,
        'data' => [
            'APP_URL' => $appUrl,
            'APP_ENV' => $appEnv,
            'TRUST_PROXY_HEADERS' => admin_bool_env($post['TRUST_PROXY_HEADERS'] ?? false),
            'DB_HOST' => $db['data']['DB_HOST'],
            'DB_PORT' => $db['data']['DB_PORT'],
            'DB_DATABASE' => $db['data']['DB_DATABASE'],
            'DB_USERNAME' => $db['data']['DB_USERNAME'],
            'DB_PASSWORD' => $db['data']['DB_PASSWORD'],
            'SMTP_HOST' => $smtpHost,
            'SMTP_PORT' => (string) max(1, $smtpPort),
            'SMTP_USERNAME' => admin_clip((string) ($post['SMTP_USERNAME'] ?? ''), 190),
            'SMTP_PASSWORD' => (string) ($post['SMTP_PASSWORD'] ?? ''),
            'SMTP_ENCRYPTION' => $smtpEncryption === '' ? 'tls' : $smtpEncryption,
            'SMTP_FROM_EMAIL' => $smtpFrom,
            'SMTP_FROM_NAME' => admin_clip((string) ($post['SMTP_FROM_NAME'] ?? ''), 120),
            'ADMIN_SESSION_IDLE_SECONDS' => (string) admin_session_timeout($post['ADMIN_SESSION_IDLE_SECONDS'] ?? 1800, 300, 86400),
            'ADMIN_SESSION_ABSOLUTE_SECONDS' => (string) admin_session_timeout($post['ADMIN_SESSION_ABSOLUTE_SECONDS'] ?? 43200, 600, 604800),
            'CUSTOMER_SESSION_IDLE_SECONDS' => (string) admin_session_timeout($post['CUSTOMER_SESSION_IDLE_SECONDS'] ?? 1800, 300, 86400),
            'CUSTOMER_SESSION_ABSOLUTE_SECONDS' => (string) admin_session_timeout($post['CUSTOMER_SESSION_ABSOLUTE_SECONDS'] ?? 43200, 600, 604800),
            'DEPLOY_FORCE_HTTPS' => admin_bool_env($post['DEPLOY_FORCE_HTTPS'] ?? false),
            'DEPLOY_CANONICAL_HOST' => $canonical,
            'DEPLOY_REWRITE_BASE' => $rewriteBase,
            'site_domain' => $canonical !== '' ? $canonical : parse_url($appUrl, PHP_URL_HOST),
        ],
    ];
}

function admin_validate_stripe_payload(array $post, bool $requireAdminPassword = true): array
{
    $enabled = admin_bool_env($post['STRIPE_ENABLED'] ?? false) === 'true';
    $mode = strtolower(admin_clip((string) ($post['STRIPE_MODE'] ?? 'test'), 10));
    if (!in_array($mode, ['test', 'live'], true)) {
        return ['ok' => false, 'message' => 'El modo de Stripe debe ser test o live.'];
    }

    $publishable = trim(admin_clip((string) ($post['STRIPE_PUBLISHABLE_KEY'] ?? ''), 190));
    $secret = (string) ($post['STRIPE_SECRET_KEY'] ?? '');
    $webhook = (string) ($post['STRIPE_WEBHOOK_SECRET'] ?? '');
    $currencyRaw = strtolower(trim((string) ($post['STRIPE_CURRENCY'] ?? 'mxn')));
    if (!preg_match('/^[a-z]{3}$/', $currencyRaw)) {
        return ['ok' => false, 'message' => 'La moneda debe ser un código ISO de 3 letras (ej. mxn).'];
    }
    $currency = $currencyRaw;

    $pkPrefix = $mode === 'live' ? 'pk_live_' : 'pk_test_';
    $skPrefix = $mode === 'live' ? 'sk_live_' : 'sk_test_';

    if ($enabled) {
        if ($publishable === '' || !str_starts_with($publishable, $pkPrefix)) {
            return ['ok' => false, 'message' => 'La clave publicable debe coincidir con el modo (' . $pkPrefix . '…).'];
        }
        $effectiveSecret = $secret !== '' ? $secret : (env_file_secret_is_set('STRIPE_SECRET_KEY') ? env_file_get('STRIPE_SECRET_KEY') : '');
        if ($effectiveSecret === '' || !str_starts_with($effectiveSecret, $skPrefix)) {
            return ['ok' => false, 'message' => 'La clave secreta debe coincidir con el modo (' . $skPrefix . '…).'];
        }
    } elseif ($publishable !== '' && !preg_match('/^pk_(test|live)_/', $publishable)) {
        return ['ok' => false, 'message' => 'La clave publicable de Stripe no es válida.'];
    }

    if ($secret !== '' && !preg_match('/^sk_(test|live)_/', $secret)) {
        return ['ok' => false, 'message' => 'La clave secreta de Stripe no es válida.'];
    }
    if ($webhook !== '' && !str_starts_with($webhook, 'whsec_')) {
        return ['ok' => false, 'message' => 'El secreto del webhook debe comenzar con whsec_.'];
    }

    $ivaRate = (float) str_replace(',', '.', trim((string) ($post['checkout_iva_rate'] ?? '16')));
    if ($ivaRate < 0 || $ivaRate > 100) {
        return ['ok' => false, 'message' => 'La tasa de IVA debe estar entre 0 y 100.'];
    }

    if ($requireAdminPassword) {
        $adminPassword = (string) ($post['admin_password'] ?? '');
        if ($adminPassword === '' || !admin_verify(admin_user(), $adminPassword)) {
            return ['ok' => false, 'message' => 'Confirma tu contraseña de administrador para guardar Stripe.'];
        }
    }

    return [
        'ok' => true,
        'data' => [
            'STRIPE_ENABLED' => $enabled ? 'true' : 'false',
            'STRIPE_MODE' => $mode,
            'STRIPE_PUBLISHABLE_KEY' => $publishable,
            'STRIPE_SECRET_KEY' => $secret,
            'STRIPE_WEBHOOK_SECRET' => $webhook,
            'STRIPE_CURRENCY' => $currency,
            'checkout_iva_rate' => number_format($ivaRate, 2, '.', ''),
        ],
    ];
}
