@extends('layouts.app')
@section('title', 'Nueva Tarea')

@section('content')
<h2>Crear Nueva Tarea</h2>

{{-- Mostrar todos los errores de validación --}}
@if($errors->any())
    <div class="alert-error">
        <strong>Corrija los siguientes errores:</strong>
        <ul>
            @foreach($errors->all() as $error)
                {{-- {{ }} escapa el mensaje. Si el mensaje contuviera HTML,
                     NO se ejecutaría. Esto es protección adicional. --}}
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- action apunta a tasks.store, method POST con CSRF --}}
<form action="{{ route('tasks.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="title">Título *</label>
        {{-- old('title') repopula el campo si la validación falla --}}
        <input type="text" id="title" name="title"
               value="{{ old('title') }}"
               placeholder="Ej: Revisar documentación de seguridad"
               maxlength="200">
        @error('title')
            <span class="error-msg">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="description">Descripción</label>
        <textarea id="description" name="description" rows="4"
                  maxlength="2000">{{ old('description') }}</textarea>
        @error('description')
            <span class="error-msg">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="status">Estado *</label>
        <select id="status" name="status">
            <option value="">-- Seleccione --</option>
            <option value="pending"
                {{ old('status') == 'pending' ? 'selected' : '' }}>
                Pendiente</option>
            <option value="in_progress"
                {{ old('status') == 'in_progress' ? 'selected' : '' }}>
                En Progreso</option>
            <option value="completed"
                {{ old('status') == 'completed' ? 'selected' : '' }}>
                Completada</option>
        </select>
        @error('status')
            <span class="error-msg">{{ $message }}</span>
        @enderror
    </div>

        <div class="form-group">
        <label for="priority">Prioridad *</label>
        <select id="priority" name="priority">
            <option value="">-- Seleccione --</option>
            <option value="low"
                {{ old('priority') == 'low' ? 'selected' : '' }}>Baja</option>
            <option value="medium"
                {{ old('priority') == 'medium' ? 'selected' : '' }}>Media</option>
            <option value="high"
                {{ old('priority') == 'high' ? 'selected' : '' }}>Alta</option>
        </select>
        @error('priority')
            <span class="error-msg">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="due_date">Fecha Límite</label>
        <input type="date" id="due_date" name="due_date"
               value="{{ old('due_date') }}">
        @error('due_date')
            <span class="error-msg">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="assigned_email">Email del Responsable</label>
        <input type="email" id="assigned_email" name="assigned_email"
               value="{{ old('assigned_email') }}"
               placeholder="responsable@ejemplo.com">
        @error('assigned_email')
            <span class="error-msg">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Crear Tarea</button>
    <a href="{{ route('tasks.index') }}">Cancelar</a>
</form>
@endsection
