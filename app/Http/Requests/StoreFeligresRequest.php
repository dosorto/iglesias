<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeligresRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_persona'    => ['required', 'integer', 'exists:personas,id', Rule::unique('feligres', 'id_persona')->whereNull('deleted_at')],
            'id_iglesia'    => ['required', 'integer', 'exists:iglesias,id'],
            'fecha_ingreso' => ['nullable', 'date'],
            'estado'        => ['required', 'in:Activo,Inactivo'],
        ];
    }

    public function attributes(): array
    {
        return [
            'id_persona'    => 'persona',
            'id_iglesia'    => 'iglesia',
            'fecha_ingreso' => 'fecha de ingreso',
            'estado'        => 'estado',
        ];
    }

    public function messages(): array
    {
        return [
            'id_persona.unique' => 'Esta persona ya está registrada como feligrés.',
        ];
    }
}
