<?php

namespace App\Livewire\PrimeraComunion;

use App\Models\AuditLog;
use App\Models\Encargado;
use App\Models\TenantIglesia;
use App\Models\PrimeraComunion;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class PrimeraComunionShow extends Component
{
    use WithFileUploads;

    public PrimeraComunion $primeraComunion;

    public string $nota_marginal     = '';
    public string $lugar_celebracion = '';
    public string $lugar_expedicion  = '';
    public string $exp_dia           = '';
    public string $exp_mes           = '';
    public string $exp_ano           = '';

    public bool $previewMode = false;
    public $firma_nueva = null;

    public function mount(PrimeraComunion $primeraComunion): void
    {
        $this->primeraComunion = $primeraComunion->load([
            'iglesia',
            'feligres.persona',
            'catequista.persona',
            'ministro.persona',
            'parroco.persona',
            'encargado.feligres.persona',
        ]);

        // Si no tiene encargado asignado, tomar el encargado activo por defecto
        if (! $this->primeraComunion->encargado) {
            $encargadoDefault = Encargado::with('feligres.persona')
                ->where('estado', 'Activo')
                ->first();

            if ($encargadoDefault) {
                $this->primeraComunion->encargado_id = $encargadoDefault->id;
                $this->primeraComunion->save();
                $this->primeraComunion->load('encargado.feligres.persona');
            }
        }

        $this->nota_marginal     = $primeraComunion->nota_marginal     ?? '';
        $this->lugar_celebracion = $primeraComunion->lugar_celebracion ?? '';
        $this->lugar_expedicion  = $primeraComunion->lugar_expedicion  ?? '';

        $fe = $primeraComunion->fecha_expedicion;
        $this->exp_dia = $fe ? (string) $fe->day           : '';
        $this->exp_mes = $fe ? (string) $fe->month         : '';
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

        $encargado = $this->primeraComunion->encargado;
        if (! $encargado) {
            $this->addError('firma_nueva', 'No hay encargado asignado a este registro.');
            return;
        }

        if ($encargado->path_firma_principal) {
            Storage::disk('public')->delete($encargado->path_firma_principal);
        }

        $path = $this->firma_nueva->store('firmas-encargado', 'public');
        $encargado->update(['path_firma_principal' => $path]);

        $this->firma_nueva = null;
        $this->primeraComunion->load('encargado.feligres.persona');
        session()->flash('success', 'Firma guardada correctamente.');
    }

    public function saveCertificate(): void
    {
        $this->validate([
            'nota_marginal'     => ['nullable', 'string', 'max:500'],
            'lugar_celebracion' => ['nullable', 'string', 'max:150'],
            'lugar_expedicion'  => ['nullable', 'string', 'max:150'],
            'exp_dia'           => ['nullable', 'integer', 'min:1', 'max:31'],
            'exp_mes'           => ['nullable', 'integer', 'min:1', 'max:12'],
            'exp_ano'           => ['nullable', 'integer', 'min:0', 'max:99'],
        ], [
            'nota_marginal.max'     => 'La nota marginal no puede superar los 500 caracteres.',
            'lugar_celebracion.max' => 'El lugar no puede superar los 150 caracteres.',
            'lugar_expedicion.max'  => 'El lugar no puede superar los 150 caracteres.',
            'exp_dia.min'           => 'El día debe ser entre 1 y 31.',
            'exp_mes.min'           => 'El mes debe ser entre 1 y 12.',
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

        $this->primeraComunion->update([
            'nota_marginal'     => $this->nota_marginal     ?: null,
            'lugar_celebracion' => $this->lugar_celebracion ?: null,
            'lugar_expedicion'  => $this->lugar_expedicion  ?: null,
            'fecha_expedicion'  => $fechaExp,
        ]);

        $this->primeraComunion->refresh();
        session()->flash('success', 'Borrador guardado correctamente.');
    }

    public function getAuditHistoryProperty()
    {
        return AuditLog::where('auditable_type', PrimeraComunion::class)
            ->where('auditable_id', $this->primeraComunion->id)
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

        return view('livewire.primera-comunion.primera-comunion-show', [
            'auditHistory'   => $this->auditHistory,
            'estadoRegistro' => $this->estadoRegistro,
            'iglesiaConfig'  => $iglesiaConfig,
        ]);
    }
}