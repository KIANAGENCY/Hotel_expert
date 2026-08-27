<?php
declare(strict_types=1);
/** @var array<int, array{label: string, href?: string}> $breadcrumbs */
$breadcrumbs = $breadcrumbs ?? [];
if ($breadcrumbs === []) {
    return;
}
?>
<nav class="text-sm text-charcoal/50 mb-8" aria-label="Ruta de navegación">
    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
        <?php foreach ($breadcrumbs as $i => $crumb): ?>
            <?php if ($i > 0): ?><li aria-hidden="true" class="text-charcoal/30">/</li><?php endif; ?>
            <li>
                <?php if (!empty($crumb['href'])): ?>
                    <a class="hover:text-turquesa" href="<?= e(url($crumb['href'])) ?>"><?= e($crumb['label']) ?></a>
                <?php else: ?>
                    <span class="text-charcoal/70"><?= e($crumb['label']) ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
