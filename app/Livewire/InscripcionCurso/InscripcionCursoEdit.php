<?php

namespace App\Livewire\InscripcionCurso;

use Livewire\Component;
use App\Models\InscripcionCurso;
use App\Models\Curso;
use App\Models\Feligres;

class InscripcionCursoEdit extends Component
{
    public InscripcionCurso $inscripcion;

    public $curso_id = null;
    public $feligres_id = null;

    public string $fecha_inscripcion = '';
    public ?bool $aprobado = null;
    public ?bool $certificado_emitido = null;
    public string $fecha_certificado = '';

    public function mount(InscripcionCurso $inscripcion): void
    {
        $this->inscripcion = $inscripcion;

        $this->curso_id = $inscripcion->curso_id;
        $this->feligres_id = $inscripcion->feligres_id;

        $this->fecha_inscripcion = $inscripcion->fecha_inscripcion?->format('Y-m-d') ?? '';
        $this->fecha_certificado = $inscripcion->fecha_certificado?->format('Y-m-d') ?? '';

        $this->aprobado = $inscripcion->aprobado;
        $this->certificado_emitido = $inscripcion->certificado_emitido;
    }

    protected function rules(): array
    {
        return [
            'curso_id' => ['required','integer','exists:cursos,id'],
            'feligres_id' => ['required','integer','exists:feligres,id'],

            'fecha_inscripcion' => ['required','date'],

            'aprobado' => ['nullable','boolean'],
            'certificado_emitido' => ['nullable','boolean'],

            'fecha_certificado' => ['nullable','date'],
        ];
    }

    protected function messages(): array
    {
        return [
            'curso_id.required' => 'Debe seleccionar un curso.',
            'curso_id.exists' => 'El curso seleccionado no existe.',

            'feligres_id.required' => 'Debe seleccionar un feligrés.',
            'feligres_id.exists' => 'El feligrés seleccionado no existe.',

            'fecha_inscripcion.required' => 'La fecha de inscripción es obligatoria.',
            'fecha_inscripcion.date' => 'La fecha de inscripción no es válida.',

            'fecha_certificado.date' => 'La fecha de certificado no es válida.',
        ];
    }

    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    public function update(): void
    {
        $this->validate();

        $this->inscripcion->update([
            'curso_id' => $this->curso_id,
            'feligres_id' => $this->feligres_id,
            'fecha_inscripcion' => $this->fecha_inscripcion,
            'aprobado' => $this->aprobado,
            'certificado_emitido' => $this->certificado_emitido,
            'fecha_certificado' => $this->fecha_certificado ?: null,
            'updated_by' => auth()->id(),
        ]);

        session()->flash('success', 'Inscripción actualizada correctamente.');

        $this->redirect(route('inscripcion-curso.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.inscripcion-curso.inscripcion-curso-edit',[
            'cursos' => Curso::orderBy('nombre')->get(),
            'feligreses' => Feligres::with('persona')->get()
        ]);
    }
}   