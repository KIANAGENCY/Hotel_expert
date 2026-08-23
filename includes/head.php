<?php
declare(strict_types=1);
/** @var string $page_title */
/** @var string $page_description */
/** @var string $page_og */
$page_title = $page_title ?? SITE_NAME;
$page_description = $page_description ?? (SITE_TAGLINE . ' Sistema integral de limpieza, desinfección y aroma insignia para hoteles.');
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
    <?php /* HubSpot: sustituir PORTAL_ID cuando el CRM esté activo
    <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/PORTAL_ID.js"></script>
    */ ?>
</head>
<body class="font-body text-charcoal bg-white antialiased">
<a class="skip-link" href="#contenido">Saltar al contenido</a>
