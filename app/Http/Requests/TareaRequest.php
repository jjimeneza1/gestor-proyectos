<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TareaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'titulo'      => ['required', 'string', 'max:200'],
            'descripcion' => ['nullable', 'string'],
            'prioridad'   => ['required', Rule::in(['alta', 'media', 'baja'])],
            'estado'      => ['required', Rule::in(['backlog', 'en_progreso', 'testing', 'terminada'])],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required'    => 'El título de la tarea es obligatorio.',
            'titulo.max'         => 'El título no puede superar los 200 caracteres.',
            'prioridad.required' => 'Debes seleccionar una prioridad.',
            'prioridad.in'       => 'La prioridad seleccionada no es válida.',
            'estado.required'    => 'Debes seleccionar un estado.',
            'estado.in'          => 'El estado seleccionado no es válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'titulo'      => 'título',
            'descripcion' => 'descripción',
            'prioridad'   => 'prioridad',
            'estado'      => 'estado',
        ];
    }
}
