<?php

namespace App\Livewire\PrimeraComunion;

use App\Models\PrimeraComunion;
use App\Models\Iglesias;
use Livewire\Component;

class PrimeraComunionEdit extends Component
{
    public PrimeraComunion $primeraComunion;

    public $iglesia_id                    = null;
    public string $fecha_primera_comunion = '';

    public string $libro_comunion = '';
    public string $folio          = '';
    public string $partida_numero = '';
    public string $observaciones  = '';

    public function mount(PrimeraComunion $primeraComunion): void
    {
        $this->primeraComunion = $primeraComunion;

        $this->iglesia_id             = $primeraComunion->iglesia_id;
        $this->fecha_primera_comunion = $primeraComunion->fecha_primera_comunion?->format('Y-m-d') ?? '';
        $this->libro_comunion         = $primeraComunion->libro_comunion ?? '';
        $this->folio                  = $primeraComunion->folio          ?? '';
        $this->partida_numero         = $primeraComunion->partida_numero ?? '';
        $this->observaciones          = $primeraComunion->observaciones  ?? '';
    }

    public function save(): void
    {
        $this->validate([
            'iglesia_id'             => ['required'],
            'fecha_primera_comunion' => ['required', 'date'],
            'libro_comunion'         => ['nullable', 'string', 'max:100'],
            'folio'                  => ['nullable', 'string', 'max:50'],
            'partida_numero'         => ['nullable', 'string', 'max:50'],
            'observaciones'          => ['nullable', 'string', 'max:500'],
        ]);

        $this->primeraComunion->update([
            'iglesia_id'             => $this->iglesia_id,
            'fecha_primera_comunion' => $this->fecha_primera_comunion,
            'libro_comunion'         => $this->libro_comunion ?: null,
            'folio'                  => $this->folio          ?: null,
            'partida_numero'         => $this->partida_numero ?: null,
            'observaciones'          => $this->observaciones  ?: null,
        ]);

        session()->flash('success', 'Primera comunión actualizada correctamente.');
        $this->redirect(route('primera-comunion.index'), navigate: true);
    }

    public function render()
    {
        $centralConn = config('tenancy.central_connection', 'mysql');
        $iglesias    = Iglesias::on($centralConn)->where('estado', 'Activo')->orderBy('nombre')->get();

        return view('livewire.primera-comunion.primera-comunion-edit', compact('iglesias'));
    }
}