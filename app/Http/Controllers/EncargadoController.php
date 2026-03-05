<?php

namespace App\Http\Controllers;

use App\Models\Encargado;

class EncargadoController extends Controller
{
    public function index()
    {
        return view('encargado.index');
    }

    public function create()
    {
        return view('encargado.create');
    }

    public function show(Encargado $encargado)
    {
        return view('encargado.show', compact('encargado'));
    }

    public function edit(Encargado $encargado)
    {
        return view('encargado.edit', compact('encargado'));
    }

    public function destroy(Encargado $encargado)
    {
        $encargado->delete();

        return redirect()->route('encargado.index')
            ->with('success', 'Encargado eliminado exitosamente.');
    }
}
