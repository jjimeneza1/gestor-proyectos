{{--
    Componente: <x-crud.table>
    Tabla reutilizable para listados CRUD con cabecera, cuerpo y paginación.

    Props:
      headers  array   Encabezados de columna. Ej: ['Nombre', 'Email', 'Acciones']

    Slots:
      default     Filas <tr> del cuerpo de la tabla
      pagination  Paginación ({{ $registros->links() }})

    Uso:
      <x-crud.table :headers="['Nombre', 'Email', 'Estado', 'Acciones']">

          @forelse($items as $item)
              <tr class="hover:bg-gray-50 transition">
                  <td class="px-6 py-4 text-sm text-gray-900">{{ $item->nombre }}</td>
                  <td class="px-6 py-4 text-sm text-gray-500">{{ $item->email }}</td>
                  <td class="px-6 py-4">...</td>
                  <td class="px-6 py-4">
                      <x-crud.action-buttons
                          :edit-url="route('items.edit', $item)"
                          :delete-url="route('items.destroy', $item)"
                          :delete-name="$item->nombre"
                      />
                  </td>
              </tr>
          @empty
              <tr>
                  <td colspan="{{ count($headers) }}" class="px-6 py-16 text-center">
                      <x-crud.empty-state message="No hay registros aún." />
                  </td>
              </tr>
          @endforelse

          <x-slot name="pagination">
              {{ $items->links() }}
          </x-slot>

      </x-crud.table>
--}}
@props([
    'headers' => [],
])

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">

            @if(!empty($headers))
                <thead class="bg-gray-50">
                    <tr>
                        @foreach($headers as $header)
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap"
                            >
                                {{ $header }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
            @endif

            <tbody class="divide-y divide-gray-100 bg-white">
                {{ $slot }}
            </tbody>

        </table>
    </div>

    {{-- Paginación --}}
    @isset($pagination)
        <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
            {{ $pagination }}
        </div>
    @endisset
</div>
