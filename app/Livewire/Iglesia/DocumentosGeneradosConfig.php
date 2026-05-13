<?php

namespace App\Livewire\Iglesia;

use App\Models\TenantIglesia;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentosGeneradosConfig extends Component
{
    use WithFileUploads;

    public ?TenantIglesia $iglesia = null;

    public string $tipo_documento = 'certificado';
    public string $nombre_documento = '';
    public string $descripcion_documento = '';

    public string $payload_json = '{\n  "firma": "",\n  "logos": {\n    "izquierdo": null,\n    "derecho": null\n  }\n}';

    public string $firma_titulo = '';
    public string $firma_nombre = '';
    public ?string $firma_path = null;

    public ?string $logo_izquierdo_path = null;
    public ?string $logo_derecho_path = null;

    public $firma_nueva = null;
    public $logo_izquierdo_nuevo = null;
    public $logo_derecho_nuevo = null;

    public function mount(): void
    {
        $this->iglesia = TenantIglesia::current();

        if (! $this->iglesia) {
            return;
        }

        $config = $this->loadConfig();
        if (! $config) {
            return;
        }

        $this->tipo_documento = $config['datos_documento']['tipo'] ?? 'certificado';
        $this->nombre_documento = $config['datos_documento']['nombre'] ?? '';
        $this->descripcion_documento = $config['datos_documento']['descripcion'] ?? '';
        $this->payload_json = json_encode($config['payload'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '{}';

        $this->firma_titulo = $config['firma']['titulo'] ?? '';
        $this->firma_nombre = $config['firma']['nombre'] ?? '';
        $this->firma_path = $config['firma']['path'] ?? null;

        $this->logo_izquierdo_path = $config['logos']['izquierdo_path'] ?? null;
        $this->logo_derecho_path = $config['logos']['derecho_path'] ?? null;
    }

    protected function rules(): array
    {
        return [
            'tipo_documento' => ['required', 'in:certificado,constancia'],
            'nombre_documento' => ['required', 'string', 'max:150'],
            'descripcion_documento' => ['nullable', 'string', 'max:500'],
            'payload_json' => ['required', 'string'],
            'firma_titulo' => ['nullable', 'string', 'max:120'],
            'firma_nombre' => ['nullable', 'string', 'max:150'],
            'firma_nueva' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'logo_izquierdo_nuevo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
            'logo_derecho_nuevo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
        ];
    }

    protected function messages(): array
    {
        return [
            'nombre_documento.required' => 'El nombre del documento es obligatorio.',
            'payload_json.required' => 'Debe ingresar el payload en formato JSON.',
            'firma_nueva.image' => 'La firma debe ser una imagen válida.',
            'logo_izquierdo_nuevo.image' => 'El logo izquierdo debe ser una imagen válida.',
            'logo_derecho_nuevo.image' => 'El logo derecho debe ser una imagen válida.',
        ];
    }

    public function guardar(): void
    {
        $this->validate();

        $payload = json_decode($this->payload_json, true);
        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($payload)) {
            $this->addError('payload_json', 'El payload debe ser un JSON válido (objeto o arreglo).');
            return;
        }

        if (! $this->iglesia) {
            session()->flash('error', 'No se encontró una iglesia activa para guardar la configuración.');
            return;
        }

        if ($this->firma_nueva) {
            if ($this->firma_path) {
                Storage::disk('public')->delete($this->firma_path);
            }
            $this->firma_path = $this->firma_nueva->store('documentos-generados/firmas', 'public');
            $this->firma_nueva = null;
        }

        if ($this->logo_izquierdo_nuevo) {
            if ($this->logo_izquierdo_path) {
                Storage::disk('public')->delete($this->logo_izquierdo_path);
            }
            $this->logo_izquierdo_path = $this->logo_izquierdo_nuevo->store('documentos-generados/logos', 'public');
            $this->logo_izquierdo_nuevo = null;
        }

        if ($this->logo_derecho_nuevo) {
            if ($this->logo_derecho_path) {
                Storage::disk('public')->delete($this->logo_derecho_path);
            }
            $this->logo_derecho_path = $this->logo_derecho_nuevo->store('documentos-generados/logos', 'public');
            $this->logo_derecho_nuevo = null;
        }

        $config = [
            'meta' => [
                'version' => 1,
                'iglesia_id' => $this->iglesia->id,
                'guardado_en' => now()->toIso8601String(),
            ],
            'datos_documento' => [
                'tipo' => $this->tipo_documento,
                'nombre' => $this->nombre_documento,
                'descripcion' => $this->descripcion_documento ?: null,
            ],
            'payload' => $payload,
            'firma' => [
                'titulo' => $this->firma_titulo ?: null,
                'nombre' => $this->firma_nombre ?: null,
                'path' => $this->firma_path,
                'url' => $this->firma_path ? asset('storage/' . ltrim($this->firma_path, '/')) : null,
            ],
            'logos' => [
                'izquierdo_path' => $this->logo_izquierdo_path,
                'derecho_path' => $this->logo_derecho_path,
                'izquierdo_url' => $this->logo_izquierdo_path ? asset('storage/' . ltrim($this->logo_izquierdo_path, '/')) : null,
                'derecho_url' => $this->logo_derecho_path ? asset('storage/' . ltrim($this->logo_derecho_path, '/')) : null,
            ],
        ];

        Storage::disk('local')->put($this->jsonPath(), json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        session()->flash('success', 'Configuración de documentos generados guardada en JSON correctamente.');
    }

    public function eliminarFirma(): void
    {
        if ($this->firma_path) {
            Storage::disk('public')->delete($this->firma_path);
            $this->firma_path = null;
        }
    }

    public function eliminarLogoIzquierdo(): void
    {
        if ($this->logo_izquierdo_path) {
            Storage::disk('public')->delete($this->logo_izquierdo_path);
            $this->logo_izquierdo_path = null;
        }
    }

    public function eliminarLogoDerecho(): void
    {
        if ($this->logo_derecho_path) {
            Storage::disk('public')->delete($this->logo_derecho_path);
            $this->logo_derecho_path = null;
        }
    }

    public function getRutaJsonProperty(): string
    {
        return storage_path('app/' . $this->jsonPath());
    }

    private function loadConfig(): ?array
    {
        if (! Storage::disk('local')->exists($this->jsonPath())) {
            return null;
        }

        $raw = Storage::disk('local')->get($this->jsonPath());
        $parsed = json_decode($raw, true);

        return is_array($parsed) ? $parsed : null;
    }

    private function jsonPath(): string
    {
        $iglesiaId = $this->iglesia?->id ?? 0;

        return 'documentos-generados/iglesia-' . $iglesiaId . '.json';
    }

    public function render()
    {
        return view('livewire.iglesia.documentos-generados-config');
    }
}
