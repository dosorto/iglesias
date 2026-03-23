<?php

namespace App\Livewire\Instructor;

use App\Models\Instructor;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;

class InstructorIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $instructorIdBeingDeleted = null;
    public $instructorNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmInstructorDeletion($id, $name)
    {
        if (! $this->canAccessInstructor((int) $id)) {
            session()->flash('error', 'No tienes permiso para eliminar este instructor.');
            return;
        }

        $this->instructorIdBeingDeleted = $id;
        $this->instructorNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if (! $this->instructorIdBeingDeleted || ! $this->canAccessInstructor((int) $this->instructorIdBeingDeleted)) {
            $this->showDeleteModal = false;
            session()->flash('error', 'No tienes permiso para eliminar este instructor.');
            return;
        }

        Instructor::findOrFail($this->instructorIdBeingDeleted)->delete();

        $this->showDeleteModal = false;

        session()->flash('success', 'Instructor eliminado exitosamente.');
    }

    public function render()
    {
        $query = Instructor::with(['feligres.persona', 'feligres.iglesia'])
            ->where(function ($query) {
                $query->whereHas('feligres.persona', function ($q) {
                    $q->where('primer_nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('segundo_nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('primer_apellido', 'like', '%' . $this->search . '%')
                        ->orWhere('segundo_apellido', 'like', '%' . $this->search . '%')
                        ->orWhere('dni', 'like', '%' . $this->search . '%');
                })->orWhereHas('feligres.iglesia', function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%');
                });
            });

        if ($this->currentUserIsInstructor()) {
            $instructorId = $this->resolveCurrentInstructorId();

            if ($instructorId) {
                $query->where('id', $instructorId);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $instructores = $query->latest()->paginate($this->perPage);

        return view('livewire.instructor.instructor-index', [
            'instructores' => $instructores,
        ]);
    }

    private function canAccessInstructor(int $instructorId): bool
    {
        if (! $this->currentUserIsInstructor()) {
            return true;
        }

        $currentInstructorId = $this->resolveCurrentInstructorId();

        return $currentInstructorId !== null && $currentInstructorId === $instructorId;
    }

    private function currentUserIsInstructor(): bool
    {
        $authUser = Auth::user();
        $currentUser = $authUser ? User::with('roles')->find($authUser->id) : null;

        return (bool) ($currentUser?->roles?->contains('name', 'instructor'));
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
            return (int) $instructorByEmail->id;
        }

        if (preg_match('/^instructor\.([0-9]+)(?:\+[0-9]+)?@tenant\.local$/', $email, $matches)) {
            $dni = $matches[1] ?? null;

            if ($dni) {
                $instructorByDni = Instructor::whereHas('feligres.persona', function ($q) use ($dni) {
                    $q->where('dni', $dni);
                })->first();

                return $instructorByDni ? (int) $instructorByDni->id : null;
            }
        }

        return null;
    }
}