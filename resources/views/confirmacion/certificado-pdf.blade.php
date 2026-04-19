<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificación de Confirmación</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #1a1a1a; background: #fff; }
        .page-wrapper { padding: 30px 46px 28px; border: none; margin: 2px; position: relative; z-index: 1; }

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

        .header { display: table; width: 100%; margin-bottom: 10px; }
        .header-logo-cell { display: table-cell; width: 85px; vertical-align: middle; text-align: center; }
        .header-logo-cell img { width: 75px; height: 75px; object-fit: contain; }
        .header-title-cell { display: table-cell; vertical-align: middle; text-align: center; }
        .header-right-cell { display: table-cell; width: 85px; vertical-align: middle; text-align: center; }
        .header-right-cell img { width: 75px; height: 75px; object-fit: contain; }

        .parish-name { font-size: 19pt; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; line-height: 1.1; }
        .diocese-name { font-size: 12pt; text-transform: uppercase; letter-spacing: 3px; color: #4a7aad; margin-top: 3px; }
        .header-address { font-size: 11pt; margin-top: 4px; color: #222; letter-spacing: 0.5px; }
        .header-divider { border-top: 2px solid #8aa8bc; margin: 6px 0 8px; }

        .hr-accent { border: none; border-top: 1px solid #7D5A1E; margin: 3px 0; }
        .ornament { text-align: center; color: #7D5A1E; font-size: 11pt; letter-spacing: 8px; margin: 3px 0; }
        .cert-title-wrap { text-align: center; margin: 8px 0; }
        .cert-title { display: inline-block; background: #7D5A1E; color: #fff; font-size: 13pt; font-weight: bold; letter-spacing: 4px; text-transform: uppercase; padding: 5px 32px; }

        .cert-intro { font-size: 11.5pt; margin-bottom: 14px; line-height: 1.6; margin-top: 14px; }
        .cert-block { font-size: 11.5pt; line-height: 2.4; margin-bottom: 6px; }
        .cert-block p { margin-bottom: 2px; }

        .field { display: inline-block; border-bottom: 1px solid #333; vertical-align: bottom; margin: 0 3px; }
        .field-sm   { min-width: 90px; }
        .field-md   { min-width: 160px; }
        .field-lg   { min-width: 260px; }
        .field-xl   { min-width: 340px; }
        .field-full { min-width: 460px; }

        .name-line { display: block; width: 100%; border-bottom: 2px solid #333; margin: 8px 0 12px; min-height: 22px; font-size: 13pt; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 1px; }

        .nota-marginal { font-size: 10.5pt; margin-top: 14px; line-height: 1.8; color: #444; }
        .issuance { font-size: 11.5pt; line-height: 2.4; margin-top: 26px; }
        .sello { font-size: 10pt; font-style: italic; margin-top: 4px; color: #666; }

        .signature-block { margin-top: 44px; text-align: center; }
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
            margin-top: 0;
            padding-top: 4px;
        }
        .sig-name { font-size: 11pt; font-weight: bold; margin-bottom: 2px; color: #1a1a1a; margin-top: 4px; }
        .sig-title { font-size: 10pt; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; color: #7D5A1E; }

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
    $certBgPath = $resolvePublicFilePath($plantillaCertificadoPath ?? ($iglesiaConfig?->path_certificado_confirmacion ?: $iglesiaConfig?->path_certificado_bautismo));

    $confirmado      = $confirmacion->feligres?->persona;
    $padrino         = $confirmacion->padrino?->persona;
    $madrina         = $confirmacion->madrina?->persona;
    $ministro        = $confirmacion->ministro?->persona;
    $encargado       = $confirmacion->encargado?->feligres?->persona;

    $iglesiaNombre   = $iglesiaConfig?->nombre ?? $confirmacion->iglesia?->nombre ?? '';
    $headerDiocesis = $iglesiaConfig?->header_diocesis ?: '';
    $headerLugar = $iglesiaConfig?->direccion ?: '';
    $ministroNombre  = $ministro?->nombre_completo ?? '';
    $encargadoNombre = $encargado?->nombre_completo ?? '';

    $padrinosStr = collect([$padrino?->nombre_completo, $madrina?->nombre_completo])
        ->filter()->implode(' y ');

    $mesesEs = [
        1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',
        7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre',
    ];

    $fc      = $confirmacion->fecha_confirmacion;
    $diaConf = $fc ? $fc->day             : '';
    $mesConf = $fc ? $mesesEs[$fc->month] : '';
    $anoConf = $fc ? $fc->year            : '';

    $fe        = $confirmacion->fecha_expedicion;
    $diaExp    = $fe ? $fe->day             : '';
    $mesExp    = $fe ? $mesesEs[$fe->month] : '';
    $anoExpMil = $fe ? ($fe->year - 2000)   : '';

    $lugarConf    = $confirmacion->lugar_confirmacion ?? '';
    $lugarExp     = $confirmacion->lugar_expedicion   ?? '';
    $notaMarginal = $confirmacion->nota_marginal      ?? '';

    $firmaPath = null;
    if ($confirmacion->encargado?->path_firma_principal) {
        $firmaPath = $resolvePublicFilePath($confirmacion->encargado->path_firma_principal);
    }
    $codigoVerificacion = $codigoVerificacion ?? '';
    $urlVerificacion = $urlVerificacion ?? '';
    $qrDataUri = $qrDataUri ?? null;
@endphp
<body @if($certBgPath && file_exists($certBgPath)) style="background-image: url('{{ $certBgPath }}'); background-size: cover; background-position: center; background-repeat: no-repeat;" @endif>
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
        <span class="cert-title">CERTIFICACI&Oacute;N DE CONFIRMACI&Oacute;N</span>
    </div>
    <hr class="hr-accent">
    <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
    <hr class="hr-accent">

    <p class="cert-intro">El infrascrito encargado del archivo de esta parroquia certifica que</p>

    <div style="margin-bottom: 18px; text-align: center;">
        <span class="name-line">{{ $confirmado?->nombre_completo ?? '' }}</span>
    </div>

    <div class="cert-block">
        <p>
            Fue confirmado (a) el día
            <span class="field field-sm">{{ $diaConf }}</span>
            del mes
            <span class="field field-md">{{ $mesConf }}</span>
            año
            <span class="field field-md">{{ $anoConf }}</span>
        </p>
        <p>En <span class="field field-lg">{{ $lugarConf }}</span></p>
        <p>Por Mons. <span class="field field-lg">{{ $ministroNombre }}</span></p>
        <p>Siendo sus padrinos: <span class="field field-xl">{{ $padrinosStr }}</span></p>
    </div>

    <div style="margin-top: 20px; margin-bottom: 6px;">
        <span class="field field-full"></span>
    </div>

    @if ($notaMarginal)
        <div class="nota-marginal">
            <strong>NOTA MARGINAL:</strong> {{ $notaMarginal }}
        </div>
    @endif

    <div class="issuance">
        <p>
            Dado en <span class="field field-lg">{{ $lugarExp }}</span>
            a los <span class="field field-sm">{{ $diaExp }}</span>
            del mes de <span class="field field-md">{{ $mesExp }}</span>
            año <span class="field field-sm">{{ $anoExpMil ? '20'.$anoExpMil : '' }}</span>
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
            <div class="signature-block" style="margin-top: 0; text-align: center;">
                @if ($firmaPath)
                    <img src="{{ $firmaPath }}" alt="Firma" class="sig-img">
                @else
                    <div style="height: 65px;"></div>
                @endif
                <div><span class="sig-line"></span></div>
                <p class="sig-name">{{ $encargadoNombre ?: '' }}</p>
                <p class="sig-title">P&aacute;rroco</p>
            </div>
        </div>
    </div>

</div>
</body>
</html>