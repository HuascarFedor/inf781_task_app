<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RuntimeException;

class AppEnvironmentServiceProvider extends ServiceProvider
{
    /**
     * Variables de entorno obligatorias para el funcionamiento
     * seguro de la aplicación. Si alguna falta, la app no inicia.
     */
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
    }
}