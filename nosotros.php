<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Sobre nosotros — Hotel Expert';
$page_description = 'Historia y filosofía B2B de Hotel Expert: estandarizar limpieza, desinfección y marketing sensorial en la hospitalidad boutique.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="relative min-h-[52vh] flex items-end overflow-hidden bg-expert noise">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1800&q=80" alt="" class="absolute inset-0 h-full w-full object-cover opacity-50">
        <div class="absolute inset-0 bg-gradient-to-t from-expert to-expert/40"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 pb-14 w-full">
            <p class="eyebrow text-aqua">Sobre nosotros</p>
            <h1 class="display mt-3 text-4xl sm:text-6xl text-white max-w-3xl">Nacimos en la fricción entre el brief de marca y el carrito de la camarista.</h1>
        </div>
    </section>

    <section class="py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-12 gap-12">
            <div class="lg:col-span-7 io-reveal">
                <h2 class="display text-3xl sm:text-4xl">Historia y filosofía B2B</h2>
                <p class="mt-5 text-lg text-charcoal/75 leading-relaxed">Hotel Expert es un sistema profesional para hoteles boutique y el sector de la hospitalidad. No vendemos “otro limpio”. Estandarizamos la experiencia: higiene, desinfección e identidad olfativa en una sola rutina de trabajo.</p>
                <p class="mt-4 text-lg text-charcoal/75 leading-relaxed">La propuesta de valor es deliberadamente operativa. Un concentrado 100% biodegradable en envase de 2 litros retornable reduce plástico y transporte de agua. La dilución ocurre en el hotel. El bidón vacío se recolecta, se higieniza y vuelve a llenarse.</p>
                <p class="mt-4 text-lg text-charcoal/75 leading-relaxed">Trabajamos con gerencia, compras y ama de llaves. Si el protocolo no cabe en un turno real, no es un sistema: es un folleto.</p>
            </div>
            <aside class="lg:col-span-5 rounded-[1.6rem] bg-arena p-8 io-reveal">
                <p class="eyebrow">Claim</p>
                <p class="mt-4 font-heading font-extrabold text-3xl text-expert leading-tight"><?= e(SITE_CLAIM) ?></p>
                <p class="mt-4 text-charcoal/70"><?= e(SITE_TAGLINE) ?></p>
            </aside>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <h2 class="display text-3xl sm:text-5xl max-w-3xl io-reveal">Marketing sensorial en la hospitalidad</h2>
            <div class="mt-12 grid md:grid-cols-3 gap-6">
                <article class="rounded-[1.4rem] bg-white p-8 io-reveal">
                    <h3 class="font-heading font-extrabold text-xl text-expert">Memoria, no ambientador</h3>
                    <p class="mt-3 text-charcoal/70">El huésped identifica el hotel por el olfato. Un aroma genérico de pasillo borra la firma. Un concentrado alineado al brief la refuerza en cada pasada.</p>
                </article>
                <article class="rounded-[1.4rem] bg-white p-8 io-reveal">
                    <h3 class="font-heading font-extrabold text-xl text-expert">Neutralizar no es cubrir</h3>
                    <p class="mt-3 text-charcoal/70">Dual ataca humedad, humo, baños, mascotas y alimentos de raíz, con un efecto que crece con el uso. El Estándar sostiene la rutina diaria.</p>
                </article>
                <article class="rounded-[1.4rem] bg-white p-8 io-reveal">
                    <h3 class="font-heading font-extrabold text-xl text-expert">Sustentabilidad que opera</h3>
                    <p class="mt-3 text-charcoal/70">Concentrado, envase retornable, biodegradabilidad. La métrica no es el manifiesto: es menos plástico en el cuarto de servicio.</p>
                </article>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
