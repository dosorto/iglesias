<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateIglesiaRequest extends FormRequest
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
    // Capturamos el ID de la iglesia desde la ruta para la validación de email único
    // Si la ruta es /iglesias/{iglesia}, esto obtendrá el ID.
    $iglesiaId = $this->route('iglesia'); 

    return [
        'nombre' => ['required', 'string', 'max:200'],
        'direccion' => ['required', 'string', 'max:300'],
        'telefono' => ['required', 'string', 'max:20'],
        'email' => [
            'nullable', 
            'email', 
            'max:200', 
            Rule::unique('iglesias', 'email')
                ->ignore($iglesiaId) // Ignora la iglesia actual si estamos editando
                ->whereNull('deleted_at') // Ignora registros eliminados (SoftDeletes)
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
}
