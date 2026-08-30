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
            <input class="field" name="cargo" placeholder="Gerencia, compras, ama de llaves…">
        </label>
    </div>
    <div class="grid sm:grid-cols-2 gap-4">
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Hotel / empresa</span>
            <input class="field" name="hotel" required placeholder="Nombre de la propiedad">
        </label>
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Ciudad / estado</span>
            <input class="field" name="ciudad" placeholder="Monterrey, N.L. · CDMX…">
        </label>
    </div>
    <div class="grid sm:grid-cols-2 gap-4">
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Correo</span>
            <input class="field" type="email" name="email" required autocomplete="email" placeholder="george.a@example.org">
        </label>
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Teléfono</span>
            <input class="field" type="tel" name="telefono" autocomplete="tel" placeholder="81 0000 0000">
        </label>
    </div>
    <label class="block">
        <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Necesidad principal</span>
        <select class="field" name="interes">
            <option value="muestra" <?= $pref === 'muestra' ? 'selected' : '' ?>>Solicitar muestra</option>
            <option value="sistema" <?= $pref === 'sistema' ? 'selected' : '' ?>>Solicitar propuesta / Sistema ELAH</option>
            <option value="asesoria" <?= $pref === 'asesoria' ? 'selected' : '' ?>>Solicitar información</option>
            <option value="difusores" <?= $pref === 'difusores' ? 'selected' : '' ?>>Aroma / difusores</option>
            <option value="reabasto" <?= $pref === 'reabasto' ? 'selected' : '' ?>>Reabasto de productos</option>
        </select>
    </label>
    <label class="block">
        <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Mensaje</span>
        <textarea class="field min-h-[120px]" name="mensaje" placeholder="Cuéntanos las áreas a cubrir, necesidades de aroma u olores, y el siguiente paso que buscas."></textarea>
    </label>
    <p class="text-sm text-charcoal/60">Al enviar, un asesor B2B te contacta. También puedes escribir al WhatsApp <?= e(site_whatsapp_display()) ?>.</p>
    <div class="flex flex-wrap gap-3">
        <button class="btn-primary justify-center py-3.5 text-base" type="submit">Enviar solicitud</button>
        <a class="btn-outline justify-center py-3.5 text-base" href="<?= e(whatsapp_url($pref === 'muestra' ? WHATSAPP_MSG_MUESTRA : WHATSAPP_MSG)) ?>" target="_blank" rel="noopener">Hablar por WhatsApp</a>
    </div>
</form>
