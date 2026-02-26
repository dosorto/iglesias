<?php

namespace App\Livewire\Instructor;

use App\Models\Instructor;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

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
        $this->instructorIdBeingDeleted = $id;
        $this->instructorNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        Instructor::findOrFail($this->instructorIdBeingDeleted)->delete();

        $this->showDeleteModal = false;

        session()->flash('success', 'Instructor eliminado exitosamente.');
    }

    public function render()
    {
        $instructores = Instructor::with(['feligres.persona', 'feligres.iglesia'])
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
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.instructor.instructor-index', [
            'instructores' => $instructores,
        ]);
    }
}