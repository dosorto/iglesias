<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInscripcionCursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'curso_id'            => ['required', 'integer', 'exists:cursos,id'],
            'feligres_id'         => ['required', 'integer', 'exists:feligres,id',
                Rule::unique('inscripciones_curso')->where(function ($query) {
                    return $query->where('curso_id', $this->curso_id)
                                 ->whereNull('deleted_at');
                })
            ],
            'fecha_inscripcion'   => ['required', 'date'],
            'aprobado'            => ['nullable', 'boolean'],
            'certificado_emitido' => ['nullable', 'boolean'],
            'fecha_certificado'   => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'curso_id'            => 'curso',
            'feligres_id'         => 'feligrés',
            'fecha_inscripcion'   => 'fecha de inscripción',
            'aprobado'            => 'aprobado',
            'certificado_emitido' => 'certificado emitido',
            'fecha_certificado'   => 'fecha de certificado',
        ];
    }

    public function messages(): array
    {
        return [
            'feligres_id.unique' => 'Este feligrés ya está inscrito en este curso.',
        ];
    }
}