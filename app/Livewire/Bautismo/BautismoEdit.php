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
    public string $nota_marginal    = '';
    public string $lugar_nacimiento = '';
    public string $lugar_expedicion = '';
    public string $exp_dia          = '';
    public string $exp_mes          = '';
    public string $exp_ano          = '';

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
        $this->nota_marginal    = $bautismo->nota_marginal ?? '';
        $this->lugar_nacimiento = $bautismo->lugar_nacimiento ?? '';
        $this->lugar_expedicion = $bautismo->lugar_expedicion ?? '';

        $fechaExp = $bautismo->fecha_expedicion;
        $this->exp_dia = $fechaExp?->day ? (string) $fechaExp->day : '';
        $this->exp_mes = $fechaExp?->month ? (string) $fechaExp->month : '';
        $this->exp_ano = $fechaExp?->year ? (string) ($fechaExp->year - 2000) : '';
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
            'nota_marginal'    => ['nullable', 'string', 'max:500'],
            'lugar_nacimiento' => ['nullable', 'string', 'max:150'],
            'lugar_expedicion' => ['nullable', 'string', 'max:150'],
            'exp_dia'          => ['nullable', 'integer', 'min:1', 'max:31'],
            'exp_mes'          => ['nullable', 'integer', 'min:1', 'max:12'],
            'exp_ano'          => ['nullable', 'integer', 'min:0', 'max:99'],
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
            'nota_marginal.max'             => 'La nota marginal no puede superar los 500 caracteres.',
            'lugar_nacimiento.max'          => 'El lugar de nacimiento no puede superar los 150 caracteres.',
            'lugar_expedicion.max'          => 'El lugar de expedición no puede superar los 150 caracteres.',
            'exp_dia.min'                   => 'El día de expedición debe ser entre 1 y 31.',
            'exp_mes.min'                   => 'El mes de expedición debe ser entre 1 y 12.',
        ];
    }

    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    public function guardar(): void
    {
        $this->validate();

        $fechaExp = null;
        if ($this->exp_dia && $this->exp_mes && $this->exp_ano !== '') {
            try {
                $fechaExp = \Carbon\Carbon::createFromDate(
                    2000 + (int) $this->exp_ano,
                    (int) $this->exp_mes,
                    (int) $this->exp_dia
                )->format('Y-m-d');
            } catch (\Exception) {
                $fechaExp = null;
            }
        }

        $this->bautismo->update([
            'iglesia_id'     => $this->iglesia_id,
            'encargado_id'   => $this->encargado_id,
            'fecha_bautismo' => $this->fecha_bautismo,
            'libro_bautismo' => $this->libro_bautismo ?: null,
            'folio'          => $this->folio ?: null,
            'partida_numero' => $this->partida_numero ?: null,
            'observaciones'  => $this->observaciones ?: null,
            'nota_marginal'    => $this->nota_marginal ?: null,
            'lugar_nacimiento' => $this->lugar_nacimiento ?: null,
            'lugar_expedicion' => $this->lugar_expedicion ?: null,
            'fecha_expedicion' => $fechaExp,
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
