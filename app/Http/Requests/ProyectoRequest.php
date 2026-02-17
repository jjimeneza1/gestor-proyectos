<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProyectoRequest extends FormRequest
{
    /**
     * Solo usuarios autenticados pueden enviar este formulario.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Reglas de validación.
     * Se aplican tanto a store (POST) como a update (PUT/PATCH).
     */
    public function rules(): array
    {
        return [
            'nombre'       => ['required', 'string', 'max:150'],
            'descripcion'  => ['nullable', 'string'],
            'fecha_limite' => ['nullable', 'date'],
        ];
    }

    /**
     * Mensajes de error en español.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del proyecto es obligatorio.',
            'nombre.max'      => 'El nombre no puede superar los 150 caracteres.',
            'fecha_limite.date' => 'La fecha límite debe ser una fecha válida (YYYY-MM-DD).',
        ];
    }

    /**
     * Nombres de atributos en español para mensajes genéricos.
     */
    public function attributes(): array
    {
        return [
            'nombre'       => 'nombre',
            'descripcion'  => 'descripción',
            'fecha_limite' => 'fecha límite',
        ];
    }
}
