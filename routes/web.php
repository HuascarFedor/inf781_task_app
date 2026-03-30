<?php

// routes/web.php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ── Ruta raíz ──────────────────────────────────────────────────
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('tasks.index')
        : redirect()->route('login');
});

// ── Rutas de autenticación (solo para invitados) ───────────────
Route::middleware('guest')->group(function () {
    // Registro
    Route::get('/register',  [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    // Login
    Route::get('/login',  [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// ── Logout (solo usuarios autenticados) ───────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // ── Rutas protegidas de tasks ──────────────────────────────
    // El middleware 'auth' redirige a /login si no hay sesión activa
    Route::resource('tasks', TaskController::class);
});
