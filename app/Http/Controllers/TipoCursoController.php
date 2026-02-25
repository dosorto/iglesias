<?php

namespace App\Http\Controllers;
use App\Models\TipoCurso;
use App\Http\Requests\StoreTipoCursoRequest;
use App\Http\Requests\UpdateTipoCursoRequest;

use Illuminate\Http\Request;

class TipoCursoController extends Controller
{
 /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tipocurso.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tipocurso.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoCursoRequest $request)
    {
        TipoCurso::create($request->validated());

        return redirect()->route('tipocurso.index')
            ->with('success', 'Tipo de curso creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoCurso $tipocurso)
    {
        return view('tipocurso.show', compact('tipocurso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoCurso $tipocurso)
    {
        return view('tipocurso.edit', compact('tipocurso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoCursoRequest $request, TipoCurso $tipocurso)
    {
        $tipocurso->update($request->validated());

        return redirect()->route('tipocurso.index')
            ->with('success', 'Tipo de curso actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoCurso $tipocurso)
    {
        $tipocurso->delete();

        return redirect()->route('tipocurso.index')
            ->with('success', 'Tipo de curso eliminado exitosamente.');
    }

}
