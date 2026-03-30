<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Hash::make() usa bcrypt con el cost configurado en hashing.php
        // NUNCA almacenar la contraseña en texto plano
        // NUNCA usar md5() o sha1() aquí

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Disparar evento Registered (permite verificación de email)
        event(new Registered($user));

        // Autenticar al usuario inmediatamente después del registro
        Auth::login($user);

        return redirect()->route('tasks.index')
            ->with('success', '¡Cuenta creada exitosamente! Bienvenido/a, ' . $user->name);
    }
}
