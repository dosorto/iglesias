<?php

namespace App\Livewire\Bautismo;

use App\Models\Bautismo;
use App\Models\Iglesias;
use App\Models\Encargado;
use Livewire\Component;

class BautismoEdit extends Component
{
    public Bautismo $bautismo;

    public ?int   $iglesia_id     = null;
    public ?int   $encargado_id   = null;
    public string $fecha_bautismo = '';
    public string $libro_bautismo = '';
    public string $folio          = '';
    public string $partida_numero = '';
    public string $observaciones  = '';

    public function mount(Bautismo $bautismo): void
    {
        $this->bautismo       = $bautismo;
        $this->iglesia_id     = $bautismo->iglesia_id;
        $this->encargado_id   = $bautismo->encargado_id;
        $this->fecha_bautismo = $bautismo->fecha_bautismo?->format('Y-m-d') ?? '';
        $this->libro_bautismo = $bautismo->libro_bautismo ?? '';
        $this->folio          = $bautismo->folio ?? '';
        $this->partida_numero = $bautismo->partida_numero ?? '';
        $this->observaciones  = $bautismo->observaciones ?? '';
    }

    protected function rules(): array
    {
        return [
            'iglesia_id'     => ['required', 'integer', 'exists:iglesias,id'],
            'encargado_id'   => ['required', 'integer', 'exists:encargado,id'],
            'fecha_bautismo' => ['required', 'date', 'before_or_equal:today'],
            'libro_bautismo' => ['nullable', 'string', 'max:100'],
            'folio'          => ['nullable', 'string', 'max:50'],
            'partida_numero' => ['nullable', 'string', 'max:50'],
            'observaciones'  => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function messages(): array
    {
        return [
            'iglesia_id.required'           => 'Debes seleccionar una iglesia.',
            'iglesia_id.exists'             => 'La iglesia seleccionada no existe.',
            'encargado_id.required'         => 'Debes seleccionar un encargado.',
            'encargado_id.exists'           => 'El encargado seleccionado no existe.',
            'fecha_bautismo.required'       => 'La fecha de bautismo es obligatoria.',
            'fecha_bautismo.date'           => 'La fecha de bautismo no es válida.',
            'fecha_bautismo.before_or_equal'=> 'La fecha de bautismo no puede ser futura.',
            'libro_bautismo.max'            => 'El libro no puede superar los 100 caracteres.',
            'folio.max'                     => 'El folio no puede superar los 50 caracteres.',
            'partida_numero.max'            => 'La partida no puede superar los 50 caracteres.',
            'observaciones.max'             => 'Las observaciones no pueden superar los 500 caracteres.',
        ];
    }

    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    public function guardar(): void
    {
        $this->validate();

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
        $this->redirect(route('bautismo.index'), navigate: false);
    }

    public function render()
    {
        $centralConn = config('tenancy.central_connection', 'mysql');
        $iglesias   = Iglesias::on($centralConn)->where('estado', 'Activo')->orderBy('nombre')->get();
        $encargados = Encargado::with('feligres.persona')->get();

        return view('livewire.bautismo.bautismo-edit', compact('iglesias', 'encargados'));
    }
}
