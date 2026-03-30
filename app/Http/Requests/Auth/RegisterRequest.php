<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[\p{L}\s\-\.]+$/u', // Solo letras, espacios, guiones y puntos
            ],

            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:150',
                'unique:users,email', // No puede existir en la BD
            ],

            'password' => [
                'required',
                'string',
                // Encadenamiento de reglas de contraseña:
                Password::min(8)        // Mínimo 8 caracteres
                    ->mixedCase()        // Al menos 1 mayúscula + 1 minúscula
                    ->numbers()          // Al menos 1 número
                    ->symbols()          // Al menos 1 símbolo (!@#$%...)
                    // ->uncompromised() // Verifica HaveIBeenPwned (producción)
                ,
                'confirmed', // Requiere campo password_confirmation
            ],

            // Este campo es validado automáticamente por 'confirmed'
            'password_confirmation' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'El nombre completo es obligatorio.',
            'name.min'           => 'El nombre debe tener al menos :min caracteres.',
            'name.regex'         => 'El nombre solo puede contener letras, espacios y guiones.',

            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.email'        => 'El correo electrónico no tiene un formato válido.',
            'email.unique'       => 'Este correo ya está registrado. ¿Desea iniciar sesión?',

            'password.required'      => 'La contraseña es obligatoria.',
            'password.min'           => 'La contraseña debe tener al menos :min caracteres.',
            'password.mixed'    => 'La contraseña debe contener mayúsculas y minúsculas.',
            'password.numbers'       => 'La contraseña debe contener al menos un número.',
            'password.symbols'       => 'La contraseña debe contener al menos un símbolo (@#$!...).',
            'password.confirmed'     => 'La confirmación de contraseña no coincide.',

            'password_confirmation.required' => 'Debe confirmar su contraseña.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'  => trim($this->name ?? ''),
            'email' => strtolower(trim($this->email ?? '')),
            // IMPORTANTE: NO sanitizar password con strip_tags/trim
            // Los símbolos son parte válida de la contraseña
        ]);
    }
}
