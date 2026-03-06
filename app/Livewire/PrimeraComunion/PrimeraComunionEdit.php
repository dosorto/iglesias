<?php

namespace App\Livewire\PrimeraComunion;

use App\Models\PrimeraComunion;
use App\Models\Iglesias;
use Illuminate\Support\Facades\DB;
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

        $this->iglesia_id             = $primeraComunion->id_iglesia;
        $this->fecha_primera_comunion = $primeraComunion->fecha_primera_comunion?->format('Y-m-d') ?? '';
        $this->libro_comunion         = $primeraComunion->libro_comunion ?? '';
        $this->folio                  = $primeraComunion->folio          ?? '';
        $this->partida_numero         = $primeraComunion->partida_numero ?? '';
        $this->observaciones          = $primeraComunion->observaciones  ?? '';
    }

    protected function rules(): array
    {
        return [
            'iglesia_id'             => ['required', 'integer', 'exists:iglesias,id'],
            'fecha_primera_comunion' => ['required', 'date', 'before_or_equal:today'],
            'libro_comunion'         => ['nullable', 'string', 'max:100'],
            'folio'                  => ['nullable', 'string', 'max:50'],
            'partida_numero'         => ['nullable', 'string', 'max:50'],
            'observaciones'          => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function messages(): array
    {
        return [
            'iglesia_id.required'                    => 'Debes seleccionar una iglesia.',
            'iglesia_id.exists'                      => 'La iglesia seleccionada no existe.',
            'fecha_primera_comunion.required'        => 'La fecha de primera comunión es obligatoria.',
            'fecha_primera_comunion.date'            => 'La fecha de primera comunión no es válida.',
            'fecha_primera_comunion.before_or_equal' => 'La fecha de primera comunión no puede ser futura.',
            'libro_comunion.max'                     => 'El libro no puede superar los 100 caracteres.',
            'folio.max'                              => 'El folio no puede superar los 50 caracteres.',
            'partida_numero.max'                     => 'La partida no puede superar los 50 caracteres.',
            'observaciones.max'                      => 'Las observaciones no pueden superar los 500 caracteres.',
        ];
    }

    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    public function save(): void
    {
        $this->validate();

        $this->primeraComunion->update([
            'id_iglesia'             => $this->iglesia_id,
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

        if (session('tenant')) {
            $iglesias = collect([DB::table('iglesias')->first()])->filter();
        } else {
            $iglesias = Iglesias::on($centralConn)->where('estado', 'Activo')->orderBy('nombre')->get();
        }

        return view('livewire.primera-comunion.primera-comunion-edit', compact('iglesias'));
    }
}