<?php

namespace App\Http\Controllers;

use App\Models\Confirmacion;
use App\Models\Iglesias;
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
            abort(422, 'Debe configurar la firma principal del encargado para generar el PDF de confirmacion.');
        }

        $iglesiaConfig = TenantIglesia::current();

        if (! $iglesiaConfig) {
            $iglesiaId     = session('tenant.id_iglesia');
            $iglesiaConfig = $iglesiaId ? Iglesias::find($iglesiaId) : null;
        }

        $tipoDocumento = 'confirmacion_certificado';
        $nombreArchivo = 'certificado-confirmacion-' . $confirmacion->id . '.pdf';
        $layoutVersion = 'header-config-v3';
        $servicioDocumentos = app(DocumentosGeneradosService::class);
        $iglesiaDocumentoId = (int) $confirmacion->iglesia_id;
        $pathFormatoConfirmacion = (string) ($iglesiaConfig?->path_certificado_confirmacion ?: $iglesiaConfig?->path_certificado_bautismo ?? '');
        $orientacionConfirmacion = (string) ($iglesiaConfig?->orientacion_certificado_confirmacion
            ?? $iglesiaConfig?->orientacion_certificado
            ?? 'portrait');

        $dataVersion = hash('sha256', implode('|', [
            (string) ($confirmacion->updated_at?->timestamp ?? 0),
            (string) ($iglesiaConfig?->updated_at?->timestamp ?? 0),
            (string) ($confirmacion->encargado?->path_firma_principal ?? ''),
            (string) ($iglesiaConfig?->path_logo ?? ''),
            (string) ($iglesiaConfig?->path_logo_derecha ?? ''),
            $pathFormatoConfirmacion,
            $orientacionConfirmacion,
            (string) ($iglesiaConfig?->header_diocesis ?? ''),
            (string) ($iglesiaConfig?->direccion ?? ''),
            (string) ($iglesiaConfig?->nombre ?? ''),
        ]));

        $documentoExistente = $servicioDocumentos->obtenerUltimo($tipoDocumento, Confirmacion::class, (int) $confirmacion->id, $iglesiaDocumentoId);
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

        $html = view('confirmacion.certificado-pdf', compact('confirmacion', 'iglesiaConfig', 'codigoVerificacion', 'urlVerificacion', 'qrDataUri'))->render();

        $orientation = $orientacionConfirmacion === 'landscape' ? 'landscape' : 'portrait';

        $pdf = Pdf::loadHTML($html)->setPaper('letter', $orientation);

        $pdfBinario = $pdf->output();

        $servicioDocumentos->guardarDocumento(
            $tipoDocumento,
            $confirmacion,
            $iglesiaDocumentoId,
            $nombreArchivo,
            [
                'emitido_en' => now()->toIso8601String(),
                'view' => 'confirmacion.certificado-pdf',
                'paper_size' => 'letter',
                'orientation' => $orientation,
                'html' => $html,
                'codigo_verificacion' => $codigoVerificacion,
                'url_verificacion' => $urlVerificacion,
                'url_qr' => $urlQr,
                'qr_data_uri' => $qrDataUri,
                'layout_version' => $layoutVersion,
                'data_version' => $dataVersion,
                'registro' => $confirmacion->toArray(),
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
}