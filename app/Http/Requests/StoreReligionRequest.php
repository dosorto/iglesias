<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReligionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // ✅ permitir la petición
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'religion' => [
                'required',
                'string',
                'max:100',
                'unique:religion,religion', // 👈 tabla singular
            ],
        ];
    }

    /**
     * Mensajes personalizados (opcional pero recomendado)
     */
    public function messages(): array
    {
        return [
            'religion.required' => 'El nombre de la religión es obligatorio.',
            'religion.unique'   => 'Esta religión ya está registrada.',
            'religion.max'      => 'La religión no debe superar los 100 caracteres.',
        ];
    }
}
