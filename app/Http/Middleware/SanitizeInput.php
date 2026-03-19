<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Campos que NO deben ser sanitizados.
     * Las contraseñas no se tocan: strip_tags podría alterarlas.
     */
    protected array $except = [
        'password',
        'password_confirmation',
        'current_password',
        '_token',
        '_method',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Solo sanitizar solicitudes con body (POST, PUT, PATCH)
        if ($request->isMethod('POST') ||
            $request->isMethod('PUT')  ||
            $request->isMethod('PATCH')) {

            $sanitized = $this->sanitizeArray(
                $request->all()
            );
            $request->replace($sanitized);
        }

        return $next($request);
    }

    /**
     * Recorre recursivamente el array de inputs y sanitiza cada valor.
     */
    private function sanitizeArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->except)) {
                continue; // Saltar campos excluidos
            }

            if (is_array($value)) {
                $data[$key] = $this->sanitizeArray($value);
            } elseif (is_string($value)) {
                // 1. trim(): elimina espacios al inicio y final
                // 2. strip_tags(): elimina etiquetas HTML/PHP
                $data[$key] = trim(strip_tags($value));
            }
            // Los valores null, bool, int, float no se modifican
        }
        return $data;
    }

}
