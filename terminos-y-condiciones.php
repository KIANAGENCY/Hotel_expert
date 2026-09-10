<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Términos y condiciones | Hotel Expert';
$page_description = 'Términos y condiciones de uso del sitio Hotel Expert, cuenta de clientes, pedidos y comunicaciones por WhatsApp y plantillas de Meta.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
$termsEmail = site_email();
$termsWhatsapp = site_whatsapp_display();
?>
<main id="contenido" class="pt-28">
    <section class="bg-expert text-white py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <nav class="text-sm text-white/50 mb-8" aria-label="Ruta de navegación">
                <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <li><a class="hover:text-aqua" href="<?= e(url('')) ?>">Inicio</a></li>
                    <li aria-hidden="true">/</li>
                    <li><span class="text-white/80">Términos y condiciones</span></li>
                </ol>
            </nav>
            <p class="eyebrow text-aqua">Legal</p>
            <h1 class="display mt-3 text-4xl sm:text-6xl text-white max-w-4xl">Términos y condiciones</h1>
            <p class="mt-5 max-w-2xl text-lg text-white/70">Vigentes desde el 9 de septiembre de 2026. Regulan el uso de este sitio, el portal de clientes, las cotizaciones B2B y las comunicaciones por WhatsApp, incluidas las plantillas de WhatsApp Business.</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-white">
        <article class="legal-doc mx-auto max-w-3xl px-4 sm:px-6">
            <p>Al acceder a <a href="<?= e(SITE_ORIGIN) ?>"><?= e(SITE_DOMAIN) ?></a>, crear una cuenta, enviar un formulario o escribirnos por WhatsApp, usted acepta estos términos en nombre propio o de la empresa hotelera que representa. Si no está de acuerdo, no use el sitio ni nuestros canales de mensajería.</p>
            <p>El tratamiento de datos personales se describe en el <a href="<?= e(url('aviso-de-privacidad/')) ?>">Aviso de privacidad</a>, que forma parte integrante de estos términos.</p>

            <h2>1. El servicio</h2>
            <p>Hotel Expert comercializa el Sistema ELAH y productos de limpieza y aromatización para hoteles. El sitio permite consultar información, solicitar muestras o cotizaciones, crear una cuenta, gestionar pedidos y, cuando esté habilitado, pagar con Stripe.</p>
            <p>Las descripciones de producto, precios y existencias pueden actualizarse sin previo aviso. Una cotización o un pedido se confirman cuando Hotel Expert lo acepta por escrito, por correo o por WhatsApp.</p>

            <h2>2. Usuarios y cuenta</h2>
            <p>El sitio está dirigido a profesionales y empresas del sector hotelero en México (B2B). Usted declara ser mayor de 18 años y estar facultado para representar al hotel o empresa indicados.</p>
            <p>Es responsable de la veracidad de los datos de su cuenta y de mantener la confidencialidad de su contraseña. Notifique cualquier uso no autorizado a <a href="mailto:<?= e($termsEmail) ?>"><?= e($termsEmail) ?></a>.</p>

            <h2>3. WhatsApp y plantillas de Meta</h2>
            <p>Puede revocar esta autorización en cualquier momento respondiendo <strong>BAJA</strong> o <strong>STOP</strong>, o escribiendo a <a href="mailto:<?= e($termsEmail) ?>"><?= e($termsEmail) ?></a> o al WhatsApp <?= e($termsWhatsapp) ?>. Dejaremos de enviarle plantillas de marketing o seguimiento no esenciales. Los avisos estrictamente necesarios para un pedido en curso podrán enviarse por otro medio (correo o llamada) si el servicio lo requiere.</p>
            <p>Usted se compromete a no usar el canal de WhatsApp para contenido ilícito, spam o acoso, y a que el número proporcionado le pertenece o está autorizado por su empresa.</p>

            <h2>4. Muestras y cotizaciones</h2>
            <p>Una solicitud de muestra o cotización no obliga a Hotel Expert a aceptar el pedido ni a garantizar disponibilidad. Nos reservamos el derecho de rechazar solicitudes incompletas, no profesionales o ajenas al giro hotelero.</p>

            <h2>5. Propiedad intelectual</h2>
            <p>El contenido del sitio, las marcas Hotel Expert y ELAH, logotipos, textos y materiales son propiedad de Hotel Expert o de sus licenciantes. No está permitida su reproducción o uso comercial sin autorización previa por escrito.</p>

            <h2>6. Uso prohibido</h2>
            <p>No está permitido intentar acceder de forma no autorizada al sitio, interferir con su seguridad, copiar el contenido de manera sistemática ni usar nuestros datos de contacto o WhatsApp para fines ajenos a una relación comercial legítima.</p>

            <h2>7. Legislación y jurisdicción</h2>
            <p>Estos términos se rigen por las leyes de los Estados Unidos Mexicanos. Para cualquier controversia, las partes se someten a los tribunales competentes de Monterrey, Nuevo León, renunciando a cualquier otro fuero que pudiera corresponderles.</p>

            <h2>8. Cambios</h2>
            <p>Podemos actualizar estos términos publicando la nueva versión en <a href="<?= e(url('terminos-y-condiciones/')) ?>"><?= e(SITE_ORIGIN) ?>/terminos-y-condiciones/</a>. El uso posterior del sitio o de WhatsApp implica la aceptación de la versión vigente.</p>

            <h2>9. Contacto</h2>
            <p>Hotel Expert<br>
            Correo: <a href="mailto:<?= e($termsEmail) ?>"><?= e($termsEmail) ?></a><br>
            WhatsApp: <?= e($termsWhatsapp) ?><br>
            Sitio: <a href="<?= e(SITE_ORIGIN) ?>"><?= e(SITE_DOMAIN) ?></a></p>
        </article>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
