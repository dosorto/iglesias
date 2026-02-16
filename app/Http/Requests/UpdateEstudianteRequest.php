<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEstudianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $estudiante = $this->route('estudiante');
        $estudianteId = $estudiante?->id ?? $estudiante;

        return [
            'cuenta' => [
                'required',
                'string',
                'max:50',
                Rule::unique('estudiantes', 'cuenta')->ignore($estudianteId)->whereNull('deleted_at'),
            ],
            'persona_id' => ['required', 'integer', 'exists:personas,id'],
        ];
    }
}
