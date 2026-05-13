<?php

namespace App\Livewire\Matrimonio;

use App\Models\Matrimonio;
use App\Models\TenantIglesia;
use Livewire\Component;

class MatrimonioShow extends Component
{
    public Matrimonio $matrimonio;

    public string $nota_marginal    = '';
    public string $lugar_expedicion = '';
    public string $exp_dia          = '';
    public string $exp_mes          = '';
    public string $exp_ano          = '';

    public function mount(Matrimonio $matrimonio): void
    {
        $this->matrimonio = $matrimonio->load([
            'iglesia',
            'esposo.persona',
            'esposa.persona',
            'testigo1.persona',
            'testigo2.persona',
            'encargado.feligres.persona',
        ]);

        $this->nota_marginal    = $matrimonio->nota_marginal    ?? '';
        $this->lugar_expedicion = $matrimonio->lugar_expedicion ?? '';
        $this->aplicarLugarExpedicionPorDefecto();

        $fe            = $matrimonio->fecha_expedicion;
        $this->exp_dia = $fe ? (string) $fe->day   : '';
        $this->exp_mes = $fe ? (string) $fe->month : '';
        $this->exp_ano = $fe ? (string) ($fe->year - 2000) : '';
    }

    private function aplicarLugarExpedicionPorDefecto(): void
    {
        if (trim($this->lugar_expedicion) !== '') {
            return;
        }

        $direccion = trim((string) ($this->matrimonio->iglesia?->direccion ?? ''));
        if ($direccion === '') {
            $direccion = trim((string) (TenantIglesia::current()?->direccion ?? ''));
        }

        if ($direccion !== '') {
            $this->lugar_expedicion = $direccion;
        }
    }

    private function resolverLugarExpedicionConfiguracion(): ?string
    {
        $direccion = trim((string) ($this->matrimonio->iglesia?->direccion ?? ''));
        if ($direccion === '') {
            $direccion = trim((string) (TenantIglesia::current()?->direccion ?? ''));
        }

        return $direccion !== '' ? $direccion : null;
    }

    public function saveCertificate(): void
    {
        $this->validate([
            'nota_marginal'    => ['nullable', 'string', 'max:500'],
            'exp_dia'          => ['nullable', 'integer', 'min:1', 'max:31'],
            'exp_mes'          => ['nullable', 'integer', 'min:1', 'max:12'],
            'exp_ano'          => ['nullable', 'integer', 'min:0', 'max:99'],
        ], [
            'nota_marginal.max'    => 'La nota marginal no puede superar los 500 caracteres.',
            'exp_dia.min'          => 'El día debe ser entre 1 y 31.',
            'exp_mes.min'          => 'El mes debe ser entre 1 y 12.',
        ]);

        $fechaExp = now()->format('Y-m-d');
        if ($this->exp_dia && $this->exp_mes && $this->exp_ano !== '') {
            try {
                $fechaExp = \Carbon\Carbon::createFromDate(
                    2000 + (int) $this->exp_ano,
                    (int) $this->exp_mes,
                    (int) $this->exp_dia
                )->format('Y-m-d');
            } catch (\Exception) {
                $fechaExp = now()->format('Y-m-d');
            }
        }

        $lugarExpedicion = $this->resolverLugarExpedicionConfiguracion();
        $this->lugar_expedicion = $lugarExpedicion ?? '';

        $this->matrimonio->update([
            'nota_marginal'    => $this->nota_marginal    ?: null,
            'lugar_expedicion' => $lugarExpedicion,
            'fecha_expedicion' => $fechaExp,
        ]);

        session()->flash('success', 'Datos de expedición guardados correctamente.');
        $this->matrimonio->refresh();
    }

    public function render()
    {
        $iglesiaConfig = TenantIglesia::current();

        return view('livewire.matrimonio.matrimonio-show', [
            'iglesiaConfig' => $iglesiaConfig,
        ]);
    }
}
