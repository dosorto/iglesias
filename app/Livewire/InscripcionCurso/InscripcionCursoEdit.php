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
    public $aprobado = '';
    public $certificado_emitido = '';
    public string $fecha_certificado = '';

    public string $persona_dni = '';
    public $persona = null;
    public $persona_estado = 'idle';
    public bool $showCrearPersona = false;

    public string $p_dni = '';
    public string $p_primer_nombre = '';
    public string $p_primer_apellido = '';
    public string $p_telefono = '';

    public function mount(InscripcionCurso $inscripcion): void
    {
        $this->inscripcion = $inscripcion;

        $this->curso_id = $inscripcion->curso_id;
        $this->feligres_id = $inscripcion->feligres_id;

        $this->fecha_inscripcion = $inscripcion->fecha_inscripcion?->format('Y-m-d') ?? '';
        $this->fecha_certificado = $inscripcion->fecha_certificado?->format('Y-m-d') ?? '';

        $this->aprobado = $inscripcion->aprobado === null ? '' : (string) $inscripcion->aprobado;
        $this->certificado_emitido = $inscripcion->certificado_emitido === null ? '' : (string) $inscripcion->certificado_emitido;
    }

    protected function rules(): array
    {
        return [
            'curso_id' => ['required','integer','exists:cursos,id'],
            'feligres_id' => ['required','integer','exists:feligres,id'],

            'fecha_inscripcion' => ['required','date'],

            'aprobado' => ['required','in:0,1'],
            'certificado_emitido' => ['required','in:0,1'],

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

            'aprobado.required' => 'Debe seleccionar si está aprobado.',
            'certificado_emitido.required' => 'Debe indicar si el certificado fue emitido.',

            'fecha_certificado.date' => 'La fecha de certificado no es válida.',
        ];
    }

    public function updated(): void
    {
        $this->validate();
    }

    public function update(): void
    {
        $this->validate();

        $this->inscripcion->update([
            'curso_id' => $this->curso_id,
            'feligres_id' => $this->feligres_id,
            'fecha_inscripcion' => $this->fecha_inscripcion,
            'aprobado' => (int) $this->aprobado,
            'certificado_emitido' => (int) $this->certificado_emitido,
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

    public function buscarPersona()
    {
        $persona = \App\Models\Persona::where('dni', $this->persona_dni)->first();

        if (!$persona) {
            $this->persona_estado = 'sin_persona';
            return;
        }

        $this->persona = $persona;

        $feligres = \App\Models\Feligres::where('persona_id',$persona->id)->first();

        if ($feligres) {
            $this->persona_estado = 'found';
            $this->feligres_id = $feligres->id;
        } else {
            $this->persona_estado = 'sin_feligres';
        }
    }

    public function registrarFeligres()
    {
        $feligres = \App\Models\Feligres::create([
            'persona_id' => $this->persona->id,
        ]);

        $this->feligres_id = $feligres->id;
        $this->persona_estado = 'found';
    }

    public function guardarPersona()
    {
        $persona = \App\Models\Persona::create([
            'dni' => $this->p_dni,
            'primer_nombre' => $this->p_primer_nombre,
            'primer_apellido' => $this->p_primer_apellido,
            'telefono' => $this->p_telefono,
        ]);

        $feligres = \App\Models\Feligres::create([
            'persona_id' => $persona->id
        ]);

        $this->feligres_id = $feligres->id;
        $this->persona_estado = 'found';
    }
}