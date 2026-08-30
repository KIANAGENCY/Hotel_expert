<?php
declare(strict_types=1);
$areas = $areas ?? areas_all();
$area_linkable = $area_linkable ?? true;
?>
<div class="grid md:grid-cols-2 gap-6">
    <?php foreach ($areas as $area): ?>
        <article class="elah-card bg-white p-8 io-reveal">
            <h3 class="font-heading font-extrabold text-2xl text-expert"><?= e($area['titulo']) ?></h3>
            <p class="mt-4 text-charcoal/70"><?= e($area['texto']) ?></p>
            <?php if ($area_linkable && !empty($area['href'])): ?>
                <a class="btn-outline mt-6" href="<?= e(url($area['href'])) ?>">Ver aplicación en esta área</a>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>
</div>
