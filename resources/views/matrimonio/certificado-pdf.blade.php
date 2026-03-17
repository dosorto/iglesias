<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Constancia de Celebración de Matrimonio</title>
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

        .page-wrapper {
            padding: 18px 18px;
            border: 4px double #7D5A1E;
            margin: 2px;
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

        /* ── DECORATIVE ── */
        .hr-accent {
            border: none;
            border-top: 1px solid #7D5A1E;
            margin: 2px 0;
        }
        .ornament {
            text-align: center;
            color: #7D5A1E;
            font-size: 10pt;
            letter-spacing: 8px;
            margin: 1px 0;
        }

        /* ── CERT TITLE BANNER ── */
        .cert-title-wrap {
            text-align: center;
            margin: 8px 0;
        }
        .cert-title {
            display: inline-block;
            background: #7D5A1E;
            color: #fff;
            font-size: 11.5pt;
            font-weight: bold;
            letter-spacing: 3px;
            text-transform: uppercase;
            padding: 4px 22px;
        }

        /* ── BODY TEXT ── */
        .body-text {
            margin-top: 16px;
            line-height: 2.08;
            font-size: 10.6pt;
        }
        .body-text p {
            margin-bottom: 2px;
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
            margin-top: 28px;
            display: table;
            width: 100%;
            page-break-inside: avoid;
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
            margin-top: 24px;
            font-size: 10.2pt;
            line-height: 1.75;
            page-break-inside: avoid;
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
            margin-top: 16px;
            font-size: 8.8pt;
            font-style: italic;
            color: #666;
        }
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

    $esposo   = $matrimonio->esposo?->persona;
    $esposa   = $matrimonio->esposa?->persona;
    $testigo1 = $matrimonio->testigo1?->persona;
    $testigo2 = $matrimonio->testigo2?->persona;
    $encargado= $matrimonio->encargado?->feligres?->persona;
    $encargadoModel = $matrimonio->encargado;
    $firmaPath = $resolvePublicFilePath($encargadoModel?->path_firma_principal);

    $iglesiaNombreHeader = $iglesiaConfig?->nombre ?? $matrimonio->iglesia?->nombre ?? 'Parroquia';

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

    $lugarExp = $matrimonio->lugar_expedicion ?? '';

    $esposoNombre  = $esposo?->nombre_completo  ?? '______________________________';
    $esposaNombre  = $esposa?->nombre_completo  ?? '______________________________';
    $sacerdote     = $encargado?->nombre_completo ?? '______________________________';
    $testigo1Nombre= $testigo1?->nombre_completo ?? '______________________________';
    $testigo2Nombre= $testigo2?->nombre_completo ?? '______________________________';
@endphp
<body>
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
            <div class="diocese-name">Di&oacute;cesis de Choluteca</div>
        </div>
        <div class="header-right-cell">
            @if ($logoIglesiaPath)
                <img src="{{ $logoIglesiaPath }}" alt="Logo">
            @endif
        </div>
    </div>

    <hr class="hr-accent">
    <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
    <hr class="hr-accent">

    <div class="cert-title-wrap">
        <span class="cert-title">CONSTANCIA DE CELEBRACI&Oacute;N DE MATRIMONIO</span>
    </div>

    <hr class="hr-accent">
    <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
    <hr class="hr-accent">

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

        <p>debidamente autorizado.</p>
    </div>

    {{-- ===== TESTIGOS ===== --}}
    <div class="body-text" style="margin-top: 10px;">
        <p><span class="section-label">Actuaron como testigos:</span></p>
        <p>
            <span class="line-field line-field-lg">{{ $testigo1Nombre }}</span>
        </p>
        <p>
            y
            <span class="line-field line-field-lg">{{ $testigo2Nombre }}</span>
        </p>
    </div>

    {{-- ===== FIRMA TESTIGOS ===== --}}
    <div class="sig-section">
        <p style="font-size:11pt; font-weight:bold; margin-bottom:4px;">Firma de los testigos:</p>
        <div class="sig-row">
            <div class="sig-cell">
                <span class="sig-line sig-line-top"></span>
                <span class="sig-label">Testigo 1</span>
                <p class="sig-name">{{ $testigo1Nombre }}</p>
            </div>
            <div class="sig-cell">
                <span class="sig-line sig-line-top"></span>
                <span class="sig-label">Testigo 2</span>
                <p class="sig-name">{{ $testigo2Nombre }}</p>
            </div>
        </div>
    </div>

    {{-- ===== FIRMA ESPOSOS ===== --}}
    <div class="sig-section">
        <p style="font-size:11pt; font-weight:bold; margin-bottom:4px;">Firma de los nuevos esposos:</p>
        <div class="sig-row">
            <div class="sig-cell">
                <span class="sig-line sig-line-top"></span>
                <span class="sig-label">Esposo</span>
                <p class="sig-name">{{ $esposoNombre }}</p>
            </div>
            <div class="sig-cell">
                <span class="sig-line sig-line-top"></span>
                <span class="sig-label">Esposa</span>
                <p class="sig-name">{{ $esposaNombre }}</p>
            </div>
        </div>
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
            <p style="font-size:9.4pt; margin-bottom: 14px;">Firma del Sacerdote celebrante:</p>
            <span class="priest-sig-line"></span>
            <p class="priest-label">Sacerdote Celebrante</p>
            <p class="priest-name">{{ $sacerdote }}</p>
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
            del a&ntilde;o
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
