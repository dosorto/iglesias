<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTipoCursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_curso' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tipo_curso', 'nombre_curso')->whereNull('deleted_at'),
            ],
            'descripcion_curso' => ['nullable', 'string', 'max:1000'],
            'estado_curso' => ['required', 'string', Rule::in(['activo', 'inactivo'])],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre_curso' => 'nombre del curso',
            'descripcion_curso' => 'descripción del curso',
            'estado_curso' => 'estado del curso',
        ];
    }
}
