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
            'path_legacy' => 'path_certificado_bautismo',
            'path_portrait' => 'path_certificado_bautismo_portrait',
            'path_landscape' => 'path_certificado_bautismo_landscape',
            'orientacion' => 'orientacion_certificado_bautismo',
            'orientacion_fallback' => 'orientacion_certificado',
            'paper_size' => 'paper_size_certificado_bautismo',
            'paper_size_fallback' => 'paper_size_certificado',
            'orientacion_default' => 'portrait',
            'paper_size_default' => 'letter',
        ],
        'confirmacion' => [
            'titulo' => 'Confirmación',
            'descripcion' => 'Formato de certificación de confirmación.',
            'path_legacy' => 'path_certificado_confirmacion',
            'path_portrait' => 'path_certificado_confirmacion_portrait',
            'path_landscape' => 'path_certificado_confirmacion_landscape',
            'orientacion' => 'orientacion_certificado_confirmacion',
            'orientacion_default' => 'portrait',
            'paper_size' => 'paper_size_certificado_confirmacion',
            'paper_size_default' => 'letter',
        ],
        'primera_comunion' => [
            'titulo' => 'Primera Comunión',
            'descripcion' => 'Formato de certificación de primera comunión.',
            'path_legacy' => 'path_certificado_primera_comunion',
            'path_portrait' => 'path_certificado_primera_comunion_portrait',
            'path_landscape' => 'path_certificado_primera_comunion_landscape',
            'orientacion' => 'orientacion_certificado_primera_comunion',
            'orientacion_default' => 'portrait',
            'paper_size' => 'paper_size_certificado_primera_comunion',
            'paper_size_default' => 'letter',
        ],
        'matrimonio' => [
            'titulo' => 'Matrimonio',
            'descripcion' => 'Formato de constancia de matrimonio.',
            'path_legacy' => 'path_certificado_matrimonio',
            'path_portrait' => 'path_certificado_matrimonio_portrait',
            'path_landscape' => 'path_certificado_matrimonio_landscape',
            'orientacion' => 'orientacion_certificado_matrimonio',
            'orientacion_default' => 'portrait',
            'paper_size' => 'paper_size_certificado_matrimonio',
            'paper_size_default' => 'letter',
        ],
        'curso' => [
            'titulo' => 'Certificado de Curso',
            'descripcion' => 'Formato de certificado para cursos aprobados.',
            'path_legacy' => 'path_certificado_curso',
            'path_portrait' => 'path_certificado_curso_portrait',
            'path_landscape' => 'path_certificado_curso_landscape',
            'orientacion' => 'orientacion_certificado_curso',
            'orientacion_default' => 'landscape',
            'paper_size' => 'paper_size_certificado_curso',
            'paper_size_default' => 'letter',
        ],
    ];

    private const PAPER_SIZES = [
        'letter' => 'Carta (Letter)',
        'legal' => 'Oficio (Legal)',
        'a4' => 'A4',
        'folio' => 'Folio',
    ];

    public ?TenantIglesia $iglesia = null;
    public array $formatos_nuevos = [];
    public array $confirmandoEliminar = [];
    public array $orientaciones = [];
    public array $paperSizes = [];

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

            $paperSize = $this->iglesia?->{$config['paper_size']} ?? null;

            if (! $paperSize && isset($config['paper_size_fallback'])) {
                $paperSize = $this->iglesia?->{$config['paper_size_fallback']} ?? null;
            }

            if (! array_key_exists((string) $paperSize, self::PAPER_SIZES)) {
                $paperSize = $config['paper_size_default'];
            }

            $this->orientaciones[$tipo] = $orientacion ?: $config['orientacion_default'];
            $this->paperSizes[$tipo] = $paperSize;
            $this->confirmandoEliminar[$tipo] = [
                'portrait' => false,
                'landscape' => false,
            ];
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

    public function guardarTamanoTipo(string $tipo): void
    {
        if (! isset(self::FORMATOS[$tipo])) {
            return;
        }

        $this->validate([
            "paperSizes.$tipo" => ['required', 'in:' . implode(',', array_keys(self::PAPER_SIZES))],
        ]);

        if (! $this->iglesia) {
            session()->flash('error', 'No se encontró una iglesia configurada.');
            return;
        }

        $columnaPaperSize = self::FORMATOS[$tipo]['paper_size'];
        $updates = [
            $columnaPaperSize => $this->paperSizes[$tipo],
        ];

        if ($tipo === 'bautismo') {
            // Compatibilidad con configuración anterior.
            $updates['paper_size_certificado'] = $this->paperSizes[$tipo];
        }

        $this->iglesia->update($updates);
        $this->iglesia->refresh();

        session()->flash('success', 'Tamaño de hoja de ' . strtolower(self::FORMATOS[$tipo]['titulo']) . ' actualizado correctamente.');
    }

    public function subirFormato(string $tipo, string $orientacion): void
    {
        if (! isset(self::FORMATOS[$tipo])) {
            return;
        }

        if (! in_array($orientacion, ['portrait', 'landscape'], true)) {
            return;
        }

        $campoArchivo = "formatos_nuevos.$tipo.$orientacion";

        $this->validate([
            $campoArchivo => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ], [
            "$campoArchivo.required" => 'Seleccione una imagen para el formato.',
            "$campoArchivo.image"    => 'El archivo debe ser una imagen.',
            "$campoArchivo.mimes"    => 'Solo se aceptan imágenes JPG o PNG.',
            "$campoArchivo.max"      => 'La imagen no debe superar 5 MB.',
        ]);

        if (! $this->iglesia) {
            session()->flash('error', 'No se encontró una iglesia configurada.');
            return;
        }

        $columnaPath = $this->columnaPathPorOrientacion($tipo, $orientacion);
        $archivoActual = $this->iglesia->{$columnaPath};

        if ($archivoActual) {
            Storage::disk('public')->delete($archivoActual);
        }

        $archivo = data_get($this->formatos_nuevos, "$tipo.$orientacion");
        $path = $archivo->store('certificados', 'public');

        $updates = [
            $columnaPath => $path,
        ];

        if ($tipo === 'bautismo') {
            // Compatibilidad con configuración anterior.
            $updates['orientacion_certificado'] = $this->orientaciones[$tipo] ?? 'portrait';
        }

        $this->iglesia->update($updates);

        $this->formatos_nuevos[$tipo][$orientacion] = null;
        $this->iglesia->refresh();

        session()->flash('success', 'Formato ' . $this->etiquetaOrientacion($orientacion) . ' de ' . strtolower(self::FORMATOS[$tipo]['titulo']) . ' actualizado correctamente.');
    }

    public function eliminarFormato(string $tipo, string $orientacion): void
    {
        if (! isset(self::FORMATOS[$tipo])) {
            return;
        }

        if (! in_array($orientacion, ['portrait', 'landscape'], true)) {
            return;
        }

        $columnaPath = $this->columnaPathPorOrientacion($tipo, $orientacion);
        $columnaLegacy = self::FORMATOS[$tipo]['path_legacy'] ?? null;

        // If this orientation is using the legacy fallback preview, delete that file too.
        $columnaObjetivo = $columnaPath;
        $archivoActual = $this->iglesia?->{$columnaPath};

        if (! filled($archivoActual) && $columnaLegacy) {
            $archivoLegacy = $this->iglesia?->{$columnaLegacy};

            if (filled($archivoLegacy)) {
                $columnaObjetivo = $columnaLegacy;
                $archivoActual = $archivoLegacy;
            }
        }

        if (! $this->iglesia || ! $archivoActual) {
            $this->confirmandoEliminar[$tipo][$orientacion] = false;
            return;
        }

        Storage::disk('public')->delete($archivoActual);
        $this->iglesia->update([$columnaObjetivo => null]);
        $this->iglesia->refresh();

        $this->confirmandoEliminar[$tipo][$orientacion] = false;
        session()->flash('success', 'Formato ' . $this->etiquetaOrientacion($orientacion) . ' de ' . strtolower(self::FORMATOS[$tipo]['titulo']) . ' eliminado.');
    }

    private function columnaPathPorOrientacion(string $tipo, string $orientacion): string
    {
        $sufijo = $orientacion === 'landscape' ? 'path_landscape' : 'path_portrait';

        return self::FORMATOS[$tipo][$sufijo];
    }

    private function etiquetaOrientacion(string $orientacion): string
    {
        return $orientacion === 'landscape' ? 'horizontal' : 'vertical';
    }

    private function urlDesdePath(?string $path): ?string
    {
        if (! filled($path)) {
            return null;
        }

        return asset('storage/' . ltrim((string) $path, '/'));
    }

    private function urlFormatoActual(array $config, string $orientacion): ?string
    {
        $columnaPath = $this->columnaPathPorOrientacion($config['tipo'], $orientacion);
        $pathOrientado = $this->iglesia?->{$columnaPath};
        if (filled($pathOrientado)) {
            return $this->urlDesdePath($pathOrientado);
        }

        $pathLegacy = $this->iglesia?->{$config['path_legacy']};

        return $this->urlDesdePath($pathLegacy);
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
            $config['tipo'] = $tipo;

            return [
                'tipo' => $tipo,
                'titulo' => $config['titulo'],
                'descripcion' => $config['descripcion'],
                'orientacion' => $config['orientacion'],
                'orientacion_default' => $config['orientacion_default'],
                'paper_size' => $config['paper_size'],
                'paper_size_default' => $config['paper_size_default'],
                'url_portrait' => $this->urlFormatoActual($config, 'portrait'),
                'url_landscape' => $this->urlFormatoActual($config, 'landscape'),
            ];
        })->all();

        $paperSizeOptions = self::PAPER_SIZES;

        return view('livewire.iglesia.certificado-config-index', compact('formatos', 'paperSizeOptions'));
    }
}
