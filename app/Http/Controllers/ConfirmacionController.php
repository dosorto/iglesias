<?php

namespace App\Http\Controllers;

use App\Models\Confirmacion;
use App\Models\Iglesias;
use App\Models\TenantIglesia;
use App\Services\DocumentosGeneradosService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ConfirmacionController extends Controller
{
    public function index()
    {
        return view('confirmacion.index');
    }

    public function create()
    {
        return view('confirmacion.create');
    }

    public function show(Confirmacion $confirmacion)
    {
        return view('confirmacion.show', compact('confirmacion'));
    }

    public function edit(Confirmacion $confirmacion)
    {
        return view('confirmacion.edit', compact('confirmacion'));
    }

    public function certificadoPdf(Confirmacion $confirmacion)
    {
        $confirmacion->loadMissing('encargado');
        if (! filled($confirmacion->encargado?->path_firma_principal)) {
            abort(422, 'Debe configurar la firma principal del encargado para generar el PDF de confirmación.');
        }

        $iglesiaConfig = TenantIglesia::current();

        if (! $iglesiaConfig) {
            $iglesiaId     = session('tenant.id_iglesia');
            $iglesiaConfig = $iglesiaId ? Iglesias::find($iglesiaId) : null;
        }

        $tipoDocumento = 'confirmacion_certificado';
        $nombreArchivo = 'certificado-confirmacion-' . $confirmacion->id . '.pdf';
        $layoutVersion = 'header-config-v8';
        $servicioDocumentos = app(DocumentosGeneradosService::class);
        $iglesiaDocumentoId = (int) $confirmacion->iglesia_id;
        $orientacionConfirmacion = (string) ($iglesiaConfig?->orientacion_certificado_confirmacion
            ?? $iglesiaConfig?->orientacion_certificado
            ?? 'portrait');
        $orientation = $orientacionConfirmacion === 'landscape' ? 'landscape' : 'portrait';
        $paperSizeConfirmacion = (string) ($iglesiaConfig?->paper_size_certificado_confirmacion
            ?? $iglesiaConfig?->paper_size_certificado
            ?? 'letter');
        $paperSizeConfirmacion = in_array($paperSizeConfirmacion, ['letter', 'legal', 'a4', 'folio'], true)
            ? $paperSizeConfirmacion
            : 'letter';
        $pathFormatoConfirmacion = (string) (
            ($orientation === 'landscape'
                ? $iglesiaConfig?->path_certificado_confirmacion_landscape
                : $iglesiaConfig?->path_certificado_confirmacion_portrait)
            ?: $iglesiaConfig?->path_certificado_confirmacion
            ?: $iglesiaConfig?->path_certificado_bautismo
            ?: ''
        );

        $dataVersion = hash('sha256', implode('|', [
            (string) ($confirmacion->updated_at?->timestamp ?? 0),
            (string) ($iglesiaConfig?->updated_at?->timestamp ?? 0),
            (string) ($confirmacion->encargado?->path_firma_principal ?? ''),
            (string) ($iglesiaConfig?->path_logo ?? ''),
            (string) ($iglesiaConfig?->path_logo_derecha ?? ''),
            $pathFormatoConfirmacion,
            $orientacionConfirmacion,
            $paperSizeConfirmacion,
            (string) ($iglesiaConfig?->header_diocesis ?? ''),
            (string) ($iglesiaConfig?->direccion ?? ''),
            (string) ($iglesiaConfig?->nombre ?? ''),
        ]));

        $documentoExistente = $servicioDocumentos->obtenerUltimo($tipoDocumento, Confirmacion::class, (int) $confirmacion->id, $iglesiaDocumentoId);
        $payloadExistente = is_array($documentoExistente?->payload) ? $documentoExistente->payload : [];
        $layoutVersionActual = (string) ($payloadExistente['layout_version'] ?? '');
        $dataVersionActual = (string) ($payloadExistente['data_version'] ?? '');
        $layoutActualizado = $layoutVersionActual === $layoutVersion;
        $dataActualizada = $dataVersionActual === $dataVersion;

        if ($documentoExistente && $layoutActualizado && $dataActualizada && ! empty($documentoExistente->path_pdf) && Storage::disk('local')->exists($documentoExistente->path_pdf)) {
            return response()->file(
                Storage::disk('local')->path($documentoExistente->path_pdf),
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $documentoExistente->nombre_archivo . '"',
                ]
            );
        }

        $confirmacion->load([
            'iglesia',
            'feligres.persona',
            'padre.persona',
            'madre.persona',
            'padrino.persona',
            'madrina.persona',
            'ministro.persona',
            'encargado.feligres.persona',
        ]);

        $plantillaCertificadoPath = $pathFormatoConfirmacion;
        $html = view('confirmacion.certificado-pdf', compact('confirmacion', 'iglesiaConfig', 'plantillaCertificadoPath'))->render();

        $pdf = Pdf::loadHTML($html)->setPaper($paperSizeConfirmacion, $orientation);

        $pdfBinario = $pdf->output();

        $servicioDocumentos->guardarDocumento(
            $tipoDocumento,
            $confirmacion,
            $iglesiaDocumentoId,
            $nombreArchivo,
            [
                'emitido_en' => now()->toIso8601String(),
                'view' => 'confirmacion.certificado-pdf',
                'paper_size' => $paperSizeConfirmacion,
                'orientation' => $orientation,
                'html' => $html,
                'layout_version' => $layoutVersion,
                'data_version' => $dataVersion,
                'registro' => $confirmacion->toArray(),
                'iglesia_config' => $iglesiaConfig?->toArray(),
            ],
            Auth::id()
        );

        return response($pdfBinario, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"',
        ]);
    }
}