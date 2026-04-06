<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Mostrar el formulario de login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Procesar el login con ciclo completo de sesión segura.
     *
     * Ciclo de 6 pasos:
     * 1. Rate Limiting (anti fuerza bruta)
     * 2. Autenticación (Auth::attempt)
     * 3. Regeneración de sesión (anti Session Fixation)
     * 4. Limpieza del rate limiter
     * 5. Log de auditoría
     * 6. Redirección segura
     */

    public function store(LoginRequest $request): RedirectResponse
    {
        // ── PASO 1: Rate Limiting ──
        $throttleKey = Str::transliterate(
            Str::lower($request->input('email')).'|'.$request->ip()
        );

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Demasiados intentos. Intente en {$seconds}s.",
                ]);
        }

        // ── PASO 2: Autenticación ──
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey, 60);

            // Log del intento fallido (sin registrar la contraseña)
            Log::warning('Login fallido', [
                'email' => $request->email,
                'ip'    => $request->ip(),
                'agent' => $request->userAgent(),
            ]);

            // Mensaje genérico: no revelar si el email existe
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Las credenciales no coinciden.',
                ]);
        }

        // ── PASO 3: Regeneración de sesión (CRÍTICO) ──
        // Genera un nuevo Session ID para prevenir Session Fixation.
        // El atacante que conocía el ID anterior ya no puede
        // usarlo porque ha sido invalidado.
        $request->session()->regenerate();


        // ── PASO 4: Limpiar rate limiter ──
        RateLimiter::clear($throttleKey);


        // ── PASO 5: Log de auditoría ──
        Log::info('Login exitoso', [
            'user_id'    => Auth::id(),
            'email'      => Auth::user()->email,
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
            'session_id' => $request->session()->getId(),
        ]);


        // ── Paso 6: Redirigir al destino original ──────────────
        // intended() recuerda la URL que el usuario intentaba visitar
        return redirect()->intended(route('tasks.index'))
            ->with('success', '¡Bienvenido/a, ' . Auth::user()->name . '!');
    }

    /**
     * Cerrar sesión con destrucción segura.
     *
     * invalidate(): Destruye TODOS los datos de la sesión.
     * regenerateToken(): Genera un nuevo token CSRF para
     * prevenir que un token robado se use después del logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log del logout
        Log::info('Logout', [
            'user_id'    => Auth::id(),
            'session_id' => $request->session()->getId(),
        ]);

        Auth::logout();

        // Destruir la sesión completa
        $request->session()->invalidate();

        // Regenerar el token CSRF
        $request->session()->regenerateToken();


        return redirect()->route('login')
            ->with('success', 'Has cerrado sesión correctamente. ¡Hasta pronto!');
    }
}
