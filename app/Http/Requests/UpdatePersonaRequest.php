<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $personaId = $this->route('persona');

        return [
            'dni' => [
                'required', 
                'string', 
                'max:20', 
                Rule::unique('personas', 'dni')->ignore($personaId)->whereNull('deleted_at')
            ],
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'sexo' => ['required', 'in:M,F'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => [
                'nullable', 
                'email', 
                'max:255', 
                Rule::unique('personas', 'email')->ignore($personaId)->whereNull('deleted_at')
            ],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'dni' => 'DNI',
            'nombre' => 'nombre',
            'apellido' => 'apellido',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'sexo' => 'sexo',
            'telefono' => 'teléfono',
            'email' => 'correo electrónico',
        ];
    }
}
