<?php

namespace App\Livewire\InscripcionCurso;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InscripcionCurso;

class InscripcionCursoIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public bool $showDeleteModal = false;
    public ?int $inscripcionIdBeingDeleted = null;

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

            InscripcionCurso::findOrFail($this->inscripcionIdBeingDeleted)->delete();

            session()->flash('success', 'Inscripción eliminada correctamente');
        }

        $this->showDeleteModal = false;
        $this->inscripcionIdBeingDeleted = null;
    }

    public function render()
    {
        $inscripciones = InscripcionCurso::with([
            'curso',
            'feligres.persona'
        ])
        ->latest()
        ->paginate($this->perPage);

        return view('livewire.inscripcion-curso.inscripcion-curso-index', compact('inscripciones'));
    }
}