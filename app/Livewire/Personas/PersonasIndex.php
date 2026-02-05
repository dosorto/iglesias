<?php

namespace App\Livewire\Personas;

use App\Models\Persona;
use App\Exports\PersonasExport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;

class PersonasIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';
    
    public $perPage = 10;
    
    // Propiedades para el modal de eliminación
    public $showDeleteModal = false;
    public $personaIdBeingDeleted = null;
    public $personaNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmPersonaDeletion($id, $name)
    {
        $this->personaIdBeingDeleted = $id;
        $this->personaNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $persona = Persona::findOrFail($this->personaIdBeingDeleted);
        $persona->delete();

        $this->showDeleteModal = false;
        
        session()->flash('success', 'Persona eliminada exitosamente.');
    }

    public function export()
    {
        abort_if(!auth()->user()->can('personas.export'), 403);
        return Excel::download(new PersonasExport, 'personas_' . now()->format('Y_m_d_His') . '.xlsx');
    }

    public function render()
    {
        $personas = Persona::query()
            ->where(function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('apellido', 'like', '%' . $this->search . '%')
                    ->orWhere('dni', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.personas.personas-index', [
            'personas' => $personas
        ]);
    }
}
