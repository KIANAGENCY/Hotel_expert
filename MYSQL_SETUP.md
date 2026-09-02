# MySQL, correo y portal de clientes

## Preparación local

1. Copia `.env.example` como `.env` y ajusta las credenciales.
2. Instala dependencias:

   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. Crea el esquema y carga los datos iniciales:

   ```bash
   php tools/setup-database.php
   ```

4. Cifra y verifica los datos sensibles existentes:

   ```bash
   php tools/encrypt-existing-pii.php
   ```

5. Si existe la base SQLite anterior, migra sus registros:

   ```bash
   php tools/migrate-sqlite-to-mysql.php
   ```

   Esta migración es de reemplazo para las tablas heredadas: ejecútala una sola vez, antes de comenzar a capturar información nueva en MySQL.

## Variables obligatorias en producción

- `APP_URL`: URL HTTPS completa del sitio.
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
- `PII_ENCRYPTION_KEY`: clave AES-256 de 32 bytes codificada como 64 caracteres hexadecimales.
- `PII_BLIND_INDEX_KEY`: clave HMAC distinta, también de 32 bytes en hexadecimal.
- `ADMIN_USERNAME`, `ADMIN_INITIAL_PASSWORD`: solo se usan si aún no existe un administrador.
- `SMTP_HOST`, `SMTP_PORT`, `SMTP_USERNAME`, `SMTP_PASSWORD`, `SMTP_ENCRYPTION`.
- `SMTP_FROM_EMAIL`, `SMTP_FROM_NAME`.

El archivo `.env` está excluido de Git. No guardes credenciales en el repositorio.

Genera cada clave de PII de manera independiente:

```bash
php -r "echo bin2hex(random_bytes(32)), PHP_EOL;"
```

Guarda ambas claves en el gestor de secretos del servidor y en un respaldo seguro separado de la base de datos. Sin `PII_ENCRYPTION_KEY` no es posible recuperar correo, teléfono ni RFC. No reutilices la misma clave para cifrado y blind indexes.

Para rotar claves, conserva temporalmente las claves anteriores, descifra y vuelve a cifrar todos los registros con claves nuevas en una ventana de mantenimiento, verifica `php tools/encrypt-existing-pii.php` y solo entonces elimina las claves anteriores. Cambiar una clave directamente en `.env` vuelve ilegibles los datos existentes.

## Seguridad de cuentas

- Las contraseñas se almacenan con `password_hash(PASSWORD_DEFAULT)`.
- Correo, teléfono y RFC se almacenan con AES-256-GCM autenticado; el payload se serializa en hexadecimal.
- Las búsquedas exactas por correo usan blind indexes HMAC-SHA-256 y no el texto cifrado.
- Los enlaces de verificación vencen en 24 horas.
- Los enlaces de recuperación vencen en una hora.
- Los tokens se almacenan como hashes SHA-256 y son de un solo uso.
- Cinco intentos fallidos bloquean temporalmente el login durante 15 minutos.
- Los pedidos se autorizan por `customer_id`, no por parámetros enviados por el navegador.

## Pruebas

Con una base MySQL de prueba configurada:

```bash
php tests/customer-auth-integration.php
```
