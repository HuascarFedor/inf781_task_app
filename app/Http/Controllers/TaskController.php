<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // $tasks = Task::latest()->paginate(10);
        // return view('tasks.index', compact('tasks'));

        $tasks = Task::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('tasks.index', compact('tasks'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('tasks.create');
    }


    /**
     * Laravel inyecta StoreTaskRequest automáticamente.
     * Si la validación falla, redirige con errores SIN llegar aquí.
     * Si pasa, $request ya contiene datos validados y sanitizados.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        // validated() retorna solo los campos que pasaron las reglas
        $validated = $request->validated();

        // user_id hardcodeado temporalmente (Guía 4 implementa Auth)
        // $validated['user_id'] = '00000000-0000-0000-0000-000000000001';
        $validated['user_id'] = Auth::id();

        $task = Task::create($validated);

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Tarea creada exitosamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Task $task): View
    {
        return view('tasks.show', compact('task'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $validated = $request->validated();
       
        $task->update($validated);
        return redirect()->route('tasks.show', $task)
            ->with('success', 'Tarea actualizada correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete(); // SoftDelete: marca deleted_at, no borra físicamente
        return redirect()->route('tasks.index')
            ->with('success', 'Tarea eliminada.');
    }
}
