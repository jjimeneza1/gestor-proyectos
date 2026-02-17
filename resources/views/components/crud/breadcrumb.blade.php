{{--
    Componente: <x-crud.breadcrumb>
    Ruta de navegación (breadcrumb) para páginas de CRUD.

    Props:
      items  array  Lista de objetos con 'label' y opcionalmente 'url'.
                    El último ítem (sin url) es la página actual.

    Uso:
      <x-slot name="breadcrumbs">
          <x-crud.breadcrumb :items="[
              ['label' => 'Dashboard',  'url' => route('dashboard')],
              ['label' => 'Proyectos',  'url' => route('proyectos.index')],
              ['label' => 'Nuevo proyecto'],
          ]"/>
      </x-slot>
--}}
@props([
    'items' => [],
])

<nav class="flex items-center flex-wrap gap-1 text-sm text-gray-500" aria-label="Breadcrumb">
    @foreach($items as $i => $item)

        {{-- Separador --}}
        @if($i > 0)
            <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        @endif

        {{-- Ítem con enlace o texto plano --}}
        @if(isset($item['url']))
            <a
                href="{{ $item['url'] }}"
                class="hover:text-indigo-600 transition truncate max-w-[180px]"
            >
                {{ $item['label'] }}
            </a>
        @else
            <span class="font-medium text-gray-700 truncate max-w-[180px]" aria-current="page">
                {{ $item['label'] }}
            </span>
        @endif

    @endforeach
</nav>
