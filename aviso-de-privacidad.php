<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
$page_title = 'Aviso de privacidad | Hotel Expert';
$page_description = 'Aviso de privacidad de Hotel Expert: tratamiento de datos personales, WhatsApp y plantillas, derechos ARCO y eliminación de información.';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
$privacyEmail = site_email();
$privacyWhatsapp = site_whatsapp_display();
?>
<main id="contenido" class="pt-28">
    <section class="bg-expert text-white py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <nav class="text-sm text-white/50 mb-8" aria-label="Ruta de navegación">
                <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <li><a class="hover:text-aqua" href="<?= e(url('')) ?>">Inicio</a></li>
                    <li aria-hidden="true">/</li>
                    <li><span class="text-white/80">Aviso de privacidad</span></li>
                </ol>
            </nav>
            <p class="eyebrow text-aqua">Legal</p>
            <h1 class="display mt-3 text-4xl sm:text-6xl text-white max-w-4xl">Aviso de privacidad</h1>
            <p class="mt-5 max-w-2xl text-lg text-white/70">Vigente desde el 9 de septiembre de 2026. Este aviso explica cómo Hotel Expert trata datos personales, incluyendo el envío de mensajes y plantillas de WhatsApp a través de la plataforma de Meta.</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-white">
        <article class="legal-doc mx-auto max-w-3xl px-4 sm:px-6">
            <p>Hotel Expert (en adelante, “Hotel Expert”, “nosotros” o “el responsable”), con sitio web <a href="<?= e(SITE_ORIGIN) ?>"><?= e(SITE_DOMAIN) ?></a> y correo de contacto <a href="mailto:<?= e($privacyEmail) ?>"><?= e($privacyEmail) ?></a>, es responsable del tratamiento de los datos personales que recabamos de clientes, prospectos y usuarios del sitio, del portal de clientes y de nuestros canales de mensajería.</p>
            <p>Este aviso se emite conforme a la Ley Federal de Protección de Datos Personales en Posesión de los Particulares y su Reglamento, y cubre los requisitos de transparencia de Meta para el uso de WhatsApp Business Platform (conversación y plantillas).</p>

            <h2>1. Datos que recabamos</h2>
            <p>Podemos recabar, según el formulario o el servicio utilizado:</p>
            <ul>
                <li>Identificación y contacto: nombre, cargo, hotel o empresa, ciudad, correo electrónico y teléfono o número de WhatsApp.</li>
                <li>Información comercial: tipo de propiedad, número de habitaciones, interés (muestra, cotización, Sistema ELAH), mensaje y datos de pedido o carrito.</li>
                <li>Cuenta y operación: credenciales de acceso, historial de pedidos, rastreo y, en su caso, datos de facturación (incluido RFC).</li>
                <li>Pagos: información necesaria para procesar cobros a través de Stripe; no almacenamos el número completo de la tarjeta.</li>
                <li>Datos técnicos: dirección IP, fecha y origen de la solicitud, y registros de envío o entrega de mensajes.</li>
                <li>Contenido de comunicaciones: mensajes que nos envíe o que le enviemos por correo, WhatsApp u otros canales de atención.</li>
            </ul>
            <p>Los datos se obtienen cuando usted llena un formulario, crea una cuenta, solicita una muestra o cotización, realiza un pedido, nos escribe o consiente recibir mensajes de WhatsApp.</p>

            <h2>2. Finalidades del tratamiento</h2>
            <p>Utilizamos los datos para:</p>
            <ul>
                <li>Atender solicitudes de información, muestra, cotización y soporte del Sistema ELAH.</li>
                <li>Crear y administrar su cuenta, pedidos, envíos y rastreo.</li>
                <li>Procesar pagos y emitir comprobantes cuando corresponda.</li>
                <li>Enviar comunicaciones comerciales y operativas por correo electrónico y <strong>WhatsApp</strong>, incluidas <strong>plantillas de WhatsApp Business</strong> (confirmaciones, seguimiento de solicitudes, avisos de pedido, recordatorios y respuestas a su interés).</li>
                <li>Cumplir obligaciones legales, prevenir fraude y mejorar el sitio.</li>
            </ul>
            <p>No vendemos datos personales. Tampoco utilizamos el contenido de sus chats de WhatsApp para anuncios de terceros.</p>

            <h2>3. WhatsApp, plantillas y Meta</h2>
            <p>Cuando nos comparte un número de teléfono o inicia una conversación, podemos enviarle mensajes de WhatsApp relacionados con su solicitud o con la relación comercial. Eso incluye mensajes de sesión y plantillas preaprobadas por Meta (por ejemplo, confirmación de solicitud, seguimiento, estatus de pedido o avisos de envío).</p>
            <p>Para entregar esos mensajes, el número, el nombre visible y el contenido necesario se transmiten a <strong>Meta Platforms, Inc. / WhatsApp</strong> como encargados o proveedores de la plataforma de mensajería, conforme a las políticas de WhatsApp Business y a los términos de Meta.</p>
            <p>Usted puede dejar de recibir mensajes de WhatsApp en cualquier momento:</p>
            <ul>
                <li>Respondiendo <strong>BAJA</strong> o <strong>STOP</strong> al hilo de WhatsApp; o</li>
                <li>Escribiendo a <a href="mailto:<?= e($privacyEmail) ?>"><?= e($privacyEmail) ?></a> o a WhatsApp <?= e($privacyWhatsapp) ?>.</li>
            </ul>
            <p>La baja de WhatsApp no elimina automáticamente su cuenta ni sus pedidos; para eso use la sección de eliminación de datos.</p>

            <h2>4. Conservación</h2>
            <p>Conservamos los datos el tiempo necesario para atender la relación comercial, cumplir obligaciones fiscales y resolver disputas, y después los bloqueamos o eliminamos conforme a la ley. Los registros de mensajería se conservan el tiempo operativo indispensable para soporte y auditoría.</p>

            <h2>5. Derechos ARCO y eliminación de datos</h2>
            <p>Usted puede solicitar acceso, rectificación, cancelación u oposición al tratamiento de sus datos (derechos ARCO), así como limitar el uso o revocar el consentimiento, incluyendo la eliminación de su información.</p>
            <p>Para ejercerlos, envíe un correo a <a href="mailto:<?= e($privacyEmail) ?>"><?= e($privacyEmail) ?></a> con el asunto “Datos personales”, su nombre, un medio de contacto y la descripción de su solicitud. Responderemos en los plazos legales aplicables.</p>
            <p>Si desea que eliminemos los datos asociados a WhatsApp, indíquelo expresamente. Tras verificar su identidad, dejaremos de enviarle plantillas y, cuando no exista una obligación legal de conservar la información, la suprimiremos o bloquearemos.</p>

            <h2>6. Cookies y sitio web</h2>
            <p>El sitio puede usar cookies técnicas de sesión para el funcionamiento del carrito, el inicio de sesión y la seguridad. No utilizamos esas cookies para vender publicidad basada en su perfil a terceros.</p>

            <h2>7. Menores de edad</h2>
            <p>Los servicios de Hotel Expert están dirigidos a empresas y profesionales hoteleros. No recabamos de forma intencional datos de menores de 18 años.</p>

            <h2>8. Cambios a este aviso</h2>
            <p>Cualquier cambio se publicará en esta misma URL: <a href="<?= e(url('aviso-de-privacidad/')) ?>"><?= e(SITE_ORIGIN) ?>/aviso-de-privacidad/</a>. El uso continuado del sitio o de nuestros canales de contacto después de la publicación implica que ha tomado conocimiento de la versión vigente.</p>

            <h2>9. Contacto</h2>
            <p>Responsable: Hotel Expert.<br>
            Correo: <a href="mailto:<?= e($privacyEmail) ?>"><?= e($privacyEmail) ?></a><br>
            WhatsApp: <?= e($privacyWhatsapp) ?><br>
            Sitio: <a href="<?= e(SITE_ORIGIN) ?>"><?= e(SITE_DOMAIN) ?></a></p>
            <p>También puede consultar los <a href="<?= e(url('terminos-y-condiciones/')) ?>">Términos y condiciones</a>.</p>
        </article>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
