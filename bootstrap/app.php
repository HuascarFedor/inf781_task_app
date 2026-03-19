<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        App\Providers\AppEnvironmentServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware global: se ejecuta en TODAS las solicitudes web
        $middleware->web(append: [
            \App\Http\Middleware\SanitizeInput::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
                // Ocultar información sensible en producción
        $exceptions->render(function (\Throwable $e, Request $request) {
            // Solo personalizar respuestas en producción
            if (!config('app.debug')) {

                // Para solicitudes API: respuesta JSON sin detalles
                if ($request->expectsJson()) {
                    $statusCode = $e instanceof HttpException
                        ? $e->getStatusCode()
                        : 500;
                    return response()->json([
                        'error'   => true,
                        'message' => $statusCode >= 500
                            ? 'Error interno del servidor.'
                            : $e->getMessage(),
                        'code'    => $statusCode,
                    ], $statusCode);
                }
            }

            // En desarrollo, Laravel muestra el stack trace normal
            return null;
        });

        // Reportar excepciones (logging) sin revelarlas al usuario
        $exceptions->report(function (\Throwable $e) {
            // Aquí se puede integrar Sentry, Bugsnag, etc.
            // El log ya es manejado por Laravel automáticamente
        });

    })->create();
