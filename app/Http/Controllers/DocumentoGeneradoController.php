<?php

namespace App\Http\Controllers;

use App\Models\DocumentoGenerado;
use App\Models\TenantIglesia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoGeneradoController extends Controller
{
    public function pdf(DocumentoGenerado $documentoGenerado)
    {
        $iglesiaActual = TenantIglesia::currentId();
        if ($iglesiaActual && (int) $documentoGenerado->iglesia_id !== (int) $iglesiaActual) {
            abort(403, 'No tienes acceso a este documento.');
        }

        return $this->streamDocumento($documentoGenerado);
    }

    public function verificar(string $codigo, Request $request)
    {
        $documentoGenerado = DocumentoGenerado::query()
            ->where('codigo_verificacion', strtoupper(trim($codigo)))
            ->latest('id')
            ->firstOrFail();

        $payload = is_array($documentoGenerado->payload) ? $documentoGenerado->payload : [];
        $payloadJson = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}';
        $hashCalculado = hash('sha256', $payloadJson);
        $integridadValida = ! empty($documentoGenerado->hash_payload)
            && hash_equals((string) $documentoGenerado->hash_payload, $hashCalculado);

        if ($request->query('formato') === 'json') {
            return response()->json([
                'valido' => $integridadValida,
                'codigo_verificacion' => $documentoGenerado->codigo_verificacion,
                'tipo_documento' => $documentoGenerado->tipo_documento,
                'fecha_emision' => optional($documentoGenerado->fecha_emision)?->toIso8601String(),
                'nombre_archivo' => $documentoGenerado->nombre_archivo,
                'fuente_tipo' => $documentoGenerado->fuente_tipo,
                'fuente_id' => $documentoGenerado->fuente_id,
                'hash_payload' => $documentoGenerado->hash_payload,
            ]);
        }

        return view('documentos.validacion', [
            'documentoGenerado' => $documentoGenerado,
            'integridadValida' => $integridadValida,
        ]);
    }

    public function pdfPorCodigo(string $codigo)
    {
        $documentoGenerado = DocumentoGenerado::query()
            ->where('codigo_verificacion', strtoupper(trim($codigo)))
            ->latest('id')
            ->firstOrFail();

        return $this->streamDocumento($documentoGenerado);
    }

    private function streamDocumento(DocumentoGenerado $documentoGenerado)
    {

        $payload = is_array($documentoGenerado->payload) ? $documentoGenerado->payload : [];
        $html = $payload['html'] ?? null;

        if (is_string($html) && trim($html) !== '') {
            $paperSize = $payload['paper_size'] ?? 'letter';
            $orientation = $payload['orientation'] ?? 'portrait';
            $pdfBinario = Pdf::loadHTML($html)
                ->setPaper($paperSize, $orientation)
                ->output();

            return response($pdfBinario, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $documentoGenerado->nombre_archivo . '"',
                'Content-Length' => (string) strlen($pdfBinario),
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        if (! empty($documentoGenerado->path_pdf) && Storage::disk('local')->exists($documentoGenerado->path_pdf)) {
            return response()->file(
                Storage::disk('local')->path($documentoGenerado->path_pdf),
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $documentoGenerado->nombre_archivo . '"',
                ]
            );
        }

        abort(404, 'No se encontró un snapshot válido para reconstruir este documento.');
    }
}
