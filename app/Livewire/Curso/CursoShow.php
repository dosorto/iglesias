<?php

namespace App\Livewire\Curso;

use Livewire\Component;
use App\Models\Curso;
use App\Models\Instructor;
use App\Models\InscripcionCurso;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CursoShow extends Component
{
    public Curso $curso;
    public bool $isInstructorView = false;



    public function mount(Curso $curso): void
    {
        $this->authorizeInstructorCourseAccess($curso);

        $this->curso = $curso->load([
            'tipoCurso',
            'instructor.feligres.persona',
            'encargado.feligres.persona',
            'auditLogs',
            'inscripcionesCurso.feligres.persona',
        ]);
    }



    public function recargarCurso(): void
    {
        $this->curso->load([
            'tipoCurso',
            'instructor.feligres.persona',
            'encargado.feligres.persona',
            'auditLogs',
            'inscripcionesCurso.feligres.persona',
        ]);


        session()->flash('success', 'Matriculado quitado correctamente.');
    }

    public function toggleAprobadoMatriculado(int $inscripcionId): void
    {
        $this->ensureCanEditInscripciones();

        $inscripcion = InscripcionCurso::query()
            ->where('curso_id', $this->curso->id)
            ->findOrFail($inscripcionId);

        if ($inscripcion->aprobado) {
            $inscripcion->update([
                'aprobado' => false,
                'certificado_emitido' => false,
                'fecha_certificado' => null,
            ]);
        } else {
            $inscripcion->update([
                'aprobado' => true,
            ]);
        }

        $this->curso->refresh();
        $this->curso->load([
            'tipoCurso',
            'instructor.feligres.persona',
            'encargado.feligres.persona',
            'auditLogs',
            'inscripcionesCurso.feligres.persona',
        ]);

        session()->flash('success', 'Estado de aprobación actualizado.');
    }

    public function aprobarTodosMatriculados(): void
    {
        $this->ensureCanEditInscripciones();

        $actualizados = InscripcionCurso::query()
            ->where('curso_id', $this->curso->id)
            ->where('aprobado', false)
            ->update([
                'aprobado' => true,
            ]);

        $this->curso->refresh();
        $this->curso->load([
            'tipoCurso',
            'instructor.feligres.persona',
            'encargado.feligres.persona',
            'auditLogs',
            'inscripcionesCurso.feligres.persona',
        ]);

        session()->flash('success', $actualizados > 0
            ? 'Se aprobaron todos los matriculados pendientes.'
            : 'No hay matriculados pendientes por aprobar.');
    }

    public function render()
    {
        return view('livewire.curso.curso-show');
    }

    private function authorizeInstructorCourseAccess(Curso $curso): void
    {
        $authUser = Auth::user();
        $currentUser = $authUser ? User::with('roles')->find($authUser->id) : null;

        if (! $currentUser) {
            abort(403);
        }

        $this->isInstructorView = (bool) ($currentUser->roles?->contains('name', 'instructor'));

        if (! $this->isInstructorView) {
            return;
        }

        $instructorId = $this->resolveCurrentInstructorId();

        if (! $instructorId || (int) $curso->instructor_id !== (int) $instructorId) {
            abort(403, 'No tienes permiso para ver este curso.');
        }
    }

    private function resolveCurrentInstructorId(): ?int
    {
        $authUser = Auth::user();

        if (! $authUser) {
            return null;
        }

        return Instructor::resolveIdFromAuthEmail($authUser->email);
    }

    private function ensureCanEditInscripciones(): void
    {
        if (! Gate::allows('inscripcion-curso.edit')) {
            abort(403, 'No tienes permiso para editar inscripciones.');
        }
    }
}