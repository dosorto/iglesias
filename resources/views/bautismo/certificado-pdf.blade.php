<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Certificación de Bautismo</title>
    <style>
        @page {
            margin: 0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #1c1c1c;
            font-size: 12pt;
            line-height: 1.45;
            background: #fff;
        }

        .page {
            position: relative;
            width: 100%;
            padding: 24px 58px 82px 48px;
            margin: 0;
            min-height: 0;
            height: auto;
            z-index: 2;
            background: transparent;
        }

        .watermark-logo {
            position: fixed;
            top: 32%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.055;
            z-index: 0;
        }

        .watermark-logo img {
            width: 360px;
            height: auto;
            object-fit: contain;
        }

        .header { display: table; width: 100%; table-layout: fixed; margin-bottom: 6px; }
        .header-logo-cell { display: table-cell; width: 82px; vertical-align: top; text-align: left; padding-top: 2px; }
        .header-logo-cell img { width: 70px; height: 70px; object-fit: contain; }
        .header-title-cell { display: table-cell; vertical-align: top; text-align: center; }
        .header-right-cell { display: table-cell; width: 90px; vertical-align: top; text-align: center; padding-top: 2px; padding-right: 20px; box-sizing: border-box; }
        .header-right-cell img { width: 62px; height: 62px; object-fit: contain; }

        .parish-name { font-size: 17pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; }
        .diocese-name { font-size: 13pt; font-weight: 700; text-transform: uppercase; margin-top: 2px; }
        .header-address { font-size: 11pt; font-weight: 700; margin-top: 2px; }

        .header-divider {
            border: none;
            border-top: 2px solid #6f99ad;
            margin: 6px 0 12px;
        }

        .doc-title {
            text-align: center;
            font-size: 15pt;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 14px;
            letter-spacing: 1px;
        }

        .text-block p {
            margin-bottom: 10px;
            text-align: justify;
        }

        .line {
            display: inline;
            padding: 0 1px;
        }

        .spacer-1 { height: 8px; }
        .spacer-2 { height: 14px; }

        .parish-signature {
            width: 300px;
            margin: 36px 145px 34px auto;
            text-align: center;
        }

        .parish-signature .signature-line {
            margin: 8px auto 0;
        }

        .signature-line {
            width: 250px;
            border-top: 1px solid #222;
            margin: 6px 0;
        }

        .signature-label {
            font-size: 12pt;
            font-weight: 700;
        }

        .signature-sub {
            font-size: 11pt;
            margin-top: 2px;
        }

        .nota-marginal {
            margin-top: 48px;
        }

        .nota-marginal-title {
            font-size: 11.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 6px;
        }

        .nota-line {
            border-bottom: 1px dotted #aaa;
            height: 22px;
        }

        .nota-text {
            font-size: 12pt;
            line-height: 1.5;
            min-height: 66px;
        }

        .nota-aclaratoria {
            margin-top: 8px;
            font-size: 11.5pt;
        }

        .expedicion {
            margin-top: 24px;
            text-align: center;
            font-size: 12pt;
        }

        .firma-area {
            margin-top: 64px;
            padding-bottom: 30mm;
        }

        .firma-table {
            margin: 0 auto;
            border-collapse: collapse;
        }

        .firma-table td {
            vertical-align: bottom;
            padding: 0;
        }

        .firma-cell { text-align: center; }
        .sello-cell { width: 110px; text-align: center; }

        .firma-encargado {
            text-align: center;
        }

        .firma-encargado .signature-line {
            width: 300px;
            margin: 8px auto 0;
        }

        .firma-img {
            max-height: 62px;
            max-width: 230px;
            margin-bottom: 2px;
        }

        .firma-nombre {
            font-size: 11pt;
            font-weight: 700;
            margin-top: 4px;
        }

        .sig-title {
            font-size: 10.5pt;
            color: #555;
            margin-top: 2px;
            letter-spacing: 0.6px;
        }

        .pie-institucional {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 2px solid #6f99ad;
            padding: 8px 17mm 10px;
            text-align: center;
            font-size: 10pt;
            color: #333;
            z-index: 3;
            background: #fff;
        }

        .pie-institucional span {
            display: inline-block;
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
    $logoIglesiaDerechaPath = $resolvePublicFilePath($iglesiaConfig?->path_logo_derecha) ?: $logoIglesiaPath;

    $bautizado = $bautismo->bautizado?->persona;
    $padre = $bautismo->padre?->persona;
    $madre = $bautismo->madre?->persona;
    $padrino = $bautismo->padrino?->persona;
    $madrina = $bautismo->madrina?->persona;
    $encargado = $bautismo->encargado?->feligres?->persona;

    $parroquiaNombre = $iglesiaConfig?->nombre ?? $bautismo->iglesia?->nombre ?? '';
    $parroquiaUpper  = mb_strtoupper($parroquiaNombre ?: 'PARROQUIA', 'UTF-8');
    $headerDiocesis  = $iglesiaConfig?->header_diocesis ?: 'Diócesis de Choluteca';
    $headerLugar     = $iglesiaConfig?->direccion ?: $bautismo->iglesia?->direccion ?: '';

    $mesesEs = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril', 5 => 'mayo', 6 => 'junio',
        7 => 'julio', 8 => 'agosto', 9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
    ];

    $fechaBautismo = $bautismo->fecha_bautismo;
    $diaBautismo = $fechaBautismo?->day ?? '';
    $mesBautismo = $fechaBautismo ? ($mesesEs[$fechaBautismo->month] ?? '') : '';

    $fechaNac = $bautizado?->fecha_nacimiento;
    $diaNac = $fechaNac?->day ?? '';
    $mesNac = $fechaNac ? ($mesesEs[$fechaNac->month] ?? '') : '';
    $anoNac = $fechaNac?->year ?? '';

    $lugarNac = $bautismo->lugar_nacimiento ?? '';

    $fechaExp = $bautismo->fecha_expedicion ?: now();
    $diaExp = $fechaExp?->day ?? '';
    $mesExp = $fechaExp ? ($mesesEs[$fechaExp->month] ?? '') : '';
    $anoExp = $fechaExp?->year ?? '';

    $lugarCelebracion = trim((string) ($bautismo->lugar_celebracion ?? ''));
    $lugarExp = trim((string) ($iglesiaConfig?->direccion ?? $bautismo->iglesia?->direccion ?? ''));
    if ($lugarExp === '') {
        $lugarExp = 'Monjarás, Marcovia, Choluteca, Honduras C. A.';
    }

    $ministroBautismo = $bautismo->ministro?->persona;
    $sacerdoteBautizo = mb_strtoupper(trim((string) (
        $ministroBautismo?->nombre_completo
        ?: ($bautismo->ministro_celebrante ?? '')
        ?: ($encargado?->nombre_completo ?? '')
    )), 'UTF-8');
    $ministroCelebrante = mb_strtoupper(trim((string) (
        ($bautismo->parroco_celebrante ?? '')
        ?: ($bautismo->ministro_celebrante ?? '')
    )), 'UTF-8');
    $firmaEncargadoNombre = mb_strtoupper(trim((string) ($encargado?->nombre_completo ?? '')), 'UTF-8');

    $firmaPath = $resolvePublicFilePath($bautismo->encargado?->path_firma_principal);

    $notaMarginal = (string) ($bautismo->nota_marginal ?? '');
    $notaAclaratoria = (string) ($bautismo->observaciones ?? '');

    $nombreBautizado = mb_strtoupper(trim((string) ($bautizado?->nombre_completo ?? '')), 'UTF-8');
    $nombrePadre = mb_strtoupper(trim((string) ($padre?->nombre_completo ?? '')), 'UTF-8');
    $nombreMadre = mb_strtoupper(trim((string) ($madre?->nombre_completo ?? '')), 'UTF-8');
    $nombrePadrino = mb_strtoupper(trim((string) ($padrino?->nombre_completo ?? '')), 'UTF-8');
    $nombreMadrina = mb_strtoupper(trim((string) ($madrina?->nombre_completo ?? '')), 'UTF-8');

    $filiacion = collect([$nombrePadre, $nombreMadre])->filter()->implode(' y ');
    $padrinos = collect([$nombrePadrino, $nombreMadrina])->filter()->implode(' y ');

    $pieDireccion = trim((string) ($iglesiaConfig?->direccion ?? $bautismo->iglesia?->direccion ?? ''));
    $pieTelefono = trim((string) ($iglesiaConfig?->telefono ?? ''));
    $pieEmail = trim((string) ($iglesiaConfig?->email ?? ''));
@endphp
<body @if($certBgPath && file_exists($certBgPath)) style="background-image: url('{{ $certBgPath }}'); background-size: cover; background-position: center; background-repeat: no-repeat;" @endif>
@if($logoIglesiaPath)
    <div class="watermark-logo">
        <img src="{{ $logoIglesiaPath }}" alt="Marca de agua">
    </div>
@endif

<div class="page">

    <div class="header">
        <div class="header-logo-cell">
            @if($logoIglesiaPath)
                <img src="{{ $logoIglesiaPath }}" alt="Logo parroquia">
            @endif
        </div>
        <div class="header-title-cell">
            <div class="parish-name">{{ $parroquiaUpper }}</div>
            <div class="diocese-name">{{ $headerDiocesis }}</div>
            @if($headerLugar)<div class="header-address">{{ $headerLugar }}</div>@endif
        </div>
        <div class="header-right-cell">
            @if($logoIglesiaDerechaPath)
                <img src="{{ $logoIglesiaDerechaPath }}" alt="Logo parroquia">
            @endif
        </div>
    </div>
    <hr class="header-divider">

    <div class="doc-title">CERTIFICACIÓN DE BAUTISMO</div>

    <div class="text-block">
        <p>
            El Infrascrito encargado del archivo de la "{{ $parroquiaUpper }}", Monjarás, Marcovia,
            Choluteca.
        </p>
        <p>
            Certifica: Que en el libro de bautismos N° <span class="line">{{ $bautismo->libro_bautismo ?? '' }}</span>
            en la página <span class="line">{{ $bautismo->folio ?? '' }}</span>
            bajo el N° <span class="line">{{ $bautismo->partida_numero ?? '' }}</span> la partida que dice:
        </p>

        <div class="spacer-1"></div>

        <p>
            En <span class="line">{{ $lugarCelebracion }}</span>
            a los <span class="line">{{ $diaBautismo }}</span>
            días del mes de <span class="line">{{ $mesBautismo }}</span>
            bauticé  (P. <span class="line">{{ $sacerdoteBautizo }}</span>)
        </p>
        <p>
            a: <span class="line">{{ $nombreBautizado }}</span>
            que nació en <span class="line">{{ $lugarNac }}</span>,
            a los <span class="line">{{ $diaNac }}</span> días del mes de <span class="line">{{ $mesNac }}</span>
            de <span class="line">{{ $anoNac }}</span>.
        </p>

        @if($filiacion)
        <p>
            Hijo(a) de <span class="line">{{ $filiacion }}</span>
        </p>
        @endif
        @if($padrinos)
        <p>
            Padrinos: <span class="line">{{ $padrinos }}</span>
        </p>
        @endif
    </div>

    <div class="parish-signature">
        <div class="signature-label">{{ $ministroCelebrante }}</div>
        <div class="signature-line"></div>
        <div class="signature-sub">Párroco</div>
    </div>

    <div class="nota-marginal">
        <div class="nota-marginal-title">Nota Marginal</div>
        @if($notaMarginal)
            <div class="nota-text">{{ $notaMarginal }}</div>
        @else
            <div class="nota-line"></div>
            <div class="nota-line"></div>
            <div class="nota-line"></div>
        @endif
        @if($notaAclaratoria)
            <div class="nota-aclaratoria">Nota Aclaratoria: {{ $notaAclaratoria }}</div>
        @endif
    </div>

    <div class="expedicion">
        Dado en {{ $lugarExp }} a los <span class="line">{{ $diaExp }}</span>
        días del mes de <span class="line">{{ $mesExp }}</span>
        del año <span class="line">{{ $anoExp }}</span>
    </div>

    <div class="firma-area">
        <table class="firma-table">
            <tr>
                <td class="firma-cell">
                    <div class="firma-encargado">
                        @if($firmaPath)
                            <img src="{{ $firmaPath }}" class="firma-img" alt="Firma encargado">
                        @endif
                        <div class="signature-line"></div>
                        <div class="firma-nombre">{{ $firmaEncargadoNombre }}</div>
                        <div class="sig-title">Encargado del Archivo</div>
                    </div>
                </td>
                <td class="sello-cell"></td>
            </tr>
        </table>
    </div>

</div>

<div class="pie-institucional">
    @if($pieDireccion)<span>Dirección: {{ $pieDireccion }}</span>@endif
    @if($pieDireccion && $pieTelefono) | @endif
    @if($pieTelefono)<span>Tel.: {{ $pieTelefono }}</span>@endif
    @if(($pieDireccion || $pieTelefono) && $pieEmail) | @endif
    @if($pieEmail)<span>Correo: {{ $pieEmail }}</span>@endif
</div>

</body>
</html>
