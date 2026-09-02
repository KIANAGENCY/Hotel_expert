<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$posts = blog_all();
$page_title = 'Recursos para limpieza, aroma y experiencia hotelera | Hotel Expert';
$page_description = 'Guías, blog, preguntas frecuentes y manual de uso del Sistema ELAH para operación, housekeeping y gerencia.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
<main id="contenido" class="pt-28">
    <section class="internal-page-hero internal-hero-resources bg-expert text-white py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <nav class="text-sm text-white/50 mb-8" aria-label="Ruta de navegación">
                <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <li><a class="hover:text-aqua" href="<?= e(url('')) ?>">Inicio</a></li>
                    <li aria-hidden="true">/</li>
                    <li><span class="text-white/80">Recursos</span></li>
                </ol>
            </nav>
            <h1 class="display text-4xl sm:text-6xl text-white max-w-4xl">Recursos para limpieza, aroma y experiencia hotelera</h1>
            <p class="mt-5 max-w-2xl text-xl text-white/70">Información práctica para responsables de operación, housekeeping, gerencia y experiencia del huésped.</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-hielo">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 grid md:grid-cols-2 gap-6">
            <article class="elah-card bg-white p-8 io-reveal">
                <h2 class="font-heading font-extrabold text-2xl text-expert">Guías</h2>
                <p class="mt-4 text-charcoal/70">Artículos prácticos sobre el Sistema ELAH, Hotel Expert Dual y el rendimiento del concentrado.</p>
                <ul class="mt-5 space-y-2">
                    <?php foreach ($posts as $post): ?>
                        <li>
                            <a class="text-turquesa font-heading font-semibold hover:underline" href="<?= e(url('articulo.php?slug=' . $post['slug'])) ?>">
                                <?= e($post['titulo']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </article>
            <article class="elah-card bg-white p-8 io-reveal">
                <h2 class="font-heading font-extrabold text-2xl text-expert">Blog</h2>
                <p class="mt-4 text-charcoal/70">Recursos editoriales sobre marketing olfativo, limpieza biodegradable y operación hotelera.</p>
                <a class="btn-outline mt-6" href="<?= e(url('blog/')) ?>">Ir al blog</a>
            </article>
            <article class="elah-card bg-white p-8 io-reveal">
                <h2 class="font-heading font-extrabold text-2xl text-expert">Preguntas frecuentes</h2>
                <p class="mt-4 text-charcoal/70">Respuestas claras sobre qué es Hotel Expert, Dual, superficies y dilución.</p>
                <a class="btn-outline mt-6" href="#faq">Ver preguntas frecuentes</a>
            </article>
            <article class="elah-card bg-white p-8 io-reveal">
                <h2 class="font-heading font-extrabold text-2xl text-expert">Manual de uso</h2>
                <p class="mt-4 text-charcoal/70">Dilución, preparación de 20 litros, aplicación y superficies compatibles.</p>
                <a class="btn-outline mt-6" href="<?= e(url('manual-de-uso/')) ?>">Abrir manual de uso</a>
            </article>
        </div>
    </section>

    <section id="faq" class="py-16 lg:py-24 bg-white scroll-mt-32">
        <div class="mx-auto max-w-3xl px-4 sm:px-6">
            <?php require __DIR__ . '/includes/partials/faq-list.php'; ?>
            <div class="mt-10 flex flex-wrap gap-3">
                <a class="btn-primary" href="<?= e(url('sistema-elah/')) ?>">Conocer el Sistema ELAH</a>
                <a class="btn-outline" href="<?= e(url('muestra/')) ?>">Solicitar muestra</a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>



