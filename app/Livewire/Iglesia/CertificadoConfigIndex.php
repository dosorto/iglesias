<?php

namespace App\Livewire\Iglesia;

use App\Models\TenantIglesia;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CertificadoConfigIndex extends Component
{
    use WithFileUploads;

    public ?TenantIglesia $iglesia = null;
    public $formato_nuevo = null;
    public bool $confirmandoEliminar = false;

    public $logo_nuevo = null;
    public bool $confirmandoEliminarLogo = false;
    public string $orientacion_certificado = 'portrait';

    public function mount(): void
    {
        $this->iglesia = TenantIglesia::current();
        $this->orientacion_certificado = $this->iglesia?->orientacion_certificado ?: 'portrait';
    }

    public function guardarOrientacion(): void
    {
        $this->validate([
            'orientacion_certificado' => ['required', 'in:portrait,landscape'],
        ]);

        if (! $this->iglesia) {
            session()->flash('error', 'No se encontró una iglesia configurada.');
            return;
        }

        $this->iglesia->update([
            'orientacion_certificado' => $this->orientacion_certificado,
        ]);

        $this->iglesia->refresh();
        session()->flash('success', 'Orientación de certificado actualizada correctamente.');
    }

    public function subirFormato(): void
    {
        $this->validate([
            'formato_nuevo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ], [
            'formato_nuevo.required' => 'Seleccione una imagen para el formato.',
            'formato_nuevo.image'    => 'El archivo debe ser una imagen.',
            'formato_nuevo.mimes'    => 'Solo se aceptan imágenes JPG o PNG.',
            'formato_nuevo.max'      => 'La imagen no debe superar 5 MB.',
        ]);

        if (! $this->iglesia) {
            session()->flash('error', 'No se encontró una iglesia configurada.');
            return;
        }

        // Eliminar archivo anterior si existe
        if ($this->iglesia->path_certificado_bautismo) {
            Storage::disk('public')->delete($this->iglesia->path_certificado_bautismo);
        }

        $path = $this->formato_nuevo->store('certificados', 'public');
        $this->iglesia->update(['path_certificado_bautismo' => $path]);

        $this->formato_nuevo = null;
        $this->iglesia->refresh();

        session()->flash('success', 'Formato de certificado actualizado correctamente.');
    }

    public function eliminarFormato(): void
    {
        if (! $this->iglesia || ! $this->iglesia->path_certificado_bautismo) {
            $this->confirmandoEliminar = false;
            return;
        }

        Storage::disk('public')->delete($this->iglesia->path_certificado_bautismo);
        $this->iglesia->update(['path_certificado_bautismo' => null]);
        $this->iglesia->refresh();

        $this->confirmandoEliminar = false;
        session()->flash('success', 'Formato de certificado eliminado.');
    }

    public function subirLogo(): void
    {
        $this->validate([
            'logo_nuevo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'logo_nuevo.required' => 'Seleccione una imagen para el logo.',
            'logo_nuevo.image'    => 'El archivo debe ser una imagen.',
            'logo_nuevo.mimes'    => 'Solo se aceptan imágenes JPG o PNG.',
            'logo_nuevo.max'      => 'La imagen no debe superar 2 MB.',
        ]);

        if (! $this->iglesia) {
            session()->flash('error', 'No se encontró una iglesia configurada.');
            return;
        }

        if ($this->iglesia->path_logo) {
            Storage::disk('public')->delete($this->iglesia->path_logo);
        }

        $path = $this->logo_nuevo->store('logos', 'public');
        $this->iglesia->update(['path_logo' => $path]);

        $this->logo_nuevo = null;
        $this->iglesia->refresh();

        session()->flash('success', 'Logo de la iglesia actualizado correctamente.');
    }

    public function eliminarLogo(): void
    {
        if (! $this->iglesia || ! $this->iglesia->path_logo) {
            $this->confirmandoEliminarLogo = false;
            return;
        }

        Storage::disk('public')->delete($this->iglesia->path_logo);
        $this->iglesia->update(['path_logo' => null]);
        $this->iglesia->refresh();

        $this->confirmandoEliminarLogo = false;
        session()->flash('success', 'Logo de la iglesia eliminado.');
    }

    public function render()
    {
        return view('livewire.iglesia.certificado-config-index');
    }
}
