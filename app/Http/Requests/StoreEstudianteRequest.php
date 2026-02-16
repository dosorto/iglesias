<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEstudianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cuenta' => ['required', 'string', 'max:50', Rule::unique('estudiantes', 'cuenta')->whereNull('deleted_at')],
            'persona_id' => ['required', 'integer', 'exists:personas,id'],
        ];
    }
}
