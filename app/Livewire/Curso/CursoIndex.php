<?php

namespace App\Livewire\Curso;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Curso;

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

    public function confirmCursoDeletion($id,$name)
    {
        $this->cursoIdBeingDeleted = $id;
        $this->cursoNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {

        if($this->cursoIdBeingDeleted){

            Curso::findOrFail($this->cursoIdBeingDeleted)->delete();

            session()->flash('success','Curso eliminado correctamente');

        }

        $this->showDeleteModal = false;
        $this->cursoIdBeingDeleted = null;
        $this->cursoNameBeingDeleted = '';

    }

    public function render()
    {

        $cursos = Curso::with([
            'iglesia',
            'tipoCurso',
            'instructor.feligres.persona',
            'encargado.feligres.persona'
        ])
        ->when($this->search,function($q){

            $q->where('nombre','like','%'.$this->search.'%')
            ->orWhereHas('iglesia',fn($i)=>
                $i->where('nombre','like','%'.$this->search.'%')
            )
            ->orWhereHas('tipoCurso',fn($t)=>
                $t->where('nombre','like','%'.$this->search.'%')
            );

        })
        ->latest()
        ->paginate($this->perPage);

        return view('livewire.curso.curso-index',compact('cursos'));

    }

}