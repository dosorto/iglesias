<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Certificado de Curso</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #1a1a1a;
            background: #fff;
        }

        .page-wrapper {
            padding: 26px 36px;
            border: 4px double #7D5A1E;
            margin: 10px;
            position: relative;
            z-index: 1;
        }

        .watermark-logo {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.08;
            z-index: 0;
        }

        .watermark-logo img {
            width: 430px;
            height: auto;
            object-fit: contain;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .header-logo-cell,
        .header-right-cell {
            display: table-cell;
            width: 85px;
            vertical-align: middle;
            text-align: center;
        }

        .header-logo-cell img,
        .header-right-cell img {
            width: 75px;
            height: 75px;
            object-fit: contain;
        }

        .header-title-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .parish-name {
            font-size: 19pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            line-height: 1.1;
        }

        .diocese-name {
            font-size: 13pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 3px;
            color: #555;
        }

        .header-address {
            font-size: 11pt;
            margin-top: 4px;
            color: #222;
            letter-spacing: 0.5px;
        }

        .header-divider {
            border-top: 2px solid #8aa8bc;
            margin: 6px 0 8px;
        }

        .hr-accent {
            border: none;
            border-top: 1px solid #7D5A1E;
            margin: 3px 0;
        }

        .ornament {
            text-align: center;
            color: #7D5A1E;
            font-size: 11pt;
            letter-spacing: 8px;
            margin: 3px 0;
        }

        .cert-title-wrap {
            text-align: center;
            margin: 8px 0;
        }

        .cert-title {
            display: inline-block;
            background: #7D5A1E;
            color: #fff;
            font-size: 13.5pt;
            font-weight: bold;
            letter-spacing: 4px;
            text-transform: uppercase;
            padding: 5px 32px;
        }

        .body-text {
            line-height: 2;
            font-size: 11.5pt;
            margin-top: 14px;
        }

        .body-text p {
            margin-bottom: 2px;
        }

        .line-field {
            display: inline-block;
            min-width: 200px;
            border-bottom: 1px solid #333;
            margin: 0 3px;
            vertical-align: bottom;
        }

        .line-field-sm { min-width: 70px; }
        .line-field-lg { min-width: 260px; }
        .line-field-xl { min-width: 320px; }

        .sig-right {
            width: 260px;
            margin-left: auto;
            margin-right: 24px;
            text-align: center;
            margin-top: 26px;
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
            border-top: 2px solid #7D5A1E;
            text-align: center;
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 2px;
            padding-top: 4px;
            color: #7D5A1E;
        }

        .issuance {
            margin-top: 22px;
            font-size: 11.5pt;
            line-height: 2;
        }

        .sig-bottom {
            margin-top: 34px;
            width: 260px;
            margin-left: auto;
            margin-right: 24px;
            text-align: center;
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

    $iglesiaNombre = $iglesiaConfig?->nombre ?? 'Capacitaciones';

    // USAR RUTAS FÍSICAS COMO EN BAUTISMO
    $logoIglesiaPath = $resolvePublicFilePath($iglesiaConfig?->path_logo);
    $logoIglesiaDerechaPath = $resolvePublicFilePath($iglesiaConfig?->path_logo_derecha) ?: $logoIglesiaPath;

    // Si tu firma está en instructor y existe ese campo, descomentá/adaptá
    // $firmaPath = $resolvePublicFilePath($curso?->instructor?->path_firma_principal);
    $firmaPath = null;

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
@endphp

<body>
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
                <div class="diocese-name">Sistema de Gestión de Capacitaciones</div>
                <div class="header-address">Constancia formal de aprobación de curso</div>
            </div>

            <div class="header-right-cell">
                @if ($logoIglesiaDerechaPath && file_exists($logoIglesiaDerechaPath))
                    <img src="{{ $logoIglesiaDerechaPath }}" alt="Logo">
                @endif
            </div>
        </div>

        <div class="header-divider"></div>

        <hr class="hr-accent">
        <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
        <hr class="hr-accent">

        <div class="cert-title-wrap">
            <span class="cert-title">CERTIFICADO DE APROBACIÓN</span>
        </div>

        <hr class="hr-accent">
        <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
        <hr class="hr-accent">

        <div class="body-text">
            <p>El presente documento certifica que:</p>

            <p>
                <span class="line-field line-field-xl">{{ $persona?->nombre_completo ?? 'N/A' }}</span>
            </p>

            <p>
                con número de identidad
                <span class="line-field line-field-lg">{{ $persona?->dni ?? 'N/A' }}</span>
            </p>

            <p>ha aprobado satisfactoriamente el curso denominado:</p>

            <p>
                <span class="line-field line-field-xl">{{ $curso?->nombre ?? 'N/A' }}</span>
            </p>

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

            <p>
                Instructor responsable:
                <span class="line-field line-field-xl">{{ $instructor?->nombre_completo ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="sig-right">
            @if ($instructor?->nombre_completo)
                <p class="sig-name">{{ $instructor->nombre_completo }}</p>
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
                <span class="line-field line-field-xl">{{ $encargado?->nombre_completo ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="sig-bottom">
            @if ($firmaPath && file_exists($firmaPath))
                <p style="text-align:center; margin-bottom: 2px;">
                    <img src="{{ $firmaPath }}" style="max-height:50px; max-width:180px;" alt="Firma">
                </p>
            @endif

            <div class="sig-line-accent">A U T O R I Z A C I Ó N</div>
        </div>
    </div>

</body>
</html>