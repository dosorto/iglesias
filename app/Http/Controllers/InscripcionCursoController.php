<?php

namespace App\Http\Controllers;

use App\Models\InscripcionCurso;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TenantIglesia;


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



 
    public function certificadoPdf(InscripcionCurso $inscripcion)
    {
        $inscripcion->load([
            'curso.instructor.feligres.persona',
            'curso.encargado.feligres.persona',
            'feligres.persona',
        ]);

        $iglesiaConfig = TenantIglesia::current();
        $orientation = $iglesiaConfig?->orientacion_certificado === 'landscape' ? 'landscape' : 'portrait';

        $pdf = Pdf::loadView('certificados.curso-pdf', compact('inscripcion', 'iglesiaConfig'))
            ->setPaper('letter', $orientation);

        $nombreArchivo = 'certificado-curso-' . $inscripcion->id . '.pdf';

        return $pdf->stream($nombreArchivo);
    }

    public function matricula(\App\Models\InscripcionCurso $inscripcionCurso)
    {
        return view('curso.matricula', [
            'inscripcionId' => $inscripcionCurso->id,
        ]);
    }

    public function createFromInstructor(\App\Models\Instructor $instructor)
    {
        return view('instructor.inscripcion-create', [
            'instructor' => $instructor
        ]);
    }
}