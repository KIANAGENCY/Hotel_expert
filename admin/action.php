<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
require_once __DIR__ . '/includes/validation.php';
admin_require_login();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !admin_csrf_ok($_POST['csrf'] ?? null)) {
    admin_flash('Sesión inválida. Intenta de nuevo.', 'error');
    header('Location: ' . admin_url('index.php'));
    exit;
}

$action = (string) ($_POST['action'] ?? '');

switch ($action) {
    case 'lead_update':
        $validated = admin_validate_lead_payload($_POST);
        if (!$validated['ok']) {
            admin_redirect_error($validated['message'], admin_url('lead.php?id=' . (int) ($_POST['id'] ?? 0)));
        }
        lead_update($validated['data']['id'], $validated['data']['estado'], $validated['data']['notas']);
        admin_flash('Lead actualizado.');
        header('Location: ' . admin_url('lead.php?id=' . $validated['data']['id']));
        break;

    case 'lead_delete':
        lead_delete((int) ($_POST['id'] ?? 0));
        admin_flash('Lead eliminado.');
        header('Location: ' . admin_url('leads.php'));
        break;

    case 'product_save':
        $validated = admin_validate_product_payload($_POST);
        if (!$validated['ok']) {
            $slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) ($_POST['slug'] ?? '')));
            admin_redirect_error($validated['message'], admin_url($slug !== '' ? 'producto.php?slug=' . rawurlencode($slug) : 'producto.php'));
        }
        $data = $validated['data'];
        $sortOrder = $data['sort_order'];
        unset($data['sort_order']);
        producto_save($data['slug'], $data, $sortOrder);
        admin_flash('Producto guardado.');
        header('Location: ' . admin_url('producto.php?slug=' . rawurlencode($data['slug'])));
        break;

    case 'product_delete':
        producto_delete(preg_replace('/[^a-z0-9-]/', '', (string) ($_POST['slug'] ?? '')));
        admin_flash('Producto eliminado.');
        header('Location: ' . admin_url('productos.php'));
        break;

    case 'post_save':
        $validated = admin_validate_post_payload($_POST);
        if (!$validated['ok']) {
            $slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) ($_POST['slug'] ?? '')));
            admin_redirect_error($validated['message'], admin_url($slug !== '' ? 'post.php?slug=' . rawurlencode($slug) : 'post.php'));
        }
        $data = $validated['data'];
        $sortOrder = $data['sort_order'];
        unset($data['sort_order']);
        blog_save($data['slug'], $data, $sortOrder);
        admin_flash('Artículo guardado.');
        header('Location: ' . admin_url('post.php?slug=' . rawurlencode($data['slug'])));
        break;

    case 'post_delete':
        blog_delete(preg_replace('/[^a-z0-9-]/', '', (string) ($_POST['slug'] ?? '')));
        admin_flash('Artículo eliminado.');
        header('Location: ' . admin_url('blog.php'));
        break;

    case 'order_save':
        $validated = admin_validate_order_payload($_POST);
        if (!$validated['ok']) {
            $id = strtoupper(trim((string) ($_POST['id'] ?? '')));
            admin_redirect_error($validated['message'], admin_url($id !== '' ? 'pedido.php?id=' . rawurlencode($id) : 'pedido.php'));
        }
        $data = $validated['data'];
        $products = productos_all();
        $orderItems = [];
        $itemNames = [];
        foreach ((array) ($_POST['product_slug'] ?? []) as $index => $slug) {
            $slug = preg_replace('/[^a-z0-9-]/', '', (string) $slug);
            $quantity = min(99, max(1, (int) (($_POST['product_qty'] ?? [])[$index] ?? 1)));
            if ($slug === '' || !isset($products[$slug])) {
                continue;
            }
            $product = $products[$slug];
            $orderItems[] = [
                'slug' => $slug,
                'name' => $product['nombre'],
                'quantity' => $quantity,
                'unit_price' => (int) $product['precio'],
            ];
            $itemNames[] = $quantity . '× ' . $product['nombre'];
        }
        pedido_save([
            'id' => $data['id'],
            'email' => $data['email'],
            'hotel' => $data['hotel'],
            'estado' => $data['estado'],
            'fecha' => $data['fecha'],
            'eta' => $data['eta'],
            'items' => $itemNames ? implode(', ', $itemNames) : $data['items'],
            'guia' => $data['guia'],
            'order_items' => $orderItems,
        ]);
        admin_flash('Pedido guardado.');
        header('Location: ' . admin_url('pedido.php?id=' . rawurlencode($data['id'])));
        break;

    case 'order_delete':
        pedido_delete(strtoupper(trim((string) ($_POST['id'] ?? ''))));
        admin_flash('Pedido eliminado.');
        header('Location: ' . admin_url('pedidos.php'));
        break;

    case 'faq_save':
        $validated = admin_validate_faq_payload($_POST);
        if (!$validated['ok']) {
            admin_redirect_error($validated['message'], admin_url('faq.php'));
        }
        $data = $validated['data'];
        faq_save($data['id'], $data['pregunta'], $data['respuesta'], $data['sort_order']);
        admin_flash('FAQ guardada.');
        header('Location: ' . admin_url('faq.php'));
        break;

    case 'faq_delete':
        faq_delete((int) ($_POST['id'] ?? 0));
        admin_flash('FAQ eliminada.');
        header('Location: ' . admin_url('faq.php'));
        break;

    case 'area_save':
        $validated = admin_validate_area_payload($_POST);
        if (!$validated['ok']) {
            admin_redirect_error($validated['message'], admin_url('areas.php'));
        }
        $data = $validated['data'];
        area_save($data['id'], $data['titulo'], $data['texto'], $data['href'], $data['sort_order']);
        admin_flash('Área guardada.');
        header('Location: ' . admin_url('areas.php'));
        break;

    case 'area_delete':
        area_delete((int) ($_POST['id'] ?? 0));
        admin_flash('Área eliminada.');
        header('Location: ' . admin_url('areas.php'));
        break;

    case 'settings_save':
        $validated = admin_validate_settings_payload($_POST);
        if (!$validated['ok']) {
            admin_redirect_error($validated['message'], admin_url('config.php'));
        }
        settings_save($validated['data']['settings']);
        $newPass = $validated['data']['new_password'];
        if ($newPass !== '') {
            admin_change_password(admin_user(), $newPass);
            admin_logout();
            admin_flash('Configuración y contraseña actualizadas. Inicia sesión nuevamente.', 'success');
            header('Location: ' . admin_url('login.php'));
            exit;
        }
        admin_flash('Configuración guardada.');
        header('Location: ' . admin_url('config.php'));
        break;

    case 'customer_verify':
        $id = (int) ($_POST['id'] ?? 0);
        $customer = $id > 0 ? customer_get($id) : null;
        if (!$customer) {
            admin_redirect_error('No se encontró esa cuenta.', admin_url('clientes.php'));
        }
        customer_mark_verified($id);
        admin_flash('Cuenta activada: ' . (string) $customer['email'] . '. Ya puede iniciar sesión.');
        header('Location: ' . admin_url('clientes.php'));
        break;

    case 'customer_resend':
        require_once __DIR__ . '/../includes/customer-auth.php';
        $id = (int) ($_POST['id'] ?? 0);
        $customer = $id > 0 ? customer_get($id) : null;
        if (!$customer || !empty($customer['email_verified_at'])) {
            admin_redirect_error('Esa cuenta no está pendiente de verificación.', admin_url('clientes.php'));
        }
        $url = customer_verification_url($id);
        $sent = $url !== null && send_customer_email(
            (string) $customer['email'],
            'Verifica tu cuenta — Hotel Expert',
            'Verifica tu correo',
            'Confirma tu correo para acceder a tus pedidos, rastreo y recompra.',
            'Verificar mi cuenta',
            $url
        );
        if ($sent) {
            admin_flash('Correo reenviado a ' . (string) $customer['email'] . '.');
        } elseif ($url) {
            admin_flash('El correo no salió. Enlace manual (válido 24 h): ' . $url, 'warning');
        } else {
            admin_redirect_error('No se pudo generar el enlace de verificación.', admin_url('clientes.php'));
        }
        header('Location: ' . admin_url('clientes.php'));
        break;

    case 'totp_start':
        if (admin_totp_is_enabled(admin_user())) {
            admin_redirect_error('La verificación en dos pasos ya está activa.', admin_url('config.php'));
        }
        admin_totp_begin_setup();
        admin_flash('Escanea el código QR y confirma con un código de 6 dígitos.');
        header('Location: ' . admin_url('config.php'));
        break;

    case 'totp_cancel':
        admin_totp_clear_setup();
        unset($_SESSION['admin_totp_recovery_plain']);
        admin_flash('Activación de 2FA cancelada.');
        header('Location: ' . admin_url('config.php'));
        break;

    case 'totp_confirm':
        $setupSecret = admin_totp_setup_secret();
        $password = (string) ($_POST['confirm_password'] ?? '');
        $code = (string) ($_POST['totp_code'] ?? '');
        if ($setupSecret === '') {
            admin_redirect_error('La activación expiró. Vuelve a iniciar el proceso.', admin_url('config.php'));
        }
        if (!admin_verify(admin_user(), $password)) {
            admin_redirect_error('La contraseña actual no es correcta.', admin_url('config.php'));
        }
        if (totp_verify($setupSecret, $code) === null) {
            admin_redirect_error('El código de 6 dígitos no es válido. Revisa la hora de tu teléfono e inténtalo de nuevo.', admin_url('config.php'));
        }
        $recovery = totp_recovery_codes();
        admin_totp_enable(admin_user(), $setupSecret, $recovery);
        admin_totp_clear_setup();
        $_SESSION['admin_totp_recovery_plain'] = $recovery;
        admin_flash('2FA activado. Guarda los códigos de recuperación en un lugar seguro.');
        header('Location: ' . admin_url('config.php'));
        break;

    case 'totp_ack_recovery':
        unset($_SESSION['admin_totp_recovery_plain']);
        admin_flash('Códigos de recuperación confirmados.');
        header('Location: ' . admin_url('config.php'));
        break;

    case 'totp_recovery_regenerate':
        if (!admin_totp_is_enabled(admin_user())) {
            admin_redirect_error('Activa 2FA antes de generar códigos de recuperación.', admin_url('config.php'));
        }
        if (!admin_verify(admin_user(), (string) ($_POST['recovery_password'] ?? ''))) {
            admin_redirect_error('La contraseña actual no es correcta.', admin_url('config.php'));
        }
        if (!admin_totp_consume(admin_user(), (string) ($_POST['totp_code'] ?? ''))) {
            admin_redirect_error('El código de verificación no es válido.', admin_url('config.php'));
        }
        $recovery = totp_recovery_codes();
        admin_totp_replace_recovery(admin_user(), $recovery);
        $_SESSION['admin_totp_recovery_plain'] = $recovery;
        admin_flash('Se generaron códigos de recuperación nuevos. Los anteriores ya no sirven.');
        header('Location: ' . admin_url('config.php'));
        break;

    case 'totp_disable':
        if (!admin_totp_is_enabled(admin_user())) {
            admin_flash('La verificación en dos pasos ya estaba desactivada.');
            header('Location: ' . admin_url('config.php'));
            break;
        }
        if (!admin_verify(admin_user(), (string) ($_POST['disable_password'] ?? ''))) {
            admin_redirect_error('La contraseña actual no es correcta.', admin_url('config.php'));
        }
        if (!admin_totp_consume(admin_user(), (string) ($_POST['disable_code'] ?? ''))) {
            admin_redirect_error('El código de verificación no es válido.', admin_url('config.php'));
        }
        admin_totp_disable(admin_user());
        admin_totp_clear_setup();
        unset($_SESSION['admin_totp_recovery_plain']);
        admin_flash('La verificación en dos pasos quedó desactivada.');
        header('Location: ' . admin_url('config.php'));
        break;

    case 'deployment_test_db':
        require_once __DIR__ . '/../includes/env-file.php';
        $validated = admin_validate_deploy_db_payload($_POST);
        if (!$validated['ok']) {
            admin_redirect_error($validated['message'], admin_url('deploy.php'));
        }
        if (!env_file_test_database($validated['data'])) {
            admin_redirect_error('No se pudo conectar a la base de datos con esas credenciales.', admin_url('deploy.php'));
        }
        admin_flash('Conexión a la base de datos correcta.');
        header('Location: ' . admin_url('deploy.php'));
        break;

    case 'deployment_test_mail':
        require_once __DIR__ . '/../includes/mailer.php';
        [$fromEmail] = mailer_from();
        $ok = send_customer_email(
            $fromEmail,
            'Prueba de correo — Hotel Expert',
            'El correo del sitio funciona',
            'Si lees este mensaje, Hotel Expert ya puede enviar correos desde el servidor.',
            'Ir al panel',
            rtrim(env('APP_URL', SITE_ORIGIN), '/') . '/admin/deploy.php'
        );
        if ($ok) {
            admin_flash('Correo de prueba enviado a ' . $fromEmail . '. Revisa bandeja de entrada y spam.');
        } else {
            admin_redirect_error('No se pudo enviar el correo de prueba. Revisa usuario, contraseña y servidor SMTP.', admin_url('deploy.php'));
        }
        header('Location: ' . admin_url('deploy.php'));
        break;

    case 'deployment_save':
        require_once __DIR__ . '/../includes/env-file.php';
        require_once __DIR__ . '/../includes/htaccess-deploy.php';
        $validated = admin_validate_deploy_payload($_POST, true);
        if (!$validated['ok']) {
            admin_redirect_error($validated['message'], admin_url('deploy.php'));
        }
        $data = $validated['data'];
        if (!env_file_test_database($data)) {
            admin_redirect_error('No se pudo conectar a la base de datos. Corrige las credenciales antes de guardar.', admin_url('deploy.php'));
        }
        try {
            $envBackup = env_file_update($data);
            $htBackup = htaccess_deploy_apply($data);
            $domain = (string) ($data['site_domain'] ?? '');
            if ($domain !== '') {
                settings_save(['site_domain' => $domain]);
            }
        } catch (Throwable $e) {
            admin_redirect_error('No se pudo guardar el despliegue: ' . $e->getMessage(), admin_url('deploy.php'));
        }
        $message = 'Despliegue guardado.';
        if ($envBackup !== '' || $htBackup !== '') {
            $parts = array_filter([$envBackup !== '' ? basename($envBackup) : '', $htBackup !== '' ? basename($htBackup) : '']);
            if ($parts !== []) {
                $message .= ' Respaldo: ' . implode(', ', $parts) . '.';
            }
        }
        admin_flash($message);
        header('Location: ' . admin_url('deploy.php'));
        break;

    case 'stripe_test':
        require_once __DIR__ . '/../includes/stripe-config.php';
        $validated = admin_validate_stripe_payload($_POST, false);
        if (!$validated['ok']) {
            admin_redirect_error($validated['message'], admin_url('stripe.php'));
        }
        if (!stripe_test_connection($validated['data'])) {
            admin_redirect_error('No se pudo conectar con Stripe. Verifica el modo y la clave secreta.', admin_url('stripe.php'));
        }
        admin_flash('Conexión con Stripe correcta.');
        header('Location: ' . admin_url('stripe.php'));
        break;

    case 'stripe_save':
        require_once __DIR__ . '/../includes/stripe-config.php';
        $validated = admin_validate_stripe_payload($_POST, true);
        if (!$validated['ok']) {
            admin_redirect_error($validated['message'], admin_url('stripe.php'));
        }
        if (filter_var($validated['data']['STRIPE_ENABLED'], FILTER_VALIDATE_BOOLEAN) && !stripe_test_connection($validated['data'])) {
            admin_redirect_error('Stripe está activo pero la clave secreta no responde. Corrige las credenciales antes de guardar.', admin_url('stripe.php'));
        }
        try {
            $envData = $validated['data'];
            $ivaRate = (string) $envData['checkout_iva_rate'];
            unset($envData['checkout_iva_rate']);
            $backup = env_file_update($envData, ['STRIPE_SECRET_KEY', 'STRIPE_WEBHOOK_SECRET']);
            settings_save(['checkout_iva_rate' => $ivaRate]);
        } catch (Throwable $e) {
            admin_redirect_error('No se pudo guardar Stripe: ' . $e->getMessage(), admin_url('stripe.php'));
        }
        $message = 'Configuración de Stripe guardada.';
        if ($backup !== '') {
            $message .= ' Respaldo: ' . basename($backup) . '.';
        }
        admin_flash($message);
        header('Location: ' . admin_url('stripe.php'));
        break;

    default:
        admin_flash('Acción no reconocida.', 'error');
        header('Location: ' . admin_url('index.php'));
}

exit;
