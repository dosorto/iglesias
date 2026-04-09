<?php

namespace App\Services;

use App\Models\DocumentoGenerado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentosGeneradosService
{
    public function construirUrlVerificacion(string $codigoVerificacion): string
    {
        return $this->construirUrlPublica('documentos.verificar', ['codigo' => $codigoVerificacion]);
    }

    public function construirUrlVerificacionPdf(string $codigoVerificacion): string
    {
        return $this->construirUrlPublica('documentos.verificar.pdf', ['codigo' => $codigoVerificacion]);
    }

    private function construirUrlPublica(string $routeName, array $params = []): string
    {
        $path = route($routeName, $params, false);
        $baseUrl = trim((string) env('QR_PUBLIC_BASE_URL', ''), " \t\n\r\0\x0B/");

        if ($baseUrl === '') {
            $baseUrl = trim((string) config('app.url', ''), " \t\n\r\0\x0B/");
        }

        return $baseUrl !== '' ? 'https://' . preg_replace('#^https?://#', '', $baseUrl) . $path : url($path);
    }

    public function obtenerUltimo(string $tipoDocumento, string $fuenteTipo, int $fuenteId): ?DocumentoGenerado
    {
        return DocumentoGenerado::query()
            ->where('tipo_documento', $tipoDocumento)
            ->where('fuente_tipo', $fuenteTipo)
            ->where('fuente_id', $fuenteId)
            ->latest('id')
            ->first();
    }

    public function guardarDocumento(
        string $tipoDocumento,
        Model $fuente,
        ?int $iglesiaId,
        string $nombreArchivo,
        array $payload,
        ?int $userId = null,
        ?string $codigoVerificacion = null
    ): DocumentoGenerado {
        $hashPayload = $this->hashPayload($payload);

        $ultimoDocumento = DocumentoGenerado::query()
            ->where('tipo_documento', $tipoDocumento)
            ->where('fuente_tipo', $fuente::class)
            ->where('fuente_id', (int) $fuente->getKey())
            ->latest('id')
            ->first();

        // Avoid creating duplicate rows when the rendered snapshot is identical.
        if ($ultimoDocumento && $ultimoDocumento->hash_payload === $hashPayload) {
            return $ultimoDocumento;
        }

        $codigoVerificacion = $codigoVerificacion ?: $this->generarCodigoVerificacionUnico();

        return DocumentoGenerado::create([
            'tipo_documento' => $tipoDocumento,
            'fuente_tipo' => $fuente::class,
            'fuente_id' => (int) $fuente->getKey(),
            'iglesia_id' => $iglesiaId,
            'fecha_emision' => now(),
            'nombre_archivo' => $nombreArchivo,
            'path_pdf' => null,
            'payload' => $payload,
            'codigo_verificacion' => $codigoVerificacion,
            'hash_payload' => $hashPayload,
            'created_by' => $userId,
        ]);
    }

    public function generarCodigoVerificacionUnico(): string
    {
        $codigoVerificacion = strtoupper(Str::random(14));

        for ($i = 0; $i < 5; $i++) {
            if (! DocumentoGenerado::query()->where('codigo_verificacion', $codigoVerificacion)->exists()) {
                return $codigoVerificacion;
            }

            $codigoVerificacion = strtoupper(Str::random(14));
        }

        return strtoupper(Str::uuid()->toString());
    }

    public function hashPayload(array $payload): string
    {
        $payloadJson = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}';

        return hash('sha256', $payloadJson);
    }
}
