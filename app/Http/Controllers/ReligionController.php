<?php

namespace App\Http\Controllers;

use App\Models\Religion;
use App\Http\Requests\StoreReligionRequest;
use App\Http\Requests\UpdateReligionRequest;
use Illuminate\Http\Request;

class ReligionController extends Controller
{
    /**
     * Muestra la lista de religiones.
     */
    public function index()
    {
        return view('religion.index');
    }

    /**
     * Muestra el formulario para crear una nueva religión.
     */
    public function create()
    {
        return view('religion.create');
    }

    /**
     * Almacena una religión recién creada en la base de datos.
     */
    public function store(StoreReligionRequest $request)
    {
        Religion::create(['religion' => $request->religion]);

        return redirect()->route('religion.index')
            ->with('success', 'Religión registrada exitosamente.');
    }

    /**
     * Muestra una religión específica (opcional, si tienes vista de detalles).
     */
    public function show(Religion $religion) // Laravel suele pluralizar a 'religiones', pero el binding puede ser 'religione'
    {
        return view('religion.show', compact('religion'));
    }

    /**
     * Muestra el formulario para editar una religión existente.
     */
    public function edit(Religion $religion)
    {
        return view('religion.edit', compact('religion'));
    }

    /**
     * Actualiza la religión en la base de datos.
     */
    public function update(UpdateReligionRequest $request, Religion $religion)
    {
        $religion->update(['religion' =>$request->religion]);

        return redirect()->route('religion.index')
            ->with('success', 'Religión actualizada exitosamente.');
    }

    /**
     * Elimina la religión de la base de datos.
     */
    public function destroy(Religion $religion)
    {
        $religion->delete();

        return redirect()->route('religion.index')
            ->with('success', 'Religión eliminada exitosamente.');
    }
}