<?php

namespace App\Livewire\Tipocurso;

use App\Models\TipoCurso as TipoCursoModel;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TipoCursoCreate extends Component
{
    public string $nombre_curso     = '';
    public string $descripcion_curso = '';

    protected function rules(): array
    {
        return [
            'nombre_curso'      => [
                'required',
                'string',
                'max:100',
                'regex:/[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ]/',
                Rule::unique('tipos_curso', 'nombre_curso')->whereNull('deleted_at'),
            ],
            'descripcion_curso' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'nombre_curso.required'  => 'El nombre del tipo de curso es obligatorio.',
            'nombre_curso.max'       => 'El nombre no puede superar los 100 caracteres.',
            'nombre_curso.regex'     => 'El nombre debe contener al menos una letra.',
            'nombre_curso.unique'    => 'Ya existe un tipo de curso con ese nombre.',
        ];
    }

    // Validación en tiempo real campo a campo
    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    public function guardar(): void
    {
        $data = $this->validate();

        // Normalizar: convierte '' a null para descripcion_curso
        $data['descripcion_curso'] = $data['descripcion_curso'] ?: null;

        TipoCursoModel::create($data);

        session()->flash('success', 'Tipo de Curso registrado exitosamente.');
        $this->redirect(route('tipocurso.index'), navigate: false);
    }

    public function render()
    {
        return view('livewire.tipocurso.tipocurso-create');
    }
}
