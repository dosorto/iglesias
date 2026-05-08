<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Constancia de Celebración de Matrimonio</title>
    @php $isLandscape = (($iglesiaConfig?->orientacion_certificado_matrimonio ?? $iglesiaConfig?->orientacion_certificado) === 'landscape'); @endphp
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            margin: 8px;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #1a1a1a;
            background: #fff;
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

        .page-wrapper {
            padding: 22px 38px 108px 38px;
            border: none;
            margin: 2px;
            position: relative;
            z-index: 1;
        }

        /* ── HEADER ── */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .header-logo-cell {
            display: table-cell;
            width: 72px;
            vertical-align: middle;
            text-align: center;
        }
        .header-logo-cell img {
            width: 58px;
            height: 58px;
            object-fit: contain;
        }
        .header-title-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .parish-name {
            font-size: 15.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.05;
        }
        .diocese-name {
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1px;
            color: #555;
        }
        .header-address {
            font-size: 9.5pt;
            margin-top: 3px;
            color: #222;
            letter-spacing: 0.4px;
        }
        .header-divider {
            border-top: 1px solid #6f99ad;
            margin: 7px 0 14px;
        }
        .header-right-cell {
            display: table-cell;
            width: 72px;
            vertical-align: middle;
            text-align: center;
        }
        .header-right-cell img {
            width: 58px;
            height: 58px;
            object-fit: contain;
        }

        .doc-title {
            text-align: center;
            font-size: 15.5pt;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.4px;
            margin-bottom: 12px;
        }

        /* ── BODY TEXT ── */
        .body-text {
            margin-top: 16px;
            line-height: 2.24;
            font-size: 10.6pt;
            width: 94%;
            margin-left: auto;
            margin-right: auto;
        }
        .body-text p {
            margin-bottom: 3px;
        }
        .line-field {
            display: inline-block;
            min-width: 220px;
            border-bottom: 1px solid #333;
            margin: 0 3px;
            vertical-align: bottom;
        }
        .line-field-sm { min-width: 70px; }
        .line-field-lg { min-width: 280px; }
        .line-field-xl { min-width: 360px; }
        .section-label { font-weight: bold; }

        /* ── SIGNATURES SECTION ── */
        .sig-section {
            margin-top: 30px;
            page-break-inside: avoid;
            width: 94%;
            margin-left: auto;
            margin-right: auto;
        }
        .sig-row {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        .sig-cell {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 12px;
        }
        .sig-line {
            display: block;
            width: 65%;
            margin: 0 auto 4px;
            border-top: 1px solid #333;
        }
        .sig-line-top {
            margin-top: 26px;
        }
        .sig-label {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #444;
        }
        .sig-name {
            font-size: 9.4pt;
            margin-top: 1px;
        }

        /* ── PRIEST + SEAL ── */
        .priest-section {
            margin-top: 100px;
            display: table;
            width: 100%;
            page-break-inside: avoid;
            width: 94%;
            margin-left: auto;
            margin-right: auto;
        }
        .seal-cell {
            display: table-cell;
            width: 50%;
            vertical-align: bottom;
        }
        .seal-box {
            width: 78px;
            height: 78px;
            border: 2px dashed #999;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .seal-text {
            text-align: center;
            font-size: 7.5pt;
            color: #999;
        }
        .priest-cell {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding-bottom: 2px;
        }
        .priest-sig-line {
            display: block;
            width: 66%;
            margin: 0 auto 4px;
            border-top: 2px solid #7D5A1E;
        }
        .priest-label {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #7D5A1E;
        }
        .priest-name {
            font-size: 9.4pt;
            margin-top: 1px;
        }

        /* ── ISSUANCE ── */
        .issuance {
            margin-top: 18px;
            font-size: 10.2pt;
            line-height: 1.95;
            page-break-inside: avoid;
            width: 94%;
            margin-left: auto;
            margin-right: auto;
        }

        .signature-image-wrap {
            height: 42px;
            margin-bottom: 4px;
            text-align: center;
        }

        .signature-image {
            max-height: 42px;
            max-width: 180px;
        }

        .note-marginal {
            margin-top: 10px;
            font-size: 8.8pt;
            font-style: italic;
            color: #666;
        }


        body.is-landscape .page-wrapper { padding: 14px 24px 18px; }
        body.is-landscape .header { margin-bottom: 5px; }
        body.is-landscape .header-divider { margin: 4px 0 6px; }
        body.is-landscape .cert-title-wrap { margin: 5px 0; }
        body.is-landscape .cert-title { font-size: 10.8pt; padding: 3px 18px; letter-spacing: 2px; }
        body.is-landscape .body-text {
            margin-top: 10px;
            line-height: 1.5;
            font-size: 9.3pt;
            width: 96%;
        }
        body.is-landscape .body-text p { margin-bottom: 1px; }
        body.is-landscape .line-field { min-width: 170px; }
        body.is-landscape .line-field-sm { min-width: 52px; }
        body.is-landscape .line-field-lg { min-width: 220px; }
        body.is-landscape .line-field-xl { min-width: 280px; }
        body.is-landscape .priest-section {
            margin-top: 28px;
            width: 96%;
        }
        body.is-landscape .seal-box {
            width: 64px;
            height: 64px;
        }
        body.is-landscape .priest-label,
        body.is-landscape .sig-label { font-size: 8pt; }
        body.is-landscape .priest-name,
        body.is-landscape .sig-name { font-size: 8.7pt; }
        body.is-landscape .issuance {
            margin-top: 10px;
            font-size: 9pt;
            line-height: 1.45;
            width: 96%;
        }
        body.is-landscape .note-marginal { margin-top: 7px; font-size: 8.2pt; }
        body.is-landscape .signature-image-wrap { height: 34px; margin-bottom: 3px; }
        body.is-landscape .signature-image { max-height: 34px; }
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

    $logoIglesiaPath = $resolvePublicFilePath($iglesiaConfig?->path_logo);
    $logoIglesiaDerechaPath = $resolvePublicFilePath($iglesiaConfig?->path_logo_derecha) ?: $logoIglesiaPath;
    $certBgPath = $resolvePublicFilePath($plantillaCertificadoPath ?? ($iglesiaConfig?->path_certificado_matrimonio ?: $iglesiaConfig?->path_certificado_bautismo));

    $esposo   = $matrimonio->esposo?->persona;
    $esposa   = $matrimonio->esposa?->persona;
    $testigo1 = $matrimonio->testigo1?->persona;
    $testigo2 = $matrimonio->testigo2?->persona;
    $encargado= $matrimonio->encargado?->feligres?->persona;
    $encargadoModel = $matrimonio->encargado;
    $firmaPath = $resolvePublicFilePath($encargadoModel?->path_firma_principal);
    $firmaEncargadoNombre = trim((string) ($encargado?->nombre_completo ?? ''));

    $iglesiaNombreHeader = $matrimonio->iglesia?->nombre ?? $iglesiaConfig?->nombre ?? '';
    $headerDiocesis = $iglesiaConfig?->header_diocesis ?: '';
    $headerLugar = $iglesiaConfig?->direccion ?: '';

    $mesesEs = [
        1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',
        5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',
        9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre',
    ];

    $fm = $matrimonio->fecha_matrimonio;
    $diaM = $fm?->day ?? '__';
    $mesM = $fm ? $mesesEs[$fm->month] : '__________';
    $anoM = $fm ? substr((string)$fm->year, 2) : '____';

    $fe = $matrimonio->fecha_expedicion;
    $diaE = $fe?->day ?? '__';
    $mesE = $fe ? $mesesEs[$fe->month] : '__________';
    $anoE = $fe ? substr((string)$fe->year, 2) : '____';

    $lugarExp = trim((string) ($iglesiaConfig?->direccion ?? ''));
    if ($lugarExp === '') {
        $lugarExp = trim((string) ($matrimonio->iglesia?->direccion ?? ''));
    }
    if ($lugarExp === '') {
        $lugarExp = trim((string) ($matrimonio->lugar_expedicion ?? ''));
    }
    if ($lugarExp === '') {
        $lugarExp = 'Monjaras, Marcovia, Choluteca, Honduras C. A.';
    }

    $esposoNombre  = $esposo?->nombre_completo  ?? '______________________________';
    $esposaNombre  = $esposa?->nombre_completo  ?? '______________________________';
    $sacerdote     = $encargado?->nombre_completo ?? '______________________________';
    $testigo1Nombre= $testigo1?->nombre_completo ?? '______________________________';
    $testigo2Nombre= $testigo2?->nombre_completo ?? '______________________________';
    $codigoVerificacion = $codigoVerificacion ?? '';
    $urlVerificacion = $urlVerificacion ?? '';
    $qrDataUri = $qrDataUri ?? null;
@endphp
<body class="{{ $isLandscape ? 'is-landscape' : '' }}" @if($certBgPath && file_exists($certBgPath)) style="background-image: url('{{ $certBgPath }}'); background-size: cover; background-position: center; background-repeat: no-repeat;" @endif>
@if ($logoIglesiaPath)
    <div class="watermark-logo">
        <img src="{{ $logoIglesiaPath }}" alt="Marca de agua">
    </div>
@endif
<div class="page-wrapper">

    {{-- ===== HEADER ===== --}}
    <div class="header">
        <div class="header-logo-cell">
            @if ($logoIglesiaPath)
                <img src="{{ $logoIglesiaPath }}" alt="Logo">
            @endif
        </div>
        <div class="header-title-cell">
            <div class="parish-name">{{ $iglesiaNombreHeader }}</div>
            <div class="diocese-name">{{ $headerDiocesis }}</div>
            <div class="header-address">{{ $headerLugar }}</div>
        </div>
        <div class="header-right-cell">
            @if ($logoIglesiaDerechaPath)
                <img src="{{ $logoIglesiaDerechaPath }}" alt="Logo">
            @endif
        </div>
    </div>

    <div class="header-divider"></div>
    <div class="doc-title">CONSTANCIA DE CELEBRACI&Oacute;N DE MATRIMONIO</div>

    {{-- ===== BODY ===== --}}
    <div class="body-text">
        <p>
            En la parroquia de
            <span class="line-field line-field-xl">{{ $iglesiaNombreHeader }}</span>
        </p>

        <p>
            el d&iacute;a
            <span class="line-field line-field-sm">{{ $diaM }}</span>
            del mes de
            <span class="line-field">{{ $mesM }}</span>
            del a&ntilde;o
            <span class="line-field line-field-sm">{{ $anoM }}</span>
        </p>

        <p>
            <span class="line-field line-field-xl">{{ $esposoNombre }}</span>
        </p>

        <p>
            y
            <span class="line-field line-field-xl">{{ $esposaNombre }}</span>
        </p>

        <p>
            celebraron su matrimonio en presencia del Padre
            <span class="line-field line-field-xl">{{ $sacerdote }}</span>
        </p>

        <p>debidamente autorizado. </p>
    </div>

    {{-- ===== TESTIGOS ===== --}}
    <div class="body-text" style="margin-top: 20px;">
        <p><span class="section-label">Actuaron como testigos:</span></p>
        <p>
            <span class="line-field line-field-lg">{{ $testigo1Nombre }}</span>
        </p>
        <p>
            y
            <span class="line-field line-field-lg">{{ $testigo2Nombre }}</span>
        </p>
    </div>

    {{-- ===== SELLO Y SACERDOTE ===== --}}
    <div class="priest-section">
        <div class="seal-cell">
            <div class="seal-box">
                <p class="seal-text">Sello de la<br>Parroquia</p>
            </div>
        </div>
        <div class="priest-cell">
            @if ($firmaPath && file_exists($firmaPath))
                <div class="signature-image-wrap">
                    <img src="{{ $firmaPath }}" alt="Firma del sacerdote" class="signature-image">
                </div>
            @endif
            <span class="priest-sig-line"></span>
            <div class="priest-name">{{ $firmaEncargadoNombre }}</div>
        </div>
    </div>

    {{-- ===== EXPEDICIÓN ===== --}}
    @if ($lugarExp || $fe)
    <div class="issuance">
        <p>
            Se expide en
            <span class="line-field line-field-lg">{{ $lugarExp ?: '______________________________' }}</span>,
            el d&iacute;a
            <span class="line-field line-field-sm">{{ $diaE }}</span>
            del mes de
            <span class="line-field line-field-lg">{{ $mesE }}</span>
            del a&ntilde;o dos mil
            <span class="line-field line-field-sm">{{ $anoE }}</span>
        </p>
    </div>
    @endif

    {{-- Nota marginal --}}
    @if ($matrimonio->nota_marginal)
    <div class="note-marginal">
        <span style="font-weight:bold; font-style:normal; color:#555;">Nota marginal:</span>
        {{ $matrimonio->nota_marginal }}
    </div>
    @endif


</div>
</body>
</html>
