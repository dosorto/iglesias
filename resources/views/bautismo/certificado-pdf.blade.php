<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Certificación de Bautismo</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #1c1c1c;
            font-size: 12pt;
            line-height: 1.45;
            background: #fff;
        }

        .page {
            padding: 26px 38px 30px;
            position: relative;
            margin: 8px;
            border: none;
            z-index: 2;
            background: transparent;
        }

        .watermark-logo {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.075;
            z-index: 0;
        }

        .watermark-logo img {
            width: 390px;
            height: auto;
            object-fit: contain;
        }

        .header { display: table; width: 100%; margin-bottom: 8px; }
        .header-logo-cell { display: table-cell; width: 88px; vertical-align: top; text-align: left; padding-top: 2px; }
        .header-logo-cell img { width: 80px; height: 80px; object-fit: contain; }
        .header-title-cell { display: table-cell; vertical-align: top; text-align: center; }
        .header-right-cell { display: table-cell; width: 88px; vertical-align: top; text-align: right; padding-top: 2px; }
        .header-right-cell img { width: 80px; height: 80px; object-fit: contain; }

        .parish-name { font-size: 19pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; }
        .diocese-name { font-size: 14pt; font-weight: 700; text-transform: uppercase; margin-top: 3px; }
        .header-address { font-size: 12pt; font-weight: 700; margin-top: 3px; }

        .header-divider {
            border: none;
            border-top: 1px solid #6f99ad;
            margin: 7px 0 14px;
        }

        .doc-title {
            text-align: center;
            font-size: 15.5pt;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 12px;
            letter-spacing: 0.4px;
        }

        .text-block p {
            margin-bottom: 8px;
        }

        .line {
            display: inline;
            padding: 0 1px;
        }


        .spacer-1 { height: 10px; }
        .spacer-2 { height: 18px; }
        .spacer-3 { height: 26px; }

        .signature-center {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .signature-line {
            width: 250px;
            margin: 0 auto 6px;
            border-top: 1px solid #222;
        }

        .signature-label {
            font-size: 12pt;
            font-weight: 700;
        }

        .signature-sub {
            font-size: 11pt;
            margin-top: 2px;
        }

        .notes p {
            margin-bottom: 8px;
        }

        .bottom-section {
            margin-top: 42px;
        }

        .bottom-signatures {
            display: table;
            width: 100%;
            margin-top: 20px;
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

        .signature-bottom {
            text-align: center;
            margin-top: 0;
        }

        .signature-bottom .signature-line {
            width: 285px;
        }

        .firma-img {
            max-height: 58px;
            max-width: 210px;
            margin-bottom: 2px;
        }

        .firma-nombre {
            font-size: 11pt;
            font-weight: 700;
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

    $lugarExp = trim((string) ($iglesiaConfig?->direccion ?? ''));
    if ($lugarExp === '') {
        $lugarExp = trim((string) ($bautismo->iglesia?->direccion ?? ''));
    }
    if ($lugarExp === '') {
        $lugarExp = trim((string) ($bautismo->lugar_expedicion ?? ''));
    }
    if ($lugarExp === '') {
        $lugarExp = 'Monjaras, Marcovia, Choluteca, Honduras C. A.';
    }

    $parrocoCelebrante = mb_strtoupper(trim((string) ($bautismo->parroco_celebrante ?: ($encargado?->nombre_completo ?? ''))), 'UTF-8');
    $firmaEncargadoNombre = mb_strtoupper(trim((string) ($encargado?->nombre_completo ?? '')), 'UTF-8');

    $firmaPath = $resolvePublicFilePath($bautismo->encargado?->path_firma_principal);

    $notaMarginal = (string) ($bautismo->nota_marginal ?? '');
    $notaAclaratoria = (string) ($bautismo->observaciones ?? '');

    $nombreBautizado = mb_strtoupper(trim((string) ($bautizado?->nombre_completo ?? '')), 'UTF-8');
    $nombrePadre = mb_strtoupper(trim((string) ($padre?->nombre_completo ?? '')), 'UTF-8');
    $nombreMadre = mb_strtoupper(trim((string) ($madre?->nombre_completo ?? '')), 'UTF-8');
    $nombrePadrino = mb_strtoupper(trim((string) ($padrino?->nombre_completo ?? '')), 'UTF-8');
    $nombreMadrina = mb_strtoupper(trim((string) ($madrina?->nombre_completo ?? '')), 'UTF-8');
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

    <div class="doc-title">CERTIFICACION DE BAUTISMO</div>

    <div class="text-block">
        <p>
            El Infrascrito encargado del archivo de la Parroquia "{{ $parroquiaUpper }}", Monjaras, Marcovia,
            Choluteca.
        </p>
        <p>
            Certifica: Que en el libro de bautismos N° <span class="line line-xs">{{ $bautismo->libro_bautismo ?? '' }}</span>
            en la pagina <span class="line line-xs">{{ $bautismo->folio ?? '' }}</span>
            bajo el N° <span class="line line-xs">{{ $bautismo->partida_numero ?? '' }}</span>
        </p>
        <p>la partida que dice:</p>

        <div class="spacer-1"></div>

        <p>
            En <span class="line line-lg">{{ $parroquiaNombre }}</span>
            a los <span class="line line-xxs">{{ $diaBautismo }}</span>
            dias del mes de <span class="line line-md">{{ $mesBautismo }}</span>
        </p>
        <p>
            (P. <span class="line line-xl">{{ $firmaEncargadoNombre }}</span>)
        </p>
        <p>
            a: <span class="line line-xl">{{ $nombreBautizado }}</span>
            que nacio en <span class="line line-md">{{ $lugarNac }}</span>
        </p>
        <p>
            <span class="line line-xxs">{{ $diaNac }}</span>
            dias del mes de <span class="line line-md">{{ $mesNac }}</span>
            de: <span class="line line-md">{{ $anoNac }}</span>
        </p>
        <p>
            Hijo(a) de <span class="line line-lg">{{ $nombrePadre }}</span>
            y <span class="line line-lg">{{ $nombreMadre }}</span>
        </p>
        <p>
            Padrinos: <span class="line line-lg">{{ $nombrePadrino }}</span>
            y <span class="line line-lg">{{ $nombreMadrina }}</span>
        </p>
    </div>

    <div class="signature-center">
        <div class="signature-label">{{ $parrocoCelebrante }}</div>
        <div class="signature-line"></div>
        <div class="signature-sub">Cura Parroco</div>
    </div>

    <div class="notes">
        @if($notaMarginal)
        <p>Nota Marginal: <span class="line line-xl">{{ $notaMarginal }}</span></p>
        @endif
        @if($notaAclaratoria)
        <p>Nota Aclaratoria: <span class="line line-xl">{{ $notaAclaratoria }}</span></p>
        @endif
    </div>

    <div class="bottom-section">
        <p>
            Dado en {{ $lugarExp }} a los <span class="line line-xs">{{ $diaExp }}</span>
            dias del mes de <span class="line line-md">{{ $mesExp }}</span>
            del año <span class="line line-sm">{{ $anoExp }}</span>
        </p>

        <div class="bottom-signatures">
            <div class="seal-cell"></div>
            <div class="signature-cell">
                <div class="signature-bottom">
                    @if($firmaPath)
                        <img src="{{ $firmaPath }}" class="firma-img" alt="Firma encargado">
                    @endif
                    <div class="signature-line"></div>
                    <div class="firma-nombre">{{ $firmaEncargadoNombre }}</div>
                </div>
            </div>
        </div>
    </div>


</div>
</body>
</html>
