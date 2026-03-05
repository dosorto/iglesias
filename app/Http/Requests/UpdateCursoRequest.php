<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCursoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required','string','max:200'],
            'fecha_inicio' => ['nullable','date'],
            'fecha_fin' => ['nullable','date','after_or_equal:fecha_inicio'],
            'estado' => ['required','in:Activo,Finalizado,Cancelado'],

            'iglesia_id' => ['required','exists:iglesias,id'],
            'tipo_curso_id' => ['required','exists:tipos_curso,id'],
            'instructor_id' => ['required','exists:instructores,id'],
            'encargado_id' => ['required','exists:encargado,id'],
        ];
    }
}
