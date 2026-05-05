<?php

namespace App\Livewire\InscripcionCurso;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InscripcionCurso;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InscripcionCursoIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public bool $showDeleteModal = false;
    public ?int $inscripcionIdBeingDeleted = null;
    public bool $isInstructorView = false;
    public ?int $currentInstructorId = null;

    // 🔹 NUEVO: para filtrar desde Instructor Show
    public ?int $feligresId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDeletion($id)
    {
        $this->inscripcionIdBeingDeleted = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->inscripcionIdBeingDeleted) {
            $inscripcion = InscripcionCurso::with('curso')->findOrFail($this->inscripcionIdBeingDeleted);

            if (! $this->canManageInscripcion($inscripcion)) {
                $this->showDeleteModal = false;
                $this->inscripcionIdBeingDeleted = null;
                session()->flash('error', 'No tienes permiso para eliminar esta inscripción.');
                return;
            }

            $inscripcion->delete();

            session()->flash('success', 'Inscripción eliminada correctamente');
        }

        $this->showDeleteModal = false;
        $this->inscripcionIdBeingDeleted = null;
    }

    public function render()
    {
        $this->resolveInstructorContext();

        $query = InscripcionCurso::with([
            'curso.instructor.feligres.persona',
            'curso.instructors.feligres.persona',
            'feligres.persona'
        ]);

        if ($this->isInstructorView) {
            if ($this->currentInstructorId) {
                $query->whereHas('curso', function ($q) {
                    $q->where('instructor_id', $this->currentInstructorId);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($this->feligresId) {
            $query->where('feligres_id', $this->feligresId);
        }

        if ($this->search) {
            $query->where(function ($query) {
                $query->whereHas('feligres.persona', function ($q) {
                    $q->where('primer_nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('primer_apellido', 'like', '%' . $this->search . '%')
                    ->orWhere('dni', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('curso', function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('curso.instructor.feligres.persona', function ($q) {
                    $q->where('primer_nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('primer_apellido', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('curso.instructors.feligres.persona', function ($q) {
                    $q->where('primer_nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('primer_apellido', 'like', '%' . $this->search . '%');
                });
            });
        }

        $inscripciones = $query
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.inscripcion-curso.inscripcion-curso-index', compact('inscripciones'));
    }

    public function toggleAprobado(int $id): void
    {
        $this->resolveInstructorContext();

        $inscripcion = \App\Models\InscripcionCurso::with('curso')->findOrFail($id);

        if (! $this->canManageInscripcion($inscripcion)) {
            session()->flash('error', 'No tienes permiso para actualizar esta inscripción.');
            return;
        }

        if ($inscripcion->aprobado) {
            $inscripcion->update([
                'aprobado' => 0,
                'certificado_emitido' => 0,
                'fecha_certificado' => null,
            ]);
        } else {
            $inscripcion->update([
                'aprobado' => 1,
            ]);
        }

        session()->flash('success', 'Estado de aprobación actualizado.');
    }

    public function aprobarTodos(): void
    {
        $this->resolveInstructorContext();

        $query = \App\Models\InscripcionCurso::query()->where('aprobado', 0);

        if ($this->isInstructorView) {
            if (! $this->currentInstructorId) {
                session()->flash('error', 'No se pudo identificar tu perfil de instructor.');
                return;
            }

            $query->whereHas('curso', function ($q) {
                $q->where('instructor_id', $this->currentInstructorId);
            });
        }

        $query->update([
            'aprobado' => 1,
        ]);

        session()->flash('success', 'Se aprobaron todas las inscripciones pendientes.');
    }

    public function mount($feligresId = null)
    {
        $this->feligresId = $feligresId;
        $this->resolveInstructorContext();
    }

    private function resolveInstructorContext(): void
    {
        $authUser = Auth::user();
        $currentUser = $authUser ? User::with('roles')->find($authUser->id) : null;

        $this->isInstructorView = (bool) ($currentUser?->roles?->contains('name', 'instructor'));

        if (! $this->isInstructorView) {
            $this->currentInstructorId = null;
            return;
        }

        $this->currentInstructorId = $authUser
            ? Instructor::resolveIdFromAuthEmail($authUser->email)
            : null;
    }

    private function canManageInscripcion(InscripcionCurso $inscripcion): bool
    {
        if (! $this->isInstructorView) {
            return true;
        }

        if (! $this->currentInstructorId) {
            return false;
        }

        return (int) ($inscripcion->curso?->instructor_id ?? 0) === (int) $this->currentInstructorId;
    }
}