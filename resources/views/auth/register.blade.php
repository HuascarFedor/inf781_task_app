@extends('layouts.app')
@section('title', 'Crear Cuenta')

@section('content')
<div style="max-width:480px; margin:0 auto;">
    <h2 style="color:#025043; border-bottom:3px solid #025043;
               padding-bottom:8px;">Crear Cuenta Nueva</h2>

    @if($errors->any())
        <div class="alert-error">
            <strong>Corrija los siguientes errores:</strong>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Nombre Completo *</label>
            <input type="text" id="name" name="name"
                   value="{{ old('name') }}"
                   placeholder="Ej: Juan Pérez López"
                   autocomplete="name" required>
            @error('name')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="email">Correo Electrónico *</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="correo@ejemplo.com"
                   autocomplete="email" required>
            @error('email')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="password">Contraseña *</label>
            <input type="password" id="password" name="password"
                   placeholder="Mínimo 8 chars, mayús., número, símbolo"
                   autocomplete="new-password" required>
            <small style="color:#64748b;">
                Debe contener: mayúsculas, minúsculas, números y símbolos (@#$!...)
            </small>
            @error('password')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar Contraseña *</label>
            <input type="password" id="password_confirmation"
                   name="password_confirmation"
                   placeholder="Repita la contraseña"
                   autocomplete="new-password" required>
            @error('password_confirmation')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>
        {{-- Indicador visual de fortaleza (solo UI, la validación real es server-side) --}}
        <div style="background:#f0faf7; border:1px solid #025043;
                    padding:12px; border-radius:6px; margin-bottom:16px;
                    font-size:0.875rem;">
            <strong style="color:#025043;">Requisitos de contraseña:</strong>
            <ul style="margin:4px 0; padding-left:20px;">
                <li>Mínimo 8 caracteres</li>
                <li>Al menos una letra mayúscula (A-Z)</li>
                <li>Al menos una letra minúscula (a-z)</li>
                <li>Al menos un número (0-9)</li>
                <li>Al menos un símbolo (!@#$%^&*)</li>
            </ul>
        </div>

        <button type="submit" class="btn btn-primary"
                style="width:100%;">Crear Cuenta</button>
    </form>
    <p style="text-align:center; margin-top:16px;">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}">Iniciar Sesión</a>
    </p>
</div>
@endsection
