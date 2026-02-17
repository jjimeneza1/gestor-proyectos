{{--
    Componente: <x-crud.empty-state>
    Estado vacío para tablas sin registros.

    Props:
      message    string  Texto principal
      createUrl  string  URL para crear el primer registro (opcional)
      createLabel string  Etiqueta del botón (default: '+ Nuevo registro')

    Uso (dentro de @empty en @forelse):
      @empty
          <tr>
              <td colspan="5" class="px-6 py-16">
                  <x-crud.empty-state
                      message="No hay proyectos registrados."
                      :create-url="route('proyectos.create')"
                      create-label="+ Nuevo proyecto"
                  />
              </td>
          </tr>
--}}
@props([
    'message'     => 'No hay registros que mostrar.',
    'createUrl'   => null,
    'createLabel' => '+ Nuevo registro',
])

<div class="flex flex-col items-center justify-center text-center py-4">
    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-gray-100 mb-3">
        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                     a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
    </div>
    <p class="text-sm font-medium text-gray-600">{{ $message }}</p>

    @if($createUrl)
        <a
            href="{{ $createUrl }}"
            class="mt-3 inline-flex items-center px-4 py-2 text-sm font-medium text-white
                   bg-indigo-600 rounded-lg hover:bg-indigo-700 transition"
        >
            {{ $createLabel }}
        </a>
    @endif
</div>
