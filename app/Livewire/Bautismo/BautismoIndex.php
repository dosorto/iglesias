<?php

namespace App\Livewire\Bautismo;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Bautismo;

class BautismoIndex extends Component
{
    use WithPagination;

    public string $search  = '';
    public int    $perPage = 10;

    public bool   $showDeleteModal         = false;
    public ?int   $bautismoIdBeingDeleted  = null;
    public string $bautismoNameBeingDeleted = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmBautismoDeletion(int $id, string $name): void
    {
        $this->bautismoIdBeingDeleted  = $id;
        $this->bautismoNameBeingDeleted = $name;
        $this->showDeleteModal          = true;
    }

    public function delete(): void
    {
        if ($this->bautismoIdBeingDeleted) {
            Bautismo::findOrFail($this->bautismoIdBeingDeleted)->delete();
            session()->flash('success', 'Bautismo eliminado correctamente.');
        }
        $this->showDeleteModal         = false;
        $this->bautismoIdBeingDeleted  = null;
        $this->bautismoNameBeingDeleted = '';
    }

    public function render()
    {
        // Issue #6: Cargar feligrés eliminados para preservar datos históricos en sacramentos
        $bautismos = Bautismo::with([
            'iglesia',
            'bautizado' => fn($q) => $q->withTrashed(),
            'bautizado.persona',
            'encargado.feligres.persona'
        ])
            ->when($this->search, function ($q) {
                $q->whereHas('bautizado.persona', fn ($p) =>
                    $p->where('primer_nombre',    'like', "%{$this->search}%")
                      ->orWhere('primer_apellido',  'like', "%{$this->search}%")
                      ->orWhere('segundo_apellido', 'like', "%{$this->search}%")
                      ->orWhere('dni',              'like', "%{$this->search}%")
                )->orWhereHas('iglesia', fn ($i) =>
                    $i->where('nombre', 'like', "%{$this->search}%")
                );
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.bautismo.bautismo-index', compact('bautismos'));
    }
}
