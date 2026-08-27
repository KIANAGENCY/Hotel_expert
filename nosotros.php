<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Quiénes Somos | Hotel Expert – Sistema ELAH de Limpieza y Aromatización';
$page_description = 'Hotel Expert diseña sistemas de limpieza biodegradable y aromatización de marca para hoteles en todo México. Conoce el Sistema ELAH.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="relative min-h-[62vh] flex items-end overflow-hidden bg-expert noise">
        <img src="https://images.unsplash.com/photo-1564501049412-61c2a3083791?auto=format&fit=crop&w=2000&q=85" alt="Experiencia de hospitalidad" class="absolute inset-0 h-full w-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-t from-expert via-expert/70 to-expert/20"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 pb-16 w-full">
            <p class="eyebrow text-aqua">Hotel Expert</p>
            <h1 class="display mt-3 text-4xl sm:text-6xl text-white max-w-4xl">Unimos limpieza y aromatización bajo una sola oferta.</h1>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-12 gap-12">
            <div class="lg:col-span-7">
                <p class="eyebrow">Nuestra propuesta</p>
                <h2 class="display mt-3 text-3xl sm:text-5xl">No son productos separados. Es el Sistema ELAH.</h2>
                <p class="mt-6 text-xl text-charcoal/70 leading-relaxed">Cobertura nacional, productos 100% biodegradables y un mismo aroma insignia en cada punto de contacto del huésped.</p>
                <p class="mt-6 text-xl text-charcoal/70 leading-relaxed">ELAH responde a dos necesidades que los hoteles resolvían por separado: limpiar sus instalaciones y ambientar el aroma de su marca.</p>
                <p class="mt-5 text-lg text-charcoal/70 leading-relaxed">Integramos multiusos concentrados, sprays ambientales, aromas y difusores eléctricos para que cada punto de contacto refuerce la misma identidad olfativa.</p>
            </div>
            <aside class="lg:col-span-5 rounded-[1.75rem] bg-hielo p-8 sm:p-10">
                <p class="eyebrow">Sistema ELAH</p>
                <p class="mt-4 font-heading font-extrabold text-3xl text-expert leading-tight">Estandarización de Limpieza y Aroma de Hoteles.</p>
                <p class="mt-5 text-charcoal/65">Una experiencia consistente en cada habitación, área y propiedad.</p>
            </aside>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <p class="eyebrow">Para toda la industria hotelera</p>
            <h2 class="display mt-3 text-3xl sm:text-5xl max-w-3xl">Una solución para hoteles independientes y cadenas.</h2>
            <div class="mt-12 grid md:grid-cols-2 gap-6">
                <article class="elah-card bg-white p-8 sm:p-10">
                    <h3 class="font-heading font-extrabold text-3xl text-expert">Hoteles independientes</h3>
                    <p class="mt-4 text-lg text-charcoal/70">Pequeñas y medianas propiedades que buscan una identidad olfativa distintiva y una compra concentrada en dueño o gerencia general.</p>
                </article>
                <article class="elah-card bg-expert text-white p-8 sm:p-10">
                    <h3 class="font-heading font-extrabold text-3xl">Cadenas hoteleras</h3>
                    <p class="mt-4 text-lg text-white/70">Propiedades que necesitan estandarizar la experiencia entre múltiples ubicaciones, con coordinación de compras y housekeeping.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-28 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <p class="eyebrow">Un sistema integrado</p>
                <h2 class="display mt-3 text-3xl sm:text-5xl">Operación y marca en la misma conversación.</h2>
                <p class="mt-5 text-lg text-charcoal/70">ELAH habla a quien utiliza el producto y a quien decide el presupuesto: eficiencia técnica para housekeeping e identidad de marca para gerencia.</p>
                <p class="mt-5 text-lg text-charcoal/70">Su diferenciador es integrar limpieza y aromatización bajo el mismo aroma insignia, con eliminación real de olores mediante Hotel Expert Dual.</p>
            </div>
            <div class="elah-coverage bg-arena p-8 sm:p-10">
                <p class="eyebrow">Cobertura nacional</p>
                <h3 class="font-heading font-extrabold text-3xl text-expert mt-3">Llegamos a toda la República.</h3>
                <p class="mt-4 text-charcoal/70">Distribuimos por paquetería y confirmamos tiempos de entrega según la ubicación de cada propiedad.</p>
                <a class="btn-primary mt-7" href="<?= e(url('contacto.php')) ?>">Contactar a Hotel Expert</a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
