<?php

namespace App\Livewire\Iglesia;

use App\Models\Iglesias;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class IglesiasIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';
    
    public $perPage = 10;
    
    // Propiedades para el modal de eliminación
    public $showDeleteModal = false;
    public $iglesiaIdBeingDeleted = null;
    public $iglesiaNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmIglesiaDeletion($id, $name)
    {
        $this->iglesiaIdBeingDeleted = $id;
        $this->iglesiaNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $iglesia = Iglesias::findOrFail($this->iglesiaIdBeingDeleted);
        $iglesia->delete();

        $this->showDeleteModal = false;
        
        session()->flash('success', 'Iglesia eliminada exitosamente.');
    }

    public function render()
    {
        $iglesias = Iglesias::query()
            ->where(function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('parroco_nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('direccion', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.iglesia.iglesias-index', [
            'iglesias' => $iglesias
        ]);
    }
}