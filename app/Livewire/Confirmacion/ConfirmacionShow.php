<?php

namespace App\Livewire\Confirmacion;

use App\Models\AuditLog;
use App\Models\Confirmacion;
use App\Models\Iglesias;
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
        ]);

        $this->nota_marginal    = $confirmacion->nota_marginal    ?? '';
        $this->lugar_nacimiento = $confirmacion->lugar_nacimiento ?? '';
        $this->lugar_expedicion = $confirmacion->lugar_expedicion ?? '';

        $fe = $confirmacion->fecha_expedicion;
        $this->exp_dia = $fe ? (string) $fe->day   : '';
        $this->exp_mes = $fe ? (string) $fe->month : '';
        $this->exp_ano = $fe ? (string) ($fe->year - 2000) : '';
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

        $ministro = $this->confirmacion->ministro;
        if (! $ministro) {
            $this->addError('firma_nueva', 'No hay ministro asignado a esta confirmación.');
            return;
        }

        if ($ministro->path_firma_principal) {
            Storage::disk('public')->delete($ministro->path_firma_principal);
        }

        $path = $this->firma_nueva->store('firmas-ministro', 'public');
        $ministro->update(['path_firma_principal' => $path]);

        $this->firma_nueva = null;
        $this->confirmacion->load('ministro.persona');
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
        $iglesiaId     = session('tenant.id_iglesia');
        $iglesiaConfig = $iglesiaId ? Iglesias::find($iglesiaId) : null;

        return view('livewire.confirmacion.confirmacion-show', [
            'auditHistory'   => $this->auditHistory,
            'estadoRegistro' => $this->estadoRegistro,
            'iglesiaConfig'  => $iglesiaConfig,
        ]);
    }
}