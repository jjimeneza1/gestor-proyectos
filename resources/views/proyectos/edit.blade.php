<x-app-layout>
    <x-slot name="title">Editar — {{ $proyecto->nombre }}</x-slot>
    <x-slot name="header">Editar proyecto</x-slot>

    <x-slot name="breadcrumbs">
        <x-crud.breadcrumb :items="[
            ['label' => 'Dashboard',          'url' => route('dashboard')],
            ['label' => 'Proyectos',          'url' => route('proyectos.index')],
            ['label' => $proyecto->nombre,    'url' => route('proyectos.show', $proyecto)],
            ['label' => 'Editar'],
        ]"/>
    </x-slot>

    <div class="max-w-2xl">
        <x-crud.page-header
            title="Editar proyecto"
            subtitle="{{ $proyecto->nombre }}"
        />

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            @include('proyectos._form', ['proyecto' => $proyecto])
        </div>
    </div>

</x-app-layout>
