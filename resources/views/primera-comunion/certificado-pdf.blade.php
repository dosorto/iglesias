<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificación de Primera Comunión</title>
    @php $isLandscape = (($iglesiaConfig?->orientacion_certificado_primera_comunion ?? $iglesiaConfig?->orientacion_certificado) === 'landscape'); @endphp
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #1a1a1a; background: #fff; }

        /* Marca de agua — igual que bautismo y confirmacion */
        .watermark-logo { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.08; z-index: 0; }
        .watermark-logo img { width: 430px; height: auto; object-fit: contain; }

        .page-wrapper { padding: 26px 36px 24px; border: none; margin: 2px; position: relative; z-index: 1; }

        .header { display: table; width: 100%; margin-bottom: 10px; }
        .header-logo-cell { display: table-cell; width: 90px; vertical-align: middle; text-align: center; }
        .header-logo-cell img { width: 80px; height: 80px; object-fit: contain; }
        .header-title-cell { display: table-cell; vertical-align: middle; text-align: center; }
        .header-right-cell { display: table-cell; width: 90px; vertical-align: middle; text-align: center; }
        .header-right-cell img { width: 80px; height: 80px; object-fit: contain; }

        .parish-name { font-size: 19pt; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; line-height: 1.1; }
        .diocese-name { font-size: 13pt; text-transform: uppercase; letter-spacing: 1px; margin-top: 3px; color: #555; }
        .header-address { font-size: 11pt; margin-top: 4px; color: #222; letter-spacing: 0.5px; }
        .header-divider { border-top: 1px solid #6f99ad; margin: 7px 0 18px; }

        .doc-title {
            text-align: center;
            font-size: 15.5pt;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.4px;
            margin-bottom: 24px;
        }

        .body-text { font-size: 12.5pt; line-height: 2.6; }
        .body-text p { margin-bottom: 4px; }

        .underline { display: inline-block; border-bottom: 1px solid #333; vertical-align: bottom; }
        .name-line { display: block; width: 100%; border-bottom: 2px solid #7D5A1E; margin: 10px 0 18px; min-height: 26px; font-size: 14pt; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 1px; color: #1a1a1a; }

        .nota-marginal { font-size: 10.5pt; margin-top: 14px; line-height: 1.8; color: #444; }

        .bottom-signatures {
            display: table;
            width: 100%;
            margin-top: 60px;
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

        .sello {
            width: 78px;
            height: 78px;
            margin: 0 auto;
            border: 2px dashed #999;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 7.5pt;
            color: #999;
            line-height: 1.2;
        }

        .signature-block { margin-top: 0; text-align: center; }
        .sig-img { max-height: 65px; max-width: 210px; object-fit: contain; display: block; margin: 0 auto; }
        .sig-line { display: inline-block; width: 260px; border-top: 2px solid #7D5A1E; margin-top: 0; padding-top: 4px; }
        .sig-name { font-size: 11pt; font-weight: bold; margin-top: 4px; margin-bottom: 2px; color: #1a1a1a; }
        .sig-title { font-size: 11pt; font-weight: bold; color: #7D5A1E; letter-spacing: 1px; text-transform: uppercase; }


        body.is-landscape .page-wrapper { padding: 14px 22px 24px; }
        body.is-landscape .header { margin-bottom: 6px; }
        body.is-landscape .header-logo-cell,
        body.is-landscape .header-right-cell { width: 72px; }
        body.is-landscape .header-logo-cell img,
        body.is-landscape .header-right-cell img { width: 58px; height: 58px; }
        body.is-landscape .parish-name { font-size: 15pt; letter-spacing: 1px; }
        body.is-landscape .diocese-name { font-size: 10pt; margin-top: 1px; }
        body.is-landscape .header-address { font-size: 9pt; margin-top: 2px; }
        body.is-landscape .header-divider { margin: 4px 0 6px; }
        body.is-landscape .ornament { margin: 1px 0; }
        body.is-landscape .cert-title-wrap { margin: 5px 0; }
        body.is-landscape .cert-title { font-size: 11.5pt; padding: 3px 24px; letter-spacing: 3px; }
        body.is-landscape .body-text { margin-top: 10px !important; font-size: 9.6pt; line-height: 1.45; }
        body.is-landscape .body-text p { margin-bottom: 2px; }
        body.is-landscape .name-line { margin: 4px 0 8px; min-height: 18px; font-size: 10.5pt; }
        body.is-landscape .nota-marginal { margin-top: 8px; font-size: 9pt; line-height: 1.45; }
        body.is-landscape .signature-block { margin-top: 20px; }
        body.is-landscape .sig-img { max-height: 46px; }
        body.is-landscape .sig-line { width: 220px; }
        body.is-landscape .sig-name { font-size: 9.5pt; }
        body.is-landscape .sig-title { font-size: 9pt; }
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
    $certBgPath = $resolvePublicFilePath($plantillaCertificadoPath ?? ($iglesiaConfig?->path_certificado_primera_comunion ?: $iglesiaConfig?->path_certificado_bautismo));

    $iglesia         = $primeraComunion->iglesia;
    $comulgante      = $primeraComunion->feligres?->persona;
    $encargadoModel  = $primeraComunion->encargado;
    $encargadoPersn  = $encargadoModel?->feligres?->persona;
    $iglesiaNombre   = $iglesiaConfig?->nombre ?? $iglesia?->nombre ?? '';
    $headerDiocesis = $iglesiaConfig?->header_diocesis ?: '';
    $headerLugar = $iglesiaConfig?->direccion ?: '';
    $encargadoNombre = mb_strtoupper($encargadoPersn?->nombre_completo ?? '', 'UTF-8');

    $firmaPath = $resolvePublicFilePath($encargadoModel?->path_firma_principal);

    $mesesEs = [
        1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',
        7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre',
    ];

    $fechaComunion = $primeraComunion->fecha_primera_comunion;
    $diaComunion   = $fechaComunion ? $fechaComunion->day             : '';
    $mesComunion   = $fechaComunion ? $mesesEs[$fechaComunion->month] : '';
    $anoComunion   = $fechaComunion ? $fechaComunion->year            : '';

    $fechaExp  = $primeraComunion->fecha_expedicion ?: now();
    $diaExp    = $fechaExp ? $fechaExp->day             : '';
    $mesExp    = $fechaExp ? $mesesEs[$fechaExp->month] : '';
    $anoExpMil = $fechaExp ? ($fechaExp->year - 2000)   : '';

    $lugarCelebracion = trim((string) ($primeraComunion->lugar_celebracion ?? ''));
    if ($lugarCelebracion === '') {
        $lugarCelebracion = trim((string) ($iglesiaConfig?->direccion ?? ''));
    }
    if ($lugarCelebracion === '') {
        $lugarCelebracion = trim((string) ($iglesia?->direccion ?? ''));
    }
    if ($lugarCelebracion === '') {
        $lugarCelebracion = 'Monjaras, Marcovia';
    }
    $lugarExp = trim((string) ($iglesiaConfig?->direccion ?? ''));
    if ($lugarExp === '') {
        $lugarExp = trim((string) ($iglesia?->direccion ?? ''));
    }
    if ($lugarExp === '') {
        $lugarExp = trim((string) ($primeraComunion->lugar_expedicion ?? ''));
    }
    if ($lugarExp === '') {
        $lugarExp = 'Monjaras, Marcovia, Choluteca, Honduras C. A.';
    }
    $notaMarginal     = $primeraComunion->nota_marginal     ?? '';
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

    <div class="header">
        <div class="header-logo-cell">
            @if ($logoIglesiaPath)
                <img src="{{ $logoIglesiaPath }}" alt="Logo">
            @else
                <div class="logo-placeholder"></div>
            @endif
        </div>
        <div class="header-title-cell">
            <div class="parish-name">{{ $iglesiaNombre }}</div>
            <div class="diocese-name">{{ $headerDiocesis }}</div>
            <div class="header-address">{{ $headerLugar }}</div>
        </div>
        <div class="header-right-cell">
            @if ($logoIglesiaDerechaPath)
                <img src="{{ $logoIglesiaDerechaPath }}" alt="Logo">
            @else
                <div class="logo-placeholder"></div>
            @endif
        </div>
    </div>

    <div class="header-divider"></div>

    <div class="doc-title">CERTIFICACI&Oacute;N DE PRIMERA COMUNI&Oacute;N</div>

    <div class="body-text" style="margin-top: 10px;">

        <p>El infrascrito encargado del archivo de esta parroquia certifica que</p>

        <span class="name-line">{{ mb_strtoupper($comulgante?->nombre_completo ?? '', 'UTF-8') }}</span>

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

    <div class="body-text" style="margin-top: 50px;">
        <p>
            Dado en
            <span>{{ $lugarExp }}</span>
            a los
            <span class="underline" style="min-width:80px; text-align:center;">{{ $diaExp }}</span>
            del mes de
            <span class="underline" style="min-width:140px; text-align:center;">{{ $mesExp }}</span>
            año
            <span class="underline" style="min-width:80px; text-align:center;">{{ $anoExpMil ? '20'.str_pad($anoExpMil, 2, '0', STR_PAD_LEFT) : '' }}</span>
        </p>
    </div>

    <div class="bottom-signatures">
        <div class="seal-cell">
            <div class="sello">Sello de la<br>Parroquia</div>
        </div>
        <div class="signature-cell">
            <div class="signature-block">
                @if ($firmaPath)
                    <img src="{{ $firmaPath }}" alt="Firma" class="sig-img">
                @else
                    <div style="height: 65px;"></div>
                @endif
                <div>
                    <span class="sig-line"></span>
                </div>
                <div class="sig-name">{{ $encargadoNombre }}</div>
            </div>
        </div>
    </div>


</div>
</body>
</html>