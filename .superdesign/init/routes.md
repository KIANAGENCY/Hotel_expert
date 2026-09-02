# Routes

Framework: PHP 8.3 multi-page application using Apache mod_rewrite. Shared layout: includes/head.php + includes/header.php + includes/footer.php.

- `/` → `index.php`
- `/sistema-elah/` → `sistema-elah.php`
- `/productos/` → `productos.php`
- `/productos/hotel-expert/` → `producto.php?slug=estandar`
- `/productos/hotel-expert-dual/` → `producto.php?slug=dual`
- `/aroma-insignia/` → `aroma-insignia.php`
- `/recursos/` → `recursos.php`
- `/blog/` → `blog.php`
- `/manual-de-uso/` → `manual-de-uso.php`
- `/nosotros/` → `nosotros.php`
- `/contacto/` → `contacto.php`

Legacy pages remain addressable and should redirect or remain stable where indexed. Routing is defined in `.htaccess`.

