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
        $personas = Persona::orderBy('primer_apellido')->orderBy('primer_nombre')->get();
        $iglesias = Iglesias::where('estado', 'Activo')->orderBy('nombre')->get();

        return view('feligres.create', compact('personas', 'iglesias'));
    }

    public function store(StoreFeligresRequest $request)
    {
        Feligres::create($request->validated());

        return redirect()->route('feligres.index')
            ->with('success', 'Feligrés registrado exitosamente.');
    }

    public function show(Feligres $feligre)
    {
        $feligre->load(['persona', 'iglesia', 'encargado', 'auditLogs']);

        return view('feligres.show', compact('feligre'));
    }

    public function edit(Feligres $feligre)
    {
        $personas = Persona::orderBy('primer_apellido')->orderBy('primer_nombre')->get();
        $iglesias = Iglesias::where('estado', 'Activo')->orderBy('nombre')->get();

        return view('feligres.edit', compact('feligre', 'personas', 'iglesias'));
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
