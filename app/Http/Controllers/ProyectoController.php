<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Http\Requests\ProyectoRequest;

class ProyectoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * GET /proyectos
     * Lista paginada de proyectos del usuario autenticado.
     */
    public function index()
    {
        $proyectos = Proyecto::delUsuario()
            ->withCount('tareas')
            ->orderByRaw('fecha_limite IS NULL, fecha_limite ASC')
            ->paginate(10);

        return view('proyectos.index', compact('proyectos'));
    }

    /**
     * GET /proyectos/create
     * Muestra el formulario de creación.
     */
    public function create()
    {
        return view('proyectos.create');
    }

    /**
     * POST /proyectos
     * Persiste el nuevo proyecto.
     */
    public function store(ProyectoRequest $request)
    {
        Proyecto::create([
            'user_id'      => auth()->id(),
            'nombre'       => $request->nombre,
            'descripcion'  => $request->descripcion,
            'fecha_limite' => $request->fecha_limite,
        ]);

        return redirect()
            ->route('proyectos.index')
            ->with('success', 'Proyecto creado correctamente.');
    }

    /**
     * GET /proyectos/{proyecto}
     * Detalle del proyecto con sus tareas.
     */
    public function show(Proyecto $proyecto)
    {
        $this->autorizarPropietario($proyecto);

        $tareas = $proyecto->tareas()->orderBy('orden')->get()->groupBy('estado');

        return view('proyectos.show', compact('proyecto', 'tareas'));
    }

    /**
     * GET /proyectos/{proyecto}/edit
     * Muestra el formulario de edición.
     */
    public function edit(Proyecto $proyecto)
    {
        $this->autorizarPropietario($proyecto);

        return view('proyectos.edit', compact('proyecto'));
    }

    /**
     * PUT /proyectos/{proyecto}
     * Actualiza el proyecto.
     */
    public function update(ProyectoRequest $request, Proyecto $proyecto)
    {
        $this->autorizarPropietario($proyecto);

        $proyecto->update($request->validated());

        return redirect()
            ->route('proyectos.index')
            ->with('success', 'Proyecto actualizado correctamente.');
    }

    /**
     * DELETE /proyectos/{proyecto}
     * Elimina el proyecto (y sus tareas por cascada).
     */
    public function destroy(Proyecto $proyecto)
    {
        $this->autorizarPropietario($proyecto);

        $proyecto->delete();

        return redirect()
            ->route('proyectos.index')
            ->with('success', 'Proyecto eliminado correctamente.');
    }

    // ---------------------------------------------------------------
    // Helpers privados
    // ---------------------------------------------------------------

    /**
     * Aborta con 403 si el proyecto no pertenece al usuario autenticado.
     */
    private function autorizarPropietario(Proyecto $proyecto): void
    {
        abort_if($proyecto->user_id !== auth()->id(), 403, 'No tienes permiso para acceder a este proyecto.');
    }
}
