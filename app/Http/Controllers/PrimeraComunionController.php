<?php

namespace App\Http\Controllers;

use App\Models\PrimeraComunion;
use App\Models\Encargado;
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

class PrimeraComunionController extends Controller
{
    public function index()
    {
        return view('primera-comunion.index');
    }

    public function create()
    {
        return view('primera-comunion.create');
    }

    public function show(PrimeraComunion $primeraComunion)
    {
        $primeraComunion->load([
            'iglesia',
            'feligres.persona',
            'catequista.persona',
            'ministro.persona',
            'parroco.persona',
        ]);

        return view('primera-comunion.show', compact('primeraComunion'));
    }

    public function edit(PrimeraComunion $primeraComunion)
    {
        return view('primera-comunion.edit', compact('primeraComunion'));
    }

    public function certificadoPdf(PrimeraComunion $primeraComunion)
    {
        $primeraComunion->loadMissing('encargado');
        if (! filled($primeraComunion->encargado?->path_firma_principal)) {
            abort(422, 'Debe configurar la firma principal del encargado para generar el PDF de primera comunion.');
        }

        $iglesiaConfig = TenantIglesia::current();

        $tipoDocumento = 'primera_comunion_certificado';
        $nombreArchivo = 'certificado-primera-comunion-' . $primeraComunion->id . '.pdf';
        $layoutVersion = 'header-config-v5';
        $servicioDocumentos = app(DocumentosGeneradosService::class);
        $iglesiaDocumentoId = (int) $primeraComunion->id_iglesia;
        $orientacionPrimeraComunion = (string) ($iglesiaConfig?->orientacion_certificado_primera_comunion
            ?? $iglesiaConfig?->orientacion_certificado
            ?? 'portrait');
        $orientation = $orientacionPrimeraComunion === 'landscape' ? 'landscape' : 'portrait';
        $paperSizePrimeraComunion = (string) ($iglesiaConfig?->paper_size_certificado_primera_comunion
            ?? $iglesiaConfig?->paper_size_certificado
            ?? 'letter');
        $paperSizePrimeraComunion = in_array($paperSizePrimeraComunion, ['letter', 'legal', 'a4', 'folio'], true)
            ? $paperSizePrimeraComunion
            : 'letter';
        $pathFormatoPrimeraComunion = (string) (
            ($orientation === 'landscape'
                ? $iglesiaConfig?->path_certificado_primera_comunion_landscape
                : $iglesiaConfig?->path_certificado_primera_comunion_portrait)
            ?: $iglesiaConfig?->path_certificado_primera_comunion
            ?: $iglesiaConfig?->path_certificado_bautismo
            ?: ''
        );

        $dataVersion = hash('sha256', implode('|', [
            (string) ($primeraComunion->updated_at?->timestamp ?? 0),
            (string) ($iglesiaConfig?->updated_at?->timestamp ?? 0),
            (string) ($primeraComunion->encargado?->path_firma_principal ?? ''),
            (string) ($iglesiaConfig?->path_logo ?? ''),
            (string) ($iglesiaConfig?->path_logo_derecha ?? ''),
            $pathFormatoPrimeraComunion,
            $orientacionPrimeraComunion,
            $paperSizePrimeraComunion,
            (string) ($iglesiaConfig?->header_diocesis ?? ''),
            (string) ($iglesiaConfig?->direccion ?? ''),
            (string) ($iglesiaConfig?->nombre ?? ''),
        ]));

        $documentoExistente = $servicioDocumentos->obtenerUltimo($tipoDocumento, PrimeraComunion::class, (int) $primeraComunion->id, $iglesiaDocumentoId);
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

        $primeraComunion->load([
            'iglesia',
            'feligres.persona',
            'catequista.persona',
            'ministro.persona',
            'parroco.persona',
        ]);

        // Encargado activo de la iglesia (para su firma en el PDF)
        $encargado = Encargado::whereHas('feligres', function ($q) use ($primeraComunion) {
                $q->where('id_iglesia', $primeraComunion->id_iglesia);
            })
            ->where('estado', 'Activo')
            ->whereNull('deleted_at')
            ->latest()
            ->first();

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

        $plantillaCertificadoPath = $pathFormatoPrimeraComunion;
        $html = view('primera-comunion.certificado-pdf', compact('primeraComunion', 'encargado', 'iglesiaConfig', 'codigoVerificacion', 'urlVerificacion', 'qrDataUri', 'plantillaCertificadoPath'))->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper($paperSizePrimeraComunion, $orientation);

        $pdfBinario = $pdf->output();

        $servicioDocumentos->guardarDocumento(
            $tipoDocumento,
            $primeraComunion,
            $iglesiaDocumentoId,
            $nombreArchivo,
            [
                'emitido_en' => now()->toIso8601String(),
                'view' => 'primera-comunion.certificado-pdf',
                'paper_size' => $paperSizePrimeraComunion,
                'orientation' => $orientation,
                'html' => $html,
                'codigo_verificacion' => $codigoVerificacion,
                'url_verificacion' => $urlVerificacion,
                'url_qr' => $urlQr,
                'qr_data_uri' => $qrDataUri,
                'layout_version' => $layoutVersion,
                'data_version' => $dataVersion,
                'registro' => $primeraComunion->toArray(),
                'encargado' => $encargado?->toArray(),
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