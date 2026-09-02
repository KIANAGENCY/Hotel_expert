<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/admin-auth.php';
require_once __DIR__ . '/../includes/env-file.php';
require_once __DIR__ . '/../includes/htaccess-deploy.php';
admin_require_login();
require __DIR__ . '/includes/layout.php';

$d = env_file_deploy_defaults();
$piiEncryption = env_file_secret_is_set('PII_ENCRYPTION_KEY');
$piiBlind = env_file_secret_is_set('PII_BLIND_INDEX_KEY');
$envBackup = env_file_latest_backup_basename(env_file_path());
$htaccessBackup = env_file_latest_backup_basename(htaccess_deploy_path());

admin_layout_start('Despliegue y servidor', 'deploy');

admin_page_header('Sistema', 'Despliegue y servidor', 'Configura dominio, base de datos, correo y reglas Apache para producción.');
?>
<form method="post" action="<?= e(admin_url('action.php')) ?>" class="admin-card admin-form" style="max-width:760px">
    <input type="hidden" name="csrf" value="<?= e(admin_csrf()) ?>">

    <h2 class="admin-section-title">Dominio y entorno</h2>
    <?php admin_field('URL pública', 'APP_URL', $d['APP_URL']); ?>
    <?php admin_select('Entorno', 'APP_ENV', ['local' => 'Local / desarrollo', 'production' => 'Producción'], $d['APP_ENV']); ?>
    <label class="admin-label admin-checkbox-row">
        <input type="checkbox" name="TRUST_PROXY_HEADERS" value="1" <?= filter_var($d['TRUST_PROXY_HEADERS'], FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' ?>>
        <span>Confiar cabeceras de proxy (X-Forwarded-Proto)</span>
    </label>
    <?php admin_field('Host canónico', 'DEPLOY_CANONICAL_HOST', $d['DEPLOY_CANONICAL_HOST']); ?>
    <label class="admin-label admin-checkbox-row">
        <input type="checkbox" name="DEPLOY_FORCE_HTTPS" value="1" <?= filter_var($d['DEPLOY_FORCE_HTTPS'], FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' ?>>
        <span>Forzar HTTPS en Apache</span>
    </label>
    <?php admin_field('Subcarpeta (RewriteBase)', 'DEPLOY_REWRITE_BASE', $d['DEPLOY_REWRITE_BASE']); ?>
    <p style="font-size:13px;color:var(--admin-text-muted);margin:0 0 16px">Solo si el sitio no está en la raíz del dominio. Ejemplo: <code>hotel</code> para <code>/hotel/</code>.</p>

    <h2 class="admin-section-title">Base de datos</h2>
    <?php admin_field('Host', 'DB_HOST', $d['DB_HOST']); ?>
    <?php admin_field('Puerto', 'DB_PORT', $d['DB_PORT'], 'number'); ?>
    <?php admin_field('Base de datos', 'DB_DATABASE', $d['DB_DATABASE']); ?>
    <?php admin_field('Usuario', 'DB_USERNAME', $d['DB_USERNAME']); ?>
    <?php admin_field('Contraseña' . (env_file_secret_is_set('DB_PASSWORD') ? ' (configurada — vacío = mantener)' : ''), 'DB_PASSWORD', '', 'password'); ?>

    <h2 class="admin-section-title">Correo SMTP</h2>
    <?php admin_field('Servidor SMTP', 'SMTP_HOST', $d['SMTP_HOST']); ?>
    <?php admin_field('Puerto SMTP', 'SMTP_PORT', $d['SMTP_PORT'], 'number'); ?>
    <?php admin_field('Usuario SMTP', 'SMTP_USERNAME', $d['SMTP_USERNAME']); ?>
    <?php admin_field('Contraseña SMTP' . (env_file_secret_is_set('SMTP_PASSWORD') ? ' (configurada — vacío = mantener)' : ''), 'SMTP_PASSWORD', '', 'password'); ?>
    <?php admin_select('Cifrado', 'SMTP_ENCRYPTION', ['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'Sin cifrado'], $d['SMTP_ENCRYPTION']); ?>
    <?php admin_field('Correo remitente', 'SMTP_FROM_EMAIL', $d['SMTP_FROM_EMAIL'], 'email'); ?>
    <?php admin_field('Nombre remitente', 'SMTP_FROM_NAME', $d['SMTP_FROM_NAME']); ?>

    <h2 class="admin-section-title">Sesiones</h2>
    <?php admin_field('Inactividad admin (segundos)', 'ADMIN_SESSION_IDLE_SECONDS', $d['ADMIN_SESSION_IDLE_SECONDS'], 'number'); ?>
    <?php admin_field('Duración máxima admin (segundos)', 'ADMIN_SESSION_ABSOLUTE_SECONDS', $d['ADMIN_SESSION_ABSOLUTE_SECONDS'], 'number'); ?>
    <?php admin_field('Inactividad portal cliente (segundos)', 'CUSTOMER_SESSION_IDLE_SECONDS', $d['CUSTOMER_SESSION_IDLE_SECONDS'], 'number'); ?>
    <?php admin_field('Duración máxima portal cliente (segundos)', 'CUSTOMER_SESSION_ABSOLUTE_SECONDS', $d['CUSTOMER_SESSION_ABSOLUTE_SECONDS'], 'number'); ?>

    <h2 class="admin-section-title">Estado de seguridad</h2>
    <div class="admin-deploy-status">
        <p>
            <span class="admin-badge <?= $piiEncryption ? 'admin-badge-success' : 'admin-badge-warning' ?>">
                PII_ENCRYPTION_KEY: <?= $piiEncryption ? 'configurada' : 'pendiente' ?>
            </span>
        </p>
        <p>
            <span class="admin-badge <?= $piiBlind ? 'admin-badge-success' : 'admin-badge-warning' ?>">
                PII_BLIND_INDEX_KEY: <?= $piiBlind ? 'configurada' : 'pendiente' ?>
            </span>
        </p>
        <p style="font-size:13px;color:var(--admin-text-muted);margin:8px 0 0">
            Las claves PII solo se rotan por CLI (<code>tools/encrypt-existing-pii.php</code>).
            <?php if ($envBackup !== '' || $htaccessBackup !== ''): ?>
                Último respaldo:
                <?php if ($envBackup !== ''): ?><code><?= e($envBackup) ?></code><?php endif; ?>
                <?php if ($htaccessBackup !== ''): ?><code><?= e($htaccessBackup) ?></code><?php endif; ?>
            <?php else: ?>
                Aún no hay respaldos automáticos.
            <?php endif; ?>
        </p>
    </div>

    <h2 class="admin-section-title">Confirmar cambios</h2>
    <?php admin_field('Contraseña de administrador', 'admin_password', '', 'password'); ?>
    <p style="font-size:13px;color:var(--admin-text-muted);margin:0 0 16px">Obligatoria para guardar. Se crearán respaldos de <code>.env</code> y <code>.htaccess</code> antes de escribir.</p>

    <div class="admin-form-actions" style="display:flex;gap:12px;flex-wrap:wrap">
        <button class="admin-btn admin-btn-secondary" type="submit" name="action" value="deployment_test_db">Probar conexión DB</button>
        <button class="admin-btn admin-btn-primary" type="submit" name="action" value="deployment_save">Guardar despliegue</button>
    </div>
</form>
<?php admin_layout_end(); ?>
