<?php

namespace App\Livewire\Iglesia;

use App\Models\TenantIglesia;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CertificadoConfigIndex extends Component
{
    use WithFileUploads;

    private const FORMATOS = [
        'bautismo' => [
            'titulo' => 'Bautismo',
            'descripcion' => 'Formato de certificado de bautismo.',
            'path' => 'path_certificado_bautismo',
            'url' => 'certificado_bautismo_url',
            'orientacion' => 'orientacion_certificado_bautismo',
            'orientacion_fallback' => 'orientacion_certificado',
            'orientacion_default' => 'portrait',
        ],
        'confirmacion' => [
            'titulo' => 'Confirmación',
            'descripcion' => 'Formato de certificación de confirmación.',
            'path' => 'path_certificado_confirmacion',
            'url' => 'certificado_confirmacion_url',
            'orientacion' => 'orientacion_certificado_confirmacion',
            'orientacion_default' => 'portrait',
        ],
        'primera_comunion' => [
            'titulo' => 'Primera Comunión',
            'descripcion' => 'Formato de certificación de primera comunión.',
            'path' => 'path_certificado_primera_comunion',
            'url' => 'certificado_primera_comunion_url',
            'orientacion' => 'orientacion_certificado_primera_comunion',
            'orientacion_default' => 'portrait',
        ],
        'matrimonio' => [
            'titulo' => 'Matrimonio',
            'descripcion' => 'Formato de constancia de matrimonio.',
            'path' => 'path_certificado_matrimonio',
            'url' => 'certificado_matrimonio_url',
            'orientacion' => 'orientacion_certificado_matrimonio',
            'orientacion_default' => 'portrait',
        ],
        'curso' => [
            'titulo' => 'Certificado de Curso',
            'descripcion' => 'Formato de certificado para cursos aprobados.',
            'path' => 'path_certificado_curso',
            'url' => 'certificado_curso_url',
            'orientacion' => 'orientacion_certificado_curso',
            'orientacion_default' => 'landscape',
        ],
    ];

    public ?TenantIglesia $iglesia = null;
    public array $formatos_nuevos = [];
    public array $confirmandoEliminar = [];
    public array $orientaciones = [];

    public $logo_nuevo = null;
    public bool $confirmandoEliminarLogo = false;
    public $logo_derecha_nuevo = null;
    public bool $confirmandoEliminarLogoDerecha = false;
    public string $orientacion_certificado = 'portrait';

    public function mount(): void
    {
        $this->iglesia = TenantIglesia::current();
        $this->orientacion_certificado = $this->iglesia?->orientacion_certificado ?: 'portrait';

        foreach (self::FORMATOS as $tipo => $config) {
            $orientacion = $this->iglesia?->{$config['orientacion']} ?? null;

            if (! $orientacion && isset($config['orientacion_fallback'])) {
                $orientacion = $this->iglesia?->{$config['orientacion_fallback']} ?? null;
            }

            $this->orientaciones[$tipo] = $orientacion ?: $config['orientacion_default'];
            $this->confirmandoEliminar[$tipo] = false;
        }
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

    public function guardarOrientacionTipo(string $tipo): void
    {
        if (! isset(self::FORMATOS[$tipo])) {
            return;
        }

        $this->validate([
            "orientaciones.$tipo" => ['required', 'in:portrait,landscape'],
        ]);

        if (! $this->iglesia) {
            session()->flash('error', 'No se encontró una iglesia configurada.');
            return;
        }

        $columnaOrientacion = self::FORMATOS[$tipo]['orientacion'];
        $updates = [
            $columnaOrientacion => $this->orientaciones[$tipo],
        ];

        if ($tipo === 'bautismo') {
            // Compatibilidad con configuración anterior.
            $updates['orientacion_certificado'] = $this->orientaciones[$tipo];
            $this->orientacion_certificado = $this->orientaciones[$tipo];
        }

        $this->iglesia->update($updates);
        $this->iglesia->refresh();

        session()->flash('success', 'Orientación de ' . strtolower(self::FORMATOS[$tipo]['titulo']) . ' actualizada correctamente.');
    }

    public function subirFormato(string $tipo): void
    {
        if (! isset(self::FORMATOS[$tipo])) {
            return;
        }

        $this->validate([
            "formatos_nuevos.$tipo" => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ], [
            "formatos_nuevos.$tipo.required" => 'Seleccione una imagen para el formato.',
            "formatos_nuevos.$tipo.image"    => 'El archivo debe ser una imagen.',
            "formatos_nuevos.$tipo.mimes"    => 'Solo se aceptan imágenes JPG o PNG.',
            "formatos_nuevos.$tipo.max"      => 'La imagen no debe superar 5 MB.',
        ]);

        if (! $this->iglesia) {
            session()->flash('error', 'No se encontró una iglesia configurada.');
            return;
        }

        $columnaPath = self::FORMATOS[$tipo]['path'];
        $archivoActual = $this->iglesia->{$columnaPath};

        if ($archivoActual) {
            Storage::disk('public')->delete($archivoActual);
        }

        $archivo = data_get($this->formatos_nuevos, $tipo);
        $path = $archivo->store('certificados', 'public');

        $updates = [
            $columnaPath => $path,
        ];

        if ($tipo === 'bautismo') {
            // Compatibilidad con configuración anterior.
            $updates['orientacion_certificado'] = $this->orientaciones[$tipo] ?? 'portrait';
        }

        $this->iglesia->update($updates);

        $this->formatos_nuevos[$tipo] = null;
        $this->iglesia->refresh();

        session()->flash('success', 'Formato de ' . strtolower(self::FORMATOS[$tipo]['titulo']) . ' actualizado correctamente.');
    }

    public function eliminarFormato(string $tipo): void
    {
        if (! isset(self::FORMATOS[$tipo])) {
            return;
        }

        $columnaPath = self::FORMATOS[$tipo]['path'];
        $archivoActual = $this->iglesia?->{$columnaPath};

        if (! $this->iglesia || ! $archivoActual) {
            $this->confirmandoEliminar[$tipo] = false;
            return;
        }

        Storage::disk('public')->delete($archivoActual);
        $this->iglesia->update([$columnaPath => null]);
        $this->iglesia->refresh();

        $this->confirmandoEliminar[$tipo] = false;
        session()->flash('success', 'Formato de ' . strtolower(self::FORMATOS[$tipo]['titulo']) . ' eliminado.');
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

    public function subirLogoDerecha(): void
    {
        $this->validate([
            'logo_derecha_nuevo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'logo_derecha_nuevo.required' => 'Seleccione una imagen para el logo derecho.',
            'logo_derecha_nuevo.image'    => 'El archivo debe ser una imagen.',
            'logo_derecha_nuevo.mimes'    => 'Solo se aceptan imágenes JPG o PNG.',
            'logo_derecha_nuevo.max'      => 'La imagen no debe superar 2 MB.',
        ]);

        if (! $this->iglesia) {
            session()->flash('error', 'No se encontró una iglesia configurada.');
            return;
        }

        if ($this->iglesia->path_logo_derecha) {
            Storage::disk('public')->delete($this->iglesia->path_logo_derecha);
        }

        $path = $this->logo_derecha_nuevo->store('logos', 'public');
        $this->iglesia->update(['path_logo_derecha' => $path]);

        $this->logo_derecha_nuevo = null;
        $this->iglesia->refresh();

        session()->flash('success', 'Logo derecho actualizado correctamente.');
    }

    public function eliminarLogoDerecha(): void
    {
        if (! $this->iglesia || ! $this->iglesia->path_logo_derecha) {
            $this->confirmandoEliminarLogoDerecha = false;
            return;
        }

        Storage::disk('public')->delete($this->iglesia->path_logo_derecha);
        $this->iglesia->update(['path_logo_derecha' => null]);
        $this->iglesia->refresh();

        $this->confirmandoEliminarLogoDerecha = false;
        session()->flash('success', 'Logo derecho eliminado.');
    }

    public function render()
    {
        $formatos = collect(self::FORMATOS)->map(function (array $config, string $tipo): array {
            return [
                'tipo' => $tipo,
                'titulo' => $config['titulo'],
                'descripcion' => $config['descripcion'],
                'path' => $config['path'],
                'url' => $config['url'],
                'orientacion' => $config['orientacion'],
                'orientacion_default' => $config['orientacion_default'],
                'url_actual' => $this->iglesia?->{$config['url']} ?? null,
            ];
        })->all();

        return view('livewire.iglesia.certificado-config-index', compact('formatos'));
    }
}
