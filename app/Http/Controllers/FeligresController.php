<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeligresRequest;
use App\Http\Requests\UpdateFeligresRequest;
use App\Models\Feligres;
use App\Models\Iglesias;
use App\Models\Persona;

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
}
