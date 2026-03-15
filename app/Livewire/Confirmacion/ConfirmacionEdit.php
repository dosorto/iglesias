<?php

namespace App\Livewire\Confirmacion;

use App\Models\Confirmacion;
use App\Models\Iglesias;
use App\Models\Feligres;
use Livewire\Component;

class ConfirmacionEdit extends Component
{
    public Confirmacion $confirmacion;

    public ?int   $iglesia_id           = null;
    public ?int   $ministro_id          = null;
    public string $fecha_confirmacion   = '';
    public string $lugar_confirmacion   = '';
    public string $libro_confirmacion   = '';
    public string $folio                = '';
    public string $partida_numero       = '';
    public string $observaciones        = '';

    public function mount(Confirmacion $confirmacion): void
    {
        $this->confirmacion        = $confirmacion;
        $this->iglesia_id          = $confirmacion->iglesia_id;
        $this->ministro_id         = $confirmacion->ministro_id;
        $this->fecha_confirmacion  = $confirmacion->fecha_confirmacion?->format('Y-m-d') ?? '';
        $this->lugar_confirmacion  = $confirmacion->lugar_confirmacion ?? '';
        $this->libro_confirmacion  = $confirmacion->libro_confirmacion ?? '';
        $this->folio               = $confirmacion->folio ?? '';
        $this->partida_numero      = $confirmacion->partida_numero ?? '';
        $this->observaciones       = $confirmacion->observaciones ?? '';
    }

    protected function rules(): array
    {
        return [
            'iglesia_id'          => ['required', 'integer', 'exists:iglesias,id'],
            'ministro_id'         => ['nullable', 'integer', 'exists:feligres,id'],
            'fecha_confirmacion'  => ['required', 'date', 'before_or_equal:today'],
            'lugar_confirmacion'  => ['nullable', 'string', 'max:200'],
            'libro_confirmacion'  => ['nullable', 'string', 'max:50'],
            'folio'               => ['nullable', 'string', 'max:50'],
            'partida_numero'      => ['nullable', 'string', 'max:50'],
            'observaciones'       => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function messages(): array
    {
        return [
            'iglesia_id.required'            => 'Debes seleccionar una iglesia.',
            'iglesia_id.exists'              => 'La iglesia seleccionada no existe.',
            'fecha_confirmacion.required'    => 'La fecha de confirmación es obligatoria.',
            'fecha_confirmacion.date'        => 'La fecha de confirmación no es válida.',
            'fecha_confirmacion.before_or_equal' => 'La fecha de confirmación no puede ser futura.',
            'lugar_confirmacion.max'         => 'El lugar no puede superar los 200 caracteres.',
            'libro_confirmacion.max'         => 'El libro no puede superar los 50 caracteres.',
            'folio.max'                      => 'El folio no puede superar los 50 caracteres.',
            'partida_numero.max'             => 'La partida no puede superar los 50 caracteres.',
            'observaciones.max'              => 'Las observaciones no pueden superar los 500 caracteres.',
        ];
    }

    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    public function guardar(): void
    {
        $this->validate();

        $this->confirmacion->update([
            'iglesia_id'          => $this->iglesia_id,
            'ministro_id'         => $this->ministro_id ?: null,
            'fecha_confirmacion'  => $this->fecha_confirmacion,
            'lugar_confirmacion'  => $this->lugar_confirmacion ?: null,
            'libro_confirmacion'  => $this->libro_confirmacion ?: null,
            'folio'               => $this->folio ?: null,
            'partida_numero'      => $this->partida_numero ?: null,
            'observaciones'       => $this->observaciones ?: null,
        ]);

        session()->flash('success', 'Confirmación actualizada correctamente.');
        $this->redirect(route('confirmacion.index'), navigate: false);
    }

    public function render()
    {
        $centralConn = config('tenancy.central_connection', 'mysql');
        $iglesias    = Iglesias::on($centralConn)->where('estado', 'Activo')->orderBy('nombre')->get();
        $ministros   = Feligres::with('persona')->get();

        return view('livewire.confirmacion.confirmacion-edit', compact('iglesias', 'ministros'));
    }
}