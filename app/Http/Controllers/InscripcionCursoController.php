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

    public function certificadoPdf(\App\Models\InscripcionCurso $inscripcion)
    {
        $inscripcion->load([
            'curso.instructor.feligres.persona',
            'curso.encargado.feligres.persona',
            'feligres.persona',
        ]);

        if (! $inscripcion->aprobado) {
            abort(403, 'La inscripción no está aprobada.');
        }

        return view('certificados.curso-pdf', compact('inscripcion'));
    }

    public function createFromInstructor(\App\Models\Instructor $instructor)
    {
        return view('instructor.inscripcion-create', [
            'instructor' => $instructor
        ]);
    }
}