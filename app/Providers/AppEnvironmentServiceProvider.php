<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RuntimeException;

class AppEnvironmentServiceProvider extends ServiceProvider
{
    protected array $requiredVars = [
        'APP_ENV',
        'DB_CONNECTION',
        'DB_HOST',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD',
    ];

    public function boot(): void
    {
        // Evitar romper comandos Artisan (ej: key:generate, migrate, etc.)
        if ($this->app->runningInConsole()) {
            return;
        }

        // Validar variables normales
        foreach ($this->requiredVars as $var) {
            if (empty(env($var))) {
                throw new RuntimeException(
                    "Variable de entorno obligatoria no configurada: {$var}"
                );
            }
        }

        // Validación especial para APP_KEY
        if (empty(config('app.key'))) {
            throw new RuntimeException(
                'APP_KEY no configurada. Ejecuta: php artisan key:generate'
            );
        }
    }
}

/*
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RuntimeException;

class AppEnvironmentServiceProvider extends ServiceProvider
{
    protected array $requiredVars = [
        'APP_KEY',
        'APP_ENV',
        'DB_CONNECTION',
        'DB_HOST',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD',
    ];

    public function boot(): void
    {
        
        foreach ($this->requiredVars as $var) {
            if (empty(env($var))) {
                throw new RuntimeException(
                    "Variable de entorno obligatoria no configurada: {$var}"
                );
            }
        }
        

        
        // ❗ No validar durante ejecución en consola (Artisan, Tinker, etc.)
        if ($this->app->runningInConsole()) {
            return;
        }

        // ❗ Validar solo en producción (fail-fast real)
        if (! $this->app->environment('production')) {
            return;
        }

        foreach ($this->requiredVars as $var) {
            if (empty(config($var))) {
                throw new RuntimeException(
                    "Configuración obligatoria no definida: {$var}"
                );
            }
        }
        
    }
}
*/