<?php

namespace App\Livewire\Tipocurso;

use App\Models\TipoCurso as TipoCursoModel;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TipoCursoEdit extends Component
{
    public TipoCursoModel $tipoCurso;

    public string $nombre_curso      = '';
    public string $descripcion_curso = '';
    public string $estado_curso      = 'activo';

    public function mount(TipoCursoModel $tipocurso): void
    {
        $this->tipoCurso       = $tipocurso;
        $this->nombre_curso      = $tipocurso->nombre_curso;
        $this->descripcion_curso = $tipocurso->descripcion_curso ?? '';
        $this->estado_curso      = $tipocurso->estado_curso;
    }

    protected function rules(): array
    {
        return [
            'nombre_curso'      => [
                'required',
                'string',
                'max:100',
                'regex:/[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ]/',
                Rule::unique('tipos_curso', 'nombre_curso')
                    ->ignore($this->tipoCurso->id)
                    ->whereNull('deleted_at'),
            ],
            'descripcion_curso' => ['nullable', 'string', 'max:1000'],
            'estado_curso'      => ['required', 'string', Rule::in(['activo', 'inactivo'])],
        ];
    }

    protected function messages(): array
    {
        return [
            'nombre_curso.required' => 'El nombre del tipo de curso es obligatorio.',
            'nombre_curso.max'      => 'El nombre no puede superar los 100 caracteres.',
            'nombre_curso.regex'    => 'El nombre debe contener al menos una letra.',
            'nombre_curso.unique'   => 'Ya existe un tipo de curso con ese nombre.',
            'estado_curso.required' => 'El estado es obligatorio.',
            'estado_curso.in'       => 'El estado debe ser Activo o Inactivo.',
        ];
    }

    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    public function guardar(): void
    {
        $data = $this->validate();
        $data['descripcion_curso'] = $data['descripcion_curso'] ?: null;

        $this->tipoCurso->update($data);

        session()->flash('success', 'Tipo de Curso actualizado exitosamente.');
        $this->redirect(route('tipocurso.index'), navigate: false);
    }

    public function render()
    {
        return view('livewire.tipocurso.tipocurso-edit');
    }
}
