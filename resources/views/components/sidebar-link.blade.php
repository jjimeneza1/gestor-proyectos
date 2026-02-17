{{--
    Componente: <x-sidebar-link>
    Enlace de navegación para el sidebar con ícono y estado activo.

    Props:
      href    string  URL del enlace
      active  bool    Si está activo (resaltado)

    Slots:
      icon    SVG del ícono (5x5)
      default Texto del enlace

    Uso en navigation.blade.php:
      <x-sidebar-link :href="route('proyectos.index')" :active="request()->routeIs('proyectos.*')">
          <x-slot name="icon">
              <svg class="w-5 h-5" ...>...</svg>
          </x-slot>
          Proyectos
      </x-sidebar-link>
--}}
@props([
    'href'   => '#',
    'active' => false,
])

<a
    href="{{ $href }}"
    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
           {{ $active
               ? 'bg-indigo-600 text-white'
               : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
    @if($active) aria-current="page" @endif
>
    @isset($icon)
        <span class="shrink-0">{{ $icon }}</span>
    @endisset

    <span class="truncate">{{ $slot }}</span>
</a>
