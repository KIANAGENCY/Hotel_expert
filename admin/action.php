<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !admin_csrf_ok($_POST['csrf'] ?? null)) {
    admin_flash('Sesión inválida. Intenta de nuevo.', 'error');
    header('Location: ' . admin_url('index.php'));
    exit;
}

$action = (string) ($_POST['action'] ?? '');

switch ($action) {
    case 'lead_update':
        lead_update((int) ($_POST['id'] ?? 0), (string) ($_POST['estado'] ?? 'nuevo'), trim((string) ($_POST['notas'] ?? '')));
        admin_flash('Lead actualizado.');
        header('Location: ' . admin_url('lead.php?id=' . (int) $_POST['id']));
        break;

    case 'lead_delete':
        lead_delete((int) ($_POST['id'] ?? 0));
        admin_flash('Lead eliminado.');
        header('Location: ' . admin_url('leads.php'));
        break;

    case 'product_save':
        $slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) ($_POST['slug'] ?? '')));
        if ($slug === '') {
            admin_flash('Slug inválido.', 'error');
            header('Location: ' . admin_url('productos.php'));
            exit;
        }
        $claims = array_values(array_filter(array_map('trim', explode("\n", (string) ($_POST['claims'] ?? '')))));
        $superficies = array_values(array_filter(array_map('trim', explode("\n", (string) ($_POST['superficies'] ?? '')))));
        $noUsar = array_values(array_filter(array_map('trim', explode("\n", (string) ($_POST['no_usar'] ?? '')))));
        producto_save($slug, [
            'slug' => $slug,
            'sku' => trim((string) ($_POST['sku'] ?? '')),
            'nombre' => trim((string) ($_POST['nombre'] ?? '')),
            'categoria' => trim((string) ($_POST['categoria'] ?? '')),
            'subtitulo' => trim((string) ($_POST['subtitulo'] ?? '')),
            'resumen' => trim((string) ($_POST['resumen'] ?? '')),
            'presentacion' => trim((string) ($_POST['presentacion'] ?? '')),
            'rendimiento' => trim((string) ($_POST['rendimiento'] ?? '')),
            'precio' => max(0, (int) ($_POST['precio'] ?? 0)),
            'precio_texto' => trim((string) ($_POST['precio_texto'] ?? '')),
            'precio_lista' => trim((string) ($_POST['precio_lista'] ?? '')) ?: null,
            'iva' => !empty($_POST['iva']),
            'imagen' => trim((string) ($_POST['imagen'] ?? '')) ?: null,
            'alt' => trim((string) ($_POST['alt'] ?? '')),
            'icono' => trim((string) ($_POST['icono'] ?? '')),
            'funcion' => trim((string) ($_POST['funcion'] ?? '')),
            'especialidad' => trim((string) ($_POST['especialidad'] ?? '')),
            'claims' => $claims,
            'superficies' => $superficies,
            'no_usar' => $noUsar,
        ], (int) ($_POST['sort_order'] ?? 0));
        admin_flash('Producto guardado.');
        header('Location: ' . admin_url('producto.php?slug=' . rawurlencode($slug)));
        break;

    case 'product_delete':
        producto_delete(preg_replace('/[^a-z0-9-]/', '', (string) ($_POST['slug'] ?? '')));
        admin_flash('Producto eliminado.');
        header('Location: ' . admin_url('productos.php'));
        break;

    case 'post_save':
        $slug = preg_replace('/[^a-z0-9-]/', '', strtolower((string) ($_POST['slug'] ?? '')));
        $cuerpo = array_values(array_filter(array_map('trim', explode("\n\n", (string) ($_POST['cuerpo'] ?? '')))));
        blog_save($slug, [
            'slug' => $slug,
            'titulo' => trim((string) ($_POST['titulo'] ?? '')),
            'seo_titulo' => trim((string) ($_POST['seo_titulo'] ?? '')),
            'meta_descripcion' => trim((string) ($_POST['meta_descripcion'] ?? '')),
            'bajada' => trim((string) ($_POST['bajada'] ?? '')),
            'extracto' => trim((string) ($_POST['extracto'] ?? '')),
            'categoria' => trim((string) ($_POST['categoria'] ?? '')),
            'fecha' => trim((string) ($_POST['fecha'] ?? date('Y-m-d'))),
            'lectura' => trim((string) ($_POST['lectura'] ?? '4 min')),
            'cover' => trim((string) ($_POST['cover'] ?? '')),
            'cuerpo' => $cuerpo,
        ], (int) ($_POST['sort_order'] ?? 0));
        admin_flash('Artículo guardado.');
        header('Location: ' . admin_url('post.php?slug=' . rawurlencode($slug)));
        break;

    case 'post_delete':
        blog_delete(preg_replace('/[^a-z0-9-]/', '', (string) ($_POST['slug'] ?? '')));
        admin_flash('Artículo eliminado.');
        header('Location: ' . admin_url('blog.php'));
        break;

    case 'order_save':
        $id = strtoupper(trim((string) ($_POST['id'] ?? '')));
        pedido_save([
            'id' => $id,
            'email' => trim((string) ($_POST['email'] ?? '')),
            'hotel' => trim((string) ($_POST['hotel'] ?? '')),
            'estado' => (string) ($_POST['estado'] ?? 'procesando'),
            'fecha' => trim((string) ($_POST['fecha'] ?? '')),
            'eta' => trim((string) ($_POST['eta'] ?? '')),
            'items' => trim((string) ($_POST['items'] ?? '')),
            'guia' => trim((string) ($_POST['guia'] ?? '')),
        ]);
        admin_flash('Pedido guardado.');
        header('Location: ' . admin_url('pedido.php?id=' . rawurlencode($id)));
        break;

    case 'order_delete':
        pedido_delete(strtoupper(trim((string) ($_POST['id'] ?? ''))));
        admin_flash('Pedido eliminado.');
        header('Location: ' . admin_url('pedidos.php'));
        break;

    case 'faq_save':
        faq_save(
            ((int) ($_POST['id'] ?? 0)) ?: null,
            trim((string) ($_POST['pregunta'] ?? '')),
            trim((string) ($_POST['respuesta'] ?? '')),
            (int) ($_POST['sort_order'] ?? 0)
        );
        admin_flash('FAQ guardada.');
        header('Location: ' . admin_url('faq.php'));
        break;

    case 'faq_delete':
        faq_delete((int) ($_POST['id'] ?? 0));
        admin_flash('FAQ eliminada.');
        header('Location: ' . admin_url('faq.php'));
        break;

    case 'area_save':
        area_save(
            ((int) ($_POST['id'] ?? 0)) ?: null,
            trim((string) ($_POST['titulo'] ?? '')),
            trim((string) ($_POST['texto'] ?? '')),
            trim((string) ($_POST['href'] ?? '')),
            (int) ($_POST['sort_order'] ?? 0)
        );
        admin_flash('Área guardada.');
        header('Location: ' . admin_url('areas.php'));
        break;

    case 'area_delete':
        area_delete((int) ($_POST['id'] ?? 0));
        admin_flash('Área eliminada.');
        header('Location: ' . admin_url('areas.php'));
        break;

    case 'settings_save':
        settings_save([
            'site_name' => trim((string) ($_POST['site_name'] ?? '')),
            'site_tagline' => trim((string) ($_POST['site_tagline'] ?? '')),
            'site_claim' => trim((string) ($_POST['site_claim'] ?? '')),
            'site_domain' => trim((string) ($_POST['site_domain'] ?? '')),
            'whatsapp' => preg_replace('/\D/', '', (string) ($_POST['whatsapp'] ?? '')),
            'whatsapp_display' => trim((string) ($_POST['whatsapp_display'] ?? '')),
            'email_ventas' => trim((string) ($_POST['email_ventas'] ?? '')),
            'social_facebook' => trim((string) ($_POST['social_facebook'] ?? '')),
            'social_instagram' => trim((string) ($_POST['social_instagram'] ?? '')),
        ]);
        if ($newPass = (string) ($_POST['new_password'] ?? '')) {
            if (strlen($newPass) >= 8) {
                admin_change_password(admin_user(), $newPass);
                admin_flash('Configuración y contraseña actualizadas.');
            } else {
                admin_flash('Configuración guardada. La contraseña debe tener al menos 8 caracteres.', 'error');
            }
        } else {
            admin_flash('Configuración guardada.');
        }
        header('Location: ' . admin_url('config.php'));
        break;

    default:
        admin_flash('Acción no reconocida.', 'error');
        header('Location: ' . admin_url('index.php'));
}

exit;
