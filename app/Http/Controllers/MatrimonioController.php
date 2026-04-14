<?php

namespace App\Http\Controllers;

use App\Models\Matrimonio;
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
        $tipoDocumento = 'matrimonio_constancia';
        $nombreArchivo = 'constancia-matrimonio-' . $matrimonio->id . '.pdf';
        $servicioDocumentos = app(DocumentosGeneradosService::class);

        $documentoExistente = $servicioDocumentos->obtenerUltimo($tipoDocumento, Matrimonio::class, (int) $matrimonio->id, (int) $matrimonio->iglesia_id);
        $payloadExistente = is_array($documentoExistente?->payload) ? $documentoExistente->payload : [];
        $urlQrExistente = (string) ($payloadExistente['url_qr'] ?? '');
        $snapshotConQr = ! empty($payloadExistente['html'])
            && ! empty($payloadExistente['codigo_verificacion'])
            && ! empty($payloadExistente['qr_data_uri'])
            && str_ends_with(strtolower($urlQrExistente), '/pdf');
        if ($documentoExistente && $snapshotConQr) {
            return Pdf::loadHTML($payloadExistente['html'])
                ->setPaper($payloadExistente['paper_size'] ?? 'letter', $payloadExistente['orientation'] ?? 'portrait')
                ->stream($documentoExistente->nombre_archivo);
        }

        if ($documentoExistente && ! empty($documentoExistente->path_pdf) && Storage::disk('local')->exists($documentoExistente->path_pdf)) {
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

        $iglesiaConfig = TenantIglesia::current();
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

        $html = view('matrimonio.certificado-pdf', compact('matrimonio', 'iglesiaConfig', 'codigoVerificacion', 'urlVerificacion', 'qrDataUri'))->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper('letter', 'portrait');

        $pdfBinario = $pdf->output();

        $servicioDocumentos->guardarDocumento(
            $tipoDocumento,
            $matrimonio,
            $matrimonio->iglesia_id,
            $nombreArchivo,
            [
                'emitido_en' => now()->toIso8601String(),
                'view' => 'matrimonio.certificado-pdf',
                'paper_size' => 'letter',
                'orientation' => 'portrait',
                'html' => $html,
                'codigo_verificacion' => $codigoVerificacion,
                'url_verificacion' => $urlVerificacion,
                'url_qr' => $urlQr,
                'qr_data_uri' => $qrDataUri,
                'registro' => $matrimonio->toArray(),
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
