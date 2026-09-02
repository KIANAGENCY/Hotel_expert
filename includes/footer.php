<?php
declare(strict_types=1);
?>
<footer class="site-footer bg-expert text-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:py-20">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <img src="<?= e(url('assets/img/logo-light.svg?v=white-blue-large-3')) ?>" alt="Hotel Expert" class="h-16 w-auto" width="193" height="64">
                <p class="mt-7 eyebrow text-aqua">Sistema ELAH</p>
                <p class="mt-3 max-w-md font-heading text-xl font-bold leading-snug"><?= e(SITE_TAGLINE) ?>.</p>
                <p class="mt-5 text-white/65"><?= e(SITE_CLAIM) ?></p>
            </div>
            <nav class="lg:col-span-4" aria-label="Navegación del pie">
                <p class="footer-title">Navegación</p>
                <ul class="mt-5 grid gap-x-6 gap-y-3 sm:grid-cols-2">
                    <?php foreach ($nav as [$label, $href]): ?>
                        <li><a class="footer-link" href="<?= e(url($href)) ?>"><?= e($label) ?></a></li>
                    <?php endforeach; ?>
                    <li><a class="footer-link" href="<?= e(url('blog/')) ?>">Blog</a></li>
                    <li><a class="footer-link" href="<?= e(url('manual-de-uso/')) ?>">Manual de uso</a></li>
                    <li><a class="footer-link" href="<?= e(url('cuenta/')) ?>">Mi cuenta</a></li>
                    <li><a class="footer-link" href="<?= e(url('rastreo/')) ?>">Rastrear pedido</a></li>
                </ul>
            </nav>
            <div class="lg:col-span-3">
                <a href="<?= e(url('muestra/')) ?>" class="btn-primary w-full justify-center sm:w-auto">Solicitar muestra</a>
                <div class="mt-7 space-y-3">
                    <a class="footer-contact" href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener noreferrer" aria-label="Contactar a Hotel Expert por WhatsApp">WhatsApp</a>
                    <a class="footer-contact" href="mailto:<?= e(site_email()) ?>">Correo</a>
                </div>
                <?php if (!empty($social['facebook']) || !empty($social['instagram'])): ?>
                <div class="mt-6 flex gap-4" aria-label="Redes sociales">
                    <?php if (!empty($social['facebook'])): ?><a href="<?= e($social['facebook']) ?>" target="_blank" rel="noopener noreferrer" aria-label="Hotel Expert en Facebook">Facebook</a><?php endif; ?>
                    <?php if (!empty($social['instagram'])): ?><a href="<?= e($social['instagram']) ?>" target="_blank" rel="noopener noreferrer" aria-label="Hotel Expert en Instagram">Instagram</a><?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-12 flex flex-col gap-3 border-t border-white/10 pt-6 text-sm text-white/50 sm:flex-row sm:items-center sm:justify-between">
            <p>© <?= date('Y') ?> Hotel Expert</p>
            <p><?= e(SITE_CLAIM) ?></p>
        </div>
    </div>
</footer>

<a href="<?= e(whatsapp_url()) ?>" class="wa-fab" target="_blank" rel="noopener noreferrer" aria-label="Escribir a Hotel Expert por WhatsApp">
    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.5 3.5A11 11 0 0 0 2.1 17.4L1 23l5.8-1.1A11 11 0 0 0 12 23a11 11 0 0 0 8.5-19.5zM12 21a9 9 0 0 1-4.6-1.3l-.3-.2-3.4.6.7-3.3-.2-.3A9 9 0 1 1 12 21zm5-6.7c-.3-.1-1.6-.8-1.9-.9s-.4-.1-.6.1-.7.9-.8 1-.3.2-.6.1a7.4 7.4 0 0 1-2.2-1.4 8.2 8.2 0 0 1-1.5-1.9c-.2-.3 0-.4.1-.6l.4-.5.1-.3a.5.5 0 0 0 0-.5c0-.1-.6-1.4-.8-1.9s-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.9 11.9 0 0 0 4.6 4.1 15 15 0 0 0 1.5.6 3.6 3.6 0 0 0 1.6.1 2.7 2.7 0 0 0 1.8-1.3 2.2 2.2 0 0 0 .1-1.3c-.1-.1-.3-.2-.6-.3z"/></svg>
</a>
<?php
require_once __DIR__ . '/stripe-config.php';
require_once __DIR__ . '/cart-pricing.php';
$cartProducts = productos_all();
$cartData = [];
foreach ($cartProducts as $slug => $product) {
    $cartData[$slug] = [
        'slug' => $slug,
        'nombre' => $product['nombre'],
        'precio' => $product['precio'],
        'precio_texto' => $product['precio_texto'],
        'presentacion' => $product['presentacion'],
        'imagen' => $product['imagen'],
        'icono' => $product['icono'],
        'iva' => !empty($product['iva']),
    ];
}
$stripeReady = stripe_is_enabled() && stripe_status_summary()['ready'];
?>
<script>
window.ELAH_BASE = <?= json_encode(BASE_URL, JSON_UNESCAPED_SLASHES) ?>;
window.ELAH_WHATSAPP = <?= json_encode(site_whatsapp()) ?>;
window.ELAH_PRODUCTS = <?= json_encode($cartData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
window.ELAH_CART = {
  ivaRate: <?= json_encode(checkout_iva_rate()) ?>,
  stripeEnabled: <?= $stripeReady ? 'true' : 'false' ?>
};
</script>
<script src="<?= e(url('assets/js/main.js')) ?>?v=<?= (int) filemtime(ROOT_PATH . '/assets/js/main.js') ?>" defer></script>
</body>
</html>
