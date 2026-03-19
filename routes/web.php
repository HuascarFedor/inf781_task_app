<?php

// routes/web.php
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// Rutas de recursos para tasks (genera 7 rutas automáticamente)
Route::resource('tasks', TaskController::class);