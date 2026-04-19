<?php

namespace App\Livewire\Confirmacion;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Confirmacion;

class ConfirmacionIndex extends Component
{
    use WithPagination;

    public string $search  = '';
    public int    $perPage = 10;

    public bool   $showDeleteModal              = false;
    public ?int   $confirmacionIdBeingDeleted   = null;
    public string $confirmacionNameBeingDeleted = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmConfirmacionDeletion(int $id, string $name): void
    {
        $this->confirmacionIdBeingDeleted   = $id;
        $this->confirmacionNameBeingDeleted = $name;
        $this->showDeleteModal              = true;
    }

    public function delete(): void
    {
        if ($this->confirmacionIdBeingDeleted) {
            Confirmacion::findOrFail($this->confirmacionIdBeingDeleted)->delete();
            session()->flash('success', 'Confirmación eliminada correctamente.');
        }

        $this->showDeleteModal              = false;
        $this->confirmacionIdBeingDeleted   = null;
        $this->confirmacionNameBeingDeleted = '';
    }

    public function render()
    {
        // Issue #6: Cargar feligrés eliminados para preservar datos históricos en sacramentos
        $confirmaciones = Confirmacion::with([
            'iglesia',
            'feligres' => fn($q) => $q->withTrashed(),
            'feligres.persona',
            'ministro' => fn($q) => $q->withTrashed(),
            'ministro.persona',
        ])
            ->when($this->search, function ($q) {
                $q->whereHas('feligres.persona', fn ($p) =>
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

        return view('livewire.confirmacion.confirmacion-index', compact('confirmaciones'));
    }
}
