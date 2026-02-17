{{--
    Componente: <x-crud.page-header>
    Encabezado de página CRUD con título, subtítulo y slot de acciones.

    Props:
      title     string  Título principal (requerido)
      subtitle  string  Subtítulo descriptivo (opcional)

    Slots:
      actions   Botones de acción (ej: "Nuevo registro")

    Uso:
      <x-crud.page-header title="Proyectos" subtitle="Gestión de proyectos">
          <x-slot name="actions">
              <a href="{{ route('proyectos.create') }}" class="btn-primary">
                  + Nuevo proyecto
              </a>
          </x-slot>
      </x-crud.page-header>
--}}
@props([
    'title'    => '',
    'subtitle' => null,
])

<div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900 leading-tight">{{ $title }}</h2>
        @if($subtitle)
            <p class="text-sm text-gray-500 mt-0.5">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="flex items-center gap-2 mt-3 sm:mt-0 shrink-0">
            {{ $actions }}
        </div>
    @endisset
</div>
