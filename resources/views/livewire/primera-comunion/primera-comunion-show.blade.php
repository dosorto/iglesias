<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalle de Primera Comunión</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Información completa del registro</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('primera-comunion.edit')
                <a href="{{ route('primera-comunion.edit', $primeraComunion) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>
            @endcan
            <button onclick="window.print()"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar PDF
            </button>
            <a href="{{ route('primera-comunion.index') }}"
               class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    {{-- Documento imprimible --}}
    <div id="documento-print">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Datos del evento --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Datos de la Primera Comunión
                </h2>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Iglesia</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $primeraComunion->iglesia?->nombre ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Fecha</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $primeraComunion->fecha_primera_comunion?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Libro</dt>
                        <dd class="text-sm font-mono text-gray-900 dark:text-white">{{ $primeraComunion->libro_comunion ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Folio</dt>
                        <dd class="text-sm font-mono text-gray-900 dark:text-white">{{ $primeraComunion->folio ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Partida N°</dt>
                        <dd class="text-sm font-mono text-gray-900 dark:text-white">{{ $primeraComunion->partida_numero ?? '—' }}</dd>
                    </div>
                    @if($primeraComunion->observaciones)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Observaciones</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ $primeraComunion->observaciones }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Personas --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                    Personas Involucradas
                </h2>
                <dl class="space-y-3">
                    @foreach ([
                        'Comulgante' => $primeraComunion->feligres,
                        'Catequista' => $primeraComunion->catequista,
                        'Ministro'   => $primeraComunion->ministro,
                        'Párroco'    => $primeraComunion->parroco,
                    ] as $rol => $feligres)
                        <div class="flex justify-between items-center">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">{{ $rol }}</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white text-right">
                                @if($feligres?->persona)
                                    <span>{{ $feligres->persona->nombre_completo }}</span>
                                    <span class="block text-xs font-mono text-gray-400">{{ $feligres->persona->dni }}</span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">—</span>
                                @endif
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>

        {{-- Sección imprimible: documento formal --}}
        <div class="hidden-screen" id="doc-formal">
            <div style="font-family:'Times New Roman',serif; padding:15mm 18mm; background:white;">

                {{-- Encabezado --}}
                <div style="text-align:center; margin-bottom:10mm;">
                    <div style="font-size:13pt; font-weight:bold; text-transform:uppercase; letter-spacing:1px;">
                        {{ $primeraComunion->iglesia?->nombre ?? 'Iglesia' }}
                    </div>
                    <hr style="border:none; border-top:2px solid #000; margin:4mm 0 3mm;">
                    <div style="font-size:15pt; font-weight:bold; text-transform:uppercase; letter-spacing:2px;">
                        Constancia de Primera Comunión
                    </div>
                    <div style="font-size:10pt; font-style:italic; color:#555; margin-top:2mm;">
                        Registro Sacramental — Sacramento de la Eucaristía
                    </div>
                    <hr style="border:none; border-top:1px solid #000; margin:4mm 0;">
                </div>

                {{-- Datos generales --}}
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:3mm 5mm; margin-bottom:8mm; font-size:9.5pt;">
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-weight:bold; font-size:8pt; text-transform:uppercase; color:#444;">Iglesia</span>
                        <span style="border-bottom:1px solid #999; padding-bottom:1mm; margin-top:1mm;">{{ $primeraComunion->iglesia?->nombre ?? '—' }}</span>
                    </div>
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-weight:bold; font-size:8pt; text-transform:uppercase; color:#444;">Fecha del Sacramento</span>
                        <span style="border-bottom:1px solid #999; padding-bottom:1mm; margin-top:1mm;">{{ $primeraComunion->fecha_primera_comunion?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-weight:bold; font-size:8pt; text-transform:uppercase; color:#444;">Párroco</span>
                        <span style="border-bottom:1px solid #999; padding-bottom:1mm; margin-top:1mm;">{{ $primeraComunion->iglesia?->parroco_nombre ?? '—' }}</span>
                    </div>
                </div>

                {{-- Tabla participantes --}}
                <div style="margin-top:6mm;">
                    <div style="font-size:10pt; font-weight:bold; text-transform:uppercase; letter-spacing:1px; margin-bottom:3mm; text-align:center;">
                        Participantes del Sacramento
                    </div>
                    <table style="width:100%; border-collapse:collapse; font-size:9.5pt;">
                        <thead>
                            <tr style="background:#1e293b; color:white;">
                                <th style="padding:3mm 4mm; text-align:left; font-size:8.5pt; text-transform:uppercase;">Rol</th>
                                <th style="padding:3mm 4mm; text-align:left; font-size:8.5pt; text-transform:uppercase;">Nombre Completo</th>
                                <th style="padding:3mm 4mm; text-align:left; font-size:8.5pt; text-transform:uppercase;">DNI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ([
                                ['rol' => 'Comulgante', 'bg' => '#dbeafe', 'color' => '#1d4ed8', 'feligres' => $primeraComunion->feligres],
                                ['rol' => 'Catequista', 'bg' => '#dcfce7', 'color' => '#16a34a', 'feligres' => $primeraComunion->catequista],
                                ['rol' => 'Ministro',   'bg' => '#fef9c3', 'color' => '#a16207', 'feligres' => $primeraComunion->ministro],
                                ['rol' => 'Párroco',    'bg' => '#fce7f3', 'color' => '#9d174d', 'feligres' => $primeraComunion->parroco],
                            ] as $i => $p)
                                <tr style="{{ $i % 2 === 1 ? 'background:#f8fafc;' : '' }}">
                                    <td style="padding:3mm 4mm; border-bottom:1px solid #e2e8f0;">
                                        <span style="background:{{ $p['bg'] }}; color:{{ $p['color'] }}; padding:1px 8px; border-radius:20px; font-size:7.5pt; font-weight:bold; text-transform:uppercase;">{{ $p['rol'] }}</span>
                                    </td>
                                    <td style="padding:3mm 4mm; border-bottom:1px solid #e2e8f0;">{{ $p['feligres']?->persona?->nombre_completo ?? '—' }}</td>
                                    <td style="padding:3mm 4mm; border-bottom:1px solid #e2e8f0;">{{ $p['feligres']?->persona?->dni ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Datos de registro --}}
                <div style="margin-top:8mm; border:1px solid #cbd5e1; padding:5mm;">
                    <div style="font-size:9pt; font-weight:bold; text-transform:uppercase; margin-bottom:4mm; color:#475569;">
                        Datos de Registro
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:3mm 5mm; font-size:9pt;">
                        <div style="display:flex; flex-direction:column;">
                            <span style="font-weight:bold; font-size:8pt; text-transform:uppercase; color:#444;">Libro</span>
                            <span style="border-bottom:1px solid #999; padding-bottom:1mm; margin-top:1mm;">{{ $primeraComunion->libro_comunion ?? '—' }}</span>
                        </div>
                        <div style="display:flex; flex-direction:column;">
                            <span style="font-weight:bold; font-size:8pt; text-transform:uppercase; color:#444;">Folio</span>
                            <span style="border-bottom:1px solid #999; padding-bottom:1mm; margin-top:1mm;">{{ $primeraComunion->folio ?? '—' }}</span>
                        </div>
                        <div style="display:flex; flex-direction:column;">
                            <span style="font-weight:bold; font-size:8pt; text-transform:uppercase; color:#444;">Partida N°</span>
                            <span style="border-bottom:1px solid #999; padding-bottom:1mm; margin-top:1mm;">{{ $primeraComunion->partida_numero ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Observaciones --}}
                @if($primeraComunion->observaciones)
                    <div style="margin-top:6mm; font-size:9pt;">
                        <div style="font-weight:bold; text-transform:uppercase; font-size:8pt; color:#444;">Observaciones</div>
                        <div style="border:1px solid #cbd5e1; padding:3mm; min-height:12mm; margin-top:2mm;">{{ $primeraComunion->observaciones }}</div>
                    </div>
                @endif

                {{-- Firmas --}}
                <div style="margin-top:20mm; display:grid; grid-template-columns:1fr 1fr; gap:10mm;">
                    <div style="text-align:center;">
                        <div style="border-top:1px solid #000; padding-top:2mm; font-size:9pt;">
                            {{ $primeraComunion->parroco?->persona?->nombre_completo ?? '___________________________' }}
                        </div>
                        <div style="font-size:8pt; color:#555; margin-top:1mm;">Párroco / Celebrante</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="border-top:1px solid #000; padding-top:2mm; font-size:9pt;">
                            {{ $primeraComunion->feligres?->persona?->nombre_completo ?? '___________________________' }}
                        </div>
                        <div style="font-size:8pt; color:#555; margin-top:1mm;">Comulgante</div>
                    </div>
                </div>

                {{-- Pie --}}
                <div style="margin-top:15mm; text-align:center; font-size:8pt; color:#94a3b8; border-top:1px solid #e2e8f0; padding-top:4mm;">
                    Documento generado el {{ now()->format('d/m/Y H:i') }} &nbsp;·&nbsp; {{ $primeraComunion->iglesia?->nombre ?? '' }}
                    @if($primeraComunion->iglesia?->email) &nbsp;·&nbsp; {{ $primeraComunion->iglesia->email }} @endif
                </div>

            </div>
        </div>

    </div>

    <style>
        /* En pantalla: ocultar documento formal */
        .hidden-screen { display: none !important; }

        /* Al imprimir: solo mostrar documento formal */
        @media print {
            html, body {
                background: white !important;
                background-color: white !important;
            }
            body * {
                visibility: hidden !important;
                background: transparent !important;
            }
            #doc-formal {
                visibility: visible !important;
                display: block !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                background: white !important;
                z-index: 99999 !important;
            }
            #doc-formal * {
                visibility: visible !important;
                background: transparent !important;
                color: black !important;
            }
            #doc-formal table thead tr {
                background: #1e293b !important;
            }
            #doc-formal table thead tr th {
                color: white !important;
            }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</div>