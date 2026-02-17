<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateReligionRequest extends FormRequest
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
    // Capturamos el ID de la religion desde la ruta para la validación de email único
    // Si la ruta es /iglesias/{iglesia}, esto obtendrá el ID.
    $iglesiaId = $this->route('religion'); 

    return [
        'religion' => ['required', 'string', 'max:200'],
        ];
    }

    public function attributes(): array
    {
        return [
            'religion' => 'religion',
        ];
    }
}