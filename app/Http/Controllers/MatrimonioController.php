<?php

namespace App\Http\Controllers;

use App\Models\Matrimonio;
use App\Models\TenantIglesia;
use App\Services\DocumentosGeneradosService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MatrimonioController extends Controller
{
    public function index()
    {
        return view('matrimonio.index');
    }

    public function create()
    {
        return view('matrimonio.create');
    }

    public function show(Matrimonio $matrimonio)
    {
        return view('matrimonio.show', compact('matrimonio'));
    }

    public function edit(Matrimonio $matrimonio)
    {
        return view('matrimonio.edit', compact('matrimonio'));
    }

    public function certificadoPdf(Matrimonio $matrimonio)
    {
        $matrimonio->loadMissing('encargado');
        if (! filled($matrimonio->encargado?->path_firma_principal)) {
            abort(422, 'Debe configurar la firma principal del encargado para generar la constancia de matrimonio.');
        }

        $iglesiaConfig = TenantIglesia::current();

        $sanitizarNombre = fn(string $s): string =>
            preg_replace('/[^a-z]/', '', mb_strtolower(
                str_replace(['á','é','í','ó','ú','ü','ñ','à','â','ã','ê','î','ô','û'],
                            ['a','e','i','o','u','u','n','a','a','a','e','i','o','u'],
                            explode(' ', trim($s))[0] ?? ''), 'UTF-8')) ?: 'persona';

        $tipoDocumento = 'matrimonio_constancia';
        $nombreArchivo = 'constancia-matrimonio-' . $matrimonio->id . '.pdf';
        $layoutVersion = 'header-config-v8';
        $servicioDocumentos = app(DocumentosGeneradosService::class);
        $iglesiaDocumentoId = (int) $matrimonio->iglesia_id;
        $orientacionMatrimonio = (string) ($iglesiaConfig?->orientacion_certificado_matrimonio
            ?? $iglesiaConfig?->orientacion_certificado
            ?? 'portrait');
        $orientation = $orientacionMatrimonio === 'landscape' ? 'landscape' : 'portrait';
        $paperSizeMatrimonio = (string) ($iglesiaConfig?->paper_size_certificado_matrimonio
            ?? $iglesiaConfig?->paper_size_certificado
            ?? 'letter');
        $paperSizeMatrimonio = in_array($paperSizeMatrimonio, ['letter', 'legal', 'a4', 'folio'], true)
            ? $paperSizeMatrimonio
            : 'letter';
        $pathFormatoMatrimonio = (string) (
            ($orientation === 'landscape'
                ? $iglesiaConfig?->path_certificado_matrimonio_landscape
                : $iglesiaConfig?->path_certificado_matrimonio_portrait)
            ?: $iglesiaConfig?->path_certificado_matrimonio
            ?: $iglesiaConfig?->path_certificado_bautismo
            ?: ''
        );

        $dataVersion = hash('sha256', implode('|', [
            (string) ($matrimonio->updated_at?->timestamp ?? 0),
            (string) ($iglesiaConfig?->updated_at?->timestamp ?? 0),
            (string) ($matrimonio->encargado?->path_firma_principal ?? ''),
            (string) ($iglesiaConfig?->path_logo ?? ''),
            (string) ($iglesiaConfig?->path_logo_derecha ?? ''),
            $pathFormatoMatrimonio,
            $orientacionMatrimonio,
            $paperSizeMatrimonio,
            (string) ($iglesiaConfig?->header_diocesis ?? ''),
            (string) ($iglesiaConfig?->direccion ?? ''),
            (string) ($iglesiaConfig?->nombre ?? ''),
        ]));

        $documentoExistente = $servicioDocumentos->obtenerUltimo($tipoDocumento, Matrimonio::class, (int) $matrimonio->id, $iglesiaDocumentoId);
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

        $matrimonio->load([
            'iglesia',
            'esposo.persona',
            'esposa.persona',
            'testigo1.persona',
            'testigo2.persona',
            'encargado.feligres.persona',
        ]);

        $nombreArchivo = sprintf(
            'constancia-matrimonio-%s-%s-%s.pdf',
            $matrimonio->id,
            $sanitizarNombre($matrimonio->esposo?->persona?->nombre_completo ?? ''),
            ($matrimonio->fecha_expedicion ?? now())->format('Ymd')
        );

        $plantillaCertificadoPath = $pathFormatoMatrimonio;
        $html = view('matrimonio.certificado-pdf', compact('matrimonio', 'iglesiaConfig', 'plantillaCertificadoPath'))->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper($paperSizeMatrimonio, $orientation);

        $pdfBinario = $pdf->output();

        $servicioDocumentos->guardarDocumento(
            $tipoDocumento,
            $matrimonio,
            $iglesiaDocumentoId,
            $nombreArchivo,
            [
                'emitido_en' => now()->toIso8601String(),
                'view' => 'matrimonio.certificado-pdf',
                'paper_size' => $paperSizeMatrimonio,
                'orientation' => $orientation,
                'html' => $html,
                'layout_version' => $layoutVersion,
                'data_version' => $dataVersion,
                'registro' => $matrimonio->toArray(),
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
