<?php

namespace App\Http\Controllers;

use App\Models\InscripcionCurso;

class InscripcionCursoController extends Controller
{
    public function index()
    {
        return view('inscripcion-curso.index');
    }

    public function create()
    {
        return view('inscripcion-curso.create');
    }

    public function show(InscripcionCurso $inscripcionCurso)
    {
        return view('inscripcion-curso.show', compact('inscripcionCurso'));
    }

    public function edit(InscripcionCurso $inscripcionCurso)
    {
        return view('inscripcion-curso.edit', compact('inscripcionCurso'));
    }

    public function destroy(InscripcionCurso $inscripcionCurso)
    {
        $inscripcionCurso->delete();

        return redirect()->route('inscripcion-curso.index')
            ->with('success','Inscripción eliminada correctamente.');
    }
}