<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/stripe-config.php';
require_once __DIR__ . '/includes/cart-pricing.php';
$customer = !empty($_SESSION['customer_id']) ? customer_get((int) $_SESSION['customer_id']) : null;
$stripeReady = stripe_is_enabled() && stripe_status_summary()['ready'];
$reorderCart = is_array($_SESSION['reorder_cart'] ?? null) ? $_SESSION['reorder_cart'] : null;
unset($_SESSION['reorder_cart']);
$page = 'catalogo';
$page_title = 'Mi cotización ELAH — Hotel Expert';
$page_description = 'Revisa productos y cantidades del Sistema ELAH y solicita una cotización para tu hotel.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="py-14 lg:py-20 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <p class="eyebrow">Solicitud B2B</p>
            <h1 class="display mt-3 text-4xl sm:text-6xl">Mi cotización ELAH</h1>
            <p class="mt-4 max-w-2xl text-lg text-charcoal/65">Revisa cantidades, IVA y total. Puedes pagar en línea o solicitar cotización sin cobro.</p>
        </div>
    </section>

    <section class="py-12 lg:py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="cart-surface" data-cart-root></div>
            <div class="mt-5 flex flex-wrap gap-3" data-cart-dependent hidden>
                <a class="btn-outline" href="<?= e(url('catalogo.php')) ?>">Seguir comprando</a>
                <a class="btn-outline" data-cart-whatsapp href="https://wa.me/<?= e(site_whatsapp()) ?>" target="_blank" rel="noopener">Consultar por WhatsApp</a>
            </div>

            <?php if (!empty($_SESSION['form_error'])): ?>
                <p class="mt-6 rounded-2xl bg-expert text-white px-5 py-4" role="alert"><?= e($_SESSION['form_error']) ?></p>
                <?php unset($_SESSION['form_error']); ?>
            <?php endif; ?>

            <form action="<?= e(url('procesar-contacto.php')) ?>" method="post" class="checkout-form mt-10 grid gap-6 lg:grid-cols-12 lg:items-start" data-quote-form data-cart-dependent hidden>
                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="origen" value="cotizacion">
                <input type="hidden" name="interes" value="Sistema ELAH">
                <input type="hidden" name="carrito" id="cart-json" value="">

                <div class="checkout-fields lg:col-span-7 rounded-[1.75rem] bg-white p-6 sm:p-8 shadow-glass">
                    <p class="eyebrow">Tus datos</p>
                    <h2 class="font-heading mt-2 text-2xl font-extrabold text-expert">¿A quién preparamos el pedido?</h2>
                    <p class="mt-2 text-charcoal/60">Usaremos estos datos para identificar tu compra y coordinar la entrega.</p>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Nombre *</span><input class="field" name="nombre" required maxlength="190" autocomplete="name" placeholder="Nombre y apellido" value="<?= e($customer['nombre'] ?? '') ?>"></label>
                        <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Hotel / empresa *</span><input class="field" name="hotel" required maxlength="190" autocomplete="organization" placeholder="Nombre de la propiedad" value="<?= e($customer['hotel'] ?? '') ?>"></label>
                        <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Correo *</span><input class="field" type="email" name="email" required maxlength="254" autocomplete="email" placeholder="compras@hotel.com" value="<?= e($customer['email'] ?? '') ?>" <?= $customer ? 'readonly' : '' ?>></label>
                        <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Teléfono *</span><input class="field" type="tel" name="telefono" required maxlength="60" pattern="[0-9+() .\x2d]{7,60}" title="Ingresa un teléfono con lada (de 7 a 15 dígitos)." autocomplete="tel" placeholder="81 0000 0000" value="<?= e($customer['telefono'] ?? '') ?>"></label>
                        <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Ciudad y estado *</span><input class="field" name="ciudad" required maxlength="190" autocomplete="address-level2" placeholder="Monterrey, Nuevo León"></label>
                        <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Cargo (opcional)</span><input class="field" name="cargo" maxlength="190" placeholder="Gerencia, compras, housekeeping"></label>
                        <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Habitaciones (opcional)</span><input class="field" type="number" name="habitaciones" min="1" max="999999" step="1" inputmode="numeric" placeholder="Ej. 42"></label>
                        <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">RFC (opcional)</span><input class="field" name="rfc" maxlength="13" pattern="[A-Za-zÑñ&]{3,4}[0-9]{6}[A-Za-z0-9]{3}" title="Ingresa un RFC válido de 12 o 13 caracteres, o déjalo vacío." placeholder="RFC de facturación" value="<?= e($customer['rfc'] ?? '') ?>"></label>
                    </div>
                    <label class="mt-5 block"><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Comentarios (opcional)</span><textarea class="field min-h-[110px]" name="mensaje" maxlength="5000" placeholder="Áreas, necesidades de aroma o indicaciones de entrega."></textarea></label>
                    <label class="mt-5 flex gap-3 items-start text-sm text-charcoal/60">
                        <input type="checkbox" class="mt-1 accent-[#008C95]" name="contact_consent" value="1" data-quote-consent>
                        <span>Acepto que Hotel Expert me contacte para atender esta solicitud de cotización.</span>
                    </label>
                </div>

                <aside class="checkout-summary lg:col-span-5 lg:sticky lg:top-28">
                    <div class="cart-summary-card rounded-[1.75rem] p-6 sm:p-7">
                        <p class="eyebrow">Resumen</p>
                        <h2 class="font-heading mt-2 text-2xl font-extrabold text-expert">Tu pedido</h2>
                        <div class="mt-6" data-cart-summary></div>
                        <p class="mt-5 text-sm text-charcoal/55">El envío se confirma según la ubicación. No se realizará ningún cobro al solicitar cotización.</p>
                    </div>
                </aside>

                <div class="checkout-actions lg:col-span-12 rounded-[1.75rem] bg-hielo p-6 sm:p-8">
                    <h2 class="font-heading text-xl font-extrabold text-expert">¿Cómo deseas continuar?</h2>
                    <p class="mt-1 text-charcoal/60">Elige pagar en línea o enviar la solicitud sin realizar un cobro.</p>
                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <button class="btn-outline justify-center btn-lg w-full" type="submit" data-checkout-intent="quote">Solicitar cotización formal</button>
                        <?php if ($stripeReady): ?>
                            <button class="btn-primary justify-center btn-lg w-full" type="submit" data-checkout-intent="payment">Pagar con tarjeta</button>
                        <?php else: ?>
                            <p class="checkout-payment-unavailable sm:col-span-2">El pago con tarjeta no está disponible temporalmente. Puedes solicitar una cotización.</p>
                        <?php endif; ?>
                    </div>
                    <?php if ($stripeReady): ?><p class="mt-3 text-center text-sm text-charcoal/55 sm:text-left">Pago seguro procesado por Stripe. El total incluye IVA.</p><?php endif; ?>
                </div>
            </form>
        </div>
    </section>
</main>
<?php if ($reorderCart): ?><script>window.ELAH_REORDER_CART = <?= json_encode($reorderCart, JSON_UNESCAPED_SLASHES) ?>;</script><?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
