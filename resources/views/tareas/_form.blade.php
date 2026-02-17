{{--
    Partial compartido por tareas/create.blade.php y tareas/edit.blade.php.
    Variables esperadas:
      $proyecto (Proyecto)  — siempre presente (en edit viene de $tarea->proyecto)
      $tarea    (Tarea|null) — null en creación, modelo en edición
--}}
@php
    $esEdicion = isset($tarea);
    $accion    = $esEdicion
        ? route('tareas.update', $tarea)
        : route('proyectos.tareas.store', $proyecto);

    $prioridades = ['alta' => 'Alta', 'media' => 'Media', 'baja' => 'Baja'];
    $estados     = [
        'backlog'     => 'Backlog',
        'en_progreso' => 'En progreso',
        'testing'     => 'Testing',
        'terminada'   => 'Terminada',
    ];
@endphp

<form action="{{ $accion }}" method="POST" novalidate>
    @csrf
    @if($esEdicion)
        @method('PUT')
    @endif

    <div class="space-y-5">

        {{-- Título --}}
        <div>
            <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">
                Título <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="titulo"
                name="titulo"
                value="{{ old('titulo', $tarea->titulo ?? '') }}"
                maxlength="200"
                placeholder="Ej. Diseñar pantalla de login"
                class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                       focus:border-indigo-500 focus:ring-indigo-500
                       @error('titulo') border-red-400 bg-red-50 @enderror"
                autofocus
            >
            @error('titulo')
                <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Descripción --}}
        <div>
            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                Descripción
                <span class="text-gray-400 font-normal">(opcional)</span>
            </label>
            <textarea
                id="descripcion"
                name="descripcion"
                rows="3"
                placeholder="Criterios de aceptación, notas o contexto adicional…"
                class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                       focus:border-indigo-500 focus:ring-indigo-500 resize-none
                       @error('descripcion') border-red-400 bg-red-50 @enderror"
            >{{ old('descripcion', $tarea->descripcion ?? '') }}</textarea>
            @error('descripcion')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Prioridad y Estado en la misma fila --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Prioridad --}}
            <div>
                <label for="prioridad" class="block text-sm font-medium text-gray-700 mb-1">
                    Prioridad <span class="text-red-500">*</span>
                </label>
                <select
                    id="prioridad"
                    name="prioridad"
                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           @error('prioridad') border-red-400 bg-red-50 @enderror"
                >
                    <option value="" disabled {{ old('prioridad', $tarea->prioridad ?? '') === '' ? 'selected' : '' }}>
                        Selecciona…
                    </option>
                    @foreach($prioridades as $valor => $etiqueta)
                        <option
                            value="{{ $valor }}"
                            {{ old('prioridad', $tarea->prioridad ?? 'media') === $valor ? 'selected' : '' }}
                        >
                            {{ $etiqueta }}
                        </option>
                    @endforeach
                </select>
                @error('prioridad')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Estado --}}
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">
                    Estado <span class="text-red-500">*</span>
                </label>
                <select
                    id="estado"
                    name="estado"
                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           @error('estado') border-red-400 bg-red-50 @enderror"
                >
                    <option value="" disabled {{ old('estado', $tarea->estado ?? '') === '' ? 'selected' : '' }}>
                        Selecciona…
                    </option>
                    @foreach($estados as $valor => $etiqueta)
                        <option
                            value="{{ $valor }}"
                            {{ old('estado', $tarea->estado ?? 'backlog') === $valor ? 'selected' : '' }}
                        >
                            {{ $etiqueta }}
                        </option>
                    @endforeach
                </select>
                @error('estado')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>

    {{-- Acciones --}}
    <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
        <a
            href="{{ route('proyectos.show', $proyecto) }}"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300
                   rounded-lg hover:bg-gray-50 transition"
        >
            Cancelar
        </a>
        <button
            type="submit"
            class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg
                   hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500
                   focus:ring-offset-2 transition"
        >
            {{ $esEdicion ? 'Guardar cambios' : 'Crear tarea' }}
        </button>
    </div>

</form>
