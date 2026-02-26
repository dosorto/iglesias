<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInstructorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'feligres_id' => [
                'required',
                'integer',
                'exists:feligres,id',
                Rule::unique('instructores', 'feligres_id')->whereNull('deleted_at'),
            ],
            'path_firma' => ['required', 'string', 'max:200'],
        ];
    }

    public function attributes(): array
    {
        return [
            'feligres_id' => 'feligrés',
            'path_firma'  => 'firma',
        ];
    }

    public function messages(): array
    {
        return [
            'feligres_id.unique' => 'Este feligrés ya tiene un instructor asignado.',
        ];
    }
}