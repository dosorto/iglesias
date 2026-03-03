<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstructorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $instructorId = $this->route('instructor')?->id ?? $this->route('instructor');

        return [
            'feligres_id'    => [
                'required',
                'integer',
                'exists:feligres,id',
                Rule::unique('instructores', 'feligres_id')
                    ->ignore($instructorId)
                    ->whereNull('deleted_at'),
            ],
            'fecha_ingreso'  => ['nullable', 'date'],
            'estado'         => ['required', 'in:Activo,Inactivo'],
            'path_firma'     => ['nullable', 'string', 'max:200'],
        ];
    }

    public function attributes(): array
    {
        return [
            'feligres_id'    => 'feligrés',
            'fecha_ingreso'  => 'fecha de ingreso',
            'estado'         => 'estado',
            'path_firma'     => 'firma',
        ];
    }

    public function messages(): array
    {
        return [
            'feligres_id.unique' => 'Este feligrés ya tiene un instructor asignado.',
        ];
    }
}