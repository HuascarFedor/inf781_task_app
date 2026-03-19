@extends('layouts.app')
@section('title', 'Lista de Tareas')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Mis Tareas</h2>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        + Nueva Tarea</a>
</div>

@forelse($tasks as $task)
    <div style="background:white; padding:16px; border-radius:8px;
                border:1px solid #e5e7eb; margin-bottom:12px;">
        <div style="display:flex; justify-content:space-between;">
            {{-- {{ }} es crítico aquí: el título viene de la BD --}}
            <h3 style="margin:0;">
                <a href="{{ route('tasks.show', $task) }}">
                    {{ $task->title }}
                </a>
            </h3>
            <span class="badge badge-{{ $task->status }}">
                {{ $task->status }}</span>
        </div>
        @if($task->description)
            <p style="color:#6b7280; margin:8px 0 0;">
                {{ Str::limit($task->description, 120) }}</p>
        @endif
    </div>
@empty
    <p>No hay tareas registradas. <a href="{{ route('tasks.create') }}">
        Crear la primera.</a></p>
@endforelse

{{ $tasks->links() }}
@endsection
