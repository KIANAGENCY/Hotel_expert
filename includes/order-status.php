<?php
declare(strict_types=1);

function order_statuses(): array
{
    return [
        'procesando' => 'Procesando',
        'preparacion' => 'En preparación',
        'transito' => 'En tránsito',
        'entregado' => 'Entregado',
    ];
}

function order_step_state(string $current, string $step): string
{
    $order = array_keys(order_statuses());
    $currentIndex = array_search($current, $order, true);
    $stepIndex = array_search($step, $order, true);
    if ($currentIndex === false || $stepIndex === false) {
        return '';
    }
    return $stepIndex < $currentIndex ? 'is-done' : ($stepIndex === $currentIndex ? 'is-current' : '');
}

function render_order_timeline(string $current): void
{
    $statuses = order_statuses();
    $keys = array_keys($statuses);
    $currentIndex = array_search($current, $keys, true);
    ?>
    <div class="order-timeline" aria-label="Progreso del pedido">
        <?php foreach ($statuses as $slug => $label):
            $index = array_search($slug, $keys, true);
            $lineDone = $index !== 0 && $currentIndex !== false && $index <= $currentIndex;
            ?>
            <div class="order-timeline-step <?= e(order_step_state($current, $slug)) ?>">
                <?php if ($index !== 0): ?><span class="order-timeline-line <?= $lineDone ? 'is-done' : '' ?>"></span><?php endif; ?>
                <span class="step-dot <?= e(order_step_state($current, $slug)) ?>"></span>
                <span><?= e($label) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}
