<?php

namespace App\Livewire\Bautismo;

use App\Models\AuditLog;
use App\Models\Bautismo;
use App\Models\DocumentoGenerado;
use App\Models\TenantIglesia;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class BautismoShow extends Component
{
    use WithFileUploads;
    public Bautismo $bautismo;

    
    // Certificate fields (editable from the show page)
    public string $nota_marginal    = '';
    public string $ministro_celebrante = '';
    public string $parroco_celebrante  = '';
    public string $lugar_nacimiento = '';
    public string $lugar_celebracion = '';
    public string $exp_dia          = '';
    public string $exp_mes          = '';  // 1–12
    public string $exp_ano          = '';  // full year, e.g. 2026

    public bool $previewMode = false;
    public $firma_nueva = null;

    public function mount(Bautismo $bautismo): void
    {
        $this->bautismo = $bautismo->load([
            'iglesia',
            'bautizado.persona',
            'padre.persona',
            'madre.persona',
            'padrino.persona',
            'madrina.persona',
            'ministro.persona',
            'encargado.feligres.persona',
        ]);

        $this->nota_marginal    = $bautismo->nota_marginal    ?? '';
        $this->ministro_celebrante = $bautismo->ministro_celebrante ?? '';
        $this->parroco_celebrante  = $bautismo->parroco_celebrante  ?? '';
        $this->lugar_nacimiento = $bautismo->lugar_nacimiento  ?? '';
        $this->lugar_celebracion = $bautismo->lugar_celebracion ?? '';

        $fe = $bautismo->fecha_expedicion;
        if ($fe) {
            $this->exp_dia = (string) $fe->day;
            $this->exp_mes = (string) $fe->month;
            $this->exp_ano = (string) $fe->year;
        } else {
            $today = now();
            $this->exp_dia = (string) $today->day;
            $this->exp_mes = (string) $today->month;
            $this->exp_ano = (string) $today->year;
        }
    }

    private function resolverLugarCelebracionConfiguracion(): ?string
    {
        return trim($this->lugar_celebracion) !== '' ? trim($this->lugar_celebracion) : null;
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

        $encargado = $this->bautismo->encargado;
        if (! $encargado) {
            $this->addError('firma_nueva', 'No hay encargado asignado a este bautismo.');
            return;
        }

        if ($encargado->path_firma_principal) {
            Storage::disk('public')->delete($encargado->path_firma_principal);
        }

        $path = $this->firma_nueva->store('firmas-encargado', 'public');
        $encargado->update(['path_firma_principal' => $path]);

        $this->firma_nueva = null;
        $this->bautismo->load('encargado.feligres.persona');
        $iglesiaDocumentoId = TenantIglesia::currentId();

        DocumentoGenerado::query()
            ->where('tipo_documento', 'bautismo_certificado')
            ->where('fuente_tipo', Bautismo::class)
            ->where('fuente_id', (int) $this->bautismo->id)
            ->when($iglesiaDocumentoId !== null, fn ($query) => $query->where('iglesia_id', $iglesiaDocumentoId))
            ->delete();

        session()->flash('success', 'Firma guardada correctamente.');
    }

    public function saveCertificate(): void
    {
        $this->validate([
            'nota_marginal'    => ['nullable', 'string', 'max:500'],
            'ministro_celebrante' => ['nullable', 'string', 'max:150'],
            'parroco_celebrante'  => ['nullable', 'string', 'max:150'],
            'lugar_nacimiento' => ['nullable', 'string', 'max:150'],
            'lugar_celebracion' => ['required', 'string', 'max:255'],
            'exp_dia'          => ['nullable', 'integer', 'min:1', 'max:31'],
            'exp_mes'          => ['nullable', 'integer', 'min:1', 'max:12'],
            'exp_ano'          => ['nullable', 'integer', 'digits:4', 'min:1900', 'max:2100'],
        ], [
            'nota_marginal.max'    => 'La nota marginal no puede superar los 500 caracteres.',
            'ministro_celebrante.max' => 'El nombre del ministro celebrante no puede superar los 150 caracteres.',
            'parroco_celebrante.max'  => 'El ministro celebrante / párroco del momento no puede superar los 150 caracteres.',
            'lugar_nacimiento.max' => 'El lugar de nacimiento no puede superar los 150 caracteres.',
            'lugar_celebracion.required' => 'El lugar de celebración es obligatorio.',
            'lugar_celebracion.max' => 'El lugar de celebración no puede superar los 255 caracteres.',
            'exp_dia.min'          => 'El día debe ser entre 1 y 31.',
            'exp_mes.min'          => 'El mes debe ser entre 1 y 12.',
        ]);

        $this->guardarCertificado();
    }

    private function guardarCertificado(): void
    {
        $fechaExp = now()->format('Y-m-d');
        if ($this->exp_dia && $this->exp_mes && $this->exp_ano !== '') {
            try {
                $fechaExp = \Carbon\Carbon::createFromDate(
                    (int) $this->exp_ano,
                    (int) $this->exp_mes,
                    (int) $this->exp_dia
                )->format('Y-m-d');
            } catch (\Exception) {
                $fechaExp = now()->format('Y-m-d');
            }
        }

        $lugarCelebracion = $this->resolverLugarCelebracionConfiguracion();
        $this->lugar_celebracion = $lugarCelebracion ?? '';

        $this->bautismo->update([
            'nota_marginal'    => $this->nota_marginal    ?: null,
            'ministro_celebrante' => $this->ministro_celebrante ?: null,
            'parroco_celebrante'  => $this->parroco_celebrante  ?: null,
            'lugar_nacimiento' => $this->lugar_nacimiento ?: null,
            'lugar_celebracion' => $lugarCelebracion,
            'fecha_expedicion' => $fechaExp,
        ]);

        $iglesiaDocumentoId = TenantIglesia::currentId();

        DocumentoGenerado::query()
            ->where('tipo_documento', 'bautismo_certificado')
            ->where('fuente_tipo', Bautismo::class)
            ->where('fuente_id', (int) $this->bautismo->id)
            ->when($iglesiaDocumentoId !== null, fn ($query) => $query->where('iglesia_id', $iglesiaDocumentoId))
            ->delete();

        $this->bautismo->refresh();
        session()->flash('success', 'Borrador guardado correctamente.');
    }

    public function getAuditHistoryProperty()
    {
        return AuditLog::where('auditable_type', Bautismo::class)
            ->where('auditable_id', $this->bautismo->id)
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

        return view('livewire.bautismo.bautismo-show', [
            'auditHistory'   => $this->auditHistory,
            'estadoRegistro' => $this->estadoRegistro,
            'iglesiaConfig'  => $iglesiaConfig,
        ]);
    }
}
