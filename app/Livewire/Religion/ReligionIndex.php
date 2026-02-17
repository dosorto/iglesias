<?php

namespace App\Livewire\Religion;

use App\Models\Religion;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class ReligionIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';
    
    public $perPage = 10;
    
    // Propiedades para el modal de eliminación
    public $showDeleteModal = false;
    public $religionIdBeingDeleted = null;
    public $religionNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmReligionDeletion($id, $name)
    {
        $this->religionIdBeingDeleted = $id;
        $this->religionNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $religion = Religion::findOrFail($this->religionIdBeingDeleted);
        $religion->delete();

        $this->showDeleteModal = false;
        
        session()->flash('success', 'Religion eliminada exitosamente.');
    }

    public function render()
    {
        $religion = Religion::query()
            ->where(function($query) {
                $query->where('religion', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.religion.religion-index', [
            'religion' => $religion
        ]);
    }
}