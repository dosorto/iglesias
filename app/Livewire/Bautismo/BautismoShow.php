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
    public string $lugar_nacimiento = '';
    public string $lugar_celebracion = '';
    public string $exp_dia          = '';
    public string $exp_mes          = '';  // 1–12
    public string $exp_ano          = '';  // full year, e.g. 2026

    public bool $previewMode = false;
    public $firma_nueva = null;

    public string $avisoMinistroCelebrante = '';
    public bool   $mostrarAvisoMinistroCelebrante = false;

    public function mount(Bautismo $bautismo): void
    {
        $this->bautismo = $bautismo->load([
            'iglesia',
            'bautizado.persona',
            'padre.persona',
            'madre.persona',
            'padrino.persona',
            'madrina.persona',
            'encargado.feligres.persona',
        ]);

        $this->nota_marginal    = $bautismo->nota_marginal    ?? '';
        $this->ministro_celebrante = $bautismo->ministro_celebrante ?? '';
        $this->lugar_nacimiento = $bautismo->lugar_nacimiento  ?? '';
        $this->lugar_celebracion = $bautismo->lugar_celebracion ?? '';
        $this->aplicarLugarCelebracionPorDefecto();

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

    private function aplicarLugarCelebracionPorDefecto(): void
    {
        if (trim($this->lugar_celebracion) !== '') {
            return;
        }

        $nombreIglesia = trim((string) ($this->bautismo->iglesia?->nombre ?? ''));
        if ($nombreIglesia === '') {
            $nombreIglesia = trim((string) (TenantIglesia::current()?->nombre ?? ''));
        }

        if ($nombreIglesia !== '') {
            $this->lugar_celebracion = $nombreIglesia;
        }
    }

    private function resolverLugarCelebracionConfiguracion(): ?string
    {
        $nombreIglesia = trim((string) ($this->bautismo->iglesia?->nombre ?? ''));
        if ($nombreIglesia === '') {
            $nombreIglesia = trim((string) (TenantIglesia::current()?->nombre ?? ''));
        }

        return $nombreIglesia !== '' ? $nombreIglesia : null;
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
            'lugar_nacimiento' => ['nullable', 'string', 'max:150'],
            'exp_dia'          => ['nullable', 'integer', 'min:1', 'max:31'],
            'exp_mes'          => ['nullable', 'integer', 'min:1', 'max:12'],
            'exp_ano'          => ['nullable', 'integer', 'digits:4', 'min:1900', 'max:2100'],
        ], [
            'nota_marginal.max'    => 'La nota marginal no puede superar los 500 caracteres.',
            'ministro_celebrante.max' => 'El nombre del ministro celebrante no puede superar los 150 caracteres.',
            'lugar_nacimiento.max' => 'El lugar de nacimiento no puede superar los 150 caracteres.',
            'exp_dia.min'          => 'El día debe ser entre 1 y 31.',
            'exp_mes.min'          => 'El mes debe ser entre 1 y 12.',
        ]);

        // Verificar y aplicar ministro_celebrante antes de guardar
        if (!$this->verificarMinistroCelebrante()) {
            return; // Muestra aviso, no guarda aún
        }

        $this->guardarCertificado();
    }

    private function verificarMinistroCelebrante(): bool
    {
        // Si ministro_celebrante está vacío
        if (trim($this->ministro_celebrante) === '') {
            // Obtener el nombre del encargado
            if ($this->bautismo->encargado_id) {
                $encargado = $this->bautismo->encargado;
                if ($encargado) {
                    $nombreEncargado = trim((string) ($encargado->nombre_completo ?? ''));
                    if ($nombreEncargado !== '') {
                        // Mostrar aviso
                        $this->avisoMinistroCelebrante = "El campo 'Ministro Celebrante' está vacío. ¿Desea llenarlo automáticamente con: {$nombreEncargado}?";
                        $this->mostrarAvisoMinistroCelebrante = true;
                        return false; // No guardar aún
                    }
                }
            }
        }
        return true; // Puede guardar normalmente
    }

    public function confirmarYGuardarConMinistroCelebrante(): void
    {
        // Llenar ministro_celebrante con el nombre del encargado
        if ($this->bautismo->encargado_id) {
            $encargado = $this->bautismo->encargado;
            if ($encargado) {
                $this->ministro_celebrante = trim((string) ($encargado->nombre_completo ?? ''));
            }
        }

        $this->mostrarAvisoMinistroCelebrante = false;
        $this->guardarCertificado();
    }

    public function rechazarAvisoYGuardar(): void
    {
        $this->mostrarAvisoMinistroCelebrante = false;
        $this->avisoMinistroCelebrante = '';
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
