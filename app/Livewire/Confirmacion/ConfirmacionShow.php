<?php

namespace App\Livewire\Confirmacion;

use App\Models\AuditLog;
use App\Models\Confirmacion;
use App\Models\Encargado;
use App\Models\Iglesias;
use App\Models\TenantIglesia;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ConfirmacionShow extends Component
{
    use WithFileUploads;

    public Confirmacion $confirmacion;

    public string $nota_marginal    = '';
    public string $lugar_nacimiento = '';
    public string $lugar_expedicion = '';
    public string $exp_dia          = '';
    public string $exp_mes          = '';
    public string $exp_ano          = '';

    public bool $previewMode = false;
    public $firma_nueva = null;

    public function mount(Confirmacion $confirmacion): void
    {
        $this->confirmacion = $confirmacion->load([
            'iglesia',
            'feligres.persona',
            'padre.persona',
            'madre.persona',
            'padrino.persona',
            'madrina.persona',
            'ministro.persona',
            'encargado.feligres.persona',
        ]);

        // Si no tiene encargado asignado, tomar el encargado activo por defecto
        // igual que hace bautismo en su mount
        if (! $this->confirmacion->encargado) {
            $encargadoDefault = Encargado::with('feligres.persona')
                ->where('estado', 'Activo')
                ->first();

            if ($encargadoDefault) {
                $this->confirmacion->encargado_id = $encargadoDefault->id;
                $this->confirmacion->save();
                $this->confirmacion->load('encargado.feligres.persona');
            }
        }

        $this->nota_marginal    = $confirmacion->nota_marginal    ?? '';
        $this->lugar_nacimiento = $confirmacion->lugar_nacimiento ?? '';
        $this->lugar_expedicion = $confirmacion->lugar_expedicion ?? '';
        $this->aplicarLugarExpedicionPorDefecto();

        $fe = $confirmacion->fecha_expedicion;
        $this->exp_dia = $fe ? (string) $fe->day   : '';
        $this->exp_mes = $fe ? (string) $fe->month : '';
        $this->exp_ano = $fe ? (string) ($fe->year - 2000) : '';
    }

    private function aplicarLugarExpedicionPorDefecto(): void
    {
        if (trim($this->lugar_expedicion) !== '') {
            return;
        }

        $direccion = trim((string) ($this->confirmacion->iglesia?->direccion ?? ''));
        if ($direccion === '') {
            $direccion = trim((string) (TenantIglesia::current()?->direccion ?? ''));
        }

        if ($direccion !== '') {
            $this->lugar_expedicion = $direccion;
        }
    }

    public function togglePreview(): void
    {
        $this->previewMode = ! $this->previewMode;
    }

    public function uploadFirma(): void
    {
        $this->validate([
            'firma_nueva' => ['required', 'image', 'max:2048'],
        ], [
            'firma_nueva.required' => 'Seleccione una imagen para la firma.',
            'firma_nueva.image'    => 'El archivo debe ser una imagen.',
            'firma_nueva.max'      => 'La imagen no debe superar 2 MB.',
        ]);

        $encargado = $this->confirmacion->encargado;
        if (! $encargado) {
            $this->addError('firma_nueva', 'No hay encargado asignado a esta confirmación.');
            return;
        }

        if ($encargado->path_firma_principal) {
            Storage::disk('public')->delete($encargado->path_firma_principal);
        }

        $path = $this->firma_nueva->store('firmas-encargado', 'public');
        $encargado->update(['path_firma_principal' => $path]);

        $this->firma_nueva = null;
        $this->confirmacion->load('encargado.feligres.persona');
        session()->flash('success', 'Firma guardada correctamente.');
    }

    public function saveCertificate(): void
    {
        $this->validate([
            'nota_marginal'    => ['nullable', 'string', 'max:500'],
            'lugar_nacimiento' => ['nullable', 'string', 'max:150'],
            'lugar_expedicion' => ['nullable', 'string', 'max:150'],
            'exp_dia'          => ['nullable', 'integer', 'min:1', 'max:31'],
            'exp_mes'          => ['nullable', 'integer', 'min:1', 'max:12'],
            'exp_ano'          => ['nullable', 'integer', 'min:0', 'max:99'],
        ], [
            'nota_marginal.max'    => 'La nota marginal no puede superar los 500 caracteres.',
            'lugar_nacimiento.max' => 'El lugar de nacimiento no puede superar los 150 caracteres.',
            'lugar_expedicion.max' => 'El lugar no puede superar los 150 caracteres.',
            'exp_dia.min'          => 'El día debe ser entre 1 y 31.',
            'exp_mes.min'          => 'El mes debe ser entre 1 y 12.',
        ]);

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

        $this->confirmacion->update([
            'nota_marginal'    => $this->nota_marginal    ?: null,
            'lugar_nacimiento' => $this->lugar_nacimiento ?: null,
            'lugar_expedicion' => $this->lugar_expedicion ?: null,
            'fecha_expedicion' => $fechaExp,
        ]);

        $this->confirmacion->refresh();
        session()->flash('success', 'Borrador guardado correctamente.');
    }

    public function getAuditHistoryProperty()
    {
        return AuditLog::where('auditable_type', Confirmacion::class)
            ->where('auditable_id', $this->confirmacion->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
    }

    public function getEstadoRegistroProperty(): string
    {
        return ($this->exp_dia && $this->exp_mes && $this->exp_ano) ? 'Emitido' : 'Nuevo Registro';
    }

    public function render()
    {
        $iglesiaConfig = TenantIglesia::current();

        return view('livewire.confirmacion.confirmacion-show', [
            'auditHistory'   => $this->auditHistory,
            'estadoRegistro' => $this->estadoRegistro,
            'iglesiaConfig'  => $iglesiaConfig,
        ]);
    }
}