<?php

namespace App\Http\Controllers;

use App\Models\Bautismo;
use App\Models\TenantIglesia;
use App\Services\DocumentosGeneradosService;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BautismoController extends Controller
{
    public function index()
    {
        return view('bautismo.index');
    }

    public function create()
    {
        return view('bautismo.create');
    }

    public function show(Bautismo $bautismo)
    {
        return view('bautismo.show', compact('bautismo'));
    }

    public function edit(Bautismo $bautismo)
    {
        return view('bautismo.edit', compact('bautismo'));
    }

    
    
    public function certificadoPdf(Bautismo $bautismo)
    {
        $bautismo->loadMissing('encargado');
        if (! filled($bautismo->encargado?->path_firma_principal)) {
            abort(422, 'Debe configurar la firma principal del encargado para generar el PDF de bautismo.');
        }

        $iglesiaConfig = TenantIglesia::current();

        $tipoDocumento = 'bautismo_certificado';
        $nombreArchivo = 'certificado-bautismo-' . $bautismo->id . '.pdf';
        $layoutVersion = 'header-config-v5';
        $servicioDocumentos = app(DocumentosGeneradosService::class);
        $iglesiaDocumentoId = (int) $bautismo->iglesia_id;
        $orientacionBautismo = (string) ($iglesiaConfig?->orientacion_certificado_bautismo
            ?? $iglesiaConfig?->orientacion_certificado
            ?? 'portrait');
        $orientation = $orientacionBautismo === 'landscape' ? 'landscape' : 'portrait';
        $paperSizeBautismo = (string) ($iglesiaConfig?->paper_size_certificado_bautismo
            ?? $iglesiaConfig?->paper_size_certificado
            ?? 'letter');
        $paperSizeBautismo = in_array($paperSizeBautismo, ['letter', 'legal', 'a4', 'folio'], true)
            ? $paperSizeBautismo
            : 'letter';
        $pathFormatoBautismo = (string) (
            ($orientation === 'landscape'
                ? $iglesiaConfig?->path_certificado_bautismo_landscape
                : $iglesiaConfig?->path_certificado_bautismo_portrait)
            ?: $iglesiaConfig?->path_certificado_bautismo
            ?: ''
        );

        $dataVersion = hash('sha256', implode('|', [
            (string) ($bautismo->updated_at?->timestamp ?? 0),
            (string) ($iglesiaConfig?->updated_at?->timestamp ?? 0),
            (string) ($bautismo->encargado?->path_firma_principal ?? ''),
            (string) ($iglesiaConfig?->path_logo ?? ''),
            (string) ($iglesiaConfig?->path_logo_derecha ?? ''),
            $pathFormatoBautismo,
            $orientacionBautismo,
            $paperSizeBautismo,
            (string) ($iglesiaConfig?->header_diocesis ?? ''),
            (string) ($iglesiaConfig?->direccion ?? ''),
            (string) ($iglesiaConfig?->nombre ?? ''),
        ]));

        $documentoExistente = $servicioDocumentos->obtenerUltimo($tipoDocumento, Bautismo::class, (int) $bautismo->id, $iglesiaDocumentoId);
        $payloadExistente = is_array($documentoExistente?->payload) ? $documentoExistente->payload : [];
        $urlQrExistente = (string) ($payloadExistente['url_qr'] ?? '');
        $layoutVersionActual = (string) ($payloadExistente['layout_version'] ?? '');
        $dataVersionActual = (string) ($payloadExistente['data_version'] ?? '');
        $layoutActualizado = $layoutVersionActual === $layoutVersion;
        $dataActualizada = $dataVersionActual === $dataVersion;
        $snapshotConQr = ! empty($payloadExistente['html'])
            && ! empty($payloadExistente['codigo_verificacion'])
            && ! empty($payloadExistente['qr_data_uri'])
            && str_ends_with(strtolower($urlQrExistente), '/pdf')
            && $layoutActualizado
            && $dataActualizada;
        if ($documentoExistente && $snapshotConQr) {
            return Pdf::loadHTML($payloadExistente['html'])
                ->setPaper($payloadExistente['paper_size'] ?? 'letter', $payloadExistente['orientation'] ?? 'portrait')
                ->stream($documentoExistente->nombre_archivo);
        }

        if ($documentoExistente && $layoutActualizado && $dataActualizada && ! empty($documentoExistente->path_pdf) && Storage::disk('local')->exists($documentoExistente->path_pdf)) {
            return response()->file(
                Storage::disk('local')->path($documentoExistente->path_pdf),
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $documentoExistente->nombre_archivo . '"',
                ]
            );
        }

        $bautismo->load([
            'iglesia',
            'bautizado.persona',
            'padre.persona',
            'madre.persona',
            'padrino.persona',
            'madrina.persona',
            'encargado.feligres.persona',
        ]);

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

        $plantillaCertificadoPath = $pathFormatoBautismo;
        $html = view('bautismo.certificado-pdf', compact('bautismo', 'iglesiaConfig', 'codigoVerificacion', 'urlVerificacion', 'qrDataUri', 'plantillaCertificadoPath'))->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper($paperSizeBautismo, $orientation);

        $pdfBinario = $pdf->output();

        $servicioDocumentos->guardarDocumento(
            $tipoDocumento,
            $bautismo,
            $iglesiaDocumentoId,
            $nombreArchivo,
            [
                'emitido_en' => now()->toIso8601String(),
                'view' => 'bautismo.certificado-pdf',
                'paper_size' => $paperSizeBautismo,
                'orientation' => $orientation,
                'html' => $html,
                'codigo_verificacion' => $codigoVerificacion,
                'url_verificacion' => $urlVerificacion,
                'url_qr' => $urlQr,
                'qr_data_uri' => $qrDataUri,
                'layout_version' => $layoutVersion,
                'data_version' => $dataVersion,
                'registro' => $bautismo->toArray(),
                'iglesia_config' => $iglesiaConfig?->toArray(),
            ],
            Auth::id(),
            $codigoVerificacion
        );


        return response($pdfBinario, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
