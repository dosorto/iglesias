<?php

namespace App\Livewire\Encargado;

use Livewire\Component;
use App\Models\Bautismo;
use App\Models\Confirmacion;
use App\Models\Encargado;
use App\Models\Matrimonio;
use App\Models\PrimeraComunion;

class EncargadoShow extends Component
{
    public Encargado $encargado;
    public array $sacramentosCount = [];

    public function mount(Encargado $encargado): void
    {
        $this->encargado = $encargado->load([
            'feligres.persona',
            'feligres.iglesia',
            'auditLogs',
        ]);

        $id = $encargado->id;

        $this->sacramentosCount = [
            'bautismos'        => Bautismo::where('encargado_id', $id)->count(),
            'matrimonios'      => Matrimonio::where('encargado_id', $id)->count(),
            'confirmaciones'   => Confirmacion::where('encargado_id', $id)->count(),
            'comuniones'       => PrimeraComunion::where('encargado_id', $id)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.encargado.encargado-show');
    }
}
