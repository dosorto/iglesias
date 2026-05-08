<?php

namespace App\Http\Controllers;

use App\Models\InscripcionCurso;
use App\Models\TenantIglesia;
use App\Services\DocumentosGeneradosService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
        $tipoDocumento = 'curso_certificado';
        $nombreArchivo = 'certificado-curso-' . $inscripcion->id . '.pdf';
        $servicioDocumentos = app(DocumentosGeneradosService::class);

        $inscripcion->load([
            'curso.instructor.feligres.persona',
            'curso.encargado.feligres.persona',
            'feligres.persona',
        ]);

        $iglesiaConfig = TenantIglesia::current();
        $iglesiaId = (int) ($inscripcion->curso?->iglesia_id ?: TenantIglesia::currentId());
        $orientacionCurso = (string) ($iglesiaConfig?->orientacion_certificado_curso
            ?? $iglesiaConfig?->orientacion_certificado
            ?? 'landscape');
        $orientation = $orientacionCurso === 'portrait' ? 'portrait' : 'landscape';
        $paperSizeCurso = (string) ($iglesiaConfig?->paper_size_certificado_curso
            ?? $iglesiaConfig?->paper_size_certificado
            ?? 'letter');
        $paperSizeCurso = in_array($paperSizeCurso, ['letter', 'legal', 'a4', 'folio'], true)
            ? $paperSizeCurso
            : 'letter';
        $pathFormatoCurso = (string) (
            ($orientation === 'landscape'
                ? $iglesiaConfig?->path_certificado_curso_landscape
                : $iglesiaConfig?->path_certificado_curso_portrait)
            ?: $iglesiaConfig?->path_certificado_curso
            ?: $iglesiaConfig?->path_certificado_bautismo
            ?: ''
        );

        $plantillaCertificadoPath = $pathFormatoCurso;
        $html = view('certificados.curso-pdf', compact('inscripcion', 'iglesiaConfig', 'plantillaCertificadoPath'))->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper($paperSizeCurso, $orientation);

        $pdfBinario = $pdf->output();

        $servicioDocumentos->guardarDocumento(
            $tipoDocumento,
            $inscripcion,
            $iglesiaId,
            $nombreArchivo,
            [
                'emitido_en' => now()->toIso8601String(),
                'view' => 'certificados.curso-pdf',
                'paper_size' => $paperSizeCurso,
                'orientation' => $orientation,
                'html' => $html,
                'registro' => $inscripcion->toArray(),
                'iglesia_config' => $iglesiaConfig?->toArray(),
            ],
            Auth::id()
        );

        return response($pdfBinario, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"',
        ]);
    }

    public function certificadosAprobadosPdf(Request $request)
    {
        $cursoId = (int) $request->integer('curso_id');

        $query = InscripcionCurso::with([
            'curso.instructor.feligres.persona',
            'curso.encargado.feligres.persona',
            'feligres.persona',
        ])
            ->where('aprobado', true);

        if ($cursoId > 0) {
            $query->where('curso_id', $cursoId);
        }

        $inscripciones = $query
            ->orderByDesc('fecha_certificado')
            ->orderByDesc('id')
            ->get();

        if ($inscripciones->isEmpty()) {
            return redirect()->route('inscripcion-curso.index')
                ->with('error', $cursoId > 0
                    ? 'No hay certificados aprobados para este curso.'
                    : 'No hay certificados aprobados para generar.');
        }

        foreach ($inscripciones as $inscripcion) {
            if (! $inscripcion->certificado_emitido || ! $inscripcion->fecha_certificado) {
                $inscripcion->update([
                    'certificado_emitido' => true,
                    'fecha_certificado' => $inscripcion->fecha_certificado ?: now()->toDateString(),
                ]);
            }
        }

        $inscripciones->load([
            'curso.instructor.feligres.persona',
            'curso.encargado.feligres.persona',
            'feligres.persona',
        ]);

        $iglesiaConfig = TenantIglesia::current();
        $paperSizeCursoMasivo = (string) ($iglesiaConfig?->paper_size_certificado_curso
            ?? $iglesiaConfig?->paper_size_certificado
            ?? 'letter');
        $paperSizeCursoMasivo = in_array($paperSizeCursoMasivo, ['letter', 'legal', 'a4', 'folio'], true)
            ? $paperSizeCursoMasivo
            : 'letter';
        $nombreArchivo = 'certificados-cursos-aprobados-' . now()->format('Ymd-His') . '.pdf';

        $pdf = Pdf::loadView('certificados.curso-masivo-pdf', compact('inscripciones', 'iglesiaConfig'))
            ->setPaper($paperSizeCursoMasivo, 'landscape');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"',
        ]);
    }

    public function matricula(\App\Models\InscripcionCurso $inscripcionCurso)
    {
        return view('curso.matricula', [
            'inscripcionId' => $inscripcionCurso->id,
        ]);
    }

    public function createFromInstructor(\App\Models\Instructor $instructor)
    {
        return view('Instructor.inscripcion-create', [
            'instructor' => $instructor
        ]);
    }
}