# Shared UI components

## `includes/form-contacto.php`

Full source:

```php
<?php
declare(strict_types=1);
$pref = $pref ?? '';
?>
<form action="<?= e(url('procesar-contacto.php')) ?>" method="post" class="grid gap-4">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="origen" value="<?= e($page ?? 'contacto') ?>">
    <div class="grid sm:grid-cols-2 gap-4">
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Nombre</span>
            <input class="field" name="nombre" required autocomplete="name" placeholder="Nombre y apellido">
        </label>
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Cargo</span>
            <input class="field" name="cargo" placeholder="Gerencia, compras, ama de llavesâ€¦">
        </label>
    </div>
    <div class="grid sm:grid-cols-2 gap-4">
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Hotel / empresa</span>
            <input class="field" name="hotel" required placeholder="Nombre de la propiedad">
        </label>
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Ciudad / estado</span>
            <input class="field" name="ciudad" placeholder="Monterrey, N.L. Â· CDMXâ€¦">
        </label>
    </div>
    <div class="grid sm:grid-cols-2 gap-4">
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Tipo de propiedad</span>
            <select class="field" name="tipo_propiedad">
                <option value="">Selecciona una opciÃ³n</option>
                <option value="independiente">Hotel independiente</option>
                <option value="boutique">Hotel boutique</option>
                <option value="cadena">Cadena / grupo hotelero</option>
                <option value="auto-hotel">Auto hotel</option>
                <option value="otro">Otro</option>
            </select>
        </label>
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">NÃºmero de habitaciones</span>
            <input class="field" name="habitaciones" inputmode="numeric" placeholder="Ej. 42">
        </label>
    </div>
    <div class="grid sm:grid-cols-2 gap-4">
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Correo</span>
            <input class="field" type="email" name="email" required autocomplete="email" placeholder="george.a@example.org">
        </label>
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">TelÃ©fono</span>
            <input class="field" type="tel" name="telefono" autocomplete="tel" placeholder="81 0000 0000">
        </label>
    </div>
    <label class="block">
        <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Necesidad principal</span>
        <select class="field" name="interes">
            <option value="muestra" <?= $pref === 'muestra' ? 'selected' : '' ?>>Solicitar muestra</option>
            <option value="sistema" <?= $pref === 'sistema' ? 'selected' : '' ?>>Solicitar propuesta / Sistema ELAH</option>
            <option value="asesoria" <?= $pref === 'asesoria' ? 'selected' : '' ?>>Solicitar informaciÃ³n</option>
            <option value="difusores" <?= $pref === 'difusores' ? 'selected' : '' ?>>Aroma / difusores</option>
            <option value="reabasto" <?= $pref === 'reabasto' ? 'selected' : '' ?>>Reabasto de productos</option>
        </select>
    </label>
    <label class="block">
        <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Mensaje</span>
        <textarea class="field min-h-[120px]" name="mensaje" placeholder="CuÃ©ntanos las Ã¡reas a cubrir, necesidades de aroma u olores, y el siguiente paso que buscas."></textarea>
    </label>
    <p class="text-sm text-charcoal/60">Al enviar, un asesor B2B te contacta. TambiÃ©n puedes escribir al WhatsApp <?= WHATSAPP_DISPLAY ?>.</p>
    <div class="flex flex-wrap gap-3">
        <button class="btn-primary justify-center py-3.5 text-base" type="submit">Enviar mi solicitud</button>
        <a class="btn-outline justify-center py-3.5 text-base" href="<?= e(whatsapp_url($pref === 'muestra' ? WHATSAPP_MSG_MUESTRA : WHATSAPP_MSG)) ?>" target="_blank" rel="noopener">Hablar por WhatsApp</a>
    </div>
</form>


```

## `includes/partials/cta-band.php`

Full source:

```php
<?php
declare(strict_types=1);
$title = $cta_title ?? 'PruÃ©balo en tu propio hotel';
$text = $cta_text ?? 'Solicita informaciÃ³n sobre nuestro paquete muestra y conoce Hotel Expert antes de implementar el sistema completo.';
$primary_label = $cta_primary_label ?? 'Solicitar muestra';
$primary_href = $cta_primary_href ?? 'contacto.php?tipo=muestra';
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


```

## `includes/partials/faq-list.php`

Full source:

```php
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


```

## `includes/partials/product-comparison.php`

Full source:

```php
<?php
declare(strict_types=1);
?>
<div class="grid lg:grid-cols-2 gap-6">
    <article class="elah-compare bg-white p-8 sm:p-10 io-reveal">
        <p class="eyebrow">Hotel Expert</p>
        <h3 class="font-heading font-extrabold text-3xl text-expert mt-3">Hotel Expert</h3>
        <p class="mt-4 text-charcoal/70">Para limpieza, desinfecciÃ³n y aroma insignia.</p>
        <ul class="mt-6 space-y-2 text-charcoal/70">
            <li>Limpia</li>
            <li>Desinfecta</li>
            <li>Incorpora el aroma insignia</li>
        </ul>
        <a class="btn-outline mt-7" href="<?= e(url('producto.php?slug=estandar')) ?>">Ver Hotel Expert</a>
    </article>
    <article class="elah-compare bg-expert text-white p-8 sm:p-10 io-reveal">
        <p class="eyebrow text-aqua">Hotel Expert Dual</p>
        <h3 class="font-heading font-extrabold text-3xl mt-3">Hotel Expert Dual</h3>
        <p class="mt-4 text-white/70">Para limpieza, desinfecciÃ³n y aroma insignia cuando ademÃ¡s existe una necesidad relevante de neutralizaciÃ³n de malos olores.</p>
        <ul class="mt-6 space-y-2 text-white/70">
            <li>Limpia</li>
            <li>Desinfecta</li>
            <li>Aromatiza con aroma insignia</li>
            <li>Neutraliza malos olores</li>
        </ul>
        <a class="btn-primary mt-7" href="<?= e(url('producto.php?slug=dual')) ?>">Ver Hotel Expert Dual</a>
    </article>
</div>


```

## `includes/partials/process-steps.php`

Full source:

```php
<?php
declare(strict_types=1);
$steps = $steps ?? [
    ['Limpieza', 'OperaciÃ³n cotidiana con aroma insignia'],
    ['Aroma', 'Refuerzo en momentos y espacios clave'],
    ['Consistencia', 'Misma identidad en distintos puntos de contacto'],
    ['Experiencia', 'Hospitalidad perceptible para el huÃ©sped'],
];
?>
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <?php foreach ($steps as $i => $step): ?>
        <div class="rounded-3xl bg-hielo p-6 io-reveal">
            <span class="font-heading font-extrabold text-aqua text-2xl">0<?= $i + 1 ?></span>
            <h3 class="mt-3 font-heading font-extrabold text-xl text-expert"><?= e($step[0]) ?></h3>
            <p class="mt-2 text-sm text-charcoal/65"><?= e($step[1]) ?></p>
            <?php if ($i < count($steps) - 1): ?>
                <p class="mt-4 hidden lg:block text-turquesa font-heading font-bold text-sm" aria-hidden="true">â†’</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>


```

