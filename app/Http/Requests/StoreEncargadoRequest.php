<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEncargadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_feligres'          => ['required', 'integer', 'exists:feligres,id', Rule::unique('encargado', 'id_feligres')->whereNull('deleted_at')],
            'path_firma_principal' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'id_feligres'          => 'feligrés',
            'path_firma_principal' => 'firma principal',
        ];
    }

    public function messages(): array
    {
        return [
            'id_feligres.unique' => 'Este feligrés ya tiene un encargado asignado.',
        ];
    }
}
