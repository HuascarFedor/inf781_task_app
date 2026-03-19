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
    ];

    protected array $requiredVarsForNonSqlite = [
        'DB_HOST',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD',
    ];

    public function boot(): void
    {
        // Skip validation during key:generate to avoid chicken-and-egg issue
        if ($this->app->runningInConsole() && in_array('key:generate', $_SERVER['argv'] ?? [])) {
            return;
        }

        foreach ($this->requiredVars as $var) {
            if (empty(env($var))) {
                throw new RuntimeException(
                    "Variable de entorno obligatoria no configurada: {$var}"
                );
            }
        }

        // DB_HOST, DB_USERNAME, DB_PASSWORD are not needed for SQLite
        if (env('DB_CONNECTION') !== 'sqlite') {
            foreach ($this->requiredVarsForNonSqlite as $var) {
                if (empty(env($var))) {
                    throw new RuntimeException(
                        "Variable de entorno obligatoria no configurada: {$var}"
                    );
                }
            }
        }
    }
}