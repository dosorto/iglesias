<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Certificado de Curso</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12pt;
            color: #1f1f1f;
            background: #fff;
        }

        .page-wrapper {
            padding: 24px 30px;
            border: none;
            margin: 2px;
            position: relative;
            overflow: visible;
        }

        .watermark-logo {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            z-index: 0;
        }

        .watermark-logo img {
            width: 360px;
            height: auto;
            object-fit: contain;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 14px;
            position: relative;
            z-index: 1;
        }

        .header-logo-cell,
        .header-right-cell {
            display: table-cell;
            width: 90px;
            vertical-align: middle;
            text-align: center;
        }

        .header-logo-cell img,
        .header-right-cell img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .header-title-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 0 10px;
        }

        .parish-name {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .diocese-name {
            font-size: 11pt;
            text-transform: uppercase;
            margin-top: 4px;
            color: #555;
        }

        .header-address {
            font-size: 10.5pt;
            margin-top: 4px;
            color: #444;
        }

        .header-divider {
            border-top: 1px solid #444;
            margin: 10px 0 14px;
            position: relative;
            z-index: 1;
        }

        .cert-title-wrap {
            text-align: center;
            margin: 10px 0 18px;
            position: relative;
            z-index: 1;
        }

        .cert-title {
            display: inline-block;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-bottom: 4px;
        }

        .body-text {
            line-height: 1.9;
            font-size: 11.5pt;
            margin-top: 12px;
            position: relative;
            z-index: 1;
        }

        .body-text p {
            margin-bottom: 6px;
        }

        .line-field {
            display: inline;
            margin: 0 1px;
            padding: 0 1px;
        }

        .line-field-sm { }
        .line-field-lg { }
        .line-field-xl { }

        .sig-right {
            width: 260px;
            margin-left: auto;
            margin-right: 20px;
            text-align: center;
            margin-top: 24px;
            position: relative;
            z-index: 1;
        }

        .sig-name {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .sig-line-accent {
            display: block;
            width: 220px;
            margin: 0 auto;
            border-top: 1px solid #333;
            text-align: center;
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 1px;
            padding-top: 4px;
            color: #333;
        }

        .issuance {
            margin-top: 22px;
            font-size: 11.5pt;
            line-height: 1.9;
            position: relative;
            z-index: 1;
        }

        .sig-bottom {
            width: 260px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .footer-row {
            display: table;
            width: 100%;
            margin-top: 16px;
            position: relative;
            z-index: 1;
        }

        .footer-left {
            display: table-cell;
            width: 100px;
            vertical-align: bottom;
        }

        .footer-right {
            display: table-cell;
            text-align: right;
            vertical-align: bottom;
            padding-right: 20px;
        }

    </style>
</head>

@php
    $resolvePublicFilePath = function (?string $path): ?string {
        if (! $path) {
            return null;
        }

        $normalized = trim((string) parse_url($path, PHP_URL_PATH) ?: $path);
        $normalized = ltrim($normalized, '/\\');

        if ($normalized === '') {
            return null;
        }

        $candidate = str_starts_with($normalized, 'storage/')
            ? public_path($normalized)
            : public_path('storage/' . $normalized);

        return is_file($candidate) ? $candidate : null;
    };

    $curso = $inscripcion->curso;
    $persona = $inscripcion->feligres?->persona;
    $instructor = $curso?->instructor?->feligres?->persona;
    $encargado = $curso?->encargado?->feligres?->persona;

    $iglesiaNombre = $iglesiaConfig?->nombre ?? '';
    $headerDiocesis = $iglesiaConfig?->header_diocesis ?: '';
    $headerLugar = $iglesiaConfig?->direccion ?: '';
    $logoIglesiaPath = $resolvePublicFilePath($iglesiaConfig?->path_logo);
    $logoIglesiaDerechaPath = $resolvePublicFilePath($iglesiaConfig?->path_logo_derecha) ?: $logoIglesiaPath;
    $certBgPath = $resolvePublicFilePath($plantillaCertificadoPath ?? ($iglesiaConfig?->path_certificado_curso ?: $iglesiaConfig?->path_certificado_bautismo));

    $firmaPath = $resolvePublicFilePath($curso?->instructor?->path_firma);

    $fechaInicio = $curso?->fecha_inicio;
    $fechaFin = $curso?->fecha_fin;
    $fechaCertificado = $inscripcion->fecha_certificado;

    $mesesEs = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
    ];

    $diaCert = $fechaCertificado?->day ?? '';
    $mesCert = $fechaCertificado ? $mesesEs[$fechaCertificado->month] : '';
    $anoCert = $fechaCertificado?->year ?? '';

    $diaInicio = $fechaInicio?->day ?? '';
    $mesInicio = $fechaInicio ? $mesesEs[$fechaInicio->month] : '';
    $anoInicio = $fechaInicio?->year ?? '';

    $diaFin = $fechaFin?->day ?? '';
    $mesFin = $fechaFin ? $mesesEs[$fechaFin->month] : '';
    $anoFin = $fechaFin?->year ?? '';

    $codigoVerificacion = $codigoVerificacion ?? '';
    $urlVerificacion = $urlVerificacion ?? '';
    $qrDataUri = $qrDataUri ?? null;
@endphp

<body @if($certBgPath && file_exists($certBgPath)) style="background-image: url('{{ $certBgPath }}'); background-size: cover; background-position: center; background-repeat: no-repeat;" @endif>
    @if ($logoIglesiaPath && file_exists($logoIglesiaPath))
        <div class="watermark-logo">
            <img src="{{ $logoIglesiaPath }}" alt="Marca de agua">
        </div>
    @endif

    <div class="page-wrapper">

        <div class="header">
            <div class="header-logo-cell">
                @if ($logoIglesiaPath && file_exists($logoIglesiaPath))
                    <img src="{{ $logoIglesiaPath }}" alt="Logo">
                @endif
            </div>

            <div class="header-title-cell">
                <div class="parish-name">{{ $iglesiaNombre }}</div>
                <div class="diocese-name">{{ $headerDiocesis }}</div>
                <div class="header-address">{{ $headerLugar }}</div>
            </div>

            <div class="header-right-cell">
                @if ($logoIglesiaDerechaPath && file_exists($logoIglesiaDerechaPath))
                    <img src="{{ $logoIglesiaDerechaPath }}" alt="Logo">
                @endif
            </div>
        </div>

        <div class="header-divider"></div>

        <div class="cert-title-wrap">
            <span class="cert-title">Certificado de Aprobación</span>
        </div>

        <div class="body-text">
            <p>El presente documento certifica que:</p>

            <p>
                <span class="line-field line-field-xl">{{ mb_strtoupper($persona?->nombre_completo ?? 'N/A', 'UTF-8') }}</span>
            </p>

            <p>
                con número de identidad
                <span class="line-field line-field-lg">{{ $persona?->dni ?? 'N/A' }}</span>
            </p>

            <p>ha aprobado satisfactoriamente el curso denominado:</p>

            <p>
                <span class="line-field line-field-xl" style="font-weight:bold; font-size:14pt;">{{ $curso?->nombre ?? 'N/A' }}</span>
            </p>

            <p style="margin-bottom:18px;"></p>

            <p>
                @if($fechaInicio && $fechaFin)
                    desarrollado desde el
                    <span class="line-field line-field-sm">{{ $diaInicio }}</span>
                    de
                    <span class="line-field">{{ $mesInicio }}</span>
                    de
                    <span class="line-field line-field-sm">{{ $anoInicio }}</span>
                    hasta el
                    <span class="line-field line-field-sm">{{ $diaFin }}</span>
                    de
                    <span class="line-field">{{ $mesFin }}</span>
                    de
                    <span class="line-field line-field-sm">{{ $anoFin }}</span>.
                @elseif($fechaInicio)
                    desarrollado a partir del
                    <span class="line-field line-field-sm">{{ $diaInicio }}</span>
                    de
                    <span class="line-field">{{ $mesInicio }}</span>
                    de
                    <span class="line-field line-field-sm">{{ $anoInicio }}</span>.
                @else
                    registrado en el sistema institucional de capacitaciones.
                @endif
            </p>

            <p style="margin-bottom:18px;"></p>

            <p>
                Instructor responsable:
                <span class="line-field line-field-xl">{{ mb_strtoupper($instructor?->nombre_completo ?? 'N/A', 'UTF-8') }}</span>
            </p>

            <p style="margin-bottom:18px;"></p>

            {{-- Observaciones opcional --}}
            @if(!empty($observaciones))
                <p><strong>Observaciones:</strong> {{ $observaciones }}</p>
            @endif
        </div>

        <div class="sig-right">
            @if ($instructor?->nombre_completo)
                <p class="sig-name">{{ mb_strtoupper($instructor->nombre_completo, 'UTF-8') }}</p>
            @endif
            <div class="sig-line-accent">I N S T R U C T O R</div>
        </div>

        <div class="issuance">
            <p>
                Emitido el
                <span class="line-field line-field-sm">{{ $diaCert ?: now()->day }}</span>
                de
                <span class="line-field">{{ $mesCert ?: $mesesEs[now()->month] }}</span>
                de
                <span class="line-field line-field-sm">{{ $anoCert ?: now()->year }}</span>
            </p>

            <p>
                Responsable administrativo:
                <span class="line-field line-field-xl">{{ mb_strtoupper($encargado?->nombre_completo ?? 'N/A', 'UTF-8') }}</span>
            </p>
        </div>

        <div class="footer-row">
            <div class="footer-left">
            </div>

            <div class="footer-right">
                <div class="sig-bottom">
                    @if ($firmaPath && file_exists($firmaPath))
                        <p style="text-align:center; margin-bottom: 2px;">
                            <img src="{{ $firmaPath }}" style="max-height:50px; max-width:180px;" alt="Firma del instructor">
                        </p>
                    @endif

                    <div class="sig-line-accent">F I R M A</div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>