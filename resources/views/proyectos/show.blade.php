<x-app-layout>
    <x-slot name="title">{{ $proyecto->nombre }}</x-slot>
    <x-slot name="header">{{ $proyecto->nombre }}</x-slot>

    <x-slot name="breadcrumbs">
        <x-crud.breadcrumb :items="[
            ['label' => 'Dashboard',       'url' => route('dashboard')],
            ['label' => 'Proyectos',       'url' => route('proyectos.index')],
            ['label' => $proyecto->nombre],
        ]"/>
    </x-slot>

    {{-- Encabezado con acciones del proyecto --}}
    <x-crud.page-header :title="$proyecto->nombre">
        <x-slot name="actions">
            <a
                href="{{ route('proyectos.tareas.create', $proyecto) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm
                       font-medium rounded-lg hover:bg-indigo-700 transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva tarea
            </a>
            <a
                href="{{ route('proyectos.edit', $proyecto) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm
                       font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                             m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
            <button
                @click="confirmDelete('{{ route('proyectos.destroy', $proyecto) }}', '{{ addslashes($proyecto->nombre) }}')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white text-red-600 text-sm
                       font-medium rounded-lg border border-red-200 hover:bg-red-50 transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7
                             m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Eliminar
            </button>
        </x-slot>
    </x-crud.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Panel de info del proyecto ─────────────────────────── --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Detalles del proyecto</h3>

                <div>
                    <p class="text-xs text-gray-400 mb-1">Descripción</p>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        {{ $proyecto->descripcion ?: 'Sin descripción.' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-400 mb-1">Fecha límite</p>
                    @if($proyecto->fecha_limite)
                        @php
                            $diffDias = now()->startOfDay()->diffInDays($proyecto->fecha_limite->startOfDay(), false);
                        @endphp
                        <p class="text-sm font-medium
                            @if($diffDias < 0) text-red-600
                            @elseif($diffDias <= 3) text-yellow-600
                            @else text-gray-700 @endif">
                            {{ $proyecto->fecha_limite->format('d/m/Y') }}
                            <span class="font-normal text-xs ml-1 text-gray-400">
                                @if($diffDias < 0)(vencido hace {{ abs($diffDias) }} días)
                                @elseif($diffDias === 0)(vence hoy)
                                @elseif($diffDias === 1)(vence mañana)
                                @else({{ $diffDias }} días restantes)
                                @endif
                            </span>
                        </p>
                    @else
                        <p class="text-sm text-gray-400">Sin fecha límite</p>
                    @endif
                </div>

                <div>
                    <p class="text-xs text-gray-400 mb-1">Creado</p>
                    <p class="text-sm text-gray-700">{{ $proyecto->created_at->format('d/m/Y H:i') }}</p>
                </div>

                {{-- Resumen de tareas --}}
                @php $totalTareas = $tareas->flatten()->count(); @endphp
                <div class="pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-400 mb-2">Progreso</p>
                    @php
                        $terminadas  = ($tareas['terminada'] ?? collect())->count();
                        $porcentaje  = $totalTareas > 0 ? round($terminadas / $totalTareas * 100) : 0;
                    @endphp
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                        <span>{{ $terminadas }} / {{ $totalTareas }} terminadas</span>
                        <span class="font-semibold text-indigo-600">{{ $porcentaje }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div
                            class="bg-indigo-500 h-1.5 rounded-full transition-all"
                            style="width: {{ $porcentaje }}%"
                        ></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Columnas Kanban por estado ──────────────────────────── --}}
        <div class="lg:col-span-2 space-y-4">
            @php
                $columnas = [
                    'backlog'     => ['label' => 'Backlog',     'badge' => 'bg-gray-100  text-gray-600'],
                    'en_progreso' => ['label' => 'En progreso', 'badge' => 'bg-blue-100  text-blue-700'],
                    'testing'     => ['label' => 'Testing',     'badge' => 'bg-yellow-100 text-yellow-700'],
                    'terminada'   => ['label' => 'Terminada',   'badge' => 'bg-green-100  text-green-700'],
                ];
                $badgesPrioridad = [
                    'alta'  => 'bg-red-100    text-red-700',
                    'media' => 'bg-yellow-100 text-yellow-700',
                    'baja'  => 'bg-gray-100   text-gray-500',
                ];
            @endphp

            @foreach($columnas as $estado => $cfg)
                @php $tareasEstado = $tareas[$estado] ?? collect(); @endphp

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                    {{-- Cabecera de columna --}}
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $cfg['badge'] }}">
                                {{ $cfg['label'] }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium">{{ $tareasEstado->count() }}</span>
                        </div>
                        <a
                            href="{{ route('proyectos.tareas.create', $proyecto) }}?estado={{ $estado }}"
                            class="text-xs text-indigo-500 hover:text-indigo-700 font-medium transition"
                            title="Agregar tarea en {{ $cfg['label'] }}"
                        >+ Añadir</a>
                    </div>

                    {{-- Lista de tareas --}}
                    @if($tareasEstado->isEmpty())
                        <p class="px-4 py-5 text-xs text-gray-400 text-center italic">Sin tareas en esta columna</p>
                    @else
                        <ul class="divide-y divide-gray-50">
                            @foreach($tareasEstado as $tarea)
                                <li class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition group">

                                    {{-- Badge prioridad --}}
                                    <span class="mt-0.5 inline-flex shrink-0 items-center px-1.5 py-0.5 rounded text-xs font-medium {{ $badgesPrioridad[$tarea->prioridad] ?? '' }}">
                                        {{ ucfirst($tarea->prioridad) }}
                                    </span>

                                    {{-- Contenido --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $tarea->titulo }}</p>
                                        @if($tarea->descripcion)
                                            <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $tarea->descripcion }}</p>
                                        @endif
                                    </div>

                                    {{-- Acciones (visibles al hacer hover) --}}
                                    <div class="shrink-0 opacity-0 group-hover:opacity-100 transition">
                                        <x-crud.action-buttons
                                            :edit-url="route('tareas.edit', $tarea)"
                                            :delete-url="route('tareas.destroy', $tarea)"
                                            :delete-name="$tarea->titulo"
                                        />
                                    </div>

                                </li>
                            @endforeach
                        </ul>
                    @endif

                </div>
            @endforeach

            {{-- Botón flotante: Nueva tarea --}}
            @if($totalTareas === 0)
                <div class="text-center py-4">
                    <x-crud.empty-state
                        message="Este proyecto no tiene tareas aún."
                        :create-url="route('proyectos.tareas.create', $proyecto)"
                        create-label="+ Crear primera tarea"
                    />
                </div>
            @endif

        </div>

    </div>

</x-app-layout>
