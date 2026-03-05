<?php

namespace App\Livewire\Encargado;

use Livewire\Component;
use App\Models\Encargado;

class EncargadoShow extends Component
{
    public Encargado $encargado;

    public function mount(Encargado $encargado): void
    {
        $this->encargado = $encargado->load([
            'feligres.persona',
            'feligres.iglesia',
            'auditLogs',
        ]);
    }

    public function render()
    {
        return view('livewire.encargado.encargado-show');
    }
}
