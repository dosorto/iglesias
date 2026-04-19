@php
    use Illuminate\Support\Facades\Storage;
    $comulgante      = $primeraComunion->feligres?->persona;
    $catequista      = $primeraComunion->catequista?->persona;
    $ministro        = $primeraComunion->ministro?->persona;
    $parroco         = $primeraComunion->parroco?->persona;
    $encargado       = $primeraComunion->encargado?->feligres?->persona;
    $encargadoModel  = $primeraComunion->encargado;
    $firmaEncargadoDisponible = filled($primeraComunion->encargado?->path_firma_principal);
    $configurarFirmaUrl = $encargadoModel
        ? route('encargado.edit', $encargadoModel)
        : route('encargado.create');
    $iglesiaNombre   = $iglesiaConfig?->nombre ?? $primeraComunion->iglesia?->nombre ?? '';
    $logoIglesia     = $iglesiaConfig?->logo_url ?? asset('image/Logo_guest.png');
    $logoIglesiaDerecha = $iglesiaConfig?->logo_derecha_url ?? $logoIglesia;

    $mesesEs = [
        1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',
        5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',
        9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre',
    ];

    $fc = $primeraComunion->fecha_primera_comunion;
    $diaComunion = $fc?->day ?? '';
    $mesComunion = $fc ? $mesesEs[$fc->month] : '';
    $anoComunion = $fc?->year ?? '';

    $diaExp = $this->exp_dia ?? '';
    $mesExp = ($this->exp_mes && isset($mesesEs[(int)$this->exp_mes])) ? $mesesEs[(int)$this->exp_mes] : '';
    $anoExp = $this->exp_ano ?? '';

    $estadoColor = $primeraComunion->fecha_expedicion
        ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'
        : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300';

    $lugar_celebracion = $this->lugar_celebracion ?? '';
    $lugar_expedicion  = $this->lugar_expedicion  ?? '';
    $nota_marginal     = $this->nota_marginal      ?? '';
    $auditHistory      = $this->auditHistory;
    $estadoRegistro    = $this->estadoRegistro ?? 'Borrador';

    $previewVersion = max(
        $primeraComunion->updated_at?->timestamp ?? 0,
        $iglesiaConfig?->updated_at?->timestamp ?? 0,
    );
    $pdfPreviewUrl = $firmaEncargadoDisponible
        ? route('primera-comunion.certificado.pdf', $primeraComunion) . '?v=' . ($previewVersion ?: time())
        : null;
@endphp

<div class="flex flex-col lg:flex-row gap-5 items-start">

    {{-- ======================= LEFT SIDEBAR ======================= --}}
    <aside class="w-full lg:w-56 xl:w-60 shrink-0 space-y-4">

        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-600 text-green-800 dark:text-green-200 px-3 py-2 rounded-lg text-xs">
                {{ session('success') }}
            </div>
        @endif

        {{-- ACCIONES --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">Acciones</p>
            <div class="space-y-2">
                @if ($firmaEncargadoDisponible)
                    <a href="{{ route('primera-comunion.certificado.pdf', $primeraComunion) }}" target="_blank"
                       class="flex items-center w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h6M9 13h6M9 9h1"/>
                        </svg>
                        Generar PDF
                    </a>
                @else
                    <div class="w-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-300 px-3 py-2 rounded-lg text-xs font-semibold">
                        Configure la firma del encargado para generar PDF.
                    </div>
                    @can($encargadoModel ? 'encargado.edit' : 'encargado.create')
                        <a href="{{ $configurarFirmaUrl }}"
                           class="flex items-center w-full bg-amber-500 hover:bg-amber-600 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                            <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Configurar firma
                        </a>
                    @endcan
                @endif

                @can('primera-comunion.edit')
                    <a href="{{ route('primera-comunion.edit', $primeraComunion) }}"
                       class="flex items-center w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar Primera Comunión
                    </a>
                @endcan

                <a href="{{ route('primera-comunion.index') }}"
                   class="flex items-center w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        {{-- INFORMACIÓN DEL LIBRO --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">Información del Libro</p>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">Parroquia</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide">
                        {{ strtoupper($primeraComunion->iglesia?->nombre ?? 'CHOLUTECA') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">Libro / Tomo</p>
                    <p class="text-sm font-mono font-semibold text-gray-800 dark:text-gray-100">
                        {{ $primeraComunion->libro_comunion ?? '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">Estado del Registro</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $estadoColor }}">
                        {{ $estadoRegistro }}
                    </span>
                </div>
            </div>
        </div>

        {{-- EXPEDICIÓN CONSTANCIA --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 space-y-3">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Expedición Certificado</p>

            <div>
                <label class="block text-xs text-gray-400 dark:text-gray-500 mb-1">Lugar</label>
                <input wire:model="lugar_expedicion" type="text"
                       class="w-full px-2 py-1.5 text-xs rounded-md border border-gray-300 dark:border-gray-600
                              bg-white dark:bg-gray-700 text-gray-900 dark:text-white
                              focus:ring-1 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-xs text-gray-400 dark:text-gray-500 mb-1">Día / Mes / Año</label>
                <div class="grid grid-cols-3 gap-1">
                    <input wire:model="exp_dia" type="number" min="1" max="31" placeholder="DD"
                           class="px-2 py-1.5 text-xs rounded-md border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700 text-gray-900 dark:text-white
                                  focus:ring-1 focus:ring-blue-500 focus:border-transparent">
                    <input wire:model="exp_mes" type="number" min="1" max="12" placeholder="MM"
                           class="px-2 py-1.5 text-xs rounded-md border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700 text-gray-900 dark:text-white
                                  focus:ring-1 focus:ring-blue-500 focus:border-transparent">
                    <input wire:model="exp_ano" type="number" min="0" max="99" placeholder="AA"
                           class="px-2 py-1.5 text-xs rounded-md border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700 text-gray-900 dark:text-white
                                  focus:ring-1 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-400 dark:text-gray-500 mb-1">Nota Marginal</label>
                <textarea wire:model="nota_marginal" rows="2"
                          class="w-full px-2 py-1.5 text-xs rounded-md border border-gray-300 dark:border-gray-600
                                 bg-white dark:bg-gray-700 text-gray-900 dark:text-white
                                 focus:ring-1 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
            </div>

            <button wire:click="saveCertificate"
                    class="w-full px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-colors">
                Guardar datos expedición
            </button>
        </div>

        {{-- HISTORIAL DE VERSIONES --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">Historial de Versiones</p>
            @if ($auditHistory->isNotEmpty())
                <div class="space-y-3">
                    @foreach ($auditHistory as $i => $log)
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                    v{{ $auditHistory->count() - $i }} &ndash; {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}
                                </p>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500">{{ $log->user_name ?? 'Sistema' }}</p>
                            </div>
                            <span class="text-[10px] uppercase font-medium px-1.5 py-0.5 rounded
                                {{ $log->event === 'created' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                                {{ $log->event === 'created' ? 'Nuevo' : 'Edit.' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                v1 &ndash; {{ $primeraComunion->created_at?->format('d/m/Y') }}
                            </p>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500">Sistema</p>
                        </div>
                        <span class="text-[10px] uppercase font-medium px-1.5 py-0.5 rounded bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                            Nuevo
                        </span>
                    </div>
                    @if ($primeraComunion->updated_at && $primeraComunion->updated_at->ne($primeraComunion->created_at))
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                    v2 &ndash; {{ $primeraComunion->updated_at?->format('d/m/Y') }}
                                </p>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500">Sistema</p>
                            </div>
                            <span class="text-[10px] uppercase font-medium px-1.5 py-0.5 rounded bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                Edit.
                            </span>
                        </div>
                    @endif
                </div>
            @endif
        </div>

    </aside>

    {{-- ======================= MAIN CONTENT ======================= --}}
    <div class="flex-1 min-w-0 space-y-4">

        {{-- TÍTULO --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Constancia de Primera Comunión</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $diaComunion ? "celebrada el {$diaComunion} de {$mesComunion} de {$anoComunion}" : '—' }}
                        @if($iglesiaNombre) &bull; {{ $iglesiaNombre }} @endif
                    </p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                    #{{ $primeraComunion->id }}
                </span>
            </div>
        </div>

        {{-- COMULGANTE --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">Comulgante</p>
            <div class="flex items-center gap-3 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700/40">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center shrink-0">
                    <span class="text-white text-sm font-bold">{{ strtoupper(substr($comulgante?->primer_nombre ?? '?', 0, 1)) }}</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $comulgante?->nombre_completo ?? '—' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">DNI: {{ $comulgante?->dni ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- MINISTROS --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">Catequista, Ministro y Párroco</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach ([
                    ['label' => 'Catequista', 'persona' => $catequista, 'color' => 'violet'],
                    ['label' => 'Ministro', 'persona' => $ministro, 'color' => 'teal'],
                    ['label' => 'Párroco', 'persona' => $parroco, 'color' => 'amber'],
                ] as $item)
                    <div class="p-3 rounded-lg border bg-{{ $item['color'] }}-50 dark:bg-{{ $item['color'] }}-900/20 border-{{ $item['color'] }}-200 dark:border-{{ $item['color'] }}-700/40">
                        <p class="text-xs font-semibold uppercase tracking-wide text-{{ $item['color'] }}-600 dark:text-{{ $item['color'] }}-400">{{ $item['label'] }}</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">{{ $item['persona']?->nombre_completo ?? '—' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">DNI: {{ $item['persona']?->dni ?? '—' }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- OBSERVACIONES --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">Nota Marginal</p>
            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $nota_marginal ?: '—' }}</p>
        </div>

        {{-- VISTA PREVIA CONSTANCIA --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Vista Previa de Constancia</p>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-gray-50 dark:bg-gray-900">
                @if ($firmaEncargadoDisponible)
                    <iframe
                        src="{{ $pdfPreviewUrl }}"
                        class="w-full h-[980px]"
                        title="Vista previa constancia de primera comunion">
                    </iframe>
                @else
                    <div class="p-6 text-sm text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-900/20">
                        <p>No se puede mostrar la vista previa ni generar PDF hasta configurar la firma del encargado.</p>
                        @can($encargadoModel ? 'encargado.edit' : 'encargado.create')
                            <a href="{{ $configurarFirmaUrl }}"
                               class="mt-3 inline-flex items-center bg-amber-500 hover:bg-amber-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-colors">
                                Configurar firma del encargado
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>