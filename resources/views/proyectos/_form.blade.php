{{--
    Partial compartido por create.blade.php y edit.blade.php.
    Variables esperadas:
      $proyecto (Proyecto|null) — null en creación, modelo en edición.
--}}
@php
    $esEdicion = isset($proyecto);
    $accion    = $esEdicion ? route('proyectos.update', $proyecto) : route('proyectos.store');
@endphp

<form action="{{ $accion }}" method="POST" novalidate>
    @csrf
    @if($esEdicion)
        @method('PUT')
    @endif

    <div class="space-y-5">

        {{-- Nombre --}}
        <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                Nombre <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                value="{{ old('nombre', $proyecto->nombre ?? '') }}"
                maxlength="150"
                placeholder="Ej. Rediseño de sitio web"
                class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                       focus:border-indigo-500 focus:ring-indigo-500
                       @error('nombre') border-red-400 bg-red-50 @enderror"
                autofocus
            >
            @error('nombre')
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
                rows="4"
                placeholder="Describe el objetivo o alcance del proyecto…"
                class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                       focus:border-indigo-500 focus:ring-indigo-500 resize-none
                       @error('descripcion') border-red-400 bg-red-50 @enderror"
            >{{ old('descripcion', $proyecto->descripcion ?? '') }}</textarea>
            @error('descripcion')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Fecha límite --}}
        <div>
            <label for="fecha_limite" class="block text-sm font-medium text-gray-700 mb-1">
                Fecha límite
                <span class="text-gray-400 font-normal">(opcional)</span>
            </label>
            <input
                type="date"
                id="fecha_limite"
                name="fecha_limite"
                value="{{ old('fecha_limite', isset($proyecto) && $proyecto->fecha_limite ? $proyecto->fecha_limite->format('Y-m-d') : '') }}"
                class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                       focus:border-indigo-500 focus:ring-indigo-500
                       @error('fecha_limite') border-red-400 bg-red-50 @enderror"
            >
            @error('fecha_limite')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

    </div>

    {{-- Acciones --}}
    <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
        <a
            href="{{ route('proyectos.index') }}"
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
            {{ $esEdicion ? 'Guardar cambios' : 'Crear proyecto' }}
        </button>
    </div>

</form>
