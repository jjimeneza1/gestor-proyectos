<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TareaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Rutas protegidas (requieren autenticación)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Proyectos — CRUD completo (REST)
    // GET    /proyectos              → index   (listado)
    // GET    /proyectos/create       → create  (formulario nuevo)
    // POST   /proyectos              → store   (guardar nuevo)
    // GET    /proyectos/{id}         → show    (detalle)
    // GET    /proyectos/{id}/edit    → edit    (formulario edición)
    // PUT    /proyectos/{id}         → update  (guardar edición)
    // DELETE /proyectos/{id}         → destroy (eliminar)
    Route::resource('proyectos', ProyectoController::class);

    // Tareas — recurso anidado shallow bajo proyectos
    // Rutas con proyecto en URL (create, store):
    //   GET    /proyectos/{proyecto}/tareas/create → proyectos.tareas.create
    //   POST   /proyectos/{proyecto}/tareas        → proyectos.tareas.store
    // Rutas sin proyecto en URL (edit, update, destroy):
    //   GET    /tareas/{tarea}/edit                → tareas.edit
    //   PUT    /tareas/{tarea}                     → tareas.update
    //   DELETE /tareas/{tarea}                     → tareas.destroy
    Route::resource('proyectos.tareas', TareaController::class)
        ->shallow()
        ->except(['index', 'show']);

});

require __DIR__.'/auth.php';
