{{--
    Componente: <x-crud.action-buttons>
    Grupo de botones de acción para filas de tabla CRUD (ver, editar, eliminar).
    El botón eliminar abre el modal global de confirmación via Alpine.js.

    Props:
      showUrl     string|null  URL de la vista detalle (opcional)
      editUrl     string|null  URL del formulario de edición (opcional)
      deleteUrl   string|null  URL del endpoint DELETE (opcional)
      deleteName  string       Nombre del registro para mostrar en el modal

    Uso:
      <x-crud.action-buttons
          :show-url="route('proyectos.show', $proyecto)"
          :edit-url="route('proyectos.edit', $proyecto)"
          :delete-url="route('proyectos.destroy', $proyecto)"
          :delete-name="$proyecto->nombre"
      />

    Nota: deleteUrl requiere que el layout tenga x-data="crudApp" (ya incluido).
--}}
@props([
    'showUrl'    => null,
    'editUrl'    => null,
    'deleteUrl'  => null,
    'deleteName' => '',
])

<div class="flex items-center gap-0.5">

    {{-- Botón Ver --}}
    @if($showUrl)
        <a
            href="{{ $showUrl }}"
            title="Ver detalle"
            class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                   text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                         -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </a>
    @endif

    {{-- Botón Editar --}}
    @if($editUrl)
        <a
            href="{{ $editUrl }}"
            title="Editar"
            class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                   text-indigo-400 hover:text-indigo-600 hover:bg-indigo-50 transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                         m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </a>
    @endif

    {{-- Botón Eliminar — abre modal global de confirmación --}}
    @if($deleteUrl)
        <button
            @click="confirmDelete('{{ $deleteUrl }}', '{{ addslashes($deleteName) }}')"
            title="Eliminar"
            type="button"
            class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                   text-red-400 hover:text-red-600 hover:bg-red-50 transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7
                         m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </button>
    @endif

</div>
