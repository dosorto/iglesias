<?php

namespace App\Livewire\Bautismo;

use App\Models\Bautismo;
use App\Models\Iglesias;
use App\Models\Encargado;
use Livewire\Component;

class BautismoEdit extends Component
{
    public Bautismo $bautismo;

    // Paso 1
    public $iglesia_id     = null;
    public $encargado_id   = null;
    public string  $fecha_bautismo = '';

    // Libro
    public string $libro_bautismo = '';
    public string $folio          = '';
    public string $partida_numero = '';
    public string $observaciones  = '';

    public function mount(Bautismo $bautismo): void
    {
        $this->bautismo = $bautismo;

        $this->iglesia_id     = $bautismo->iglesia_id;
        $this->encargado_id   = $bautismo->encargado_id;
        $this->fecha_bautismo = $bautismo->fecha_bautismo?->format('Y-m-d') ?? '';
        $this->libro_bautismo = $bautismo->libro_bautismo ?? '';
        $this->folio          = $bautismo->folio ?? '';
        $this->partida_numero = $bautismo->partida_numero ?? '';
        $this->observaciones  = $bautismo->observaciones ?? '';
    }

    public function save(): void
    {
        $this->validate([
            'iglesia_id'     => ['required'],
            'encargado_id'   => ['required'],
            'fecha_bautismo' => ['required', 'date'],
            'libro_bautismo' => ['nullable', 'string', 'max:100'],
            'folio'          => ['nullable', 'string', 'max:50'],
            'partida_numero' => ['nullable', 'string', 'max:50'],
            'observaciones'  => ['nullable', 'string', 'max:500'],
        ]);

        $this->bautismo->update([
            'iglesia_id'     => $this->iglesia_id,
            'encargado_id'   => $this->encargado_id,
            'fecha_bautismo' => $this->fecha_bautismo,
            'libro_bautismo' => $this->libro_bautismo ?: null,
            'folio'          => $this->folio ?: null,
            'partida_numero' => $this->partida_numero ?: null,
            'observaciones'  => $this->observaciones ?: null,
        ]);

        session()->flash('success', 'Bautismo actualizado correctamente.');
        $this->redirect(route('bautismo.index'), navigate: true);
    }

    public function render()
    {
        $centralConn = config('tenancy.central_connection', 'mysql');
        $iglesias   = Iglesias::on($centralConn)->where('estado', 'Activo')->orderBy('nombre')->get();
        $encargados = Encargado::with('feligres.persona')->get();

        return view('livewire.bautismo.bautismo-edit', compact('iglesias', 'encargados'));
    }
}
