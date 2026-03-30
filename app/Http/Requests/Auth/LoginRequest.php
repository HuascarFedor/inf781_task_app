<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !Auth::check();
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            // remember_me es opcional
            'remember' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Ingrese un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }

    /**
     * Verificar el límite de intentos antes de procesar el login.
     * Protección contra ataques de fuerza bruta.
     */
    public function ensureIsNotRateLimited(): void
    {
        // Clave única por email + IP del cliente
        $key = Str::transliterate(
            Str::lower($this->email) . '|' . $this->ip()
        );

        if (!RateLimiter::tooManyAttempts($key, 5)) {
            return; // Menos de 5 intentos, continuar
        }

        $seconds = RateLimiter::availableIn($key);

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
    public function incrementRateLimiter(): void
    {
        $key = Str::transliterate(
            Str::lower($this->email) . '|' . $this->ip()
        );
        RateLimiter::hit($key, 300); // Bloqueo de 5 minutos
    }

    public function clearRateLimiter(): void
    {
        $key = Str::transliterate(
            Str::lower($this->email) . '|' . $this->ip()
        );
        RateLimiter::clear($key);
    }
}