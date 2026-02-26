<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEncargadoRequest;
use App\Http\Requests\UpdateEncargadoRequest;
use App\Models\Encargado;
use App\Models\Feligres;

class EncargadoController extends Controller
{
    public function index()
    {
        return view('encargado.index');
    }

    public function create()
    {
        // Only feligreses without an encargado yet
        $feligres = Feligres::with('persona')
            ->whereDoesntHave('encargado')
            ->orderBy('id')
            ->get();

        return view('encargado.create', compact('feligres'));
    }

    public function store(StoreEncargadoRequest $request)
    {
        Encargado::create($request->validated());

        return redirect()->route('encargado.index')
            ->with('success', 'Encargado registrado exitosamente.');
    }

    public function show(Encargado $encargado)
    {
        $encargado->load(['feligres.persona', 'feligres.iglesia', 'auditLogs']);

        return view('encargado.show', compact('encargado'));
    }

    public function edit(Encargado $encargado)
    {
        // All feligreses that either have no encargado or are the current one
        $feligres = Feligres::with('persona')
            ->where(function ($q) use ($encargado) {
                $q->whereDoesntHave('encargado')
                    ->orWhere('id', $encargado->id_feligres);
            })
            ->orderBy('id')
            ->get();

        return view('encargado.edit', compact('encargado', 'feligres'));
    }

    public function update(UpdateEncargadoRequest $request, Encargado $encargado)
    {
        $encargado->update($request->validated());

        return redirect()->route('encargado.index')
            ->with('success', 'Encargado actualizado exitosamente.');
    }

    public function destroy(Encargado $encargado)
    {
        $encargado->delete();

        return redirect()->route('encargado.index')
            ->with('success', 'Encargado eliminado exitosamente.');
    }
}
