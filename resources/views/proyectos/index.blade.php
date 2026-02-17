<x-app-layout>
    <x-slot name="title">Proyectos</x-slot>
    <x-slot name="header">Proyectos</x-slot>

    <x-slot name="breadcrumbs">
        <x-crud.breadcrumb :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Proyectos'],
        ]"/>
    </x-slot>

    {{-- Encabezado de página --}}
    <x-crud.page-header
        title="Proyectos"
        subtitle="Gestión de tus proyectos"
    >
        <x-slot name="actions">
            <a
                href="{{ route('proyectos.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white
                       text-sm font-medium rounded-lg hover:bg-indigo-700 transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo proyecto
            </a>
        </x-slot>
    </x-crud.page-header>

    {{-- Tabla --}}
    <x-crud.table :headers="['Nombre', 'Descripción', 'Fecha límite', 'Tareas', 'Acciones']">

        @forelse($proyectos as $proyecto)
            <tr class="hover:bg-gray-50 transition">

                {{-- Nombre --}}
                <td class="px-6 py-4">
                    <a
                        href="{{ route('proyectos.show', $proyecto) }}"
                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 hover:underline"
                    >
                        {{ $proyecto->nombre }}
                    </a>
                </td>

                {{-- Descripción --}}
                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                    {{ Str::limit($proyecto->descripcion, 70, '…') ?: '—' }}
                </td>

                {{-- Fecha límite con indicador de vencimiento --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($proyecto->fecha_limite)
                        @php
                            $hoy      = now()->startOfDay();
                            $limite   = $proyecto->fecha_limite->startOfDay();
                            $diffDias = $hoy->diffInDays($limite, false);
                        @endphp
                        <span class="inline-flex items-center gap-1 text-sm
                            @if($diffDias < 0)   text-red-600   font-semibold
                            @elseif($diffDias <= 3) text-yellow-600 font-medium
                            @else text-gray-600
                            @endif">
                            @if($diffDias < 0)
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                            {{ $proyecto->fecha_limite->format('d/m/Y') }}
                        </span>
                    @else
                        <span class="text-sm text-gray-400">—</span>
                    @endif
                </td>

                {{-- Conteo de tareas --}}
                <td class="px-6 py-4">
                    <span class="inline-flex items-center justify-center min-w-[1.5rem] h-6 px-2
                                 text-xs font-semibold rounded-full
                                 {{ $proyecto->tareas_count > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $proyecto->tareas_count }}
                    </span>
                </td>

                {{-- Acciones --}}
                <td class="px-6 py-4">
                    <x-crud.action-buttons
                        :show-url="route('proyectos.show', $proyecto)"
                        :edit-url="route('proyectos.edit', $proyecto)"
                        :delete-url="route('proyectos.destroy', $proyecto)"
                        :delete-name="$proyecto->nombre"
                    />
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-16">
                    <x-crud.empty-state
                        message="Aún no tienes proyectos. ¡Crea el primero!"
                        :create-url="route('proyectos.create')"
                        create-label="+ Nuevo proyecto"
                    />
                </td>
            </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $proyectos->links() }}
        </x-slot>

    </x-crud.table>

</x-app-layout>
