<?php

namespace App\Livewire\Curso;

use App\Exports\CursoExport;
use App\Models\Curso;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class CursoIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public bool $showDeleteModal = false;
    public ?int $cursoIdBeingDeleted = null;
    public string $cursoNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmCursoDeletion($id, $name)
    {
        $this->cursoIdBeingDeleted = $id;
        $this->cursoNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->cursoIdBeingDeleted) {
            Curso::findOrFail($this->cursoIdBeingDeleted)->delete();
            session()->flash('success', 'Curso eliminado correctamente');
        }

        $this->showDeleteModal = false;
        $this->cursoIdBeingDeleted = null;
        $this->cursoNameBeingDeleted = '';
    }

    public function export(): mixed
    {
        abort_if(!auth()->user()->can('curso.view'), 403);
        return Excel::download(new CursoExport($this->search), 'cursos_' . now()->format('Y_m_d_His') . '.xlsx');
    }

    public function render()
    {
        $authUser = Auth::user();
        $currentUser = $authUser ? User::with('roles')->find($authUser->id) : null;
        $isInstructorView = (bool) ($currentUser?->roles?->contains('name', 'instructor'));

        $query = Curso::with([
            'tipoCurso',
            'instructor' => fn($q) => $q->withTrashed(),
            'instructor.feligres.persona',
            'instructors.feligres.persona',
            'encargado.feligres.persona'
        ]);

        if ($isInstructorView) {
            $instructorId = $this->resolveCurrentInstructorId();

            if ($instructorId) {
                $query->where(function ($q) use ($instructorId) {
                    $q->where('instructor_id', $instructorId)
                        ->orWhereHas('instructors', fn ($iq) => $iq->where('instructores.id', $instructorId));
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $query->when($this->search, function ($q) {
            $q->where(function ($searchQ) {
                $searchQ->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhereHas('tipoCurso', fn($t) =>
                        $t->where('nombre_curso', 'like', '%' . $this->search . '%')
                    );
            });
        });

        $cursos = $query->latest()->paginate($this->perPage);

        return view('livewire.curso.curso-index', compact('cursos', 'isInstructorView'));
    }

    private function resolveCurrentInstructorId(): ?int
    {
        $authUser = Auth::user();

        if (! $authUser) {
            return null;
        }

        return Instructor::resolveIdFromAuthEmail($authUser->email);
    }
}