<?php

namespace App\Http\Controllers;

use App\Models\InscripcionCurso;
use App\Models\TenantIglesia;
use App\Services\DocumentosGeneradosService;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
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

        $codigoVerificacion = $servicioDocumentos->generarCodigoVerificacionUnico();
        $urlVerificacion = $servicioDocumentos->construirUrlVerificacion($codigoVerificacion);
        $urlQr = $servicioDocumentos->construirUrlVerificacionPdf($codigoVerificacion);
        $qrDataUri = Builder::create()
            ->writer(new PngWriter())
            ->data($urlQr)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::Medium)
            ->size(130)
            ->margin(1)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->build()
            ->getDataUri();

        $html = view('certificados.curso-pdf', compact('inscripcion', 'iglesiaConfig', 'codigoVerificacion', 'urlVerificacion', 'qrDataUri'))->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper('letter', 'landscape');

        $pdfBinario = $pdf->output();

        $servicioDocumentos->guardarDocumento(
            $tipoDocumento,
            $inscripcion,
            $iglesiaId,
            $nombreArchivo,
            [
                'emitido_en' => now()->toIso8601String(),
                'view' => 'certificados.curso-pdf',
                'paper_size' => 'letter',
                'orientation' => 'landscape',
                'html' => $html,
                'codigo_verificacion' => $codigoVerificacion,
                'url_verificacion' => $urlVerificacion,
                'url_qr' => $urlQr,
                'qr_data_uri' => $qrDataUri,
                'registro' => $inscripcion->toArray(),
                'iglesia_config' => $iglesiaConfig?->toArray(),
            ],
            Auth::id(),
            $codigoVerificacion
        );

        return response($pdfBinario, 200, [
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
        return view('instructor.inscripcion-create', [
            'instructor' => $instructor
        ]);
    }
}