<?php
declare(strict_types=1);
$steps = $steps ?? [
    ['Limpieza', 'Operación cotidiana con aroma insignia'],
    ['Aroma', 'Refuerzo en momentos y espacios clave'],
    ['Consistencia', 'Misma identidad en distintos puntos de contacto'],
    ['Experiencia', 'Hospitalidad perceptible para el huésped'],
];
?>
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <?php foreach ($steps as $i => $step): ?>
        <div class="rounded-3xl bg-hielo p-6 io-reveal">
            <span class="font-heading font-extrabold text-aqua text-2xl">0<?= $i + 1 ?></span>
            <h3 class="mt-3 font-heading font-extrabold text-xl text-expert"><?= e($step[0]) ?></h3>
            <p class="mt-2 text-sm text-charcoal/65"><?= e($step[1]) ?></p>
            <?php if ($i < count($steps) - 1): ?>
                <p class="mt-4 hidden lg:block text-turquesa font-heading font-bold text-sm" aria-hidden="true">→</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
