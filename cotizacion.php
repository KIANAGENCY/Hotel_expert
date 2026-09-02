<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$customer = !empty($_SESSION['customer_id']) ? customer_get((int) $_SESSION['customer_id']) : null;
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
            <p class="mt-4 max-w-2xl text-lg text-charcoal/65">Revisa cantidades y comparte los datos de tu hotel. Esta solicitud no genera un cobro.</p>
        </div>
    </section>

    <section class="py-12 lg:py-20 bg-white">
        <div class="cart-layout mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-12 gap-10 items-start" data-cart-layout>
            <div class="lg:col-span-8" data-cart-items-column>
                <div class="cart-surface" data-cart-root></div>
                <div class="mt-6 flex flex-wrap gap-3" data-cart-dependent hidden>
                    <a class="btn-outline" href="<?= e(url('catalogo.php')) ?>">Seguir comprando</a>
                    <a class="btn-primary" data-cart-whatsapp href="https://wa.me/<?= e(site_whatsapp()) ?>" target="_blank" rel="noopener">Enviar por WhatsApp</a>
                </div>
            </div>
            <aside class="lg:col-span-4 lg:sticky lg:top-28" data-cart-dependent hidden>
                <div class="cart-summary-card rounded-[1.75rem] p-7">
                    <h2 class="font-heading font-extrabold text-2xl text-expert">Resumen</h2>
                    <div class="mt-6" data-cart-summary></div>
                </div>
            </aside>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-arena" data-cart-dependent hidden>
        <div class="mx-auto max-w-4xl px-4 sm:px-6">
            <div class="text-center">
                <p class="eyebrow">Completa tu solicitud</p>
                <h2 class="display mt-3 text-3xl sm:text-5xl">Cotización para tu hotel</h2>
                <p class="mt-4 text-charcoal/65">Confirmaremos disponibilidad, IVA y tiempo de entrega según tu ubicación.</p>
            </div>
            <?php if (!empty($_SESSION['form_error'])): ?>
                <p class="mt-8 rounded-2xl bg-expert text-white px-5 py-4"><?= e($_SESSION['form_error']) ?></p>
                <?php unset($_SESSION['form_error']); ?>
            <?php endif; ?>
            <form action="<?= e(url('procesar-contacto.php')) ?>" method="post" class="mt-10 rounded-[1.75rem] bg-white p-7 sm:p-10 shadow-glass grid gap-5" data-quote-form>
                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="origen" value="cotizacion">
                <input type="hidden" name="interes" value="Sistema ELAH">
                <input type="hidden" name="carrito" id="cart-json" value="">
                <div class="grid sm:grid-cols-2 gap-5">
                    <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Nombre</span><input class="field" name="nombre" required autocomplete="name" placeholder="Nombre y apellido" value="<?= e($customer['nombre'] ?? '') ?>"></label>
                    <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Cargo</span><input class="field" name="cargo" placeholder="Gerencia, compras, housekeeping"></label>
                </div>
                <div class="grid sm:grid-cols-2 gap-5">
                    <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Hotel / empresa</span><input class="field" name="hotel" required placeholder="Nombre de la propiedad" value="<?= e($customer['hotel'] ?? '') ?>"></label>
                    <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Ciudad y estado</span><input class="field" name="ciudad" required placeholder="Monterrey, Nuevo León"></label>
                </div>
                <div class="grid sm:grid-cols-2 gap-5">
                    <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Correo</span><input class="field" type="email" name="email" required autocomplete="email" placeholder="compras@hotel.com" value="<?= e($customer['email'] ?? '') ?>" <?= $customer ? 'readonly' : '' ?>></label>
                    <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Teléfono</span><input class="field" type="tel" name="telefono" required autocomplete="tel" placeholder="81 0000 0000" value="<?= e($customer['telefono'] ?? '') ?>"></label>
                </div>
                <div class="grid sm:grid-cols-2 gap-5">
                    <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Habitaciones</span><input class="field" name="habitaciones" inputmode="numeric" placeholder="Ej. 42"></label>
                    <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">RFC (opcional)</span><input class="field" name="rfc" placeholder="RFC de facturación" value="<?= e($customer['rfc'] ?? '') ?>"></label>
                </div>
                <label><span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Comentarios</span><textarea class="field min-h-[110px]" name="mensaje" placeholder="Cuéntanos las áreas, necesidades de aroma o fecha deseada."></textarea></label>
                <label class="flex gap-3 items-start text-sm text-charcoal/60">
                    <input type="checkbox" required class="mt-1 accent-[#008C95]">
                    <span>Acepto ser contactado por Hotel Expert para recibir esta cotización.</span>
                </label>
                <button class="btn-primary justify-center btn-lg" type="submit">Solicitar cotización formal</button>
            </form>
        </div>
    </section>
</main>
<?php if ($reorderCart): ?><script>window.ELAH_REORDER_CART = <?= json_encode($reorderCart, JSON_UNESCAPED_SLASHES) ?>;</script><?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
