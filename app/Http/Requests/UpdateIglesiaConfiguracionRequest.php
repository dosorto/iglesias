<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIglesiaConfiguracionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:200'],
            'direccion' => ['required', 'string', 'max:300'],
            'header_diocesis' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre de la iglesia',
            'direccion' => 'dirección',
            'header_diocesis' => 'diócesis del encabezado',
        ];
    }
}