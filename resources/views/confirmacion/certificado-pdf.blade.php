<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificación de Confirmación</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #1a1a1a;
            background: #fff;
        }

        .watermark-logo {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.075;
            z-index: 0;
        }

        .watermark-logo img {
            width: 390px;
            height: auto;
            object-fit: contain;
        }

        .page-wrapper {
            padding: 26px 44px 30px;
            border: none;
            margin: 8px;
            position: relative;
            z-index: 2;
            background: transparent;
        }

        /* ── HEADER ── */
        .header { display: table; width: 100%; margin-bottom: 8px; }
        .header-logo-cell { display: table-cell; width: 88px; vertical-align: top; text-align: left; padding-top: 2px; }
        .header-logo-cell img { width: 80px; height: 80px; object-fit: contain; }
        .header-title-cell { display: table-cell; vertical-align: top; text-align: center; }
        .header-right-cell { display: table-cell; width: 88px; vertical-align: top; text-align: right; padding-top: 2px; }
        .header-right-cell img { width: 80px; height: 80px; object-fit: contain; }

        .parish-name { font-size: 19pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.7px; line-height: 1.1; }
        .diocese-name { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin-top: 3px; }
        .header-address { font-size: 12pt; font-weight: bold; margin-top: 3px; }

        .header-divider { border: none; border-top: 1px solid #6f99ad; margin: 7px 0 14px; }

        /* ── TÍTULO ── */
        .doc-title {
            text-align: center;
            font-size: 15.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 20px;
        }

        /* ── CUERPO ── */
        .cert-intro { font-size: 12pt; margin-bottom: 12px; line-height: 1.5; }

        .name-display {
            text-align: center;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 22px;
        }

        .cert-block { font-size: 12pt; line-height: 1.8; }
        .cert-block p { margin-bottom: 4px; }

        .nota-marginal { font-size: 10.5pt; margin-top: 18px; line-height: 1.8; color: #444; }
        .issuance { font-size: 12pt; line-height: 1.5; margin-top: 28px; }

        /* ── FIRMAS ── */
        .bottom-signatures {
            display: table;
            width: 100%;
            margin-top: 48px;
            page-break-inside: avoid;
        }

        .seal-cell {
            display: table-cell;
            width: 50%;
            vertical-align: bottom;
            text-align: center;
        }

        .signature-cell {
            display: table-cell;
            width: 50%;
            vertical-align: bottom;
            text-align: center;
        }

        .signature-block { text-align: center; }
        .sig-img {
            max-height: 65px;
            max-width: 210px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        .sig-line {
            display: inline-block;
            width: 260px;
            border-top: 2px solid #7D5A1E;
            padding-top: 4px;
        }
        .sig-name { font-size: 11pt; font-weight: bold; color: #1a1a1a; margin-top: 4px; }
    </style>
</head>
@php
    $resolvePublicFilePath = function (?string $path): ?string {
        if (! $path) return null;
        $normalized = ltrim(trim((string) parse_url($path, PHP_URL_PATH) ?: $path), '/\\');
        if ($normalized === '') return null;
        $candidate = str_starts_with($normalized, 'storage/')
            ? public_path($normalized)
            : public_path('storage/' . $normalized);
        return is_file($candidate) ? $candidate : null;
    };

    $logoIglesiaPath        = $resolvePublicFilePath($iglesiaConfig?->path_logo);
    $logoIglesiaDerechaPath = $resolvePublicFilePath($iglesiaConfig?->path_logo_derecha) ?: $logoIglesiaPath;
    $certBgPath             = $resolvePublicFilePath($plantillaCertificadoPath ?? ($iglesiaConfig?->path_certificado_confirmacion ?: $iglesiaConfig?->path_certificado_bautismo));

    if (! $logoIglesiaPath) {
        $logoEstaticoPath = public_path('image/Logo_guest.png');
        if (is_file($logoEstaticoPath)) $logoIglesiaPath = $logoEstaticoPath;
    }
    if (! $logoIglesiaDerechaPath) $logoIglesiaDerechaPath = $logoIglesiaPath;

    $confirmado     = $confirmacion->feligres?->persona;
    $padrino        = $confirmacion->padrino?->persona;
    $madrina        = $confirmacion->madrina?->persona;
    $ministro       = $confirmacion->ministro?->persona;
    $encargado      = $confirmacion->encargado?->feligres?->persona;

    $iglesiaNombre   = $iglesiaConfig?->nombre ?? $confirmacion->iglesia?->nombre ?? '';
    $headerDiocesis  = $iglesiaConfig?->header_diocesis ?: 'Diócesis de Choluteca';
    $headerLugar     = $iglesiaConfig?->direccion ?: '';

    $ministroNombre  = mb_strtoupper($ministro?->nombre_completo  ?? '', 'UTF-8');
    $encargadoNombre = mb_strtoupper($encargado?->nombre_completo ?? '', 'UTF-8');
    $padrinosStr     = mb_strtoupper(
        collect([$padrino?->nombre_completo, $madrina?->nombre_completo])->filter()->implode(' y '),
        'UTF-8'
    );

    $mesesEs = [
        1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',
        7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre',
    ];

    $fc      = $confirmacion->fecha_confirmacion;
    $diaConf = $fc ? $fc->day             : '';
    $mesConf = $fc ? $mesesEs[$fc->month] : '';
    $anoConf = $fc ? $fc->year            : '';

    $fe     = $confirmacion->fecha_expedicion ?: now();
    $diaExp = $fe ? $fe->day             : '';
    $mesExp = $fe ? $mesesEs[$fe->month] : '';
    $anoExp = $fe ? $fe->year            : '';

    $lugarConf = $confirmacion->lugar_confirmacion ?? '';
    $lugarExp  = trim((string) ($iglesiaConfig?->direccion ?? ''));
    if ($lugarExp === '') $lugarExp = trim((string) ($confirmacion->iglesia?->direccion ?? ''));
    if ($lugarExp === '') $lugarExp = trim((string) ($confirmacion->lugar_expedicion ?? ''));
    if ($lugarExp === '') $lugarExp = 'Monjaras, Marcovia, Choluteca, Honduras C. A.';

    $notaMarginal = $confirmacion->nota_marginal ?? '';

    $firmaPath = null;
    if ($confirmacion->encargado?->path_firma_principal) {
        $firmaPath = $resolvePublicFilePath($confirmacion->encargado->path_firma_principal);
    }
@endphp
<body @if($certBgPath && file_exists($certBgPath)) style="background-image: url('{{ $certBgPath }}'); background-size: cover; background-position: center; background-repeat: no-repeat;" @endif>
@if ($logoIglesiaPath)
    <div class="watermark-logo">
        <img src="{{ $logoIglesiaPath }}" alt="Marca de agua">
    </div>
@endif
<div class="page-wrapper">

    {{-- HEADER --}}
    <div class="header">
        <div class="header-logo-cell">
            @if ($logoIglesiaPath)<img src="{{ $logoIglesiaPath }}" alt="Logo">@endif
        </div>
        <div class="header-title-cell">
            <div class="parish-name">{{ mb_strtoupper($iglesiaNombre, 'UTF-8') }}</div>
            <div class="diocese-name">{{ $headerDiocesis }}</div>
            @if ($headerLugar)<div class="header-address">{{ $headerLugar }}</div>@endif
        </div>
        <div class="header-right-cell">
            @if ($logoIglesiaDerechaPath)<img src="{{ $logoIglesiaDerechaPath }}" alt="Logo">@endif
        </div>
    </div>

    <hr class="header-divider">

    {{-- TÍTULO --}}
    <div class="doc-title">CERTIFICACI&Oacute;N DE CONFIRMACI&Oacute;N</div>

    {{-- CUERPO --}}
    <p class="cert-intro">El infrascrito encargado del archivo de esta parroquia certifica que</p>

    <div class="name-display">
        {{ mb_strtoupper($confirmado?->nombre_completo ?? '', 'UTF-8') }}
    </div>

    <div class="cert-block">
        <p>
            Fue confirmado (a) el día {{ $diaConf }}
            del mes {{ $mesConf }}
            año {{ $anoConf }}
        </p>
        <p>En {{ $lugarConf }}</p>
        <p>Por Mons. {{ $ministroNombre }}</p>
        <p>Siendo sus padrinos: {{ $padrinosStr }}</p>
    </div>

    @if ($notaMarginal)
        <div class="nota-marginal">
            <strong>NOTA MARGINAL:</strong> {{ $notaMarginal }}
        </div>
    @endif

    <div class="issuance">
        <p>
            Dado en {{ $lugarExp }}
            a los {{ $diaExp }}
            dias del mes de {{ $mesExp }}
            del año {{ $anoExp }}
        </p>
    </div>

    {{-- FIRMAS --}}
    <div class="bottom-signatures">
        <div class="seal-cell"></div>
        <div class="signature-cell">
            <div class="signature-block">
                @if ($firmaPath)
                    <img src="{{ $firmaPath }}" alt="Firma" class="sig-img">
                @else
                    <div style="height: 65px;"></div>
                @endif
                <div><span class="sig-line"></span></div>
                <div class="sig-name">{{ $encargadoNombre }}</div>
            </div>
        </div>
    </div>

</div>
</body>
</html>
