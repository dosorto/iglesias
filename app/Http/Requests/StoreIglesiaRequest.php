<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIglesiaRequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:200'],
            'direccion' => ['required', 'string', 'max:300'],
            'telefono' => ['required', 'string', 'max:20'],
            'email' => [
                'nullable', 
                'email', 
                'max:200', 
                Rule::unique('iglesias', 'email')->whereNull('deleted_at')
            ],
            'parroco_nombre' => ['nullable', 'string', 'max:200'],
            'estado' => ['required', 'string', 'max:20'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'direccion' => 'direccion',
            'telefono' => 'telefono',
            'email' => 'email',
            'parroco_nombre' => 'parroco_nombre',
            'estado' => 'estado',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.requerid' => 'El nombre de la iglesia es obligatorio ',
            'nombre.string' => 'El nombre de debe ser texto ',
            'nombre.max' => 'El nombre no puede tener mas de 200 caracteres  ',
            'nombre.unique' => 'ya existe una iglesia con ese nombre  ',

        ];
    }
}
