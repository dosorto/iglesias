<?php

namespace App\Livewire\Bautismo;

use App\Models\Bautismo;
use Livewire\Component;

class BautismoShow extends Component
{
    public Bautismo $bautismo;

    public function mount(Bautismo $bautismo): void
    {
        $this->bautismo = $bautismo->load([
            'iglesia',
            'bautizado.persona',
            'padre.persona',
            'madre.persona',
            'padrino.persona',
            'madrina.persona',
            'encargado.feligres.persona',
        ]);
    }

    public function render()
    {
        return view('livewire.bautismo.bautismo-show');
    }
}
