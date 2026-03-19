<?php

namespace App\Livewire\Curso;

use Livewire\Component;
use App\Models\Curso;
use App\Models\InscripcionCurso;

class CursoShow extends Component
{
    public Curso $curso;

    public bool $showDeleteModal = false;
    public ?int $inscripcionIdAEliminar = null;

    public function mount(Curso $curso): void
    {
        $this->curso = $curso->load([
            'tipoCurso',
            'instructor.feligres.persona',
            'encargado.feligres.persona',
            'auditLogs',
            'inscripcionesCurso.feligres.persona',
        ]);
    }

    public function confirmarQuitarMatriculado(int $inscripcionId): void
    {
        $this->inscripcionIdAEliminar = $inscripcionId;
        $this->showDeleteModal = true;
    }

    public function cancelarQuitarMatriculado(): void
    {
        $this->showDeleteModal = false;
        $this->inscripcionIdAEliminar = null;
    }

    public function quitarMatriculado(int $inscripcionId): void
    {
        $inscripcion = InscripcionCurso::where('curso_id', $this->curso->id)
            ->findOrFail($inscripcionId);

        $inscripcion->delete();

        $this->curso->load([
            'tipoCurso',
            'instructor.feligres.persona',
            'encargado.feligres.persona',
            'auditLogs',
            'inscripcionesCurso.feligres.persona',
        ]);

        session()->flash('success', 'Matriculado quitado correctamente.');
    }

    public function quitarMatriculadoConfirmado(): void
    {
        if (! $this->inscripcionIdAEliminar) {
            return;
        }

        $this->quitarMatriculado($this->inscripcionIdAEliminar);
        $this->cancelarQuitarMatriculado();
    }

    public function render()
    {
        return view('livewire.curso.curso-show');
    }
}