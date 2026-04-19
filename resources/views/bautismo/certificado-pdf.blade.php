<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Certificación de Bautismo</title>
    @php $isLandscape = (($iglesiaConfig?->orientacion_certificado_bautismo ?? $iglesiaConfig?->orientacion_certificado) === 'landscape'); @endphp
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
            opacity: 0.08;
            z-index: 0;
        }

        .watermark-logo img {
            width: 430px;
            height: auto;
            object-fit: contain;
        }

        .page-wrapper {
            padding: 26px 36px 28px;
            border: none;
            margin: 2px;
            position: relative;
            z-index: 1;
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

        /* ── BODY TEXT ── */
        .body-text {
            line-height: 2;
            font-size: 11.5pt;
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
        .section-label { font-weight: bold; }

        /* ── SIGNATURES ── */
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

        /* ── ISSUANCE ── */
        .issuance {
            margin-top: 22px;
            font-size: 11.5pt;
            line-height: 2;
        }
        .sello {
            font-size: 10pt;
            font-style: italic;
            margin-top: 4px;
            color: #666;
        }
        .footer-row {
            display: table;
            width: 100%;
            margin-top: 10px;
            page-break-inside: avoid;
        }
        .footer-seal,
        .footer-qr,
        .footer-signature {
            display: table-cell;
            vertical-align: bottom;
        }
        .footer-seal {
            width: 33%;
            text-align: left;
        }
        .footer-qr {
            width: 34%;
            text-align: center;
        }
        .footer-signature {
            width: 33%;
            text-align: right;
        }
        .sig-bottom {
            width: 260px;
            margin: 0 0 0 auto;
            text-align: center;
        }

        body.is-landscape .page-wrapper {
            padding: 14px 22px 20px;
            margin: 4px;
        }
        body.is-landscape .header {
            margin-bottom: 6px;
        }
        body.is-landscape .header-logo-cell,
        body.is-landscape .header-right-cell {
            width: 72px;
        }
        body.is-landscape .header-logo-cell img,
        body.is-landscape .header-right-cell img {
            width: 58px;
            height: 58px;
        }
        body.is-landscape .parish-name {
            font-size: 15pt;
            letter-spacing: 1px;
        }
        body.is-landscape .diocese-name {
            font-size: 10.5pt;
            margin-top: 1px;
        }
        body.is-landscape .header-address {
            font-size: 9pt;
            margin-top: 2px;
        }
        body.is-landscape .header-divider {
            margin: 4px 0 6px;
        }
        body.is-landscape .ornament {
            margin: 1px 0;
        }
        body.is-landscape .cert-title-wrap {
            margin: 5px 0;
        }
        body.is-landscape .cert-title {
            font-size: 11.5pt;
            padding: 3px 24px;
            letter-spacing: 3px;
        }
        body.is-landscape .body-text {
            margin-top: 8px;
            line-height: 1.35;
            font-size: 9.5pt;
        }
        body.is-landscape .body-text p {
            margin-bottom: 1px;
        }
        body.is-landscape .line-field {
            min-width: 150px;
        }
        body.is-landscape .line-field-sm {
            min-width: 52px;
        }
        body.is-landscape .line-field-lg {
            min-width: 200px;
        }
        body.is-landscape .line-field-xl {
            min-width: 250px;
        }
        body.is-landscape .sig-right {
            margin-top: 10px;
            margin-right: 10px;
        }
        body.is-landscape .issuance {
            margin-top: 8px;
            font-size: 9.5pt;
            line-height: 1.45;
        }
        body.is-landscape .footer-row {
            margin-top: 10px;
        }
        body.is-landscape .sig-bottom {
            width: 220px;
        }

        .qr-verify {
            position: fixed;
            left: 16px;
            bottom: 12px;
            display: block;
            margin-top: 0;
            text-align: center;
            font-size: 8pt;
            color: #555;
            line-height: 1;
            z-index: 2;
        }

        .qr-verify img {
            width: 54px;
            height: 54px;
            border: 1px solid #d1d5db;
            padding: 2px;
            background: #fff;
        }

        .qr-code {
            margin-top: 3px;
            letter-spacing: 0.4px;
        }

        body.is-landscape .qr-verify img {
            width: 54px;
            height: 54px;
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

    $certBgPath = $resolvePublicFilePath($plantillaCertificadoPath ?? $iglesiaConfig?->path_certificado_bautismo);
    $logoIglesiaPath = $resolvePublicFilePath($iglesiaConfig?->path_logo);
    $logoIglesiaDerechaPath = $resolvePublicFilePath($iglesiaConfig?->path_logo_derecha) ?: $logoIglesiaPath;
    $headerDiocesis = $iglesiaConfig?->header_diocesis ?: '';
    $headerLugar = $iglesiaConfig?->direccion ?: '';
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
            @if ($logoIglesiaPath && file_exists($logoIglesiaPath))
                <img src="{{ $logoIglesiaPath }}" alt="Logo">
            @endif
        </div>
        <div class="header-title-cell">
            <div class="parish-name">{{ $iglesiaConfig?->nombre ?? $bautismo->iglesia?->nombre ?? '' }}</div>
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

    <hr class="hr-accent">
    <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
    <hr class="hr-accent">

    <div class="cert-title-wrap">
        <span class="cert-title">CERTIFICACI&Oacute;N DE BAUTISMO</span>
    </div>

    <hr class="hr-accent">
    <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
    <hr class="hr-accent">

    {{-- ===== BODY ===== --}}
    @php
        $bautizado   = $bautismo->bautizado?->persona;
        $padre       = $bautismo->padre?->persona;
        $madre       = $bautismo->madre?->persona;
        $padrino     = $bautismo->padrino?->persona;
        $madrina     = $bautismo->madrina?->persona;
        $encargado   = $bautismo->encargado?->feligres?->persona;
        $encargadoModel = $bautismo->encargado;
        $firmaPath = $resolvePublicFilePath($encargadoModel?->path_firma_principal);
        $iglesiaNombre = $iglesiaConfig?->nombre ?? $bautismo->iglesia?->nombre ?? '';

        // Bautismo date parts
        $fechaBautismo = $bautismo->fecha_bautismo;
        $diasEs = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
        $mesesEs = [
            1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',
            5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',
            9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre'
        ];

        $diaBautismo   = $fechaBautismo ? $fechaBautismo->day : '';
        $mesBautismo   = $fechaBautismo ? $mesesEs[$fechaBautismo->month] : '';

        // Year: split into "novecientos ..."  or "dos mil ..."
        $anoBautismo   = $fechaBautismo ? $fechaBautismo->year : '';
        // Birth date
        $fechaNac      = $bautizado?->fecha_nacimiento;
        $diaNac        = $fechaNac ? $fechaNac->day : '';
        $mesNac        = $fechaNac ? $mesesEs[$fechaNac->month] : '';
        $anoNac        = $fechaNac ? $fechaNac->year : '';

        // Padrinos string
        $padrinosStr   = collect([$padrino?->nombre_completo, $madrina?->nombre_completo])
                            ->filter()->implode(' y ');

        // Expedición
        $fechaExp  = $bautismo->fecha_expedicion;
        $diaExp    = $fechaExp ? $fechaExp->day : '';
        $mesExp    = $fechaExp ? $mesesEs[$fechaExp->month] : '';
        $anoExpMil = $fechaExp ? ($fechaExp->year - 2000) : '';
        $lugarNac  = $bautismo->lugar_nacimiento ?? '';
        $lugarExp  = $bautismo->lugar_expedicion ?? '';
        $notaMarginal = $bautismo->nota_marginal ?? '';
    @endphp

    <div class="body-text" style="margin-top: 14px;">

        <p>
            El infrascrito, encargado del Archivo de la Parroquia de
            <span class="line-field line-field-lg">{{ $iglesiaNombre }}</span>
        </p>

        <p>
            <span class="section-label">CERTIFICA:</span>
            Que en el libro de Bautismo No.
            <span class="line-field line-field-sm">{{ $bautismo->libro_bautismo }}</span>
            , en la Página
            <span class="line-field line-field-sm">{{ $bautismo->folio }}</span>
            , bajo el No.
            <span class="line-field line-field-sm">{{ $bautismo->partida_numero }}</span>
        </p>

        <p>Se encuentra la partida que dice:</p>

        <p>
            En
            <span class="line-field">{{ $iglesiaNombre }}</span>
            a
            <span class="line-field line-field-sm">{{ $diaBautismo }}</span>
        </p>

        <p>
            de
            <span class="line-field">{{ $mesBautismo }}</span>
            del año
            @if($anoBautismo >= 2000)
                dos mil
                <span class="line-field line-field-sm">{{ $anoBautismo - 2000 ?: '' }}</span>
            @else
                mil novecientos
                <span class="line-field line-field-sm">{{ $anoBautismo ? ($anoBautismo - 1900) : '' }}</span>
            @endif
        </p>

        <p>
            Bauticé (el P.
            <span class="line-field line-field-lg">{{ $encargado?->nombre_completo }}</span>
            Bautizó) solemnemente a:
        </p>

        <p>
            <span class="line-field line-field-xl">{{ $bautizado?->nombre_completo }}</span>
        </p>

        <p>
            Que nació en
            <span class="line-field">{{ $lugarNac }}</span>
            , el
            <span class="line-field line-field-sm">{{ $diaNac }}</span>
        </p>

        <p>
            de
            <span class="line-field">{{ $mesNac }}</span>
            del
            @if($anoNac >= 2000)
                año dos mil <span class="line-field line-field-sm">{{ $anoNac - 2000 ?: '' }}</span>
            @elseif($anoNac)
                año mil novecientos <span class="line-field line-field-sm">{{ $anoNac - 1900 }}</span>
            @else
                <span class="line-field line-field-sm"></span>
            @endif
        </p>

        <p>
            Hijo de
            <span class="line-field line-field-xl">{{ $padre?->nombre_completo }}</span>
        </p>

        <p>
            y de
            <span class="line-field line-field-xl">{{ $madre?->nombre_completo }}</span>
        </p>

        <p>
            Padrinos:
            <span class="line-field line-field-xl">{{ $padrinosStr }}</span>
        </p>

    </div>

    {{-- ===== FIRMA CURA PÁRROCO ===== --}}
    <div class="sig-right">
        @if ($encargado?->nombre_completo)
            <p class="sig-name">{{ $encargado->nombre_completo }}</p>
        @endif
        <div class="sig-line-accent">C U R A &nbsp; P&Aacute;RROCO</div>
    </div>

    {{-- ===== NOTA MARGINAL ===== --}}
    <div class="body-text" style="margin-top: 16px;">
        <p>
            <span class="section-label">NOTA MARGINAL:</span>
            <span class="line-field line-field-xl">{{ $notaMarginal }}</span>
        </p>
    </div>

    {{-- ===== ISSUANCE ===== --}}
    <div class="issuance">
        <p>
            Dado en
            <span class="line-field line-field-lg">{{ $lugarExp }}</span>
            el
            <span class="line-field line-field-sm">{{ $diaExp }}</span>
        </p>
        <p>
            de
            <span class="line-field">{{ $mesExp }}</span>
            de dos mil
            <span class="line-field line-field-sm">{{ $anoExpMil ?: '' }}</span>
        </p>
    </div>

    <div class="footer-row">
        <div class="footer-seal">
            <p class="sello">(Sello)</p>
        </div>

        <div class="footer-qr">
            @if ($qrDataUri)
                <div class="qr-verify">
                    <img src="{{ $qrDataUri }}" alt="QR de verificación">
                </div>
            @endif
        </div>

        <div class="footer-signature">
            <div class="sig-bottom">
                @if ($firmaPath && file_exists($firmaPath))
                    <p style="text-align:center; margin-bottom: 2px;">
                        <img src="{{ $firmaPath }}" style="max-height:50px; max-width:180px;">
                    </p>
                @endif
                <div class="sig-line-accent">F I R M A</div>
            </div>
        </div>
    </div>

</div>
</body>
</html>
