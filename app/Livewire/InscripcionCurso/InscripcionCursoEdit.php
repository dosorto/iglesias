<?php

namespace App\Livewire\InscripcionCurso;

use Livewire\Component;
use App\Models\InscripcionCurso;
use App\Models\Curso;

class InscripcionCursoEdit extends Component
{
    public InscripcionCurso $inscripcion;

    public $curso_id = null;
    public $feligres_id = null;

    public string $fecha_inscripcion = '';
    public string $aprobado = '0';
    public string $certificado_emitido = '0';
    public ?string $fecha_certificado = null;

    public ?string $nombreInstructor = '';
    public ?string $nombreFeligres = '';
    public ?string $dniFeligres = '';

    public function mount(InscripcionCurso $inscripcion): void
    {
        $this->inscripcion = $inscripcion->load([
            'curso.instructor.feligres.persona',
            'feligres.persona',
        ]);

        $this->curso_id = $inscripcion->curso_id;
        $this->feligres_id = $inscripcion->feligres_id;

        $this->fecha_inscripcion = $inscripcion->fecha_inscripcion?->format('Y-m-d') ?? '';
        $this->fecha_certificado = $inscripcion->fecha_certificado?->format('Y-m-d');

        $this->aprobado = (string) ((int) $inscripcion->aprobado);
        $this->certificado_emitido = (string) ((int) $inscripcion->certificado_emitido);

        $this->nombreInstructor = $inscripcion->curso?->instructor?->feligres?->persona?->nombre_completo ?? '';
        $this->nombreFeligres = $inscripcion->feligres?->persona?->nombre_completo ?? '';
        $this->dniFeligres = $inscripcion->feligres?->persona?->dni ?? '';
    }

    protected function rules(): array
    {
        return [
            'aprobado' => ['required', 'in:0,1'],
            'certificado_emitido' => ['required', 'in:0,1'],
        ];
    }

    protected function messages(): array
    {
        return [
            'aprobado.required' => 'Debe seleccionar si está aprobado.',
            'aprobado.in' => 'El valor de aprobado no es válido.',
            'certificado_emitido.required' => 'Debe indicar si el certificado fue emitido.',
            'certificado_emitido.in' => 'El valor de certificado emitido no es válido.',
        ];
    }

    public function updatedAprobado($value): void
    {
        if ((string) $value === '0') {
            $this->certificado_emitido = '0';
            $this->fecha_certificado = null;
        }
    }

    public function updatedCertificadoEmitido($value): void
    {
        if ((string) $value === '1') {
            $this->aprobado = '1';

            if (! $this->fecha_certificado) {
                $this->fecha_certificado = now()->format('Y-m-d');
            }
        }

        if ((string) $value === '0') {
            $this->fecha_certificado = null;
        }
    }

    public function update()
    {
        $this->validate();

        if ($this->aprobado === '0') {
            $this->certificado_emitido = '0';
            $this->fecha_certificado = null;
        }

        if ($this->certificado_emitido === '1') {
            $this->aprobado = '1';

            if (! $this->fecha_certificado) {
                $this->fecha_certificado = now()->format('Y-m-d');
            }
        } else {
            $this->fecha_certificado = null;
        }

        $this->inscripcion->update([
            'aprobado' => (int) $this->aprobado,
            'certificado_emitido' => (int) $this->certificado_emitido,
            'fecha_certificado' => $this->fecha_certificado,
        ]);

        session()->flash('success', 'Inscripción actualizada correctamente.');

        return redirect()->route('curso.show', $this->inscripcion->curso_id);
    }

    public function render()
    {
        return view('livewire.inscripcion-curso.inscripcion-curso-edit', [
            'curso' => Curso::with('instructor.feligres.persona')->find($this->curso_id),
        ]);
    }
}