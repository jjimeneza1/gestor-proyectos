<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Tarea;
use App\Http\Requests\TareaRequest;

class TareaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * GET /proyectos/{proyecto}/tareas/create
     * Formulario para crear una tarea dentro de un proyecto.
     */
    public function create(Proyecto $proyecto)
    {
        $this->autorizarProyecto($proyecto);

        return view('tareas.create', compact('proyecto'));
    }

    /**
     * POST /proyectos/{proyecto}/tareas
     * Persiste la nueva tarea.
     */
    public function store(TareaRequest $request, Proyecto $proyecto)
    {
        $this->autorizarProyecto($proyecto);

        // El orden es el siguiente disponible dentro del proyecto
        $orden = $proyecto->tareas()->max('orden') + 1;

        $proyecto->tareas()->create([
            'titulo'      => $request->titulo,
            'descripcion' => $request->descripcion,
            'prioridad'   => $request->prioridad,
            'estado'      => $request->estado,
            'orden'       => $orden,
        ]);

        return redirect()
            ->route('proyectos.show', $proyecto)
            ->with('success', 'Tarea creada correctamente.');
    }

    /**
     * GET /tareas/{tarea}/edit
     * Formulario de edición (ruta shallow: no requiere proyecto en la URL).
     */
    public function edit(Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        $tarea->load('proyecto');

        return view('tareas.edit', compact('tarea'));
    }

    /**
     * PUT /tareas/{tarea}
     * Actualiza la tarea.
     */
    public function update(TareaRequest $request, Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        $tarea->update($request->validated());

        return redirect()
            ->route('proyectos.show', $tarea->proyecto_id)
            ->with('success', 'Tarea actualizada correctamente.');
    }

    /**
     * DELETE /tareas/{tarea}
     * Elimina la tarea.
     */
    public function destroy(Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        $proyectoId = $tarea->proyecto_id;
        $tarea->delete();

        return redirect()
            ->route('proyectos.show', $proyectoId)
            ->with('success', 'Tarea eliminada correctamente.');
    }

    // ---------------------------------------------------------------
    // Helpers de autorización
    // ---------------------------------------------------------------

    /**
     * Verifica que el proyecto pertenezca al usuario autenticado.
     */
    private function autorizarProyecto(Proyecto $proyecto): void
    {
        abort_if(
            $proyecto->user_id !== auth()->id(),
            403,
            'No tienes permiso para gestionar tareas de este proyecto.'
        );
    }

    /**
     * Verifica que la tarea pertenezca a un proyecto del usuario autenticado.
     */
    private function autorizarTarea(Tarea $tarea): void
    {
        abort_if(
            $tarea->proyecto->user_id !== auth()->id(),
            403,
            'No tienes permiso para modificar esta tarea.'
        );
    }
}
