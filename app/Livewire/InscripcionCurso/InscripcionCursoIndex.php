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

            InscripcionCurso::findOrFail($this->inscripcionIdBeingDeleted)->delete();

            session()->flash('success', 'Inscripción eliminada correctamente');
        }

        $this->showDeleteModal = false;
        $this->inscripcionIdBeingDeleted = null;
    }

    public function render()
{
    $query = InscripcionCurso::with([
        'curso.instructor.feligres.persona',
        'feligres.persona'
    ]);

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
            });
        });
    }

    $inscripciones = $query
        ->latest()
        ->paginate($this->perPage);

    return view('livewire.inscripcion-curso.inscripcion-curso-index', compact('inscripciones'));
}

    public function mount($feligresId = null)
    {
        $this->feligresId = $feligresId;
    }
}