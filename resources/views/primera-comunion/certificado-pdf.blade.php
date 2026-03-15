<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Certificación de Primera Comunión</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #1a1a1a;
            background: #fff;
        }

        /* ── PAGE WRAPPER WITH FRAME ── */
        .page-wrapper {
            padding: 26px 36px;
            border: 4px double #7D5A1E;
            margin: 10px;
        }

        /* ── HEADER ── */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .header-logo-cell {
            display: table-cell;
            width: 85px;
            vertical-align: middle;
            text-align: center;
        }
        .header-logo-cell img {
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
        .header-right-cell {
            display: table-cell;
            width: 85px;
            vertical-align: middle;
            text-align: center;
        }
        .header-right-cell img {
            width: 75px;
            height: 75px;
            object-fit: contain;
        }

        /* ── DECORATIVE ── */
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

        /* ── CERT TITLE BANNER ── */
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

        /* ── BODY ── */
        .body-text {
            font-size: 11.5pt;
            line-height: 2.2;
        }
        .body-text p {
            margin-bottom: 6px;
        }
        .underline {
            display: inline-block;
            border-bottom: 1px solid #333;
            vertical-align: bottom;
        }
        .name-line {
            display: block;
            width: 100%;
            border-bottom: 2px solid #7D5A1E;
            margin: 8px 0 12px;
            min-height: 22px;
            font-size: 13pt;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1a1a1a;
        }

        /* ── FIRMA CENTRAL ── */
        .sig-center {
            margin-top: 50px;
            text-align: center;
        }
        .sig-center .sig-line {
            display: inline-block;
            width: 260px;
            border-top: 2px solid #7D5A1E;
            padding-top: 6px;
            font-size: 11pt;
            font-weight: bold;
            color: #7D5A1E;
        }
        .sig-center .sig-title {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 4px;
            color: #7D5A1E;
            letter-spacing: 1px;
        }

        /* ── FIRMAS PIE ── */
        .sig-footer {
            display: table;
            width: 100%;
            margin-top: 40px;
        }
        .sig-footer-cell {
            display: table-cell;
            width: 50%;
            vertical-align: bottom;
        }
        .sig-footer-cell.right {
            text-align: right;
        }
        .sig-block {
            display: inline-block;
            text-align: center;
            width: 200px;
        }
        .sig-block img {
            max-height: 50px;
            max-width: 180px;
            display: block;
            margin: 0 auto 2px;
        }
        .sig-block .sig-line {
            display: block;
            border-top: 2px solid #7D5A1E;
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 1px;
            padding-top: 4px;
            text-align: center;
            color: #7D5A1E;
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

    $certBgPath = $resolvePublicFilePath($iglesiaConfig?->path_certificado_bautismo);
    $logoIglesiaPath = $resolvePublicFilePath($iglesiaConfig?->path_logo);
@endphp
<body @if($certBgPath && file_exists($certBgPath)) style="background-image: url('{{ $certBgPath }}'); background-size: cover; background-position: center; background-repeat: no-repeat;" @endif>

<div class="page-wrapper">

    @php
        $iglesia       = $primeraComunion->iglesia;
        $comulgante    = $primeraComunion->feligres?->persona;
        $catequista    = $primeraComunion->catequista?->persona;
        $ministro      = $primeraComunion->ministro?->persona;
        $parroco       = $primeraComunion->parroco?->persona;
        $parrocoModel  = $primeraComunion->parroco;
        $iglesiaNombre = $iglesiaConfig?->nombre ?? $iglesia?->nombre ?? '';

        // Firma del párroco
        $firmaParrocoPath = $resolvePublicFilePath($parrocoModel?->path_firma_principal);

        // Firma del encargado
        $firmaEncargadoPath = $resolvePublicFilePath($encargado->path_firma_principal ?? null);

        $mesesEs = [
            1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',
            5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',
            9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre'
        ];

        $fechaComunion = $primeraComunion->fecha_primera_comunion;
        $diaComunion   = $fechaComunion ? $fechaComunion->day             : '___________';
        $mesComunion   = $fechaComunion ? $mesesEs[$fechaComunion->month] : '___________________________';
        $anoComunion   = $fechaComunion ? $fechaComunion->year            : '____________';

        $fechaExp  = $primeraComunion->fecha_expedicion;
        $diaExp    = $fechaExp ? $fechaExp->day             : '_______________';
        $mesExp    = $fechaExp ? $mesesEs[$fechaExp->month] : '______________________';
        $anoExp    = $fechaExp ? $fechaExp->year            : '____________';

        $lugarCelebracion = $primeraComunion->lugar_celebracion ?: '_______________________________________________';
        $lugarExp         = $primeraComunion->lugar_expedicion  ?: '_________________________________';
        $notaMarginal     = $primeraComunion->nota_marginal     ?? '';

        $parrocoNombre    = $parroco?->nombre_completo ?? ($iglesia?->parroco_nombre ?? '');
        $comulganteNombre = $comulgante?->nombre_completo ?? '';
    @endphp

    {{-- ===== HEADER ===== --}}
    <div class="header">

        {{-- Logo iglesia (izquierda) --}}
        <div class="header-logo-cell">
            @if ($logoIglesiaPath && file_exists($logoIglesiaPath))
                <img src="{{ $logoIglesiaPath }}" alt="Logo Parroquia">
            @endif
        </div>

        {{-- Nombre parroquia y diócesis --}}
        <div class="header-title-cell">
            <div class="parish-name">{{ $iglesiaNombre ?: 'Parroquia' }}</div>
            <div class="diocese-name">Di&oacute;cesis de Choluteca</div>
        </div>

        {{-- Logo iglesia (derecha) --}}
        <div class="header-right-cell">
            @if ($logoIglesiaPath && file_exists($logoIglesiaPath))
                <img src="{{ $logoIglesiaPath }}" alt="Logo Parroquia">
            @endif
        </div>

    </div>

    <hr class="hr-accent">
    <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
    <hr class="hr-accent">

    <div class="cert-title-wrap">
        <span class="cert-title">CERTIFICACI&Oacute;N DE PRIMERA COMUNI&Oacute;N</span>
    </div>

    <hr class="hr-accent">
    <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
    <hr class="hr-accent">

    {{-- ===== CUERPO DEL CERTIFICADO ===== --}}
    <div class="body-text" style="margin-top: 20px;">

        <p>El infrascrito encargado del archivo de esta parroquia certifica que</p>

        {{-- Nombre del comulgante en línea completa --}}
        <span class="name-line">{{ $comulganteNombre }}</span>

        <p>
            Hizo su <strong>PRIMERA COMUNIÓN</strong> el día
            <span class="underline" style="min-width:110px; text-align:center;">{{ $diaComunion }}</span>
            del mes
            <span class="underline" style="min-width:180px; text-align:center;">{{ $mesComunion }}</span>
        </p>

        <p>
            año
            <span class="underline" style="min-width:140px; text-align:center;">{{ $anoComunion }}</span>
        </p>

        <p>
            En
            <span class="underline" style="min-width:400px;">{{ $lugarCelebracion }}</span>
        </p>

        @if ($notaMarginal)
        <p style="margin-top:10px;">
            <strong>NOTA MARGINAL:</strong>
            <span class="underline" style="min-width:300px;">{{ $notaMarginal }}</span>
        </p>
        @endif

    </div>

    {{-- ===== DADO EN ===== --}}
    <div class="body-text" style="margin-top: 40px;">
        <p>
            Dado en
            <span class="underline" style="min-width:240px;">{{ $lugarExp }}</span>
            a los
            <span class="underline" style="min-width:160px; text-align:center;">{{ $diaExp }}</span>
            del mes de
        </p>
        <p>
            <span class="underline" style="min-width:260px; text-align:center;">{{ $mesExp }}</span>
            año
            <span class="underline" style="min-width:140px; text-align:center;">{{ $anoExp }}</span>
        </p>
    </div>

    {{-- ===== FIRMA DEL PÁRROCO (centrada) ===== --}}
    <div class="sig-center">
        @if ($firmaParrocoPath && file_exists($firmaParrocoPath))
            <div style="text-align:center; margin-bottom:4px;">
                <img src="{{ $firmaParrocoPath }}" style="max-height:60px; max-width:200px;">
            </div>
        @endif
        <div class="sig-line">{{ $parrocoNombre }}</div>
        <div class="sig-title">P &Aacute; R R O C O</div>
    </div>

    {{-- ===== FIRMAS PIE: encargado izq | párroco der ===== --}}
    @if ($firmaEncargadoPath || $firmaParrocoPath)
    <div class="sig-footer">

        <div class="sig-footer-cell">
            <div class="sig-block">
                @if ($firmaEncargadoPath && file_exists($firmaEncargadoPath))
                    <img src="{{ $firmaEncargadoPath }}" alt="Firma Encargado">
                @endif
                <span class="sig-line">E N C A R G A D O</span>
            </div>
        </div>

        <div class="sig-footer-cell right">
            <div class="sig-block">
                @if ($firmaParrocoPath && file_exists($firmaParrocoPath))
                    <img src="{{ $firmaParrocoPath }}" alt="Firma Párroco">
                @endif
                <span class="sig-line">C U R A &nbsp; P&Aacute;RROCO</span>
            </div>
        </div>

    </div>
    @endif

</div>
</body>
</html>