<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Feligresía</title>
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
            opacity: 0.075;
            z-index: 0;
        }

        .watermark-logo img {
            width: 390px;
            height: auto;
            object-fit: contain;
        }

        .page-wrapper {
            padding: 26px 44px 30px;
            border: none;
            margin: 8px;
            position: relative;
            z-index: 2;
            background: transparent;
        }

        .header { display: table; width: 100%; margin-bottom: 8px; }
        .header-logo-cell { display: table-cell; width: 88px; vertical-align: top; text-align: left; padding-top: 2px; }
        .header-logo-cell img { width: 80px; height: 80px; object-fit: contain; }
        .header-title-cell { display: table-cell; vertical-align: top; text-align: center; }
        .header-right-cell { display: table-cell; width: 88px; vertical-align: top; text-align: right; padding-top: 2px; }
        .header-right-cell img { width: 80px; height: 80px; object-fit: contain; }

        .parish-name { font-size: 19pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.7px; line-height: 1.1; }
        .diocese-name { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin-top: 3px; }
        .header-address { font-size: 12pt; font-weight: bold; margin-top: 3px; }

        .header-divider { border: none; border-top: 1px solid #6f99ad; margin: 7px 0 14px; }

        .doc-title {
            text-align: center;
            font-size: 15.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 22px;
        }

        .cert-intro { font-size: 12pt; margin-bottom: 14px; line-height: 1.6; text-align: justify; }

        .certifica-label {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 14px 0 14px;
        }

        .name-display {
            text-align: center;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 18px;
        }

        .cert-block { font-size: 12pt; line-height: 1.7; text-align: justify; }
        .cert-block p { margin-bottom: 10px; }

        .issuance { font-size: 12pt; line-height: 1.6; margin-top: 24px; text-align: justify; }

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

        .signature-block { text-align: center; }
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
            padding-top: 4px;
        }
        .sig-name { font-size: 11pt; font-weight: bold; color: #1a1a1a; margin-top: 4px; }
        .sig-title { font-size: 10.5pt; color: #555; margin-top: 2px; letter-spacing: 0.6px; }
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

    $logoIglesiaPath        = $resolvePublicFilePath($iglesiaConfig?->path_logo);
    $logoIglesiaDerechaPath = $resolvePublicFilePath($iglesiaConfig?->path_logo_derecha) ?: $logoIglesiaPath;

    if (! $logoIglesiaPath) {
        $logoEstaticoPath = public_path('image/Logo_guest.png');
        if (is_file($logoEstaticoPath)) $logoIglesiaPath = $logoEstaticoPath;
    }
    if (! $logoIglesiaDerechaPath) $logoIglesiaDerechaPath = $logoIglesiaPath;

    $persona       = $feligre->persona;

    $iglesiaNombre   = $iglesiaConfig?->nombre ?? $feligre->iglesia?->nombre ?? '';
    $headerDiocesis  = $iglesiaConfig?->header_diocesis ?: 'Diócesis de Choluteca';
    $headerLugar     = $iglesiaConfig?->direccion ?: '';

    $parrocoNombreUpper = mb_strtoupper(trim((string) ($parrocoNombre ?? '')), 'UTF-8');
    $nombreFeligres     = mb_strtoupper($persona?->nombre_completo ?? '', 'UTF-8');
    $dniFeligres        = $persona?->dni ?? '';

    $mesesEs = [
        1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',
        7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre',
    ];

    $fechaExp = now();
    $diaExp = $fechaExp->day;
    $mesExp = $mesesEs[$fechaExp->month];
    $anoExp = $fechaExp->year;

    $lugarExp  = trim((string) ($iglesiaConfig?->direccion ?? ''));
    if ($lugarExp === '') $lugarExp = trim((string) ($feligre->iglesia?->direccion ?? ''));
    if ($lugarExp === '') $lugarExp = 'Monjarás, Marcovia, Choluteca, Honduras C. A.';

    $firmaPath = $firmaParrocoPath ?? null;

    $tieneBautismo = $feligre->bautismos?->isNotEmpty() ?? false;
    $condicion = $tieneBautismo ? 'miembro activo y bautizado' : 'miembro activo';
@endphp
<body>
@if ($logoIglesiaPath)
    <div class="watermark-logo">
        <img src="{{ $logoIglesiaPath }}" alt="Marca de agua">
    </div>
@endif
<div class="page-wrapper">

    {{-- HEADER --}}
    <div class="header">
        <div class="header-logo-cell">
            @if ($logoIglesiaPath)<img src="{{ $logoIglesiaPath }}" alt="Logo">@endif
        </div>
        <div class="header-title-cell">
            <div class="parish-name">{{ mb_strtoupper($iglesiaNombre, 'UTF-8') }}</div>
            <div class="diocese-name">{{ $headerDiocesis }}</div>
            @if ($headerLugar)<div class="header-address">{{ $headerLugar }}</div>@endif
        </div>
        <div class="header-right-cell">
            @if ($logoIglesiaDerechaPath)<img src="{{ $logoIglesiaDerechaPath }}" alt="Logo">@endif
        </div>
    </div>

    <hr class="header-divider">

    {{-- TÍTULO --}}
    <div class="doc-title">CONSTANCIA DE FELIGRES&Iacute;A</div>

    {{-- CUERPO --}}
    <p class="cert-intro">
        El suscrito{!! $parrocoNombreUpper !== '' ? ', <strong>' . e($parrocoNombreUpper) . '</strong>' : '' !!},
        P&aacute;rroco de la <strong>{{ $iglesiaNombre }}</strong>,
        perteneciente a la <strong>{{ $headerDiocesis }}</strong>.
    </p>

    <div class="certifica-label">CERTIFICA:</div>

    <div class="cert-block">
        <p>
            Que
        </p>
    </div>

    <div class="name-display">{{ $nombreFeligres }}</div>

    <div class="cert-block">
        <p>
            identificado(a) con DNI No. <strong>{{ $dniFeligres }}</strong>,
            es {{ $condicion }} de nuestra comunidad parroquial.
        </p>
        <p>
            El/la mencionado(a) feligr&eacute;s(a) participa activamente en la vida
            sacramental y parroquial de esta instituci&oacute;n.
        </p>
    </div>

    <div class="issuance">
        <p>
            Se extiende la presente constancia para los fines que al interesado(a) convengan,
            en {{ $lugarExp }} a los {{ $diaExp }} d&iacute;as del mes de {{ $mesExp }} de {{ $anoExp }}.
        </p>
    </div>

    {{-- FIRMAS --}}
    <div class="bottom-signatures">
        <div class="seal-cell"></div>
        <div class="signature-cell">
            <div class="signature-block">
                @if ($firmaPath)
                    <img src="{{ $firmaPath }}" alt="Firma" class="sig-img">
                @else
                    <div style="height: 65px;"></div>
                @endif
                <div><span class="sig-line"></span></div>
                <div class="sig-name">{{ $parrocoNombreUpper }}</div>
                <div class="sig-title">P&aacute;rroco</div>
            </div>
        </div>
    </div>

</div>
</body>
</html>
