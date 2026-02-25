<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonaRequest extends FormRequest
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
        return [
            'dni' => [
                'required', 
                'string', 
                'max:20', 
                Rule::unique('personas', 'dni')->whereNull('deleted_at')
            ],
            'primer_nombre'    => ['required', 'string', 'max:150'],
            'segundo_nombre'   => ['nullable', 'string', 'max:150'],
            'primer_apellido'  => ['required', 'string', 'max:100'],
            'segundo_apellido' => ['nullable', 'string', 'max:100'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'sexo' => ['required', 'in:M,F'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => [
                'nullable', 
                'email', 
                'max:255', 
                Rule::unique('personas', 'email')->whereNull('deleted_at')
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
            'primer_nombre'    => 'primer nombre',
            'segundo_nombre'   => 'segundo nombre',
            'primer_apellido'  => 'primer apellido',
            'segundo_apellido' => 'segundo apellido',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'sexo' => 'sexo',
            'telefono' => 'teléfono',
            'email' => 'correo electrónico',
        ];
    }
}
