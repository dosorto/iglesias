<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3', 'max:200'],
            'fecha_inicio' => ['nullable', 'date', 'after_or_equal:today'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', 'in:Activo,Finalizado,Cancelado'],
            'tipo_curso_id' => ['required', 'exists:tipos_curso,id'],
            'instructor_id' => ['required', 'exists:instructores,id'],
            'encargado_id' => ['required', 'exists:encargado,id'],
        ];
    }
}