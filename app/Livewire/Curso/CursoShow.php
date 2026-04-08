<?php

namespace App\Livewire\Curso;

use Livewire\Component;
use App\Models\Curso;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

        if (! $authUser || ! $authUser->email) {
            return null;
        }

        $email = strtolower(trim($authUser->email));

        $instructorByEmail = Instructor::whereHas('feligres.persona', function ($q) use ($email) {
            $q->whereRaw('LOWER(email) = ?', [$email]);
        })->first();

        if ($instructorByEmail) {
            return $instructorByEmail->id;
        }

        if (preg_match('/^instructor\.([0-9]+)(?:\+[0-9]+)?@tenant\.local$/', $email, $matches)) {
            $dni = $matches[1] ?? null;

            if ($dni) {
                $instructorByDni = Instructor::whereHas('feligres.persona', function ($q) use ($dni) {
                    $q->where('dni', $dni);
                })->first();

                return $instructorByDni?->id;
            }
        }

        return null;
    }
}