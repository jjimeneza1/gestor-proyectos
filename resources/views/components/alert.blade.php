{{--
    Componente: <x-alert>
    Muestra un mensaje flash con ícono y botón de cierre.

    Props:
      type        string  'success' | 'error' | 'warning' | 'info'
      message     string  Texto del mensaje
      dismissible bool    Si se puede cerrar (default: true)

    Uso:
      <x-alert type="success" message="Registro guardado correctamente." />
      <x-alert type="error"   message="Ocurrió un error." :dismissible="false" />
--}}
@props([
    'type'        => 'info',
    'message'     => '',
    'dismissible' => true,
])

@php
$styles = [
    'success' => 'bg-green-50  border-green-200  text-green-800',
    'error'   => 'bg-red-50    border-red-200    text-red-800',
    'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
    'info'    => 'bg-blue-50   border-blue-200   text-blue-800',
];

$icons = [
    'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'error'   => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
    'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    'info'    => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
];

$style = $styles[$type] ?? $styles['info'];
$icon  = $icons[$type]  ?? $icons['info'];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 max-h-20"
    x-transition:leave-end="opacity-0 max-h-0"
    class="flex items-start gap-3 p-4 rounded-xl border text-sm {{ $style }}"
    role="alert"
>
    {{-- Ícono --}}
    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
    </svg>

    {{-- Mensaje --}}
    <p class="flex-1">{{ $message }}</p>

    {{-- Botón cerrar --}}
    @if($dismissible)
        <button
            @click="show = false"
            class="opacity-50 hover:opacity-100 transition shrink-0"
            aria-label="Cerrar"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    @endif
</div>
