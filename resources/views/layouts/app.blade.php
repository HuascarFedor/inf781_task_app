<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- CSRF Token en meta tag para peticiones AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title','Tasks')</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px;
               margin: 0 auto; padding: 20px; background: #f8fafc; }
        .alert-success { background:#d1fae5; border:1px solid #059669;
                         padding:12px; border-radius:6px; margin-bottom:16px; }
        .alert-error   { background:#fee2e2; border:1px solid #dc2626;
                         padding:12px; border-radius:6px; margin-bottom:16px; }
        .form-group  { margin-bottom:16px; }
        label        { display:block; font-weight:bold; margin-bottom:4px; }
        input, textarea, select { width:100%; padding:8px;
                                  border:1px solid #d1d5db; border-radius:4px;
                                  box-sizing:border-box; }
        .error-msg   { color:#dc2626; font-size:0.875rem; margin-top:4px; }
        .btn         { padding:10px 20px; border:none; border-radius:4px;
                       cursor:pointer; font-size:1rem; }
        .btn-primary { background:#025043; color:white; }
        .btn-danger  { background:#dc2626; color:white; }
        .badge       { padding:3px 8px; border-radius:12px; font-size:0.8rem; }
        .badge-pending     { background:#fef3c7; color:#92400e; }
        .badge-in_progress { background:#dbeafe; color:#1e40af; }
        .badge-completed   { background:#d1fae5; color:#065f46; }
    </style>
</head>
<body>
    {{-- En el <header> del layout, reemplaza la sección de navegación: --}}
    <header style="border-bottom:3px solid #025043; padding-bottom:12px; margin-bottom:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h1 style="color:#025043; margin:0;">
                <a href="{{ route('tasks.index') }}" style="text-decoration:none;color:inherit;">
                    Tasks App</a>
            </h1>
            <div>
                @auth
                    {{-- @auth solo muestra contenido a usuarios autenticados --}}
                    <span style="color:#64748b; margin-right:12px;">
                        Hola, {{ auth()->user()->name }}</span>
                    <a href="{{ route('tasks.index') }}">Mis Tareas</a> |
                    <a href="{{ route('tasks.create') }}">Nueva Tarea</a> |
                    <form action="{{ route('logout') }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <button type="submit"
                                style="background:none;border:none;color:#dc2626;
                                    cursor:pointer;font-size:1rem;">
                            Cerrar Sesión</button>
                    </form>
                @else
                    {{-- @else muestra a visitantes no autenticados --}}
                    <a href="{{ route('login') }}">Iniciar Sesión</a> |
                    <a href="{{ route('register') }}">Registrarse</a>
                @endauth
            </div>
        </div>
    </header>


    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @yield('content')
</body>
</html>
