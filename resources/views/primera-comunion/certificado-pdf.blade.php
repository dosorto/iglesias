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
            color: #000;
            background: #fff;
            padding: 40px 50px;
        }

        /* ── HEADER ── */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }
        .header-logo-cell {
            display: table-cell;
            width: 90px;
            vertical-align: middle;
        }
        .header-logo-cell img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .header-title-cell {
            display: table-cell;
            vertical-align: middle;
            padding-left: 14px;
        }
        .parish-name {
            font-family: 'Times New Roman', Times, serif;
            font-size: 22pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.1;
        }
        .diocese-name {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }
        .header-right-cell {
            display: table-cell;
            width: 90px;
            vertical-align: middle;
            text-align: right;
        }
        .header-right-cell img {
            width: 80px;
            height: 80px;
            object-fit: contain;
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
            border-bottom: 1px solid #000;
            vertical-align: bottom;
        }
        .name-line {
            display: block;
            width: 100%;
            border-bottom: 1px solid #000;
            margin: 6px 0 10px;
            min-height: 20px;
        }

        /* ── FIRMA CENTRAL ── */
        .sig-center {
            margin-top: 60px;
            text-align: center;
        }
        .sig-center .sig-line {
            display: inline-block;
            width: 260px;
            border-top: 1px solid #000;
            padding-top: 6px;
            font-size: 11pt;
            font-weight: bold;
        }
        .sig-center .sig-title {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 4px;
        }

        /* ── FIRMAS PIE (encargado izq | párroco der) ── */
        .sig-footer {
            display: table;
            width: 100%;
            margin-top: 50px;
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
            border-top: 1px solid #000;
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 1px;
            padding-top: 4px;
            text-align: center;
        }
    </style>
</head>
<body>

    @php
        $iglesia       = $primeraComunion->iglesia;
        $comulgante    = $primeraComunion->feligres?->persona;
        $catequista    = $primeraComunion->catequista?->persona;
        $ministro      = $primeraComunion->ministro?->persona;
        $parroco       = $primeraComunion->parroco?->persona;
        $parrocoModel  = $primeraComunion->parroco;
        $iglesiaNombre = $iglesia?->nombre ?? '';

        // Logo de la iglesia (izquierda)
        $logoIglesiaPath = ($iglesia && $iglesia->path_logo)
            ? public_path('storage/' . $iglesia->path_logo)
            : null;

        // Logo estático del proyecto (derecha)
        $logoEstatico = public_path('image/Logo_guest.png');

        // Firma del párroco
        $firmaParrocoPath = ($parrocoModel && $parrocoModel->path_firma_principal)
            ? public_path('storage/' . $parrocoModel->path_firma_principal)
            : null;

        // Firma del encargado
        $firmaEncargadoPath = (isset($encargado) && $encargado && $encargado->path_firma_principal)
            ? public_path('storage/' . $encargado->path_firma_principal)
            : null;

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
            <div class="diocese-name">Diócesis de Choluteca</div>
        </div>

        {{-- Logo estático (derecha) --}}
        <div class="header-right-cell">
            @if (file_exists($logoEstatico))
                <img src="{{ $logoEstatico }}" alt="Escudo">
            @endif
        </div>

    </div>

    {{-- ===== CUERPO DEL CERTIFICADO ===== --}}
    <div class="body-text">

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
        <div class="sig-title">Párroco</div>
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
                <span class="sig-line">C U R A &nbsp; P Á R R O C O</span>
            </div>
        </div>

    </div>
    @endif

</body>
</html>