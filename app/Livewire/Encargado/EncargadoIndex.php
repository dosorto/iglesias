<?php

namespace App\Livewire\Encargado;

use App\Models\Encargado;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class EncargadoIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $encargadoIdBeingDeleted = null;
    public $encargadoNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmEncargadoDeletion($id, $name)
    {
        $this->encargadoIdBeingDeleted = $id;
        $this->encargadoNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        Encargado::findOrFail($this->encargadoIdBeingDeleted)->delete();

        $this->showDeleteModal = false;

        session()->flash('success', 'Encargado eliminado exitosamente.');
    }

    public function render()
    {
        $encargados = Encargado::with(['feligres.persona', 'feligres.iglesia'])
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

        return view('livewire.encargado.encargado-index', [
            'encargados' => $encargados,
        ]);
    }
}
