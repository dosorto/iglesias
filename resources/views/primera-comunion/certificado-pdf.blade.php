<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificación de Primera Comunión</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #1a1a1a; background: #fff; }

        /* Marca de agua — igual que bautismo y confirmacion */
        .watermark-logo { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.08; z-index: 0; }
        .watermark-logo img { width: 430px; height: auto; object-fit: contain; }

        .page-wrapper { padding: 26px 36px 106px; border: 4px double #7D5A1E; margin: 10px; position: relative; z-index: 1; }

        .header { display: table; width: 100%; margin-bottom: 10px; }
        .header-logo-cell { display: table-cell; width: 85px; vertical-align: middle; text-align: center; }
        .header-logo-cell img { width: 75px; height: 75px; object-fit: contain; }
        .header-title-cell { display: table-cell; vertical-align: middle; text-align: center; }
        .header-right-cell { display: table-cell; width: 85px; vertical-align: middle; text-align: center; }
        .header-right-cell img { width: 75px; height: 75px; object-fit: contain; }

        .parish-name { font-size: 19pt; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; line-height: 1.1; }
        .diocese-name { font-size: 13pt; text-transform: uppercase; letter-spacing: 1px; margin-top: 3px; color: #555; }
        .header-address { font-size: 11pt; margin-top: 4px; color: #222; letter-spacing: 0.5px; }
        .header-divider { border-top: 2px solid #8aa8bc; margin: 6px 0 8px; }

        .hr-accent { border: none; border-top: 1px solid #7D5A1E; margin: 3px 0; }
        .ornament { text-align: center; color: #7D5A1E; font-size: 11pt; letter-spacing: 8px; margin: 3px 0; }
        .cert-title-wrap { text-align: center; margin: 8px 0; }
        .cert-title { display: inline-block; background: #7D5A1E; color: #fff; font-size: 13.5pt; font-weight: bold; letter-spacing: 4px; text-transform: uppercase; padding: 5px 32px; }

        .body-text { font-size: 11.5pt; line-height: 2.2; }
        .body-text p { margin-bottom: 6px; }

        .underline { display: inline-block; border-bottom: 1px solid #333; vertical-align: bottom; }
        .name-line { display: block; width: 100%; border-bottom: 2px solid #7D5A1E; margin: 8px 0 12px; min-height: 22px; font-size: 13pt; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 1px; color: #1a1a1a; }

        .nota-marginal { font-size: 10.5pt; margin-top: 14px; line-height: 1.8; color: #444; }
        .sello { font-size: 10pt; font-style: italic; margin-top: 4px; color: #666; }

        .signature-block { margin-top: 50px; text-align: center; }
        .sig-img { max-height: 65px; max-width: 210px; object-fit: contain; display: block; margin: 0 auto; }
        .sig-line { display: inline-block; width: 260px; border-top: 2px solid #7D5A1E; margin-top: 0; padding-top: 4px; }
        .sig-name { font-size: 11pt; font-weight: bold; margin-top: 4px; margin-bottom: 2px; color: #1a1a1a; }
        .sig-title { font-size: 11pt; font-weight: bold; color: #7D5A1E; letter-spacing: 1px; text-transform: uppercase; }

        .qr-verify {
            position: fixed;
            left: 42px;
            bottom: 38px;
            margin-top: 0;
            font-size: 8pt;
            color: #555;
            line-height: 1;
            z-index: 2;
        }
        .qr-verify img {
            width: 70px;
            height: 70px;
            border: 1px solid #d1d5db;
            padding: 2px;
            background: #fff;
        }
        .qr-code { margin-top: 3px; letter-spacing: 0.4px; }
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

    $iglesia         = $primeraComunion->iglesia;
    $comulgante      = $primeraComunion->feligres?->persona;
    $encargadoModel  = $primeraComunion->encargado;
    $encargadoPersn  = $encargadoModel?->feligres?->persona;
    $iglesiaNombre   = $iglesiaConfig?->nombre ?? $iglesia?->nombre ?? '';
    $headerDiocesis = $iglesiaConfig?->header_diocesis ?: '';
    $headerLugar = $iglesiaConfig?->direccion ?: '';
    $encargadoNombre = $encargadoPersn?->nombre_completo ?? '';

    $firmaPath = $resolvePublicFilePath($encargadoModel?->path_firma_principal);

    $mesesEs = [
        1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',
        7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre',
    ];

    $fechaComunion = $primeraComunion->fecha_primera_comunion;
    $diaComunion   = $fechaComunion ? $fechaComunion->day             : '';
    $mesComunion   = $fechaComunion ? $mesesEs[$fechaComunion->month] : '';
    $anoComunion   = $fechaComunion ? $fechaComunion->year            : '';

    $fechaExp  = $primeraComunion->fecha_expedicion;
    $diaExp    = $fechaExp ? $fechaExp->day             : '';
    $mesExp    = $fechaExp ? $mesesEs[$fechaExp->month] : '';
    $anoExpMil = $fechaExp ? ($fechaExp->year - 2000)   : '';

    $lugarCelebracion = $primeraComunion->lugar_celebracion ?? '';
    $lugarExp         = $primeraComunion->lugar_expedicion  ?? '';
    $notaMarginal     = $primeraComunion->nota_marginal     ?? '';
    $codigoVerificacion = $codigoVerificacion ?? '';
    $urlVerificacion = $urlVerificacion ?? '';
    $qrDataUri = $qrDataUri ?? null;
@endphp
<body>
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

    <hr class="hr-accent">
    <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
    <hr class="hr-accent">
    <div class="cert-title-wrap">
        <span class="cert-title">CERTIFICACI&Oacute;N DE PRIMERA COMUNI&Oacute;N</span>
    </div>
    <hr class="hr-accent">
    <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
    <hr class="hr-accent">

    <div class="body-text" style="margin-top: 20px;">

        <p>El infrascrito encargado del archivo de esta parroquia certifica que</p>

        <span class="name-line">{{ $comulgante?->nombre_completo ?? '' }}</span>

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

    <div class="body-text" style="margin-top: 40px;">
        <p>
            Dado en
            <span class="underline" style="min-width:200px;">{{ $lugarExp }}</span>
            a los
            <span class="underline" style="min-width:80px; text-align:center;">{{ $diaExp }}</span>
            del mes de
            <span class="underline" style="min-width:140px; text-align:center;">{{ $mesExp }}</span>
            año
            <span class="underline" style="min-width:80px; text-align:center;">{{ $anoExpMil ? '20'.str_pad($anoExpMil, 2, '0', STR_PAD_LEFT) : '' }}</span>
        </p>
        <p class="sello">(Sello)</p>
    </div>

    <div class="signature-block">
        @if ($firmaPath)
            <img src="{{ $firmaPath }}" alt="Firma" class="sig-img">
        @else
            <div style="height: 65px;"></div>
        @endif
        <div>
            <span class="sig-line"></span>
        </div>
        <p class="sig-name">{{ $encargadoNombre ?: '' }}</p>
        <p class="sig-title">P&aacute;rroco</p>
    </div>

    @if ($qrDataUri)
        <div class="qr-verify">
            <img src="{{ $qrDataUri }}" alt="QR de verificacion">
        </div>
    @endif

</div>
</body>
</html>