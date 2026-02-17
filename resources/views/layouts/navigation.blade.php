{{-- ================================================================
     SIDEBAR — Contenido de navegación lateral
     Para agregar módulos: copia un bloque <x-sidebar-link> y
     actualiza la ruta y el ícono SVG.
     ================================================================ --}}
<div class="flex flex-col h-full">

    {{-- Logo / Brand --}}
    <div class="flex items-center h-16 px-5 bg-gray-800 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0">
            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-500 shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z
                             M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z
                             M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z
                             M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </div>
            <span class="text-white font-semibold text-sm tracking-tight truncate">
                {{ config('app.name') }}
            </span>
        </a>
    </div>

    {{-- Links de navegación --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

        {{-- Sección: General --}}
        <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-widest">General</p>

        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </x-slot>
            Dashboard
        </x-sidebar-link>

        <x-sidebar-link
            :href="route('proyectos.index')"
            :active="request()->routeIs('proyectos.*')"
        >
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                             M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </x-slot>
            Proyectos
        </x-sidebar-link>

        {{-- ============================================================
             AGREGA AQUÍ LOS SIGUIENTES MÓDULOS CRUD
             ============================================================ --}}

    </nav>

    {{-- Info de usuario + logout (parte inferior) --}}
    <div class="p-3 border-t border-gray-700 shrink-0">
        <div class="flex items-center gap-3 px-3 py-2 mb-1">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-500 text-white text-xs font-semibold shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="flex items-center w-full gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-gray-700 hover:text-white transition"
            >
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Cerrar sesión
            </button>
        </form>
    </div>

</div>
