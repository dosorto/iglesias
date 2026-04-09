<?php

namespace App\Livewire\Iglesia;

use App\Models\Bautismo;
use App\Models\Confirmacion;
use App\Models\DocumentoGenerado;
use App\Models\Matrimonio;
use App\Models\PrimeraComunion;
use App\Models\TenantIglesia;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentosGeneradosIndex extends Component
{
    use WithPagination;

    public string $buscar = '';
    public string $filtro_tipo = '';

    protected $queryString = [
        'buscar' => ['except' => ''],
        'filtro_tipo' => ['except' => ''],
    ];

    public function updatingBuscar(): void
    {
        $this->resetPage();
    }

    public function updatingFiltroTipo(): void
    {
        $this->resetPage();
    }

    public function getTiposDisponiblesProperty()
    {
        $iglesiaId = TenantIglesia::currentId();

        return DocumentoGenerado::query()
            ->when($iglesiaId, fn ($query) => $query->where('iglesia_id', $iglesiaId))
            ->select('tipo_documento')
            ->distinct()
            ->orderBy('tipo_documento')
            ->pluck('tipo_documento');
    }

    public function nombreFuente(string $fuenteTipo): string
    {
        return match ($fuenteTipo) {
            Bautismo::class => 'Bautismo',
            Confirmacion::class => 'Confirmación',
            PrimeraComunion::class => 'Primera Comunión',
            Matrimonio::class => 'Matrimonio',
            default => 'Otro',
        };
    }

    public function rutaRegistro(string $fuenteTipo, int $fuenteId): ?string
    {
        if (! $this->registroExiste($fuenteTipo, $fuenteId)) {
            return null;
        }

        return match ($fuenteTipo) {
            Bautismo::class => route('bautismo.show', $fuenteId),
            Confirmacion::class => route('confirmacion.show', $fuenteId),
            PrimeraComunion::class => route('primera-comunion.show', $fuenteId),
            Matrimonio::class => route('matrimonio.show', $fuenteId),
            default => null,
        };
    }

    public function registroExiste(string $fuenteTipo, int $fuenteId): bool
    {
        if (! class_exists($fuenteTipo) || ! is_subclass_of($fuenteTipo, Model::class)) {
            return false;
        }

        // Match route-model binding behavior for show routes: soft-deleted rows are not accessible.
        return $fuenteTipo::query()->whereKey($fuenteId)->exists();
    }

    public function render()
    {
        $iglesiaId = TenantIglesia::currentId();

        $documentos = DocumentoGenerado::query()
            ->when($iglesiaId, fn ($query) => $query->where('iglesia_id', $iglesiaId))
            ->when($this->filtro_tipo !== '', function ($query): void {
                $query->where('tipo_documento', $this->filtro_tipo);
            })
            ->when($this->buscar !== '', function ($query): void {
                $texto = '%' . $this->buscar . '%';
                $query->where(function ($inner) use ($texto): void {
                    $inner->where('nombre_archivo', 'like', $texto)
                        ->orWhere('tipo_documento', 'like', $texto)
                        ->orWhere('codigo_verificacion', 'like', $texto)
                        ->orWhere('fuente_tipo', 'like', $texto)
                        ->orWhere('fuente_id', 'like', $texto);
                });
            })
            ->orderByDesc('fecha_emision')
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.iglesia.documentos-generados-index', [
            'documentos' => $documentos,
        ]);
    }
}
