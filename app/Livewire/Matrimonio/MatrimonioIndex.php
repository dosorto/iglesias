<?php

namespace App\Livewire\Matrimonio;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Matrimonio;

class MatrimonioIndex extends Component
{
    use WithPagination;

    public string $search  = '';
    public int    $perPage = 10;

    public bool   $showDeleteModal             = false;
    public ?int   $matrimonioIdBeingDeleted    = null;
    public string $matrimonioNameBeingDeleted  = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmMatrimonioDeletion(int $id, string $name): void
    {
        $this->matrimonioIdBeingDeleted   = $id;
        $this->matrimonioNameBeingDeleted = $name;
        $this->showDeleteModal             = true;
    }

    public function delete(): void
    {
        if ($this->matrimonioIdBeingDeleted) {
            Matrimonio::findOrFail($this->matrimonioIdBeingDeleted)->delete();
            session()->flash('success', 'Matrimonio eliminado correctamente.');
        }
        $this->showDeleteModal            = false;
        $this->matrimonioIdBeingDeleted   = null;
        $this->matrimonioNameBeingDeleted = '';
    }

    public function render()
    {
        // Issue #6: Cargar feligrés eliminados para preservar datos históricos en sacramentos
        $matrimonios = Matrimonio::with([
            'iglesia',
            ['esposo' => fn($q) => $q->withTrashed()],
            'esposo.persona',
            ['esposa' => fn($q) => $q->withTrashed()],
            'esposa.persona',
            'encargado.feligres.persona',
        ])
            ->when($this->search, function ($q) {
                $q->whereHas('esposo.persona', fn ($p) =>
                    $p->where('primer_nombre',    'like', "%{$this->search}%")
                      ->orWhere('primer_apellido',  'like', "%{$this->search}%")
                      ->orWhere('segundo_apellido', 'like', "%{$this->search}%")
                      ->orWhere('dni',              'like', "%{$this->search}%")
                )->orWhereHas('esposa.persona', fn ($p) =>
                    $p->where('primer_nombre',    'like', "%{$this->search}%")
                      ->orWhere('primer_apellido',  'like', "%{$this->search}%")
                      ->orWhere('dni',              'like', "%{$this->search}%")
                )->orWhereHas('iglesia', fn ($i) =>
                    $i->where('nombre', 'like', "%{$this->search}%")
                );
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.matrimonio.matrimonio-index', compact('matrimonios'));
    }
}
