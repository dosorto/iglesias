<?php

namespace App\Livewire\Iglesia;

use App\Models\Bautismo;
use App\Models\Confirmacion;
use App\Models\DocumentoGenerado;
use App\Models\InscripcionCurso;
use App\Models\Matrimonio;
use App\Models\PrimeraComunion;
use App\Models\TenantIglesia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentosGeneradosIndex extends Component
{
    use WithPagination;

    public string $buscar = '';
    public string $filtro_tipo = '';
    private array $personaCache = [];

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

    public function eliminarPermanentemente(int $documentoId, string $confirmacion = ''): void
    {
        $usuarioId = Auth::id();
        $esRoot = $usuarioId
            ? DB::table('model_has_roles')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('model_has_roles.model_type', 'App\\Models\\User')
                ->where('model_has_roles.model_id', $usuarioId)
                ->where('roles.name', 'root')
                ->exists()
            : false;

        if (! $esRoot) {
            abort(403, 'No autorizado.');
        }

        if (mb_strtoupper(trim($confirmacion)) !== 'ELIMINAR') {
            session()->flash('error', 'Confirmación inválida. Debes escribir ELIMINAR para borrar permanentemente.');
            return;
        }

        $documento = DocumentoGenerado::query()->findOrFail($documentoId);
        $iglesiaId = TenantIglesia::currentId();

        if ($iglesiaId && (int) $documento->iglesia_id !== (int) $iglesiaId) {
            abort(403, 'No autorizado para esta iglesia.');
        }

        $documento->delete();
        session()->flash('success', 'Documento eliminado permanentemente.');
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
            InscripcionCurso::class => 'Curso',
            default => 'Otro',
        };
    }

    public function personaRelacionada(DocumentoGenerado $documento): string
    {
        $cacheKey = $documento->fuente_tipo . '|' . $documento->fuente_id . '|' . $documento->id;
        if (array_key_exists($cacheKey, $this->personaCache)) {
            return $this->personaCache[$cacheKey];
        }

        $nombre = match ($documento->fuente_tipo) {
            Bautismo::class => Bautismo::query()
                ->with('bautizado.persona')
                ->find($documento->fuente_id)?->bautizado?->persona?->nombre_completo,

            Confirmacion::class => Confirmacion::query()
                ->with('feligres.persona')
                ->find($documento->fuente_id)?->feligres?->persona?->nombre_completo,

            PrimeraComunion::class => PrimeraComunion::query()
                ->with('feligres.persona')
                ->find($documento->fuente_id)?->feligres?->persona?->nombre_completo,

            InscripcionCurso::class => InscripcionCurso::query()
                ->with('feligres.persona')
                ->find($documento->fuente_id)?->feligres?->persona?->nombre_completo,

            Matrimonio::class => $this->nombreMatrimonio($documento->fuente_id),

            default => null,
        };

        if (! $nombre) {
            $nombre = $this->personaDesdePayload($documento);
        }

        return $this->personaCache[$cacheKey] = $nombre ?: 'Sin persona relacionada';
    }

    private function nombreMatrimonio(int $fuenteId): ?string
    {
        $registro = Matrimonio::query()
            ->with(['esposo.persona', 'esposa.persona'])
            ->find($fuenteId);

        if (! $registro) {
            return null;
        }

        $esposo = $registro->esposo?->persona?->nombre_completo;
        $esposa = $registro->esposa?->persona?->nombre_completo;

        if ($esposo && $esposa) {
            return $esposo . ' y ' . $esposa;
        }

        return $esposo ?: $esposa;
    }

    private function personaDesdePayload(DocumentoGenerado $documento): ?string
    {
        $payload = is_array($documento->payload) ? $documento->payload : [];
        $registro = is_array($payload['registro'] ?? null) ? $payload['registro'] : [];

        return match ($documento->fuente_tipo) {
            Bautismo::class => data_get($registro, 'bautizado.persona.nombre_completo'),
            Confirmacion::class => data_get($registro, 'feligres.persona.nombre_completo'),
            PrimeraComunion::class => data_get($registro, 'feligres.persona.nombre_completo'),
            InscripcionCurso::class => data_get($registro, 'feligres.persona.nombre_completo'),
            Matrimonio::class => collect([
                data_get($registro, 'esposo.persona.nombre_completo'),
                data_get($registro, 'esposa.persona.nombre_completo'),
            ])->filter()->implode(' y '),
            default => null,
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
            InscripcionCurso::class => route('inscripcion-curso.show', $fuenteId),
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
