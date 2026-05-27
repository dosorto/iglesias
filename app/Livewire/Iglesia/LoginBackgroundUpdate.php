<?php

namespace App\Livewire\Iglesia;

use App\Models\TenantIglesia;
use Illuminate\Http\UploadedFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class LoginBackgroundUpdate extends Component
{
    use WithFileUploads;

    public ?TenantIglesia $iglesia = null;
    public $imagen_nueva = null;
    public bool $guardado = false;

    public function mount(): void
    {
        $this->iglesia = TenantIglesia::current();
    }

    public function guardar(): void
    {
        $this->validate([
            'imagen_nueva' => ['required', 'image', 'max:4096'],
        ], [
            'imagen_nueva.required' => 'Debes seleccionar una imagen.',
            'imagen_nueva.image'    => 'El archivo debe ser una imagen (PNG, JPG, JPEG, WebP).',
            'imagen_nueva.max'      => 'La imagen no debe superar los 4MB.',
        ]);

        $pathAnterior = $this->iglesia->path_login_background;
        $path = $this->storePublicUpload($this->imagen_nueva, 'login-backgrounds');
        $this->iglesia->update(['path_login_background' => $path]);
        $this->deletePublicFileSilently($pathAnterior);
        $this->iglesia->refresh();

        $this->imagen_nueva = null;
        $this->guardado = true;
    }

    public function eliminar(): void
    {
        if ($this->iglesia?->path_login_background) {
            $this->deletePublicFileSilently($this->iglesia->path_login_background);
            $this->iglesia->update(['path_login_background' => null]);
            $this->iglesia->refresh();
        }
        $this->guardado = false;
    }

    public function render()
    {
        return view('livewire.iglesia.login-background-update');
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
