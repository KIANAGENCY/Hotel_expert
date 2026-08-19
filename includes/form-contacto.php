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
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Hotel / Empresa</span>
            <input class="field" name="hotel" required placeholder="Nombre de la propiedad">
        </label>
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Ciudad</span>
            <input class="field" name="ciudad" placeholder="Monterrey, CDMX…">
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
    <div class="grid sm:grid-cols-2 gap-4">
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Interés</span>
            <select class="field" name="interes">
                <option value="cotizacion" <?= $pref === 'cotizacion' ? 'selected' : '' ?>>Cotización mayoreo</option>
                <option value="muestra" <?= $pref === 'muestra' ? 'selected' : '' ?>>Solicitar muestra / prueba</option>
                <option value="estandar">Concentrado Estándar</option>
                <option value="dual">Hotel Expert Dual</option>
                <option value="sistema">Sistema completo + accesorios</option>
            </select>
        </label>
        <label class="block">
            <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Habitaciones (aprox.)</span>
            <input class="field" name="habitaciones" inputmode="numeric" placeholder="Ej. 42">
        </label>
    </div>
    <label class="block">
        <span class="mb-1.5 block text-sm font-heading font-semibold text-expert">Mensaje</span>
        <textarea class="field min-h-[120px]" name="mensaje" placeholder="Cuéntanos operación, olores persistentes o si quieres una prueba en piso."></textarea>
    </label>
    <p class="text-sm text-charcoal/60">Al enviar, un asesor B2B te contacta. También puedes escribir al WhatsApp <?= WHATSAPP_DISPLAY ?>.</p>
    <button class="btn-primary justify-center py-3.5 text-base" type="submit">Enviar solicitud</button>
</form>
