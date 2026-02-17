<x-app-layout>
    <x-slot name="title">Nueva tarea — {{ $proyecto->nombre }}</x-slot>
    <x-slot name="header">Nueva tarea</x-slot>

    <x-slot name="breadcrumbs">
        <x-crud.breadcrumb :items="[
            ['label' => 'Dashboard',         'url' => route('dashboard')],
            ['label' => 'Proyectos',         'url' => route('proyectos.index')],
            ['label' => $proyecto->nombre,   'url' => route('proyectos.show', $proyecto)],
            ['label' => 'Nueva tarea'],
        ]"/>
    </x-slot>

    <div class="max-w-2xl">
        <x-crud.page-header
            title="Nueva tarea"
            subtitle="Proyecto: {{ $proyecto->nombre }}"
        />

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            @include('tareas._form', ['proyecto' => $proyecto])
        </div>
    </div>

</x-app-layout>
