<x-app-layout>
    <x-slot name="title">Editar tarea — {{ $tarea->titulo }}</x-slot>
    <x-slot name="header">Editar tarea</x-slot>

    <x-slot name="breadcrumbs">
        <x-crud.breadcrumb :items="[
            ['label' => 'Dashboard',               'url' => route('dashboard')],
            ['label' => 'Proyectos',               'url' => route('proyectos.index')],
            ['label' => $tarea->proyecto->nombre,  'url' => route('proyectos.show', $tarea->proyecto)],
            ['label' => 'Editar tarea'],
        ]"/>
    </x-slot>

    <div class="max-w-2xl">
        <x-crud.page-header
            title="Editar tarea"
            subtitle="{{ $tarea->titulo }}"
        />

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            @include('tareas._form', [
                'proyecto' => $tarea->proyecto,
                'tarea'    => $tarea,
            ])
        </div>
    </div>

</x-app-layout>
