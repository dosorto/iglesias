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
        .page-wrapper {
            padding: 30px 46px;
            border: 4px double #7D5A1E;
            margin: 10px;
        }

        /* HEADER */
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
        .parish-name {
            font-size: 19pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            line-height: 1.1;
        }
        .diocese-name {
            font-size: 12pt;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #4a7aad;
            margin-top: 3px;
        }

        /* ORNAMENTOS */
        .hr-accent { border: none; border-top: 1px solid #7D5A1E; margin: 3px 0; }
        .ornament { text-align: center; color: #7D5A1E; font-size: 11pt; letter-spacing: 8px; margin: 3px 0; }
        .cert-title-wrap { text-align: center; margin: 8px 0; }
        .cert-title {
            display: inline-block;
            background: #7D5A1E;
            color: #fff;
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 4px;
            text-transform: uppercase;
            padding: 5px 32px;
        }

        /* BODY */
        .cert-intro {
            font-size: 11.5pt;
            margin-bottom: 14px;
            line-height: 1.6;
            margin-top: 14px;
        }
        .cert-block {
            font-size: 11.5pt;
            line-height: 2.4;
            margin-bottom: 6px;
        }
        .cert-block p { margin-bottom: 2px; }

        /* FIELDS */
        .field {
            display: inline-block;
            border-bottom: 1px solid #333;
            vertical-align: bottom;
            margin: 0 3px;
        }
        .field-sm   { min-width: 90px; }
        .field-md   { min-width: 160px; }
        .field-lg   { min-width: 260px; }
        .field-xl   { min-width: 340px; }
        .field-full { min-width: 460px; }

        /* SIGNATURE */
        .signature-block {
            margin-top: 44px;
            text-align: center;
        }
        .sig-img {
            max-height: 60px;
            max-width: 200px;
            object-fit: contain;
            display: block;
            margin: 0 auto 4px auto;
        }
        .sig-line {
            display: inline-block;
            width: 260px;
            border-top: 2px solid #7D5A1E;
            margin-bottom: 4px;
        }
        .sig-name {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 2px;
            color: #1a1a1a;
        }
        .sig-title {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #7D5A1E;
        }

        /* ISSUANCE */
        .issuance {
            font-size: 11.5pt;
            line-height: 2.4;
            margin-top: 26px;
        }
        .nota-marginal {
            font-size: 10.5pt;
            margin-top: 14px;
            line-height: 1.8;
            color: #444;
        }
        .sello {
            font-size: 10pt;
            font-style: italic;
            margin-top: 4px;
            color: #666;
        }
    </style>
</head>
@php
    $logoIglesiaPath  = ($iglesiaConfig?->path_logo) ? public_path('storage/' . $iglesiaConfig->path_logo) : null;
    $logoEstaticoPath = public_path('image/Logo_guest.png');

    $confirmado = $confirmacion->feligres?->persona;
    $padre      = $confirmacion->padre?->persona;
    $madre      = $confirmacion->madre?->persona;
    $padrino    = $confirmacion->padrino?->persona;
    $madrina    = $confirmacion->madrina?->persona;
    $ministro   = $confirmacion->ministro?->persona;

    $iglesiaNombre  = $iglesiaConfig?->nombre ?? $confirmacion->iglesia?->nombre ?? '';
    $ministroNombre = $ministro?->nombre_completo ?? '';

    $padrinosStr = collect([$padrino?->nombre_completo, $madrina?->nombre_completo])
        ->filter()->implode(' y ');

    $mesesEs = [
        1=>'enero',    2=>'febrero',   3=>'marzo',
        4=>'abril',    5=>'mayo',      6=>'junio',
        7=>'julio',    8=>'agosto',    9=>'septiembre',
        10=>'octubre', 11=>'noviembre',12=>'diciembre',
    ];

    $fc      = $confirmacion->fecha_confirmacion;
    $diaConf = $fc ? $fc->day             : '';
    $mesConf = $fc ? $mesesEs[$fc->month] : '';
    $anoConf = $fc ? $fc->year            : '';

    $fe        = $confirmacion->fecha_expedicion;
    $diaExp    = $fe ? $fe->day             : '';
    $mesExp    = $fe ? $mesesEs[$fe->month] : '';
    $anoExpMil = $fe ? ($fe->year - 2000)   : '';

    $lugarConf    = $confirmacion->lugar_confirmacion ?? $iglesiaNombre;
    $lugarExp     = $confirmacion->lugar_expedicion   ?? '';
    $notaMarginal = $confirmacion->nota_marginal      ?? '';

    // Logo
    $logoSrc = null;
    if ($logoIglesiaPath && file_exists($logoIglesiaPath)) {
        $logoSrc = $logoIglesiaPath;
    } elseif (file_exists($logoEstaticoPath)) {
        $logoSrc = $logoEstaticoPath;
    }

    // Firma del ministro
    $firmaPath = null;
    if ($confirmacion->ministro && $confirmacion->ministro->path_firma_principal) {
        $firmaAbsoluta = public_path('storage/' . $confirmacion->ministro->path_firma_principal);
        if (file_exists($firmaAbsoluta)) {
            $firmaPath = $firmaAbsoluta;
        }
    }
@endphp
<body>
<div class="page-wrapper">

    {{-- ══ HEADER: logos a ambos lados ══ --}}
    <div class="header">
        <div class="header-logo-cell">
            @if ($logoSrc)
                <img src="{{ $logoSrc }}" alt="Logo">
            @endif
        </div>
        <div class="header-title-cell">
            <div class="parish-name">{{ $iglesiaNombre ?: 'Parroquia' }}</div>
            <div class="diocese-name">Di&oacute;cesis de Choluteca</div>
        </div>
        <div class="header-right-cell">
            @if ($logoSrc)
                <img src="{{ $logoSrc }}" alt="Logo">
            @endif
        </div>
    </div>

    {{-- Ornamentos dorados --}}
    <hr class="hr-accent">
    <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
    <hr class="hr-accent">

    <div class="cert-title-wrap">
        <span class="cert-title">CERTIFICACI&Oacute;N DE CONFIRMACI&Oacute;N</span>
    </div>

    <hr class="hr-accent">
    <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
    <hr class="hr-accent">

    {{-- ══ INTRO ══ --}}
    <p class="cert-intro">
        El infrascrito encargado del archivo de esta parroquia certifica que
    </p>

    {{-- ══ NOMBRE DEL CONFIRMADO ══ --}}
    <div style="margin-bottom: 18px; text-align: center;">
        <span class="field field-full" style="font-size: 13pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">
            {{ $confirmado?->nombre_completo ?? '' }}
        </span>
    </div>

    {{-- ══ FECHA, LUGAR, MINISTRO, PADRINOS ══ --}}
    <div class="cert-block">
        <p>
            Fue confirmado (a) el día
            <span class="field field-sm">{{ $diaConf }}</span>
            del mes
            <span class="field field-md">{{ $mesConf }}</span>
            año
            <span class="field field-md">{{ $anoConf }}</span>
        </p>

        <p>
            En
            <span class="field field-lg">{{ $lugarConf }}</span>
        </p>

        <p>
            Por Mons.
            <span class="field field-lg">{{ $ministroNombre }}</span>
        </p>

        <p>
            Siendo sus padrinos:
            <span class="field field-xl">{{ $padrinosStr }}</span>
        </p>
    </div>

    {{-- ══ SEGUNDA LÍNEA DEL NOMBRE ══ --}}
    <div style="margin-top: 20px; margin-bottom: 6px;">
        <span class="field field-full"></span>
    </div>

    {{-- ══ NOTA MARGINAL ══ --}}
    @if ($notaMarginal)
        <div class="nota-marginal">
            <strong>NOTA MARGINAL:</strong> {{ $notaMarginal }}
        </div>
    @endif

    {{-- ══ ISSUANCE ══ --}}
    <div class="issuance">
        <p>
            Dado en
            <span class="field field-lg">{{ $lugarExp }}</span>
            a los
            <span class="field field-sm">{{ $diaExp }}</span>
            del mes de
        </p>
        <p>
            <span class="field field-md">{{ $mesExp }}</span>
            año
            <span class="field field-sm">{{ $anoExpMil ? '20'.$anoExpMil : '' }}</span>
        </p>
        <p class="sello">(Sello)</p>
    </div>

    {{-- ══ FIRMA ══ --}}
    <div class="signature-block">
        @if ($firmaPath)
            <img src="{{ $firmaPath }}" alt="Firma" class="sig-img">
        @endif
        <div class="sig-line"></div>
        <p class="sig-name">{{ $ministroNombre ?: 'P. ___________________' }}</p>
        <p class="sig-title">P&aacute;rroco</p>
    </div>

</div>
</body>
</html>