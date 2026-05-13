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
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
                Rule::unique('tipos_curso', 'nombre_curso')->whereNull('deleted_at'),
            ],
            'descripcion_curso' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre_curso'      => 'nombre del curso',
            'descripcion_curso' => 'descripción del curso',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_curso.regex' => 'El nombre solo debe contener letras y espacios, sin números ni caracteres especiales.',
        ];
    }
}
