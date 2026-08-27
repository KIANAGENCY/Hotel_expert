<?php
declare(strict_types=1);
$faqs = $faqs ?? (require ROOT_PATH . '/data/faq.php');
$faq_heading = $faq_heading ?? 'Preguntas frecuentes sobre Hotel Expert';
$show_heading = $faq_show_heading ?? true;
?>
<?php if ($show_heading): ?>
    <h2 class="display text-3xl sm:text-5xl"><?= e($faq_heading) ?></h2>
<?php endif; ?>
<div class="mt-10 space-y-4 <?= $show_heading ? '' : 'mt-0' ?>">
    <?php foreach ($faqs as $faq): ?>
        <details class="faq-item elah-card bg-white p-6 sm:p-7 group">
            <summary class="flex items-start justify-between gap-4 cursor-pointer list-none font-heading font-extrabold text-xl text-expert">
                <h3 class="text-left text-xl font-heading font-extrabold text-expert"><?= e($faq['pregunta']) ?></h3>
                <span class="faq-icon shrink-0 mt-1 text-2xl leading-none text-turquesa transition-transform" aria-hidden="true">+</span>
            </summary>
            <p class="mt-4 text-charcoal/70 leading-relaxed"><?= e($faq['respuesta']) ?></p>
        </details>
    <?php endforeach; ?>
</div>
