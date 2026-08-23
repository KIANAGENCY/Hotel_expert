<?php
declare(strict_types=1);
?>
<footer class="relative bg-expert text-white overflow-hidden">
    <div class="absolute inset-0 opacity-40 bg-brand pointer-events-none"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 py-16 lg:py-20">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <div class="flex items-center gap-3 mb-6">
                    <img src="<?= e(url('assets/img/logo-mark-256.png')) ?>" alt="Hotel Expert" class="h-12 w-12 object-contain" width="48" height="48">
                    <span class="leading-none">
                        <span class="block font-heading font-extrabold text-[0.7rem] tracking-[0.24em] text-white/90">HOTEL</span>
                        <span class="block font-heading font-extrabold text-[1.05rem] tracking-[0.06em] text-aqua mt-0.5">EXPERT</span>
                    </span>
                </div>
                <p class="text-2xl font-heading font-bold leading-snug max-w-md">Sistema ELAH</p>
                <p class="mt-2 text-aqua font-heading font-semibold"><?= e(SITE_TAGLINE) ?></p>
                <p class="mt-4 text-white/70 max-w-md">Limpieza, desinfección y aroma insignia bajo una sola identidad olfativa para tu hotel.</p>
                <a href="https://wa.me/<?= WHATSAPP ?>?text=<?= WHATSAPP_MSG ?>" target="_blank" rel="noopener" class="mt-6 inline-flex items-center gap-2 rounded-full bg-[#25D366] px-5 py-2.5 text-sm font-heading font-semibold text-expert hover:brightness-110">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20.5 3.5A11 11 0 0 0 2.1 17.4L1 23l5.8-1.1A11 11 0 0 0 12 23a11 11 0 0 0 8.5-19.5zM12 21a9 9 0 0 1-4.6-1.3l-.3-.2-3.4.6.7-3.3-.2-.3A9 9 0 1 1 12 21zm5-6.7c-.3-.1-1.6-.8-1.9-.9s-.4-.1-.6.1-.7.9-.8 1-.3.2-.6.1a7.4 7.4 0 0 1-2.2-1.4 8.2 8.2 0 0 1-1.5-1.9c-.2-.3 0-.4.1-.6l.4-.5.1-.3a.5.5 0 0 0 0-.5c0-.1-.6-1.4-.8-1.9s-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.9 11.9 0 0 0 4.6 4.1 15 15 0 0 0 1.5.6 3.6 3.6 0 0 0 1.6.1 2.7 2.7 0 0 0 1.8-1.3 2.2 2.2 0 0 0 .1-1.3c-.1-.1-.3-.2-.6-.3z"/></svg>
                    WhatsApp Business
                </a>
            </div>
            <div class="lg:col-span-3">
                <p class="text-aqua text-xs font-heading font-bold tracking-[0.2em] uppercase mb-4">Explora ELAH</p>
                <ul class="space-y-2 text-white/80">
                    <?php foreach ($nav as [$label, $href]): ?>
                        <li><a class="hover:text-aqua transition" href="<?= e(url($href)) ?>"><?= e($label) ?></a></li>
                    <?php endforeach; ?>
                    <li><a class="hover:text-aqua transition" href="<?= e(url('cotizacion.php')) ?>">Mi cotización</a></li>
                    <li><a class="hover:text-aqua transition" href="<?= e(url('rastreo.php')) ?>">Rastrear pedido</a></li>
                    <li><a class="hover:text-aqua transition" href="<?= e(url('blog.php')) ?>">Recursos</a></li>
                </ul>
            </div>
            <div class="lg:col-span-4">
                <p class="text-aqua text-xs font-heading font-bold tracking-[0.2em] uppercase mb-4">Contacto B2B</p>
                <ul class="space-y-3 text-white/80">
                    <li>
                        <span class="block text-xs uppercase tracking-wider text-white/40">WhatsApp / Teléfono</span>
                        <a class="hover:text-aqua" href="https://wa.me/<?= WHATSAPP ?>"><?= WHATSAPP_DISPLAY ?></a>
                    </li>
                    <li>
                        <span class="block text-xs uppercase tracking-wider text-white/40">Ventas</span>
                        <a class="hover:text-aqua" href="mailto:<?= EMAIL_VENTAS ?>"><?= EMAIL_VENTAS ?></a>
                    </li>
                    <li>
                        <span class="block text-xs uppercase tracking-wider text-white/40">Sitio</span>
                        <?= SITE_DOMAIN ?>
                    </li>
                </ul>
            </div>
        </div>
        <div class="mt-14 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-white/10 pt-6 text-sm text-white/50">
            <p>© <?= date('Y') ?> Hotel Expert. Sistema ELAH para hospitalidad.</p>
            <p>Precios + IVA · Envío nacional · Única excepción de uso: vidrio</p>
        </div>
    </div>
</footer>

<a href="https://wa.me/<?= WHATSAPP ?>?text=<?= WHATSAPP_MSG ?>"
   class="wa-fab"
   target="_blank"
   rel="noopener"
   aria-label="Escribir por WhatsApp">
    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor"><path d="M20.5 3.5A11 11 0 0 0 2.1 17.4L1 23l5.8-1.1A11 11 0 0 0 12 23a11 11 0 0 0 8.5-19.5zM12 21a9 9 0 0 1-4.6-1.3l-.3-.2-3.4.6.7-3.3-.2-.3A9 9 0 1 1 12 21zm5-6.7c-.3-.1-1.6-.8-1.9-.9s-.4-.1-.6.1-.7.9-.8 1-.3.2-.6.1a7.4 7.4 0 0 1-2.2-1.4 8.2 8.2 0 0 1-1.5-1.9c-.2-.3 0-.4.1-.6l.4-.5.1-.3a.5.5 0 0 0 0-.5c0-.1-.6-1.4-.8-1.9s-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.9 11.9 0 0 0 4.6 4.1 15 15 0 0 0 1.5.6 3.6 3.6 0 0 0 1.6.1 2.7 2.7 0 0 0 1.8-1.3 2.2 2.2 0 0 0 .1-1.3c-.1-.1-.3-.2-.6-.3z"/></svg>
</a>
<?php
$cartProducts = require ROOT_PATH . '/data/productos.php';
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
    ];
}
?>
<script>
window.ELAH_BASE = <?= json_encode(BASE_URL, JSON_UNESCAPED_SLASHES) ?>;
window.ELAH_PRODUCTS = <?= json_encode($cartData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
</script>
<script src="<?= e(url('assets/js/main.js')) ?>?v=<?= (int) filemtime(ROOT_PATH . '/assets/js/main.js') ?>"></script>
</body>
</html>
