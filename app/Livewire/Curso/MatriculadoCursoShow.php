<?php

namespace App\Livewire\Curso;

use Livewire\Component;
use App\Models\InscripcionCurso;

class MatriculadoCursoShow extends Component
{
    public int $inscripcionId;
    public ?InscripcionCurso $inscripcion = null;

    public bool $showDeleteModal = false;

    public function mount(int $inscripcionId): void
    {
        $this->inscripcionId = $inscripcionId;
        $this->cargarInscripcion();
    }

    public function cargarInscripcion(): void
    {
        $this->inscripcion = InscripcionCurso::with([
            'curso.instructor.feligres.persona',
            'curso.encargado.feligres.persona',
            'feligres.persona',
            'auditLogs.user',
        ])->findOrFail($this->inscripcionId);
    }

    public function aprobar(): void
    {
        if (! $this->inscripcion) {
            return;
        }

        $this->inscripcion->update([
            'aprobado' => true,
        ]);

        $this->cargarInscripcion();

        session()->flash('success_estado', 'El matriculado fue aprobado.');
    }

    public function quitarAprobacion(): void
    {
        if (! $this->inscripcion) {
            return;
        }

        $this->inscripcion->update([
            'aprobado' => false,
            'certificado_emitido' => false,
            'fecha_certificado' => null,
        ]);

        $this->cargarInscripcion();

        session()->flash('success_estado', 'Se quitó la aprobación y se reinició el certificado.');
    }

    public function confirmarQuitar(): void
    {
        $this->showDeleteModal = true;
    }

    public function cancelarQuitar(): void
    {
        $this->showDeleteModal = false;
    }

    public function quitar(): void
    {
        if (! $this->inscripcion) {
            return;
        }

        $this->inscripcion->delete();

        $this->showDeleteModal = false;

        $this->dispatch('matriculadoEliminado');
    }

    public function imprimirCertificado()
    {
        if (! $this->inscripcion) {
            return;
        }

        if (! $this->inscripcion->aprobado) {
            session()->flash('error', 'No puedes generar el certificado porque la inscripción no está aprobada.');
            return;
        }

        if (! $this->inscripcion->certificado_emitido) {
            $this->inscripcion->update([
                'certificado_emitido' => true,
                'fecha_certificado' => now()->toDateString(),
            ]);
        }

        $this->cargarInscripcion();

        return redirect()->route('inscripcion-curso.certificado.pdf', $this->inscripcion->id);
    }

    public function render()
    {
        return view('livewire.curso.matriculado-curso-show');
    }
}