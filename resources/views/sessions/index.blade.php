@extends('layouts.app')

@section('title', 'Sesiones Activas')

@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Sesiones Activas</h2>

    @if(session('status'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('status') }}
        </div>
    @endif

    @foreach($sessions as $s)
    <div class="border rounded p-4 mb-3
                {{ $s->is_current ? 'border-green-500 bg-green-50' : '' }}">
        <div class="flex justify-between items-start">
            <div>
                <p class="font-semibold">
                    {{ Str::limit($s->user_agent, 60) }}
                    @if($s->is_current)
                        <span class="text-green-600 text-sm">(Esta sesión)</span>
                    @endif
                </p>
                <p class="text-sm text-gray-500">
                    IP: {{ $s->ip_address }} &middot;
                    {{ $s->last_active_at }}
                </p>
            </div>
            @unless($s->is_current)
            <form method="POST"
                  action="{{ route('sessions.destroy', $s->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-red-500 hover:text-red-700 text-sm">
                    Cerrar
                </button>
            </form>
            @endunless
        </div>
    </div>
    @endforeach

    <hr class="my-6">

    <form method="POST"
          action="{{ route('sessions.destroyOthers') }}">
        @csrf
        @method('DELETE')
        <label class="block text-sm font-medium mb-1">
            Contraseña actual
        </label>
        <input type="password" name="password"
               class="border rounded p-2 w-full mb-2" required>
        @error('password')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
        <button type="submit"
                class="bg-red-500 text-white px-4 py-2 rounded">
            Cerrar todas las demás sesiones
        </button>
    </form>
</div>
