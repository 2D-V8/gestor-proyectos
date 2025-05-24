<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TasksController;

// Home route
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');




// Dashboard route with middleware
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Projects Routes
Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index'); // Lista de proyectos
Route::get('/projects/create', [ProjectsController::class, 'create'])->name('projects.create'); // Formulario de creación
Route::post('/projects', [ProjectsController::class, 'store'])->name('projects.store');
Route::get('/projects/{id}', [ProjectsController::class, 'show'])->name('projects.show'); // Ver un solo proyecto
Route::get('/projects/{id}/edit', [ProjectsController::class, 'edit'])->name('projects.edit'); // Formulario de edición
Route::put('/projects/{id}', [ProjectsController::class, 'update'])->name('projects.update'); // Actualizar proyecto
Route::delete('/projects/{id}', [ProjectsController::class, 'destroy'])->name('projects.destroy');
Route::post('/projects/{project}/share', [ProjectsController::class, 'share'])->name('projects.share');

// Tasks Routes

Route::post('/tasks', [TasksController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{id}', [TasksController::class, 'show'])->name('tasks.show'); // Ver una tarea
Route::post('/tasks', [TasksController::class, 'store'])->name('tasks.store');   // Crear tarea
Route::put('/tasks/{id}', [TasksController::class, 'update'])->name('tasks.update'); // Actualizar tarea
Route::delete('/tasks/{id}', [TasksController::class, 'destroy'])->name('tasks.destroy'); // Eliminar tarea
Route::put('/tasks/{task}', [TasksController::class, 'update'])->middleware('auth');




// Include other settings and authentication routes
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
