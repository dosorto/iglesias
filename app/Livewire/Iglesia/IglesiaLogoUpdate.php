<?php

namespace App\Livewire\Iglesia;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Iglesias;

#[Layout('layouts.app')]
class IglesiaLogoUpdate extends Component
{
    use WithFileUploads;

    public Iglesias $iglesia;
    public $logo_nuevo = null;
    public bool $guardado = false;

    public function mount(): void
    {
        $idIglesia = session('tenant.id_iglesia');
        $this->iglesia = Iglesias::findOrFail($idIglesia);
    }

    public function guardarLogo(): void
    {
        $this->validate([
            'logo_nuevo' => ['required', 'image', 'max:2048'],
        ], [
            'logo_nuevo.required' => 'Debes seleccionar una imagen.',
            'logo_nuevo.image'    => 'El archivo debe ser una imagen (PNG, JPG, JPEG).',
            'logo_nuevo.max'      => 'La imagen no debe superar los 2MB.',
        ]);

        $pathAnterior = $this->iglesia->path_logo;
        $path = $this->storePublicUpload($this->logo_nuevo, 'logos');
        $this->iglesia->update(['path_logo' => $path]);
        $this->deletePublicFileSilently($pathAnterior);
        $this->iglesia->refresh();

        $this->logo_nuevo = null;
        $this->guardado   = true;
    }

    public function eliminarLogo(): void
    {
        if ($this->iglesia->path_logo) {
            $this->deletePublicFileSilently($this->iglesia->path_logo);
            $this->iglesia->update(['path_logo' => null]);
            $this->iglesia->refresh();
        }
        $this->guardado = false;
    }

    public function render()
    {
        return view('livewire.iglesia.iglesia-logo-update');
    }

    private function storePublicUpload(UploadedFile $file, string $directory): string
    {
        Storage::disk('public')->makeDirectory($directory);

        return $file->store($directory, 'public');
    }

    private function deletePublicFileSilently(?string $path): void
    {
        if (! filled($path)) {
            return;
        }

        try {
            Storage::disk('public')->delete($path);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
