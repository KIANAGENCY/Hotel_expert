# Shared layouts

## `includes/head.php`

Shared site layout source:

```php
<?php
declare(strict_types=1);
/** @var string $page_title */
/** @var string $page_description */
/** @var string $page_og */
$page_title = $page_title ?? SITE_NAME;
$page_description = $page_description ?? (SITE_TAGLINE . ' Sistema integral de limpieza, desinfecciÃ³n y aroma insignia para hoteles.');
$page_og = $page_og ?? 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1200&q=80';
$canonical = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? SITE_DOMAIN) . ($_SERVER['REQUEST_URI'] ?? '/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?></title>
    <meta name="description" content="<?= e($page_description) ?>">
    <meta name="keywords" content="Sistema ELAH, limpieza para hoteles, aroma para hoteles, identidad olfativa, Hotel Expert Dual, difusores para hoteles">
    <meta name="author" content="Hotel Expert">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_MX">
    <meta property="og:site_name" content="Hotel Expert">
    <meta property="og:title" content="<?= e($page_title) ?>">
    <meta property="og:description" content="<?= e($page_description) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="<?= e($page_og) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($page_title) ?>">
    <meta name="twitter:description" content="<?= e($page_description) ?>">
    <link rel="icon" type="image/svg+xml" href="<?= e(url('assets/img/favicon.svg')) ?>">
    <link rel="apple-touch-icon" href="<?= e(url('assets/img/logo-mark-256.png')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        expert: '#0B2345',
                        turquesa: '#008C95',
                        aqua: '#52C8C8',
                        hielo: '#EAF5F5',
                        arena: '#F3F0EA',
                        charcoal: '#222326',
                        eco: '#4D7C4D',
                    },
                    fontFamily: {
                        heading: ['Montserrat', 'sans-serif'],
                        body: ['"Source Sans 3"', 'sans-serif'],
                    },
                    boxShadow: {
                        glass: '0 8px 40px rgba(11, 35, 69, 0.12)',
                        lift: '0 24px 60px rgba(11, 35, 69, 0.18)',
                    },
                    backgroundImage: {
                        brand: 'linear-gradient(135deg, #0B2345 0%, #008C95 100%)',
                    },
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?= e(url('assets/css/custom.css')) ?>?v=<?= (int) filemtime(ROOT_PATH . '/assets/css/custom.css') ?>">
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Hotel Expert",
      "url": "https://www.hotelexpert.mx",
      "email": "<?= EMAIL_VENTAS ?>",
      "telephone": "<?= WHATSAPP_DISPLAY ?>",
      "description": "Sistema ELAH: <?= e(SITE_TAGLINE) ?>",
      "areaServed": "MX"
    }
    </script>
    <?php /* HubSpot: sustituir PORTAL_ID cuando el CRM estÃ© activo
    <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/PORTAL_ID.js"></script>
    */ ?>
</head>
<body class="font-body text-charcoal bg-white antialiased">
<a class="skip-link" href="#contenido">Saltar al contenido</a>


```

## `includes/header.php`

Shared site layout source:

```php
<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
?>
<header id="site-header" class="site-header page-<?= e($page) ?> fixed top-0 inset-x-0 z-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="header-shell mt-3 rounded-2xl px-4 py-3 sm:px-5">
            <div class="flex items-center justify-between gap-3">
                <a href="<?= e(url('index.php')) ?>" class="brand-lockup flex items-center gap-2.5 shrink-0" aria-label="Hotel Expert inicio">
                <span class="brand-mark-shell">
                    <img src="<?= e(url('assets/img/logo-mark-256.png')) ?>" alt="" class="h-[4.4rem] w-[4.4rem] object-contain" width="70" height="70">
                </span>
                <span class="leading-none hidden sm:block">
                    <span class="brand-kicker block font-heading font-extrabold tracking-[0.24em] text-white/90">HOTEL</span>
                    <span class="brand-name block font-heading font-extrabold tracking-[0.06em] text-aqua mt-1">EXPERT</span>
                </span>
                </a>
                <nav class="hidden xl:flex items-center gap-0.5" aria-label="Principal">
                    <?php foreach ($nav as [$label, $href, $slug]): ?>
                        <a href="<?= e(url($href)) ?>"
                           class="nav-link <?= is_active($slug) ? 'is-active' : '' ?>"><?= e($label) ?></a>
                    <?php endforeach; ?>
                </nav>
                <div class="flex items-center gap-2">
                    <a href="<?= e(url('contacto.php?tipo=muestra')) ?>" class="btn-primary hidden lg:inline-flex xl:inline-flex !py-2 !px-4 text-sm shrink-0">Solicita una muestra</a>
                    <a href="<?= e(url('cotizacion.php')) ?>" class="cart-trigger shrink-0" aria-label="Ver carrito de cotizaciÃ³n">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h2l2.2 10.1a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L20.5 8H6.1M10 20h.01M17 20h.01"/></svg>
                        <span class="hidden sm:inline">CotizaciÃ³n</span>
                        <span class="cart-count" data-cart-count>0</span>
                    </a>
                    <button id="menu-btn" type="button" class="menu-btn xl:hidden shrink-0" aria-expanded="false" aria-controls="mobile-menu" aria-label="Abrir menÃº">
                        <svg id="icon-open" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                        <svg id="icon-close" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
                    </button>
                </div>
            </div>
            <nav id="mobile-menu" class="mobile-menu hidden xl:hidden" aria-label="MenÃº mÃ³vil" hidden>
                <a href="<?= e(url('contacto.php?tipo=muestra')) ?>" class="mobile-menu-cta">Solicita una muestra</a>
                <div class="mobile-menu-links">
                    <?php foreach ($nav as [$label, $href, $slug]): ?>
                        <a href="<?= e(url($href)) ?>"
                           class="mobile-menu-link <?= is_active($slug) ? 'is-active' : '' ?>"><?= e($label) ?></a>
                    <?php endforeach; ?>
                </div>
            </nav>
        </div>
    </div>
</header>


```

## `includes/footer.php`

Shared site layout source:

```php
<?php
declare(strict_types=1);
?>
<footer class="relative bg-expert text-white overflow-hidden">
    <div class="absolute inset-0 opacity-40 bg-brand pointer-events-none"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 py-16 lg:py-20">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <div class="flex items-center gap-3 mb-6">
                    <img src="<?= e(url('assets/img/logo-mark-256.png')) ?>" alt="Hotel Expert" class="h-12 w-12 object-contain" width="48" height="48">
                    <span class="leading-none">
                        <span class="block font-heading font-extrabold text-[0.7rem] tracking-[0.24em] text-white/90">HOTEL</span>
                        <span class="block font-heading font-extrabold text-[1.05rem] tracking-[0.06em] text-aqua mt-0.5">EXPERT</span>
                    </span>
                </div>
                <p class="text-xl font-heading font-bold leading-snug max-w-md"><?= e(SITE_CLAIM) ?></p>
                <p class="mt-4 text-white/70 max-w-md">Frescura que se siente. Marca que se recuerda.</p>
                <a href="<?= e(url('contacto.php?tipo=muestra')) ?>" class="btn-primary mt-6">Solicita una muestra</a>
                <a href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-2 rounded-full bg-[#25D366] px-5 py-2.5 text-sm font-heading font-semibold text-expert hover:brightness-110">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20.5 3.5A11 11 0 0 0 2.1 17.4L1 23l5.8-1.1A11 11 0 0 0 12 23a11 11 0 0 0 8.5-19.5zM12 21a9 9 0 0 1-4.6-1.3l-.3-.2-3.4.6.7-3.3-.2-.3A9 9 0 1 1 12 21zm5-6.7c-.3-.1-1.6-.8-1.9-.9s-.4-.1-.6.1-.7.9-.8 1-.3.2-.6.1a7.4 7.4 0 0 1-2.2-1.4 8.2 8.2 0 0 1-1.5-1.9c-.2-.3 0-.4.1-.6l.4-.5.1-.3a.5.5 0 0 0 0-.5c0-.1-.6-1.4-.8-1.9s-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.9 11.9 0 0 0 4.6 4.1 15 15 0 0 0 1.5.6 3.6 3.6 0 0 0 1.6.1 2.7 2.7 0 0 0 1.8-1.3 2.2 2.2 0 0 0 .1-1.3c-.1-.1-.3-.2-.6-.3z"/></svg>
                    WhatsApp
                </a>
            </div>
            <div class="lg:col-span-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <p class="text-aqua text-xs font-heading font-bold tracking-[0.2em] uppercase mb-4">Sistema</p>
                    <ul class="space-y-2 text-white/80">
                        <li><a class="hover:text-aqua transition" href="<?= e(url('sistema-elah.php')) ?>">Sistema ELAH</a></li>
                        <li><a class="hover:text-aqua transition" href="<?= e(url('sistema-elah.php#como-funciona')) ?>">CÃ³mo funciona</a></li>
                        <li><a class="hover:text-aqua transition" href="<?= e(url('aroma-insignia.php')) ?>">Aroma insignia</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-aqua text-xs font-heading font-bold tracking-[0.2em] uppercase mb-4">Productos</p>
                    <ul class="space-y-2 text-white/80">
                        <li><a class="hover:text-aqua transition" href="<?= e(url('producto.php?slug=estandar')) ?>">Hotel Expert</a></li>
                        <li><a class="hover:text-aqua transition" href="<?= e(url('producto.php?slug=dual')) ?>">Hotel Expert Dual</a></li>
                        <li><a class="hover:text-aqua transition" href="<?= e(url('producto.php?slug=aroma-difusor')) ?>">Aromas</a></li>
                        <li><a class="hover:text-aqua transition" href="<?= e(url('productos.php#difusores')) ?>">Difusores</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-aqua text-xs font-heading font-bold tracking-[0.2em] uppercase mb-4">Para hoteles</p>
                    <ul class="space-y-2 text-white/80">
                        <li><a class="hover:text-aqua transition" href="<?= e(url('limpieza-para-hoteles.php')) ?>">Limpieza profesional</a></li>
                        <li><a class="hover:text-aqua transition" href="<?= e(url('aromatizacion-para-hoteles.php')) ?>">AromatizaciÃ³n</a></li>
                        <li><a class="hover:text-aqua transition" href="<?= e(url('experiencia-huesped.php')) ?>">Experiencia del huÃ©sped</a></li>
                        <li><a class="hover:text-aqua transition" href="<?= e(url('recursos.php')) ?>">Recursos</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-aqua text-xs font-heading font-bold tracking-[0.2em] uppercase mb-4">Empresa</p>
                    <ul class="space-y-2 text-white/80">
                        <li><a class="hover:text-aqua transition" href="<?= e(url('nosotros.php')) ?>">Nosotros</a></li>
                        <li><a class="hover:text-aqua transition" href="<?= e(url('contacto.php')) ?>">Contacto</a></li>
                        <li><a class="hover:text-aqua transition" href="<?= e(url('catalogo.php')) ?>">Tienda B2B</a></li>
                        <li><a class="hover:text-aqua transition" href="<?= e(url('cotizacion.php')) ?>">Mi cotizaciÃ³n</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mt-14 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-white/10 pt-6 text-sm text-white/50">
            <p>Â© <?= date('Y') ?> Hotel Expert. Sistema ELAH para hospitalidad.</p>
            <p>Precios + IVA Â· EnvÃ­o nacional Â· Ãšnica excepciÃ³n de uso: vidrio</p>
        </div>
    </div>
</footer>

<a href="<?= e(whatsapp_url()) ?>"
   class="wa-fab"
   target="_blank"
   rel="noopener"
   aria-label="Escribir por WhatsApp">
    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor"><path d="M20.5 3.5A11 11 0 0 0 2.1 17.4L1 23l5.8-1.1A11 11 0 0 0 12 23a11 11 0 0 0 8.5-19.5zM12 21a9 9 0 0 1-4.6-1.3l-.3-.2-3.4.6.7-3.3-.2-.3A9 9 0 1 1 12 21zm5-6.7c-.3-.1-1.6-.8-1.9-.9s-.4-.1-.6.1-.7.9-.8 1-.3.2-.6.1a7.4 7.4 0 0 1-2.2-1.4 8.2 8.2 0 0 1-1.5-1.9c-.2-.3 0-.4.1-.6l.4-.5.1-.3a.5.5 0 0 0 0-.5c0-.1-.6-1.4-.8-1.9s-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.9 11.9 0 0 0 4.6 4.1 15 15 0 0 0 1.5.6 3.6 3.6 0 0 0 1.6.1 2.7 2.7 0 0 0 1.8-1.3 2.2 2.2 0 0 0 .1-1.3c-.1-.1-.3-.2-.6-.3z"/></svg>
</a>
<?php
$cartProducts = require ROOT_PATH . '/data/productos.php';
$cartData = [];
foreach ($cartProducts as $slug => $product) {
    $cartData[$slug] = [
        'slug' => $slug,
        'nombre' => $product['nombre'],
        'precio' => $product['precio'],
        'precio_texto' => $product['precio_texto'],
        'presentacion' => $product['presentacion'],
        'imagen' => $product['imagen'],
        'icono' => $product['icono'],
    ];
}
?>
<script>
window.ELAH_BASE = <?= json_encode(BASE_URL, JSON_UNESCAPED_SLASHES) ?>;
window.ELAH_PRODUCTS = <?= json_encode($cartData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
</script>
<script src="<?= e(url('assets/js/main.js')) ?>?v=<?= filemtime(ROOT_PATH . '/assets/js/main.js') ?>" defer></script>
</body>
</html>


```

