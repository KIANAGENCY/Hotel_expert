<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Hotel Expert | La idea detrás del Sistema ELAH';
$page_description = 'Hotel Expert integra limpieza profesional, aroma insignia y estandarización para hoteles independientes y grupos hoteleros en México.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="relative min-h-[62vh] flex items-end overflow-hidden bg-expert noise">
        <img src="https://images.unsplash.com/photo-1564501049412-61c2a3083791?auto=format&fit=crop&w=2000&q=85" alt="Experiencia de hospitalidad" class="absolute inset-0 h-full w-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-t from-expert via-expert/70 to-expert/20"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 pb-16 w-full">
            <nav class="text-sm text-white/50 mb-8" aria-label="Ruta de navegación">
                <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <li><a class="hover:text-aqua" href="<?= e(url('')) ?>">Inicio</a></li>
                    <li aria-hidden="true">/</li>
                    <li><span class="text-white/80">Nosotros</span></li>
                </ol>
            </nav>
            <p class="eyebrow text-aqua">Hotel Expert</p>
            <h1 class="display mt-3 text-4xl sm:text-6xl text-white max-w-4xl">Hotel Expert</h1>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-white">
        <div class="mx-auto max-w-3xl px-4 sm:px-6">
            <h2 class="display text-3xl sm:text-5xl">Convertimos una operación indispensable en un punto de contacto de marca</h2>
            <div class="mt-6 space-y-4 text-lg text-charcoal/70 leading-relaxed">
                <p>Hotel Expert nace de una idea sencilla: la limpieza ocurre todos los días y en prácticamente todos los espacios de un hotel.</p>
                <p>¿Por qué no aprovechar esa misma operación para ayudar a construir una experiencia olfativa consistente?</p>
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-12">
            <article>
                <h2 class="display text-3xl sm:text-4xl">Nuestra propuesta</h2>
                <p class="mt-5 text-lg text-charcoal/70 leading-relaxed">Integramos limpieza profesional, aroma insignia y estandarización mediante soluciones desarrolladas específicamente para el entorno hotelero.</p>
            </article>
            <article>
                <h2 class="display text-3xl sm:text-4xl">Nuestra visión</h2>
                <p class="mt-5 text-lg text-charcoal/70 leading-relaxed">Ayudar a hoteles independientes y grupos hoteleros a integrar operación e identidad olfativa de una manera práctica y consistente.</p>
            </article>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="display text-3xl sm:text-5xl">Hotel Expert en México</h2>
                <p class="mt-5 text-lg text-charcoal/70">Atendemos propiedades en México y realizamos distribución mediante paquetería.</p>
                <a class="btn-primary mt-8" href="<?= e(url('sistema-elah/')) ?>">Conoce el Sistema ELAH</a>
            </div>
            <div class="elah-coverage bg-arena p-8 sm:p-10">
                <p class="eyebrow">Cobertura nacional</p>
                <h3 class="font-heading font-extrabold text-3xl text-expert mt-3">Llegamos a toda la República.</h3>
                <p class="mt-4 text-charcoal/70">Distribuimos por paquetería y confirmamos tiempos de entrega según la ubicación de cada propiedad.</p>
                <a class="btn-outline mt-7" href="<?= e(url('contacto/')) ?>">Contactar a Hotel Expert</a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
