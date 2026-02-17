<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIglesiaRequest;
use App\Http\Requests\UpdateIglesiaRequest;
use App\Models\Iglesias;
use Illuminate\Http\Request;

class IglesiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('iglesias.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('iglesias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIglesiaRequest $request)
    {
        Iglesias::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'parroco_nombre' => $request->parroco_nombre,
            'estado' => $request->estado,
        ]);

        return redirect()->route('iglesias.index')
            ->with('sucess', 'Iglesia Creada Exitosamente. ');
    }

    /**
     * Display the specified resource.
     */
    public function show(Iglesias $iglesia)
    {
        return view('Iglesias.show', compact('iglesia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Iglesias $iglesia)
    {
        return view('iglesias.edit', compact('iglesia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIglesiaRequest $request, Iglesias $iglesia)
    {
        $iglesia->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'parroco_nombre' => $request->parroco_nombre,
            'estado' => $request->estado,
        ]);

        return redirect()->route('iglesias.index')
            ->with('sucess', 'Iglesia Actualizada Exitosamente. ');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Iglesias $iglesia)
    {
        $iglesia->delete();

        return redirect()->route('iglesias.index')
            ->with('success', 'iglesia eliminada exitosamente.');
    }
}
