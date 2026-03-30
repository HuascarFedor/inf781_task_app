<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // Verificar rate limiting ANTES de intentar la autenticación
        $request->ensureIsNotRateLimited();

        // Auth::attempt() internamente usa Hash::check() (password_verify)
        // Compara la contraseña ingresada contra el hash bcrypt en la BD
        $credentials = $request->only('email', 'password');
        $remember     = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            // Login fallido: incrementar contador de intentos
            $request->incrementRateLimiter();

            // IMPORTANTE: mensaje genérico (no revelar si email existe)
            throw ValidationException::withMessages([
                'email' => 'Las credenciales proporcionadas no son correctas.',
            ]);
        }

        // Login exitoso: limpiar rate limiter y regenerar sesión
        $request->clearRateLimiter();

        // Regenerar el ID de sesión previene Session Fixation Attack
        $request->session()->regenerate();

        return redirect()->intended(route('tasks.index'))
            ->with('success', 'Sesión iniciada correctamente.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        // Invalida la sesión completamente
        $request->session()->invalidate();

        // Regenera el token CSRF (previene CSRF post-logout)
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Has cerrado sesión correctamente.');
    }
}