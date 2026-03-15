<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificación de Confirmación</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #1a1a1a; background: #fff; }
        .page-wrapper { padding: 26px 36px; border: 4px double #7D5A1E; margin: 10px; }
        .header { display: table; width: 100%; margin-bottom: 10px; }
        .header-logo-cell { display: table-cell; width: 85px; vertical-align: middle; text-align: center; }
        .header-logo-cell img { width: 75px; height: 75px; object-fit: contain; }
        .header-title-cell { display: table-cell; vertical-align: middle; text-align: center; }
        .parish-name { font-size: 19pt; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; line-height: 1.1; }
        .diocese-name { font-size: 13pt; text-transform: uppercase; letter-spacing: 1px; margin-top: 3px; color: #555; }
        .header-right-cell { display: table-cell; width: 85px; vertical-align: middle; text-align: center; }
        .header-right-cell img { width: 75px; height: 75px; object-fit: contain; }
        .hr-accent { border: none; border-top: 1px solid #7D5A1E; margin: 3px 0; }
        .ornament { text-align: center; color: #7D5A1E; font-size: 11pt; letter-spacing: 8px; margin: 3px 0; }
        .cert-title-wrap { text-align: center; margin: 8px 0; }
        .cert-title { display: inline-block; background: #7D5A1E; color: #fff; font-size: 13.5pt; font-weight: bold; letter-spacing: 4px; text-transform: uppercase; padding: 5px 32px; }
        .body-text { line-height: 2; font-size: 11.5pt; }
        .body-text p { margin-bottom: 2px; }
        .line-field { display: inline-block; min-width: 200px; border-bottom: 1px solid #333; margin: 0 3px; vertical-align: bottom; }
        .line-field-sm { min-width: 70px; }
        .line-field-lg { min-width: 260px; }
        .line-field-xl { min-width: 320px; }
        .sig-right { text-align: right; margin-top: 26px; }
        .sig-name { text-align: right; font-size: 11pt; font-weight: bold; margin-bottom: 4px; }
        .sig-line-accent { display: inline-block; width: 220px; border-top: 2px solid #7D5A1E; text-align: center; font-size: 9.5pt; font-weight: bold; letter-spacing: 2px; padding-top: 4px; color: #7D5A1E; }
        .issuance { margin-top: 22px; font-size: 11.5pt; line-height: 2; }
        .sello { font-size: 10pt; font-style: italic; margin-top: 4px; color: #666; }
    </style>
</head>
@php
    $logoIglesiaPath = ($iglesiaConfig?->path_logo) ? public_path('storage/' . $iglesiaConfig->path_logo) : null;
    $logoEstaticoPath = public_path('image/Logo_guest.png');

    $confirmado    = $confirmacion->feligres?->persona;
    $iglesiaNombre = $iglesiaConfig?->nombre ?? $confirmacion->iglesia?->nombre ?? '';
    $ministroNombre = $confirmacion->ministro_confirmacion_nombre ?? $confirmacion->ministro?->nombre_completo ?? '';

    $mesesEs = [1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',
                7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre'];

    $fc = $confirmacion->fecha_confirmacion;
    $diaConf = $fc ? $fc->day : '';
    $mesConf = $fc ? $mesesEs[$fc->month] : '';
    $anoConf = $fc ? $fc->year : '';

    $fe = $confirmacion->fecha_expedicion;
    $diaExp    = $fe ? $fe->day : '';
    $mesExp    = $fe ? $mesesEs[$fe->month] : '';
    $anoExpMil = $fe ? ($fe->year - 2000) : '';

    $lugarExp     = $confirmacion->lugar_expedicion ?? '';
    $notaMarginal = $confirmacion->nota_marginal    ?? '';
@endphp
<body>
<div class="page-wrapper">

    <div class="header">
        <div class="header-logo-cell">
            @if ($logoIglesiaPath && file_exists($logoIglesiaPath))
                <img src="{{ $logoIglesiaPath }}" alt="Logo">
            @endif
        </div>
        <div class="header-title-cell">
            <div class="parish-name">{{ $iglesiaNombre ?: 'Parroquia' }}</div>
            <div class="diocese-name">Di&oacute;cesis de Choluteca</div>
        </div>
        <div class="header-right-cell">
            @if (file_exists($logoEstaticoPath))
                <img src="{{ $logoEstaticoPath }}" alt="Logo">
            @endif
        </div>
    </div>

    <hr class="hr-accent">
    <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
    <hr class="hr-accent">

    <div class="cert-title-wrap">
        <span class="cert-title">CERTIFICACI&Oacute;N DE CONFIRMACI&Oacute;N</span>
    </div>

    <hr class="hr-accent">
    <div class="ornament">&bull; &nbsp; &bull; &nbsp; &bull;</div>
    <hr class="hr-accent">

    <div class="body-text" style="margin-top: 14px;">

        <p>El infrascrito, encargado del Archivo de la Parroquia de
            <span class="line-field line-field-lg">{{ $iglesiaNombre }}</span>
        </p>

        <p><span style="font-weight:bold">CERTIFICA:</span>
            Que en el libro de Confirmaciones No.
            <span class="line-field line-field-sm">{{ $confirmacion->libro_confirmacion }}</span>
            , en la Página
            <span class="line-field line-field-sm">{{ $confirmacion->folio }}</span>
            , bajo el No.
            <span class="line-field line-field-sm">{{ $confirmacion->partida_numero }}</span>
        </p>

        <p>Se encuentra la partida que dice:</p>

        <p>En <span class="line-field">{{ $iglesiaNombre }}</span>
            a <span class="line-field line-field-sm">{{ $diaConf }}</span>
        </p>

        <p>del mes de <span class="line-field">{{ $mesConf }}</span>
            del año
            @if($anoConf >= 2000)
                dos mil <span class="line-field line-field-sm">{{ $anoConf - 2000 ?: '' }}</span>
            @else
                mil novecientos <span class="line-field line-field-sm">{{ $anoConf ? ($anoConf - 1900) : '' }}</span>
            @endif
        </p>

        <p>El Señor <span class="line-field line-field-lg">{{ $ministroNombre }}</span> confirmó solemnemente a:</p>

        <p><span class="line-field line-field-xl">{{ $confirmado?->nombre_completo ?? $confirmacion->nombre_feligres }}</span></p>

        <p>Hijo(a) de <span class="line-field line-field-xl">{{ $confirmacion->nombre_padre }}</span></p>

        <p>y de <span class="line-field line-field-xl">{{ $confirmacion->nombre_madre }}</span></p>

        <p>Padrino / Madrina: <span class="line-field line-field-xl">{{ $confirmacion->padrino_madrina }}</span></p>

    </div>

    <div class="sig-right">
        @if ($ministroNombre)
            <p class="sig-name">{{ $ministroNombre }}</p>
        @endif
        <div class="sig-line-accent">MINISTRO CONFIRMANTE</div>
    </div>

    <div class="body-text" style="margin-top: 16px;">
        <p><span style="font-weight:bold">NOTA MARGINAL:</span>
            <span class="line-field line-field-xl">{{ $notaMarginal }}</span>
        </p>
    </div>

    <div class="issuance">
        <p>Dado en <span class="line-field line-field-lg">{{ $lugarExp }}</span>
            el <span class="line-field line-field-sm">{{ $diaExp }}</span>
        </p>
        <p>de <span class="line-field">{{ $mesExp }}</span>
            de dos mil <span class="line-field line-field-sm">{{ $anoExpMil ?: '' }}</span>
        </p>
        <p class="sello">(Sello)</p>
    </div>

    <div style="text-align:right; margin-top: 34px;">
        <div class="sig-line-accent">ENCARGADO DE ARCHIVO</div>
    </div>

</div>
</body>
</html>