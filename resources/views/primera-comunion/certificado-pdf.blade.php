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
            padding: 30px 40px;
        }

        /* ── HEADER (3 columnas: logo iglesia | título | logo encargado) ── */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 18px;
        }
        .header-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            text-align: center;
        }
        .header-logo img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        .header-logo-placeholder {
            width: 70px;
            height: 70px;
            display: inline-block;
        }
        .header-title {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .diocese-name {
            font-size: 20pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .cert-title-box {
            display: inline-block;
            background: #000;
            color: #fff;
            font-size: 14pt;
            font-weight: bold;
            padding: 4px 18px;
            margin-top: 6px;
            border-radius: 3px;
        }

        /* ── DIVIDER ── */
        .divider {
            border: none;
            border-top: 1px solid #000;
            margin: 10px 0 14px;
        }

        /* ── BODY TEXT ── */
        .body-text {
            line-height: 2;
            font-size: 11.5pt;
        }
        .body-text p {
            margin-bottom: 4px;
        }
        .line-field {
            display: inline-block;
            min-width: 200px;
            border-bottom: 1px solid #000;
            margin: 0 4px;
            vertical-align: bottom;
        }
        .line-field-sm  { min-width: 80px; }
        .line-field-lg  { min-width: 280px; }
        .line-field-xl  { min-width: 340px; }
        .section-label  { font-weight: bold; }

        /* ── SIGNATURE AREA ── */
        .signatures {
            margin-top: 30px;
        }
        .sig-right {
            text-align: right;
            margin-bottom: 4px;
        }
        .sig-right .sig-line {
            display: inline-block;
            width: 220px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 2px;
            padding-top: 4px;
        }

        /* ── FOOTER ISSUANCE ── */
        .issuance {
            margin-top: 30px;
            font-size: 11.5pt;
            line-height: 2;
        }
        .sello {
            font-size: 10pt;
            font-style: italic;
            margin-top: 4px;
        }

        /* ── FIRMA FINAL (tabla: encargado izq | párroco der) ── */
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
            border-top: 1px solid #000;
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 2px;
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

        // Logo de la iglesia
        $logoIglesiaPath = ($iglesia && $iglesia->path_logo)
            ? public_path('storage/' . $iglesia->path_logo)
            : null;

        // Firma del párroco
        $firmaParrocoPath = ($parrocoModel && $parrocoModel->path_firma_principal)
            ? public_path('storage/' . $parrocoModel->path_firma_principal)
            : null;

        // Firma del encargado (viene del controlador como $encargado)
        $firmaEncargadoPath = (isset($encargado) && $encargado && $encargado->path_firma_principal)
            ? public_path('storage/' . $encargado->path_firma_principal)
            : null;

        $mesesEs = [
            1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',
            5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',
            9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre'
        ];

        $fechaComunion = $primeraComunion->fecha_primera_comunion;
        $diaComunion   = $fechaComunion ? $fechaComunion->day   : '';
        $mesComunion   = $fechaComunion ? $mesesEs[$fechaComunion->month] : '';
        $anoComunion   = $fechaComunion ? $fechaComunion->year  : '';

        $fechaExp  = $primeraComunion->fecha_expedicion;
        $diaExp    = $fechaExp ? $fechaExp->day             : '';
        $mesExp    = $fechaExp ? $mesesEs[$fechaExp->month] : '';
        $anoExpMil = $fechaExp ? ($fechaExp->year - 2000)   : '';

        $lugarCelebracion = $primeraComunion->lugar_celebracion ?? '';
        $lugarExp         = $primeraComunion->lugar_expedicion  ?? '';
        $notaMarginal     = $primeraComunion->nota_marginal     ?? '';
    @endphp

    {{-- ===== HEADER: logo iglesia | título | logo estático ===== --}}
    <div class="header">

        {{-- Logo de la iglesia (izquierda) --}}
        <div class="header-logo">
            @if ($logoIglesiaPath && file_exists($logoIglesiaPath))
                <img src="{{ $logoIglesiaPath }}" alt="Logo Parroquia">
            @else
                <span class="header-logo-placeholder"></span>
            @endif
        </div>

        {{-- Título central --}}
        <div class="header-title">
            <div class="diocese-name">{{ $iglesiaNombre ?: 'Diócesis de Choluteca' }}</div>
            <div class="cert-title-box">Certificación de Primera Comunión</div>
        </div>

        {{-- Logo estático del proyecto (derecha) --}}
        <div class="header-logo">
            <img src="{{ public_path('image/Logo_guest.png') }}" alt="Escudo">
        </div>

    </div>

    <hr class="divider">

    {{-- ===== BODY ===== --}}
    <div class="body-text">

        <p>
            El infrascrito, encargado del Archivo de la Parroquia de
            <span class="line-field line-field-lg">{{ $iglesiaNombre }}</span>
        </p>

        <p>
            <span class="section-label">CERTIFICA:</span>
            Que en el libro de Primera Comunión No.
            <span class="line-field line-field-sm">{{ $primeraComunion->libro_comunion }}</span>
            , en la Página
            <span class="line-field line-field-sm">{{ $primeraComunion->folio }}</span>
            , bajo el No.
            <span class="line-field line-field-sm">{{ $primeraComunion->partida_numero }}</span>
        </p>

        <p>Se encuentra la partida que dice:</p>

        <p>
            En
            <span class="line-field">{{ $iglesiaNombre }}</span>
            a
            <span class="line-field line-field-sm">{{ $diaComunion }}</span>
        </p>

        <p>
            de
            <span class="line-field">{{ $mesComunion }}</span>
            del año
            @if($anoComunion >= 2000)
                dos mil
                <span class="line-field line-field-sm">{{ $anoComunion - 2000 ?: '' }}</span>
            @else
                mil novecientos
                <span class="line-field line-field-sm">{{ $anoComunion ? ($anoComunion - 1900) : '' }}</span>
            @endif
        </p>

        <p>
            Impartió el Sacramento el P.
            <span class="line-field line-field-lg">{{ $ministro?->nombre_completo }}</span>
        </p>

        <p>
            <span class="line-field line-field-xl">{{ $comulgante?->nombre_completo }}</span>
        </p>

        <p>
            Catequista:
            <span class="line-field line-field-xl">{{ $catequista?->nombre_completo }}</span>
        </p>

        <p>
            En
            <span class="line-field line-field-xl">{{ $lugarCelebracion }}</span>
        </p>

    </div>

    {{-- ===== CURA PÁRROCO SIGNATURE ===== --}}
    <div class="signatures">
        <div class="sig-right">
            @if ($parroco?->nombre_completo)
                <p style="text-align:right; font-weight:bold; font-size:11pt; margin-bottom:4px;">
                    {{ $parroco->nombre_completo }}
                </p>
            @endif
            <div class="sig-line">C U R A &nbsp; P Á R R O C O</div>
        </div>
    </div>

    {{-- ===== NOTA MARGINAL ===== --}}
    <div class="body-text" style="margin-top:18px;">
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
        <p class="sello">(Sello)</p>
    </div>

    {{-- ===== FIRMAS FINALES: encargado izq | párroco der ===== --}}
    <div class="sig-footer">

        {{-- Encargado (izquierda) --}}
        <div class="sig-footer-cell">
            <div class="sig-block">
                @if ($firmaEncargadoPath && file_exists($firmaEncargadoPath))
                    <img src="{{ $firmaEncargadoPath }}" alt="Firma Encargado">
                @endif
                <span class="sig-line">E N C A R G A D O</span>
            </div>
        </div>

        {{-- Párroco (derecha) --}}
        <div class="sig-footer-cell right">
            <div class="sig-block">
                @if ($firmaParrocoPath && file_exists($firmaParrocoPath))
                    <img src="{{ $firmaParrocoPath }}" alt="Firma Párroco">
                @endif
                <span class="sig-line">C U R A &nbsp; P Á R R O C O</span>
            </div>
        </div>

    </div>

</body>
</html>