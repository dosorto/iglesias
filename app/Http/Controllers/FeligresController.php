<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeligresRequest;
use App\Http\Requests\UpdateFeligresRequest;
use App\Models\Encargado;
use App\Models\Feligres;
use App\Models\Iglesias;
use App\Models\Persona;
use App\Models\TenantIglesia;
use Barryvdh\DomPDF\Facade\Pdf;

class FeligresController extends Controller
{
    public function index()
    {
        return view('feligres.index');
    }

    public function create()
    {
        return view('feligres.create');
    }

    public function store(StoreFeligresRequest $request)
    {
        Feligres::create($request->validated());

        return redirect()->route('feligres.index')
            ->with('success', 'Feligrés registrado exitosamente.');
    }

    public function show(Feligres $feligre)
    {
        $feligre->load([
            'persona',
            'iglesia',
            'encargado',
            'auditLogs',
            'inscripcionesCurso.curso',
            'bautismos',
            'confirmaciones',
            'primerasComuniones',
            'matrimoniosEsposo',
            'matrimoniosEsposa',
        ]);

        $sacramentos = collect()
            ->merge($feligre->bautismos->map(function ($item) {
                return [
                    'tipo' => 'Bautismo',
                    'fecha' => $item->fecha_bautismo,
                    'route' => 'bautismo.show',
                    'permission' => 'bautismo.view',
                    'model' => $item,
                ];
            }))
            ->merge($feligre->confirmaciones->map(function ($item) {
                return [
                    'tipo' => 'Confirmación',
                    'fecha' => $item->fecha_confirmacion,
                    'route' => 'confirmacion.show',
                    'permission' => 'confirmacion.view',
                    'model' => $item,
                ];
            }))
            ->merge($feligre->primerasComuniones->map(function ($item) {
                return [
                    'tipo' => 'Primera Comunión',
                    'fecha' => $item->fecha_primera_comunion,
                    'route' => 'primera-comunion.show',
                    'permission' => 'primera-comunion.view',
                    'model' => $item,
                ];
            }))
            ->merge(
                $feligre->matrimoniosEsposo
                    ->concat($feligre->matrimoniosEsposa)
                    ->unique('id')
                    ->map(function ($item) {
                        return [
                            'tipo' => 'Matrimonio',
                            'fecha' => $item->fecha_matrimonio,
                            'route' => 'matrimonio.show',
                            'permission' => 'matrimonio.view',
                            'model' => $item,
                        ];
                    })
            )
            ->sortByDesc('fecha')
            ->values();

        $cursos = $feligre->inscripcionesCurso
            ->sortByDesc('fecha_inscripcion')
            ->values();

        return view('feligres.show', compact('feligre', 'sacramentos', 'cursos'));
    }

    public function edit(Feligres $feligre)
    {
        $iglesias = Iglesias::where('estado', 'Activo')->orderBy('nombre')->get();

        return view('feligres.edit', compact('feligre', 'iglesias'));
    }

    public function update(UpdateFeligresRequest $request, Feligres $feligre)
    {
        $feligre->update($request->validated());

        return redirect()->route('feligres.index')
            ->with('success', 'Feligrés actualizado exitosamente.');
    }

    public function destroy(Feligres $feligre)
    {
        $feligre->delete();

        return redirect()->route('feligres.index')
            ->with('success', 'Feligrés eliminado exitosamente.');
    }

    public function constanciaPdf(Feligres $feligre)
    {
        $feligre->load(['persona', 'iglesia', 'bautismos']);

        $iglesiaConfig = TenantIglesia::current();
        $parrocoConfig = trim((string) ($iglesiaConfig?->parroco_nombre ?? ''));

        $parrocoNombre = $parrocoConfig;
        $firmaParrocoPath = null;

        if ($parrocoConfig !== '') {
            $primerNombreParroco = explode(' ', $parrocoConfig)[0] ?? '';
            // Quitar digitos/sufijos numericos: "Vicente1" -> "Vicente"
            $primerNombreParroco = preg_replace('/\d+$/', '', $primerNombreParroco);

            $encargadoParroco = Encargado::query()
                ->with('feligres.persona')
                ->whereHas('feligres', fn ($q) => $q->where('id_iglesia', $feligre->id_iglesia))
                ->whereHas('feligres.persona', fn ($q) => $q->where('primer_nombre', 'like', $primerNombreParroco . '%'))
                ->first();

            if ($encargadoParroco) {
                $nombreCompleto = trim((string) ($encargadoParroco->feligres?->persona?->nombre_completo ?? ''));
                if ($nombreCompleto !== '') {
                    $parrocoNombre = $nombreCompleto;
                }

                if ($encargadoParroco->path_firma_principal) {
                    $firmaPathRaw = $encargadoParroco->path_firma_principal;
                    $normalized = ltrim(trim((string) parse_url($firmaPathRaw, PHP_URL_PATH) ?: $firmaPathRaw), '/\\');
                    $candidate = str_starts_with($normalized, 'storage/')
                        ? public_path($normalized)
                        : public_path('storage/' . $normalized);
                    $firmaParrocoPath = is_file($candidate) ? $candidate : null;
                }
            }
        }

        $slug = fn(?string $s): string => preg_replace('/[^a-z0-9]/', '', mb_strtolower(
            str_replace(['á','é','í','ó','ú','ü','ñ','à','â','ã','ê','î','ô','û'],
                        ['a','e','i','o','u','u','n','a','a','a','e','i','o','u'],
                        (string) $s), 'UTF-8'));

        $nombre = $slug($feligre->persona?->primer_nombre ?? '');
        $apellido = $slug($feligre->persona?->primer_apellido ?? '') ?: 'sinapellido';
        $titular = $nombre !== '' ? $nombre . '-' . $apellido : $apellido;
        $nombreArchivo = sprintf('constancia-feligresia-%s-%s.pdf', $titular, now()->format('Y-m-d'));

        $html = view('feligres.constancia-pdf', compact('feligre', 'iglesiaConfig', 'parrocoNombre', 'firmaParrocoPath'))->render();

        $pdf = Pdf::loadHTML($html)->setPaper('letter', 'portrait');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
