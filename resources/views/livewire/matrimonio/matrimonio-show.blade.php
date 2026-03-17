@php
    use Illuminate\Support\Facades\Storage;
    $esposo    = $matrimonio->esposo?->persona;
    $esposa    = $matrimonio->esposa?->persona;
    $testigo1  = $matrimonio->testigo1?->persona;
    $testigo2  = $matrimonio->testigo2?->persona;
    $encargado = $matrimonio->encargado?->feligres?->persona;
    $iglesiaNombre = $iglesiaConfig?->nombre ?? $matrimonio->iglesia?->nombre ?? '';

    $mesesEs = [
        1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',
        5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',
        9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre',
    ];

    $fm = $matrimonio->fecha_matrimonio;
    $diaMatrimonio = $fm?->day ?? '';
    $mesMatrimonio = $fm ? $mesesEs[$fm->month] : '';
    $anoMatrimonio = $fm?->year ?? '';

    $diaExp = $exp_dia ?? '';
    $mesExp = ($exp_mes && isset($mesesEs[(int)$exp_mes])) ? $mesesEs[(int)$exp_mes] : '';
    $anoExp = $exp_ano ?? '';

    $pdfPreviewUrl = route('matrimonio.certificado.pdf', $matrimonio) . '?v=' . ($matrimonio->updated_at?->timestamp ?? time());
@endphp

<div class="flex flex-col lg:flex-row gap-5 items-start">

    {{-- ======================= LEFT SIDEBAR ======================= --}}
    <aside class="w-full lg:w-56 xl:w-60 shrink-0 space-y-4">

        {{-- Flash --}}
        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-600 text-green-800 dark:text-green-200 px-3 py-2 rounded-lg text-xs">
                {{ session('success') }}
            </div>
        @endif

        {{-- ACCIONES --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">Acciones</p>
            <div class="space-y-2">
                <a href="{{ route('matrimonio.certificado.pdf', $matrimonio) }}" target="_blank"
                   class="flex items-center w-full bg-rose-600 hover:bg-rose-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h6M9 13h6M9 9h1"/>
                    </svg>
                    Generar Constancia PDF
                </a>

                @can('matrimonio.edit')
                    <a href="{{ route('matrimonio.edit', $matrimonio) }}"
                       class="flex items-center w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar Matrimonio
                    </a>
                @endcan

                <a href="{{ route('matrimonio.index') }}"
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
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Iglesia</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-100 tracking-wide">{{ strtoupper($iglesiaNombre ?: '—') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Libro</p>
                    <p class="font-mono font-semibold text-gray-800 dark:text-gray-100">{{ $matrimonio->libro_matrimonio ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Folio</p>
                    <p class="font-mono font-semibold text-gray-800 dark:text-gray-100">{{ $matrimonio->folio ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Partida N°</p>
                    <p class="font-mono font-semibold text-gray-800 dark:text-gray-100">{{ $matrimonio->partida_numero ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- DATOS DE EXPEDICIÓN --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 space-y-3">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Expedición Constancia</p>

            <div>
                <label class="block text-xs text-gray-400 dark:text-gray-500 mb-1">Lugar</label>
                <input wire:model="lugar_expedicion" type="text"
                       class="w-full px-2 py-1.5 text-xs rounded-md border border-gray-300 dark:border-gray-600
                              bg-white dark:bg-gray-700 text-gray-900 dark:text-white
                              focus:ring-1 focus:ring-rose-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-xs text-gray-400 dark:text-gray-500 mb-1">Día / Mes / Año</label>
                <div class="grid grid-cols-3 gap-1">
                    <input wire:model="exp_dia" type="number" min="1" max="31" placeholder="DD"
                           class="px-2 py-1.5 text-xs rounded-md border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700 text-gray-900 dark:text-white
                                  focus:ring-1 focus:ring-rose-500 focus:border-transparent">
                    <input wire:model="exp_mes" type="number" min="1" max="12" placeholder="MM"
                           class="px-2 py-1.5 text-xs rounded-md border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700 text-gray-900 dark:text-white
                                  focus:ring-1 focus:ring-rose-500 focus:border-transparent">
                    <input wire:model="exp_ano" type="number" min="0" max="99" placeholder="AA"
                           class="px-2 py-1.5 text-xs rounded-md border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700 text-gray-900 dark:text-white
                                  focus:ring-1 focus:ring-rose-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-400 dark:text-gray-500 mb-1">Nota Marginal</label>
                <textarea wire:model="nota_marginal" rows="2"
                          class="w-full px-2 py-1.5 text-xs rounded-md border border-gray-300 dark:border-gray-600
                                 bg-white dark:bg-gray-700 text-gray-900 dark:text-white
                                 focus:ring-1 focus:ring-rose-500 focus:border-transparent resize-none"></textarea>
            </div>

            <button wire:click="saveCertificate"
                    class="w-full px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold transition-colors">
                Guardar datos expedición
            </button>
        </div>

    </aside>

    {{-- ======================= MAIN CONTENT ======================= --}}
    <div class="flex-1 min-w-0 space-y-4">

        {{-- TÍTULO --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Constancia de Matrimonio</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $diaMatrimonio ? "celebrado el {$diaMatrimonio} de {$mesMatrimonio} de {$anoMatrimonio}" : '—' }}
                        @if($iglesiaNombre) &bull; {{ $iglesiaNombre }} @endif
                    </p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300">
                    #{{ $matrimonio->id }}
                </span>
            </div>
        </div>

        {{-- CONTRAYENTES --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">Contrayentes</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Esposo --}}
                <div class="flex items-center gap-3 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700/40">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center shrink-0">
                        <span class="text-white text-sm font-bold">{{ strtoupper(substr($esposo?->primer_nombre ?? '?', 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold uppercase tracking-wide">Esposo</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $esposo?->nombre_completo ?? '—' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">DNI: {{ $esposo?->dni ?? '—' }}</p>
                    </div>
                </div>
                {{-- Esposa --}}
                <div class="flex items-center gap-3 p-3 rounded-lg bg-pink-50 dark:bg-pink-900/20 border border-pink-200 dark:border-pink-700/40">
                    <div class="w-10 h-10 rounded-full bg-pink-500 flex items-center justify-center shrink-0">
                        <span class="text-white text-sm font-bold">{{ strtoupper(substr($esposa?->primer_nombre ?? '?', 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="text-xs text-pink-600 dark:text-pink-400 font-semibold uppercase tracking-wide">Esposa</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $esposa?->nombre_completo ?? '—' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">DNI: {{ $esposa?->dni ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- TESTIGOS --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">Testigos</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ([['testigo1', $testigo1, 'Testigo 1', 'violet'], ['testigo2', $testigo2, 'Testigo 2', 'teal']] as [$tKey, $tPersona, $tLabel, $tColor])
                    <div class="flex items-center gap-3 p-3 rounded-lg
                                bg-{{ $tColor }}-50 dark:bg-{{ $tColor }}-900/20
                                border border-{{ $tColor }}-200 dark:border-{{ $tColor }}-700/40">
                        <div class="w-10 h-10 rounded-full bg-{{ $tColor }}-600 flex items-center justify-center shrink-0">
                            <span class="text-white text-sm font-bold">{{ strtoupper(substr($tPersona?->primer_nombre ?? '?', 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="text-xs text-{{ $tColor }}-600 dark:text-{{ $tColor }}-400 font-semibold uppercase tracking-wide">{{ $tLabel }}</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $tPersona?->nombre_completo ?? '—' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">DNI: {{ $tPersona?->dni ?? '—' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ENCARGADO + OBSERVACIONES --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">Sacerdote Celebrante</p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $encargado?->nombre_completo ?? '—' }}</p>
            @if ($matrimonio->observaciones)
                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400 italic">{{ $matrimonio->observaciones }}</p>
            @endif
        </div>

        {{-- VISTA PREVIA CONSTANCIA --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Vista Previa de Constancia</p>
                <a href="{{ route('matrimonio.certificado.pdf', $matrimonio) }}" target="_blank"
                   class="text-xs font-semibold text-rose-600 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300 transition-colors">
                    Abrir PDF en nueva pestaña
                </a>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-gray-50 dark:bg-gray-900">
                <iframe
                    src="{{ $pdfPreviewUrl }}"
                    class="w-full h-[980px]"
                    title="Vista previa constancia de matrimonio">
                </iframe>
            </div>
        </div>

    </div>

</div>
