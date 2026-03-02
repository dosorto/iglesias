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

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $bautismos = Bautismo::with(['iglesia', 'bautizado.persona', 'encargado.feligres.persona'])
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
