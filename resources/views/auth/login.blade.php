@extends('layouts.app')
@section('title', 'Iniciar Sesión')

@section('content')
<div style="max-width:480px; margin:0 auto;">
    <h2 style="color:#025043; border-bottom:3px solid #025043;
               padding-bottom:8px;">Iniciar Sesión</h2>
    @if($errors->any())
        <div class="alert-error">
            {{-- Mensaje genérico: no revelar si email existe o no --}}
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   autocomplete="email" autofocus required>
            @error('email')<span class="error-msg">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password"
                   autocomplete="current-password" required>
            @error('password')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-group" style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" id="remember" name="remember" value="1"
                   {{ old('remember') ? 'checked' : '' }}>
            <label for="remember" style="margin:0; font-weight:normal;">
                Recordarme en este dispositivo</label>
        </div>

        <button type="submit" class="btn btn-primary"
                style="width:100%;">Iniciar Sesión</button>
    </form>

    <p style="text-align:center; margin-top:16px;">
        ¿No tienes cuenta?
        <a href="{{ route('register') }}">Crear cuenta nueva</a>
    </p>
</div>
@endsection
