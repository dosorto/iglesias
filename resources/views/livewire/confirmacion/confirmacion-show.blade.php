@php
    use Illuminate\Support\Facades\Storage;
    $confirmado    = $confirmacion->feligres?->persona;
    $padre         = $confirmacion->padre?->persona;
    $madre         = $confirmacion->madre?->persona;
    $padrino       = $confirmacion->padrino?->persona;
    $madrina       = $confirmacion->madrina?->persona;
    $ministro      = $confirmacion->ministro?->persona;
    $iglesiaNombre = $iglesiaConfig?->nombre ?? $confirmacion->iglesia?->nombre ?? '';
    $logoIglesia   = $iglesiaConfig?->logo_url ?? asset('image/Logo_guest.png');

    $mesesEs = [
        1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',
        5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',
        9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre',
    ];

    $fc = $confirmacion->fecha_confirmacion;
    $diaConfirmacion = $fc?->day ?? '';
    $mesConfirmacion = $fc ? $mesesEs[$fc->month] : '';
    $anoConfirmacion = $fc?->year ?? '';

    $fn     = $confirmado?->fecha_nacimiento;
    $diaNac = $fn?->day ?? '';
    $mesNac = $fn ? $mesesEs[$fn->month] : '';
    $anoNac = $fn?->year ?? '';

    $padrinosStr = collect([$padrino?->nombre_completo, $madrina?->nombre_completo])
        ->filter()->implode(' y ');

    $diaExp = $exp_dia ?? '';
    $mesExp = ($exp_mes && isset($mesesEs[(int)$exp_mes])) ? $mesesEs[(int)$exp_mes] : '';
    $anoExp = $exp_ano ?? '';

    $estadoColor = $confirmacion->fecha_expedicion
        ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'
        : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300';
@endphp

<div class="flex flex-col lg:flex-row gap-5 items-start">

    {{-- ======================= LEFT SIDEBAR ======================= --}}
    <aside class="w-full lg:w-56 xl:w-60 shrink-0 space-y-4">

        {{-- Flash notification --}}
        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-600 text-green-800 dark:text-green-200 px-3 py-2 rounded-lg text-xs">
                {{ session('success') }}
            </div>
        @endif

        {{-- ACCIONES --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">Acciones</p>

            <div class="space-y-2">
                <a href="{{ route('confirmacion.certificado.pdf', $confirmacion) }}" target="_blank"
                   class="flex items-center w-full bg-violet-600 hover:bg-violet-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h6M9 13h6M9 9h1"/>
                    </svg>
                    Generar PDF
                </a>

                @can('confirmacion.edit')
                    <button wire:click="saveCertificate"
                            class="flex items-center w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Guardar Cambios
                    </button>
                @endcan

                <button wire:click="togglePreview"
                        class="flex items-center w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    @if ($previewMode)
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modo Edición
                    @else
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Vista Previa
                    @endif
                </button>

                @can('confirmacion.edit')
                    <a href="{{ route('confirmacion.edit', $confirmacion) }}"
                       class="flex items-center w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar Confirmación
                    </a>
                @endcan

                <a href="{{ route('confirmacion.index') }}"
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
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">Diócesis</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide">
                        {{ strtoupper($confirmacion->iglesia?->nombre ?? 'CHOLUTECA') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">Libro / Tomo</p>
                    <p class="text-sm font-mono font-semibold text-gray-800 dark:text-gray-100">
                        {{ $confirmacion->libro_confirmacion ?? '—' }}
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
                                v1 &ndash; {{ $confirmacion->created_at?->format('d/m/Y') }}
                            </p>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500">Sistema</p>
                        </div>
                        <span class="text-[10px] uppercase font-medium px-1.5 py-0.5 rounded bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                            Nuevo
                        </span>
                    </div>
                    @if ($confirmacion->updated_at && $confirmacion->updated_at->ne($confirmacion->created_at))
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                    v2 &ndash; {{ $confirmacion->updated_at?->format('d/m/Y') }}
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

    {{-- ======================= CERTIFICATE MAIN AREA ======================= --}}
    <div class="flex-1 min-w-0">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8">

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg px-4 py-3 text-sm text-red-700 dark:text-red-400">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ─── CERTIFICATE HEADER ─── --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="shrink-0">
                    <img src="{{ $logoIglesia }}" alt="Logo" class="h-16 w-16 object-contain">
                </div>
                <div class="flex-1 text-center">
                    <h1 class="text-lg md:text-xl font-black uppercase tracking-widest text-gray-900 dark:text-white leading-tight">
                        {{ $iglesiaNombre ?: 'Parroquia' }}
                    </h1>
                    <p class="text-sm uppercase tracking-widest text-gray-500 dark:text-gray-400 mt-0.5">Di&oacute;cesis de Choluteca</p>
                </div>
                <div class="shrink-0">
                    <img src="{{ $logoIglesia }}" alt="Logo" class="h-16 w-16 object-contain">
                </div>
            </div>

            {{-- Gold ornament lines --}}
            <div class="border-t border-[#7D5A1E] my-1"></div>
            <div class="text-center text-[#7D5A1E] text-xs tracking-[12px] my-1">&bull; &bull; &bull;</div>
            <div class="border-t border-[#7D5A1E] my-1"></div>

            {{-- Gold title banner --}}
            <div class="text-center my-3">
                <span class="inline-block bg-[#7D5A1E] text-white text-sm font-bold uppercase tracking-[4px] px-8 py-2">
                    Certificaci&oacute;n de Confirmaci&oacute;n
                </span>
            </div>

            <div class="border-t border-[#7D5A1E] my-1"></div>
            <div class="text-center text-[#7D5A1E] text-xs tracking-[12px] my-1">&bull; &bull; &bull;</div>
            <div class="border-t border-[#7D5A1E] mb-4"></div>

            @php
                $placeholderClass = 'text-gray-400 dark:text-gray-500 italic text-sm';
            @endphp

            {{-- ─── CERTIFICATE BODY ─── --}}
            <div class="font-serif text-gray-800 dark:text-gray-200 leading-relaxed space-y-3 text-sm md:text-[14px]">

                {{-- Line 1 --}}
                <p>El infrascrito, encargado del Archivo de la Parroquia de</p>
                <p class="pl-2">
                    @if ($iglesiaNombre)
                        <span class="border-b border-gray-400 dark:border-gray-500 pb-0.5 font-medium">{{ $iglesiaNombre }}</span>
                    @else
                        <span class="{{ $placeholderClass }} border-b border-gray-300 dark:border-gray-600 inline-block w-64 pb-0.5">Nombre de la parroquia</span>
                    @endif
                </p>

                {{-- Line 2 --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-1">
                    <strong>CERTIFICA:</strong>
                    <span>Que en el libro de Confirmaciones No.</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[60px] text-center font-mono">{{ $confirmacion->libro_confirmacion ?: '' }}</span>
                    <span>, en la Página</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[50px] text-center font-mono">{{ $confirmacion->folio ?: '' }}</span>
                    <span>, bajo el No.</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[50px] text-center font-mono">{{ $confirmacion->partida_numero ?: '' }}</span>
                </p>

                {{-- Line 3 --}}
                <p class="italic font-semibold mt-1">Se encuentra la partida que dice:</p>

                {{-- Line 4 --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-1">
                    <span>En</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[140px] font-medium {{ $iglesiaNombre ? '' : $placeholderClass }}">
                        {{ $iglesiaNombre ?: 'Ciudad/Lugar' }}
                    </span>
                    <span>a</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[40px] text-center font-medium {{ $diaConfirmacion ? '' : $placeholderClass }}">
                        {{ $diaConfirmacion ?: 'Día' }}
                    </span>
                    <span>del mes</span>
                </p>

                {{-- Line 5 --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-1">
                    <span>de</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[120px] text-center font-medium {{ $mesConfirmacion ? '' : $placeholderClass }}">
                        {{ $mesConfirmacion ?: 'Mes' }}
                    </span>
                    <span>del año</span>
                    @if ($anoConfirmacion)
                        @if ($anoConfirmacion >= 2000)
                            <span>dos mil</span>
                            <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[50px] text-center font-medium">
                                {{ $anoConfirmacion - 2000 ?: '' }}
                            </span>
                        @else
                            <span>mil novecientos</span>
                            <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[50px] text-center font-medium">
                                {{ $anoConfirmacion - 1900 }}
                            </span>
                        @endif
                    @else
                        <span>mil novecientos</span>
                        <span class="{{ $placeholderClass }} border-b border-gray-300 dark:border-gray-600 inline-block min-w-[60px] text-center">Año (90-99)</span>
                    @endif
                </p>

                {{-- Line 6: Ministro --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-1">
                    <span>El Señor</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[180px] font-medium {{ $ministro?->nombre_completo ? '' : $placeholderClass }}">
                        {{ $ministro?->nombre_completo ?: 'Ministro Confirmante' }}
                    </span>
                    <span>confirmó solemnemente a:</span>
                </p>

                {{-- Confirmed person name --}}
                <p class="text-center font-bold uppercase tracking-widest text-base md:text-lg border-b border-gray-400 dark:border-gray-500 pb-0.5 {{ $confirmado?->nombre_completo ? 'text-gray-900 dark:text-white' : $placeholderClass }}">
                    {{ $confirmado?->nombre_completo ?: 'Nombre Completo del Confirmado' }}
                </p>
                @if ($confirmado?->dni)
                    <p class="text-center text-xs text-gray-400 font-mono">DNI: {{ $confirmado->dni }}</p>
                @endif

                {{-- Birth line 1 --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-1">
                    <span>Que nació en</span>
                    @if ($previewMode)
                        <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[140px] text-center {{ $lugar_nacimiento ? 'font-medium' : $placeholderClass }}">
                            {{ $lugar_nacimiento ?: 'Lugar de nacimiento' }}
                        </span>
                    @else
                        @can('confirmacion.edit')
                            <input type="text"
                                   wire:model.live="lugar_nacimiento"
                                   placeholder="Lugar de nacimiento"
                                   class="border-b border-gray-400 dark:border-gray-500 bg-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:border-violet-500 min-w-[140px] pb-0.5 text-sm font-serif">
                        @else
                            <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[140px] text-center {{ $lugar_nacimiento ? 'font-medium' : $placeholderClass }}">
                                {{ $lugar_nacimiento ?: 'Lugar de nacimiento' }}
                            </span>
                        @endcan
                    @endif
                    <span>, el</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[40px] text-center {{ $diaNac ? 'font-medium' : $placeholderClass }}">
                        {{ $diaNac ?: 'Día' }}
                    </span>
                </p>

                {{-- Birth line 2 --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-1">
                    <span>de</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[200px] text-center {{ ($mesNac || $anoNac) ? 'font-medium' : $placeholderClass }}">
                        {{ collect([$mesNac, $anoNac])->filter()->implode(' de ') ?: 'Mes y año de nacimiento' }}
                    </span>
                </p>

                {{-- Parents --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-1">
                    <span>Hijo(a) de</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 flex-1 min-w-[180px] font-medium {{ $padre?->nombre_completo ? '' : $placeholderClass }}">
                        {{ $padre?->nombre_completo ?: 'Nombre del padre' }}
                    </span>
                </p>
                <p class="flex flex-wrap items-end gap-x-1 gap-y-1">
                    <span>y de</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 flex-1 min-w-[180px] font-medium {{ $madre?->nombre_completo ? '' : $placeholderClass }}">
                        {{ $madre?->nombre_completo ?: 'Nombre de la madre' }}
                    </span>
                </p>

                {{-- Godparents --}}
                <div>
                    <p class="mb-1">Padrino / Madrina:</p>
                    <p class="border-b border-gray-400 dark:border-gray-500 pb-0.5 {{ $padrinosStr ? 'font-medium' : $placeholderClass }}">
                        {{ $padrinosStr ?: 'Nombres del padrino / madrina' }}
                    </p>
                </div>

                <div class="pt-2"></div>

                {{-- NOTA MARGINAL --}}
                <div class="flex flex-wrap items-start gap-x-2 gap-y-1">
                    <span class="font-bold shrink-0">NOTA MARGINAL:</span>
                    @if ($previewMode)
                        <p class="border-b border-gray-400 dark:border-gray-500 flex-1 min-w-[200px] pb-0.5 {{ $nota_marginal ? '' : $placeholderClass }}">
                            {{ $nota_marginal ?: 'Notas adicionales o sacramentos posteriores...' }}
                        </p>
                    @else
                        @can('confirmacion.edit')
                            <input type="text"
                                   wire:model.live="nota_marginal"
                                   placeholder="Notas adicionales o sacramentos posteriores..."
                                   class="border-b border-gray-400 dark:border-gray-500 bg-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:border-violet-500 flex-1 min-w-[200px] pb-0.5 text-sm font-serif">
                        @else
                            <p class="border-b border-gray-400 dark:border-gray-500 flex-1 pb-0.5">{{ $nota_marginal ?: '—' }}</p>
                        @endcan
                    @endif
                </div>

                {{-- Ministro signature --}}
                <div class="flex justify-end pt-6 pb-2">
                    <div class="text-center">
                        @if ($ministro?->nombre_completo)
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-1">{{ $ministro->nombre_completo }}</p>
                        @endif
                        <div class="w-52 border-t border-gray-500 dark:border-gray-400 pt-1">
                            <p class="text-xs font-bold uppercase tracking-[3px]">Ministro Confirmante</p>
                        </div>
                    </div>
                </div>

                {{-- Dado en --}}
                <div class="space-y-2 pt-1">
                    <p class="flex flex-wrap items-end gap-x-1 gap-y-1">
                        <span>Dado en</span>
                        @if ($previewMode)
                            <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[120px] pl-1 {{ $lugar_expedicion ? 'font-medium' : $placeholderClass }}">
                                {{ $lugar_expedicion ?: 'Lugar' }}
                            </span>
                        @else
                            @can('confirmacion.edit')
                                <input type="text"
                                       wire:model.live="lugar_expedicion"
                                       placeholder="Lugar"
                                       class="border-b border-gray-400 dark:border-gray-500 bg-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:border-violet-500 min-w-[120px] pb-0.5 text-sm font-serif">
                            @else
                                <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[120px] pl-1">{{ $lugar_expedicion ?: '—' }}</span>
                            @endcan
                        @endif
                        <span>el</span>
                        @if ($previewMode)
                            <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[40px] text-center {{ $diaExp ? 'font-medium' : $placeholderClass }}">
                                {{ $diaExp ?: 'Día' }}
                            </span>
                        @else
                            @can('confirmacion.edit')
                                <input type="number" min="1" max="31"
                                       wire:model.live="exp_dia"
                                       placeholder="Día"
                                       class="border-b border-gray-400 dark:border-gray-500 bg-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:border-violet-500 w-12 text-center pb-0.5 text-sm font-serif [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                            @else
                                <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[40px] text-center {{ $diaExp ? 'font-medium' : $placeholderClass }}">
                                    {{ $diaExp ?: 'Día' }}
                                </span>
                            @endcan
                        @endif
                    </p>

                    <p class="flex flex-wrap items-end gap-x-1 gap-y-1">
                        <span>de</span>
                        @if ($previewMode)
                            <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[100px] text-center {{ $mesExp ? 'font-medium' : $placeholderClass }}">
                                {{ $mesExp ?: 'Mes' }}
                            </span>
                        @else
                            @can('confirmacion.edit')
                                <select wire:model.live="exp_mes"
                                        class="border-b border-gray-400 dark:border-gray-500 bg-transparent text-gray-900 dark:text-gray-100 focus:outline-none focus:border-violet-500 pb-0.5 text-sm font-serif">
                                    <option value="">Mes</option>
                                    <option value="1">enero</option>
                                    <option value="2">febrero</option>
                                    <option value="3">marzo</option>
                                    <option value="4">abril</option>
                                    <option value="5">mayo</option>
                                    <option value="6">junio</option>
                                    <option value="7">julio</option>
                                    <option value="8">agosto</option>
                                    <option value="9">septiembre</option>
                                    <option value="10">octubre</option>
                                    <option value="11">noviembre</option>
                                    <option value="12">diciembre</option>
                                </select>
                            @else
                                <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[100px] text-center {{ $mesExp ? 'font-medium' : $placeholderClass }}">
                                    {{ $mesExp ?: 'Mes' }}
                                </span>
                            @endcan
                        @endif
                        <span>de dos mil</span>
                        @if ($previewMode)
                            <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[50px] text-center {{ $anoExp ? 'font-medium' : $placeholderClass }}">
                                {{ $anoExp ?: '' }}
                            </span>
                        @else
                            @can('confirmacion.edit')
                                <input type="number" min="0" max="99"
                                       wire:model.live="exp_ano"
                                       placeholder="Año"
                                       class="border-b border-gray-400 dark:border-gray-500 bg-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:border-violet-500 w-14 text-center pb-0.5 text-sm font-serif [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                            @else
                                <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[50px] text-center {{ $anoExp ? 'font-medium' : $placeholderClass }}">
                                    {{ $anoExp ?: '' }}
                                </span>
                            @endcan
                        @endif
                    </p>

                    <p class="text-xs italic text-gray-400 mt-1">(Sello)</p>
                </div>

                {{-- Encargado de Archivo signature --}}
                <div class="flex justify-end pt-4">
                    <div class="text-center">
                        @php $firmaPath = $confirmacion->ministro?->path_firma_principal; @endphp
                        @if ($firmaPath)
                            <div class="mb-1 flex justify-center">
                                <img src="{{ Storage::url($firmaPath) }}"
                                     alt="Firma ministro"
                                     class="max-h-16 max-w-[220px] object-contain">
                            </div>
                            @if (!$previewMode)
                                @can('confirmacion.edit')
                                    <div class="mt-1 mb-2">
                                        <label class="text-xs text-gray-400 cursor-pointer hover:text-violet-500 underline">
                                            Cambiar firma
                                            <input type="file" wire:model="firma_nueva" accept="image/*" class="hidden">
                                        </label>
                                        @if ($firma_nueva)
                                            <div class="flex items-center gap-2 mt-1 justify-center">
                                                <img src="{{ $firma_nueva->temporaryUrl() }}" class="max-h-10 max-w-[140px] object-contain rounded border border-gray-300">
                                                <button wire:click="uploadFirma" class="text-xs bg-violet-600 hover:bg-violet-700 text-white px-2 py-0.5 rounded">Guardar</button>
                                                <button wire:click="$set('firma_nueva', null)" class="text-xs text-gray-400 hover:text-red-500">✕</button>
                                            </div>
                                        @endif
                                        @error('firma_nueva') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                @endcan
                            @endif
                        @else
                            @if (!$previewMode)
                                @can('confirmacion.edit')
                                    <div class="mb-2 border border-dashed border-gray-400 dark:border-gray-500 rounded p-3 flex flex-col items-center gap-1">
                                        <p class="text-xs text-gray-400">Sin firma. Agregar:</p>
                                        <input type="file" wire:model="firma_nueva" accept="image/*"
                                               class="text-xs text-gray-500 dark:text-gray-400 file:mr-2 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-xs file:bg-gray-100 dark:file:bg-gray-700 file:text-gray-700 dark:file:text-gray-300">
                                        @if ($firma_nueva)
                                            <div class="flex items-center gap-2 mt-1">
                                                <img src="{{ $firma_nueva->temporaryUrl() }}" class="max-h-10 max-w-[140px] object-contain rounded border border-gray-300">
                                                <button wire:click="uploadFirma" class="text-xs bg-violet-600 hover:bg-violet-700 text-white px-2 py-0.5 rounded">Guardar</button>
                                                <button wire:click="$set('firma_nueva', null)" class="text-xs text-gray-400 hover:text-red-500">✕</button>
                                            </div>
                                        @endif
                                        @error('firma_nueva') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                @else
                                    <div class="mb-2 h-10"></div>
                                @endcan
                            @else
                                <div class="mb-2 h-10"></div>
                            @endif
                        @endif
                        <div class="w-60 border-t border-gray-500 dark:border-gray-400 pt-1">
                            <p class="text-xs font-bold uppercase tracking-[3px]">Encargado de Archivo</p>
                        </div>
                    </div>
                </div>

            </div>
            {{-- end certificate body --}}
        </div>
    </div>

</div>