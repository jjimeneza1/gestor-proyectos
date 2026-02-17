<x-app-layout>
    <x-slot name="title">Nuevo proyecto</x-slot>
    <x-slot name="header">Nuevo proyecto</x-slot>

    <x-slot name="breadcrumbs">
        <x-crud.breadcrumb :items="[
            ['label' => 'Dashboard',  'url' => route('dashboard')],
            ['label' => 'Proyectos',  'url' => route('proyectos.index')],
            ['label' => 'Nuevo proyecto'],
        ]"/>
    </x-slot>

    <div class="max-w-2xl">
        <x-crud.page-header
            title="Nuevo proyecto"
            subtitle="Completa los datos para registrar un proyecto."
        />

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            @include('proyectos._form')
        </div>
    </div>

</x-app-layout>
