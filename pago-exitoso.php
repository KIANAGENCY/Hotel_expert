<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/stripe-checkout.php';

$sessionId = trim((string) ($_GET['session_id'] ?? ''));
$orderId = null;
if ($sessionId !== '' && preg_match('/^cs_[A-Za-z0-9_]+$/', $sessionId)) {
    try {
        $orderId = stripe_fulfill_checkout($sessionId);
    } catch (Throwable) {
        $orderId = null;
    }
}

$page_title = 'Pago recibido — Hotel Expert';
$page_description = 'Tu pago con Stripe fue procesado correctamente.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-40 pb-24" data-clear-cart>
    <div class="mx-auto max-w-xl px-4 text-center">
        <p class="eyebrow">Pago confirmado</p>
        <h1 class="display mt-3 text-4xl sm:text-5xl">Gracias por tu compra.</h1>
        <?php if ($orderId): ?>
            <p class="mt-4 text-lg text-charcoal/70">Tu pedido <strong><?= e($orderId) ?></strong> quedó registrado. Te contactaremos para coordinar la entrega.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a class="btn-primary" href="<?= e(url('cuenta/pedido/?id=' . rawurlencode($orderId))) ?>">Ver pedido</a>
                <a class="btn-outline" href="<?= e(url('catalogo/')) ?>">Seguir comprando</a>
            </div>
        <?php else: ?>
            <p class="mt-4 text-lg text-charcoal/70">Estamos confirmando tu pago. Si no ves el pedido en unos minutos, contáctanos con tu comprobante de Stripe.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a class="btn-primary" href="<?= e(url('cuenta/')) ?>">Ir a mi cuenta</a>
                <a class="btn-outline" href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener">WhatsApp</a>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
