<?php
declare(strict_types=1);
$title = $cta_title ?? 'Pruébalo en tu propio hotel';
$text = $cta_text ?? 'Solicita información sobre nuestro paquete muestra y conoce Hotel Expert antes de implementar el sistema completo.';
$primary_label = $cta_primary_label ?? 'Solicitar muestra';
$primary_href = $cta_primary_href ?? 'contacto/?tipo=muestra';
$secondary_label = $cta_secondary_label ?? 'Hablar por WhatsApp';
$secondary_whatsapp = $cta_secondary_whatsapp ?? true;
$wa_message = str_contains($primary_href ?? '', 'muestra') ? WHATSAPP_MSG_MUESTRA : WHATSAPP_MSG;
$bg = $cta_bg ?? 'bg-brand';
?>
<section class="relative py-20 lg:py-24 overflow-hidden <?= e($bg) ?>">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 text-center text-white">
        <h2 class="display text-3xl sm:text-5xl text-white io-reveal"><?= e($title) ?></h2>
        <?php if ($text !== ''): ?>
            <p class="mt-5 text-xl text-white/70 io-reveal"><?= e($text) ?></p>
        <?php endif; ?>
        <div class="mt-8 flex flex-wrap justify-center gap-3 io-reveal">
            <a class="btn-light btn-lg" href="<?= e(url($primary_href)) ?>"><?= e($primary_label) ?></a>
            <?php if ($secondary_whatsapp): ?>
                <a class="btn-ghost btn-lg" href="<?= e(whatsapp_url($wa_message)) ?>" target="_blank" rel="noopener"><?= e($secondary_label) ?></a>
            <?php elseif (!empty($cta_secondary_href)): ?>
                <a class="btn-ghost btn-lg" href="<?= e(url($cta_secondary_href)) ?>"><?= e($secondary_label) ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>
