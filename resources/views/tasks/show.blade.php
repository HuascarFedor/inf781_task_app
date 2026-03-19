@extends('layouts.app')
@section('title', 'Detalle de Tarea')

@section('content')
<h2>Detalle de la Tarea</h2>

{{-- ============================================================
     DEMOSTRACIÓN DE SEGURIDAD: {{ }} vs {!! !!}
     Si el título contuviera: <script>alert('XSS')</script>
     Con {{ }}: se muestra como texto (SEGURO)
     Con {!! !!}: se ejecutaría el script (PELIGROSO)
     ============================================================ --}}

<div style="background:white; padding:24px; border-radius:8px;
            border:1px solid #e5e7eb;">

    <h3>{{ $task->title }}</h3>
    {{-- Blade escapa automáticamente. Si title = '<script>...',
         se mostrará como texto plano, no ejecutará nada. --}}

    <p><strong>Descripción:</strong></p>
    <p>{{ $task->description ?? 'Sin descripción.' }}</p>

    <p>
        <strong>Estado:</strong>
        <span class="badge badge-{{ $task->status }}">
            {{ match($task->status) {
                'pending'     => 'Pendiente',
                'in_progress' => 'En Progreso',
                'completed'   => 'Completada',
                default       => $task->status,
            } }}
        </span>
    </p>
    <p><strong>Prioridad:</strong>
        {{ ucfirst($task->priority) }}</p>

    @if($task->due_date)
        <p><strong>Fecha Límite:</strong>
            {{ $task->due_date->format('d/m/Y') }}</p>
    @endif

    @if($task->assigned_email)
        <p><strong>Responsable:</strong>
            {{ $task->assigned_email }}</p>
    @endif
    {{-- ZONA DE DEMOSTRACIÓN XSS (solo en desarrollo) --}}
    @if(config('app.debug'))
    <hr>
    <div style="background:#fff7ed; border:1px solid #f97316;
                padding:16px; border-radius:6px; margin-top:16px;">
        <strong style="color:#9a3412;">
            Demo Seguridad (solo visible con APP_DEBUG=true):
        </strong>
        <p>Con doble llave — SEGURO:</p>
        <code>{{ $task->title }}</code>

        <p>Con triple llave — PELIGROSO (no usar con input del usuario):</p>
        <code>{!! $task->title !!}</code>
        <small style="color:#9a3412;">
            Nota: si el título tuviera un script, arriba se ejecutaría.
        </small>
    </div>
    @endif

</div>
<div style="margin-top:16px;">
    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary">
        Editar</a>
    <form action="{{ route('tasks.destroy', $task) }}" method="POST"
          style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
            onclick="return confirm('¿Eliminar esta tarea?')">
            Eliminar</button>
    </form>
    <a href="{{ route('tasks.index') }}">Volver</a>
</div>
@endsection
