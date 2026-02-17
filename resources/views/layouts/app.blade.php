<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' · ' . config('app.name') : config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">

    <!-- Assets -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>

    @stack('styles')
</head>

{{-- crudApp registrado en app.js via Alpine.data() --}}
<body class="font-sans antialiased bg-gray-50" x-data="crudApp" @keydown.escape.window="closeModal()">

<div class="flex h-screen overflow-hidden">

    {{-- ================================================================
         SIDEBAR — fixed en mobile (overlay), static en desktop (flex)
         ================================================================ --}}

    {{-- Backdrop mobile --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-20 bg-gray-900/50 lg:hidden"
        style="display:none"
    ></div>

    {{-- Panel sidebar --}}
    <aside
        class="fixed inset-y-0 left-0 z-30 flex flex-col w-64 shrink-0 bg-gray-900
               transform transition-transform duration-300 ease-in-out
               lg:static lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        @include('layouts.navigation')
    </aside>

    {{-- ================================================================
         ÁREA PRINCIPAL
         ================================================================ --}}
    <div class="flex flex-col flex-1 min-w-0 overflow-y-auto">

        {{-- Topbar --}}
        <header class="sticky top-0 z-10 flex items-center h-16 px-4 bg-white border-b border-gray-200 sm:px-6 shrink-0">

            {{-- Hamburger (mobile) --}}
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="mr-3 p-1.5 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition lg:hidden"
                aria-label="Abrir menú"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Título de página --}}
            <div class="flex-1 min-w-0">
                @isset($header)
                    <h1 class="text-base font-semibold text-gray-800 truncate">{{ $header }}</h1>
                @endisset
            </div>

            {{-- Menú de usuario --}}
            <div class="relative ml-4" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    class="flex items-center gap-2 rounded-lg px-2 py-1.5 hover:bg-gray-100 focus:outline-none transition"
                >
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-semibold text-sm shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <span class="hidden text-sm font-medium text-gray-700 sm:block">{{ Auth::user()->name }}</span>
                    <svg class="hidden w-4 h-4 text-gray-400 sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50"
                    style="display:none"
                >
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-xs text-gray-400">Sesión iniciada como</p>
                        <p class="text-sm font-medium text-gray-800 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Flash messages (success, error, warning, info) --}}
        @if(session('success') || session('error') || session('warning') || session('info'))
            <div class="px-4 pt-4 sm:px-6 space-y-2">
                @foreach(['success', 'error', 'warning', 'info'] as $type)
                    @if(session($type))
                        <x-alert :type="$type" :message="session($type)" />
                    @endif
                @endforeach
            </div>
        @endif

        {{-- Breadcrumbs (slot opcional) --}}
        @isset($breadcrumbs)
            <div class="px-4 pt-3 sm:px-6">
                {{ $breadcrumbs }}
            </div>
        @endisset

        {{-- Contenido principal --}}
        <main class="flex-1 p-4 sm:p-6">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="px-6 py-3 text-xs text-gray-400 border-t border-gray-100 text-right shrink-0">
            {{ config('app.name') }} &copy; {{ date('Y') }}
        </footer>

    </div>
</div>

{{-- ================================================================
     MODAL GLOBAL — Confirmar eliminación
     Activar con: confirmDelete('url', 'Nombre del registro')
     ================================================================ --}}
<div
    x-show="modal.show"
    x-transition:enter="ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60"
    style="display:none"
>
    <div
        x-show="modal.show"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden"
        @click.stop
    >
        <div class="p-6 text-center">
            {{-- Icono --}}
            <div
                class="flex items-center justify-center w-14 h-14 mx-auto mb-4 rounded-full"
                :class="modal.type === 'danger' ? 'bg-red-100' : 'bg-yellow-100'"
            >
                <svg
                    class="w-7 h-7"
                    :class="modal.type === 'danger' ? 'text-red-600' : 'text-yellow-600'"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>

            <h3 class="text-lg font-semibold text-gray-900" x-text="modal.title"></h3>
            <p class="mt-2 text-sm text-gray-500" x-text="modal.message"></p>
        </div>

        <div class="flex gap-3 px-6 pb-6">
            <button
                @click="closeModal()"
                class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none transition"
            >
                Cancelar
            </button>

            {{-- Form con método DELETE. La action se inyecta desde Alpine --}}
            <form :action="modal.action" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="w-full px-4 py-2 text-sm font-medium text-white rounded-lg focus:outline-none transition"
                    :class="modal.type === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-yellow-600 hover:bg-yellow-700'"
                >
                    Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ================================================================
     NOTIFICACIONES TOAST
     Activar con: notify('Mensaje', 'success')
     Tipos: success | error | warning | info
     ================================================================ --}}
<div class="fixed bottom-4 right-4 z-50 flex flex-col gap-2 w-80 pointer-events-none" aria-live="polite">
    <template x-for="n in notifications" :key="n.id">
        <div
            x-show="n.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="flex items-start gap-3 p-4 rounded-xl shadow-lg border text-sm pointer-events-auto"
            :class="{
                'bg-green-50  border-green-200  text-green-800':  n.type === 'success',
                'bg-red-50    border-red-200    text-red-800':    n.type === 'error',
                'bg-yellow-50 border-yellow-200 text-yellow-800': n.type === 'warning',
                'bg-blue-50   border-blue-200   text-blue-800':   n.type === 'info',
            }"
        >
            <span class="flex-1" x-text="n.message"></span>
            <button @click="removeNotification(n.id)" class="opacity-60 hover:opacity-100 shrink-0 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>

@stack('scripts')
</body>
</html>
