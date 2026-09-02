<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$resultado = null;
$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!csrf_ok($_POST['csrf'] ?? null)) {
        $error = 'Sesión expirada. Recarga la página e inténtalo de nuevo.';
    } else {
        $id = strtoupper(trim((string) ($_POST['pedido'] ?? '')));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        if (strlen($id) > 80 || strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Revisa el folio y el correo electrónico.';
        } elseif (rate_limit_exceeded('order-tracking', $email, (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'), 10, 15)) {
            http_response_code(429);
            $error = 'Alcanzaste el límite temporal de consultas. Intenta nuevamente más tarde.';
        } else {
            $resultado = pedido_get($id, $email);
        }
        if ($error === '' && !$resultado) {
            $error = 'No encontramos un pedido con esa combinación de ID y correo. Verifica el folio o escribe a ' . site_email() . '.';
        }
    }
}

$pasos = [
    'procesando' => 'Procesando',
    'preparacion' => 'En preparación',
    'transito' => 'En tránsito',
    'entregado' => 'Entregado',
];
$orden = array_keys($pasos);

$page_title = 'Rastreo de pedido — Sistema ELAH';
$page_description = 'Consulta el progreso de envío de tu pedido ELAH con el folio y correo electrónico.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

function step_state(array $orden, string $actual, string $slug): string
{
    $iActual = array_search($actual, $orden, true);
    $i = array_search($slug, $orden, true);
    if ($i === false || $iActual === false) {
        return '';
    }
    if ($i < $iActual) {
        return 'is-done';
    }
    if ($i === $iActual) {
        return 'is-current';
    }
    return '';
}
?>
<main id="contenido" class="pt-28">
    <section class="bg-hielo py-16 sm:py-20">
        <div class="mx-auto max-w-3xl px-4 sm:px-6">
            <p class="eyebrow">Clientes ELAH</p>
            <h1 class="display mt-3 text-4xl sm:text-5xl">Rastreo de pedido</h1>
            <p class="mt-4 text-lg text-charcoal/70">Ingresa el folio y el correo con el que se registró la orden.</p>

            <form method="post" class="mt-10 rounded-[1.6rem] bg-white p-8 shadow-glass grid gap-4">
                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                <label>
                    <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">ID de pedido</span>
                    <input class="field" name="pedido" required placeholder="HE-2026-00481" value="<?= e($_POST['pedido'] ?? '') ?>">
                </label>
                <label>
                    <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Correo electrónico</span>
                    <input class="field" type="email" name="email" required placeholder="compras@hotel.com" value="<?= e($_POST['email'] ?? '') ?>">
                </label>
                <button class="btn-primary justify-center py-3" type="submit">Consultar estado</button>
            </form>

            <?php if ($error): ?>
                <p class="mt-6 rounded-2xl bg-expert/5 border border-expert/10 p-4 text-expert"><?= e($error) ?></p>
            <?php endif; ?>

            <?php if ($resultado): ?>
                <article class="mt-8 rounded-[1.6rem] bg-white p-8 border border-expert/10">
                    <p class="text-sm font-heading font-bold text-turquesa"><?= e($resultado['id']) ?> · <?= e($resultado['hotel']) ?></p>
                    <h2 class="font-heading font-extrabold text-2xl text-expert mt-1"><?= e($pasos[$resultado['estado']]) ?></h2>
                    <p class="mt-2 text-charcoal/70"><?= e($resultado['items']) ?></p>
                    <p class="mt-1 text-sm text-charcoal/50">Pedido <?= e($resultado['fecha']) ?> · Entrega estimada <?= e($resultado['eta']) ?> · Guía <?= e($resultado['guia']) ?></p>

                    <div class="mt-8 flex items-center gap-1">
                        <?php foreach ($orden as $idx => $slug):
                            $st = step_state($orden, $resultado['estado'], $slug);
                            ?>
                            <?php if ($idx > 0): ?>
                                <div class="step-line <?= in_array($st, ['is-done', 'is-current'], true) && $st === 'is-done' ? 'is-done' : (array_search($resultado['estado'], $orden) > $idx - 1 ? 'is-done' : '') ?>"></div>
                            <?php endif; ?>
                            <div class="flex flex-col items-center min-w-[4.5rem]">
                                <span class="step-dot <?= $st ?>"></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-3 grid grid-cols-4 gap-1 text-center text-[11px] sm:text-xs font-heading font-semibold text-expert/80">
                        <?php foreach ($pasos as $label): ?>
                            <span><?= e($label) ?></span>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endif; ?>

            <aside class="mt-10 rounded-2xl bg-arena p-6 text-sm text-charcoal/70">
                <p class="font-heading font-bold text-expert mb-2">¿Necesitas ayuda con tu envío?</p>
                <p>Escríbenos a <a class="font-semibold text-turquesa" href="mailto:<?= e(site_email()) ?>"><?= e(site_email()) ?></a> o por WhatsApp al <?= e(site_whatsapp_display()) ?>.</p>
            </aside>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
