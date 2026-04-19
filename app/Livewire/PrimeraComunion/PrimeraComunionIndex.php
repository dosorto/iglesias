<?php

namespace App\Livewire\PrimeraComunion;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PrimeraComunion;

class PrimeraComunionIndex extends Component
{
    use WithPagination;

    public string $search  = '';
    public int    $perPage = 10;

    public bool   $showDeleteModal                  = false;
    public ?int   $primeraComunionIdBeingDeleted     = null;
    public string $primeraComunionNameBeingDeleted   = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmPrimeraComunionDeletion(int $id, string $name): void
    {
        $this->primeraComunionIdBeingDeleted   = $id;
        $this->primeraComunionNameBeingDeleted = $name;
        $this->showDeleteModal                 = true;
    }

    public function delete(): void
    {
        if ($this->primeraComunionIdBeingDeleted) {
            PrimeraComunion::findOrFail($this->primeraComunionIdBeingDeleted)->delete();
            session()->flash('success', 'Primera comunión eliminada correctamente.');
        }
        $this->showDeleteModal                 = false;
        $this->primeraComunionIdBeingDeleted   = null;
        $this->primeraComunionNameBeingDeleted = '';
    }

    public function render()
    {
        // Issue #6: Cargar feligrés eliminados para preservar datos históricos en sacramentos
        $primeraComuniones = PrimeraComunion::with([
            'iglesia',
            'feligres' => fn($q) => $q->withTrashed(),
            'feligres.persona',
            'catequista.persona',
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

        return view('livewire.primera-comunion.primera-comunion-index', compact('primeraComuniones'));
    }
}
