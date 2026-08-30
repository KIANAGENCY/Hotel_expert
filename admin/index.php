<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

$stats = admin_stats();
$leads = leads_all();
$posts = blog_all();

admin_layout_start('Dashboard', 'index');

$actions = '<a class="admin-btn admin-btn-primary" href="' . e(admin_url('post.php')) . '"><i class="fa-solid fa-plus"></i> Nuevo artículo</a>';
admin_page_header('Centro de gestión', 'Dashboard', 'Administra leads, catálogo, pedidos y contenido del Sistema ELAH desde un solo lugar.', $actions);

$statCards = [
    ['fa-inbox', 'Leads nuevos', (string) $stats['leads_nuevos'], admin_url('leads.php')],
    ['fa-box', 'Productos', (string) $stats['productos'], admin_url('productos.php')],
    ['fa-truck-fast', 'Pedidos', (string) $stats['pedidos'], admin_url('pedidos.php')],
    ['fa-newspaper', 'Artículos', (string) $stats['blog'], admin_url('blog.php')],
];
?>
<div class="admin-stat-grid">
    <?php foreach ($statCards as [$icon, $label, $value, $href]): ?>
    <a href="<?= e($href) ?>" class="admin-card admin-stat" style="text-decoration:none;color:inherit">
        <span class="admin-stat-icon"><i class="fa-solid <?= e($icon) ?>"></i></span>
        <p class="admin-stat-label"><?= e($label) ?></p>
        <p class="admin-stat-value"><?= e($value) ?></p>
    </a>
    <?php endforeach; ?>
</div>

<div class="admin-dash-grid">
    <div class="admin-card admin-card-pad">
        <h2 class="admin-card-title admin-card-title-spaced">Actividad reciente</h2>
        <div>
            <?php if ($leads === []): ?>
                <?php admin_empty_state('fa-inbox', 'Sin actividad aún', 'Cuando lleguen solicitudes de contacto o cotización aparecerán aquí.'); ?>
            <?php else: ?>
                <?php foreach (array_slice($leads, 0, 5) as $lead): ?>
                <div class="admin-activity-item">
                    <span class="admin-activity-icon"><i class="fa-solid fa-inbox"></i></span>
                    <div>
                        <p class="admin-activity-text">Nuevo lead de <strong><?= e($lead['hotel']) ?></strong> — <?= e($lead['nombre']) ?></p>
                        <p class="admin-activity-time"><?= e(date('d M Y, H:i', strtotime($lead['fecha']))) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="admin-side-stack">
        <div class="admin-card admin-card-pad">
            <h2 class="admin-card-title admin-card-title-spaced">Estado del pipeline</h2>
            <div class="admin-health-row">
                <span class="admin-health-dot" style="background:<?= $stats['leads_nuevos'] > 0 ? 'var(--admin-warning)' : 'var(--admin-success)' ?>"></span>
                <span class="admin-health-label"><?= $stats['leads_nuevos'] > 0 ? 'Leads por atender' : 'Al día' ?></span>
            </div>
            <div class="admin-health-list">
                <div><span>Nuevos</span><span class="admin-health-accent"><?= (int) $stats['leads_nuevos'] ?></span></div>
                <div><span>Total leads</span><span class="admin-health-strong"><?= (int) $stats['leads_total'] ?></span></div>
                <div><span>Productos activos</span><span class="admin-health-success"><?= (int) $stats['productos'] ?></span></div>
            </div>
            <a class="admin-btn admin-btn-outline admin-btn-block" href="<?= e(admin_url('leads.php')) ?>">Ver leads</a>
        </div>

        <div class="admin-card admin-card-pad">
            <h2 class="admin-card-title admin-card-title-spaced-sm">Últimos artículos</h2>
            <?php if ($posts === []): ?>
                <p class="admin-upcoming-title admin-text-muted">Sin artículos publicados.</p>
            <?php else: ?>
                <?php foreach (array_slice($posts, 0, 3) as $post): ?>
                <div class="admin-upcoming-item">
                    <p class="admin-upcoming-title"><?= e($post['titulo']) ?></p>
                    <p class="admin-upcoming-date"><?= e($post['fecha']) ?> · <?= e($post['categoria']) ?></p>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="admin-card admin-card-mt">
    <div class="admin-card-head">
        <h2 class="admin-card-title">Últimos leads</h2>
        <a class="admin-btn admin-btn-outline admin-btn-sm" href="<?= e(admin_url('leads.php')) ?>">Ver todos</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Fecha</th><th>Hotel</th><th>Contacto</th><th>Estado</th></tr></thead>
            <tbody>
            <?php if ($leads === []): ?>
                <tr class="admin-table-empty"><td colspan="4"><?php admin_empty_state('fa-inbox', 'Sin leads', 'Los contactos del sitio aparecerán aquí automáticamente.'); ?></td></tr>
            <?php endif; ?>
            <?php foreach (array_slice($leads, 0, 8) as $lead): ?>
                <tr>
                    <td><?= e(date('d/m/Y H:i', strtotime($lead['fecha']))) ?></td>
                    <td><a href="<?= e(admin_url('lead.php?id=' . $lead['id'])) ?>"><?= e($lead['hotel']) ?></a></td>
                    <td><?= e($lead['nombre']) ?><div class="admin-table-meta"><?= e($lead['email']) ?></div></td>
                    <td><span class="badge badge-<?= e($lead['estado']) ?>"><?= e(admin_estados_lead()[$lead['estado']] ?? $lead['estado']) ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php admin_layout_end(); ?>
