<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$pedidos = require __DIR__ . '/data/pedidos.php';

$resultado = null;
$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!csrf_ok($_POST['csrf'] ?? null)) {
        $error = 'Sesi�n expirada. Recarga la p�gina e int�ntalo de nuevo.';
    } else {
        $id = strtoupper(trim((string) ($_POST['pedido'] ?? '')));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        foreach ($pedidos as $row) {
            if (strcasecmp($row['id'], $id) === 0 && strcasecmp($row['email'], $email) === 0) {
                $resultado = $row;
                break;
            }
        }
        if (!$resultado) {
            $error = 'No encontramos un pedido con esa combinaci�n de ID y correo. Verifica may�sculas del folio o escribe a ventas@hotelexpert.mx.';
        }
    }
}

$pasos = [
    'procesando' => 'Procesando',
    'preparacion' => 'En preparaci�n',
    'transito' => 'En tr�nsito',
    'entregado' => 'Entregado',
];
$orden = array_keys($pasos);

$page_title = 'Rastreo de pedido � Hotel Expert';
$page_description = 'Consulta el progreso de env�o de insumos Hotel Expert con tu ID de pedido y correo electr�nico.';
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
            <p class="eyebrow">Portal de clientes</p>
            <h1 class="display mt-3 text-4xl sm:text-5xl">Rastreo de pedido</h1>
            <p class="mt-4 text-lg text-charcoal/70">Ingresa el ID de pedido y el correo con el que se registr� la orden. Estados: Procesando ? En preparaci�n ? En tr�nsito ? Entregado.</p>

            <form method="post" class="mt-10 rounded-[1.6rem] bg-white p-8 shadow-glass grid gap-4">
                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                <label>
                    <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">ID de pedido</span>
                    <input class="field" name="pedido" required placeholder="HE-2026-00481" value="<?= e($_POST['pedido'] ?? '') ?>">
                </label>
                <label>
                    <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Correo electr�nico</span>
                    <input class="field" type="email" name="email" required placeholder="george.a@example.org" value="<?= e($_POST['email'] ?? '') ?>">
                </label>
                <button class="btn-primary justify-center py-3" type="submit">Consultar estado</button>
            </form>

            <?php if ($error): ?>
                <p class="mt-6 rounded-2xl bg-expert/5 border border-expert/10 p-4 text-expert"><?= e($error) ?></p>
            <?php endif; ?>

            <?php if ($resultado): ?>
                <article class="mt-8 rounded-[1.6rem] bg-white p-8 border border-expert/10">
                    <p class="text-sm font-heading font-bold text-turquesa"><?= e($resultado['id']) ?> � <?= e($resultado['hotel']) ?></p>
                    <h2 class="font-heading font-extrabold text-2xl text-expert mt-1"><?= e($pasos[$resultado['estado']]) ?></h2>
                    <p class="mt-2 text-charcoal/70"><?= e($resultado['items']) ?></p>
                    <p class="mt-1 text-sm text-charcoal/50">Pedido <?= e($resultado['fecha']) ?> � ETA <?= e($resultado['eta']) ?> � Gu�a <?= e($resultado['guia']) ?></p>

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
                <p class="font-heading font-bold text-expert mb-2">Pedidos de demostraci�n</p>
                <p>HE-2026-00481 � maria.gerente@casaluna.mx</p>
                <p>HE-2026-00412 � compras@grandplaza.mx</p>
                <p>HE-2026-00502 � ops@hotelsierra.mx</p>
                <p>HE-2026-00518 � gerencia@atelierhabita.mx</p>
            </aside>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
