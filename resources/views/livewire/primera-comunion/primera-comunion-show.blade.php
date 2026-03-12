@php
    $comulgante  = $primeraComunion->feligres?->persona;
    $catequista  = $primeraComunion->catequista?->persona;
    $ministro    = $primeraComunion->ministro?->persona;
    $parroco     = $primeraComunion->parroco?->persona;
    $encargado   = $primeraComunion->encargado?->feligres?->persona;
    $iglesiaNombre = $iglesiaConfig?->nombre ?? $primeraComunion->iglesia?->nombre ?? '';
    $logoIglesia = $iglesiaConfig?->logo_url ?? asset('image/Logo_guest.png');

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

    // Propiedades del componente Livewire
    $previewMode        = $this->previewMode;
    $lugar_celebracion  = $this->lugar_celebracion ?? '';
    $lugar_expedicion   = $this->lugar_expedicion  ?? '';
    $nota_marginal      = $this->nota_marginal      ?? '';
    $auditHistory     = $this->auditHistory;
    $estadoRegistro   = $this->estadoRegistro   ?? 'Borrador';
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
                {{-- Generar PDF --}}
                <a href="{{ route('primera-comunion.certificado.pdf', $primeraComunion) }}" target="_blank"
                   class="flex items-center w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h6M9 13h6M9 9h1"/>
                    </svg>
                    Generar PDF
                </a>

                {{-- Guardar Borrador --}}
                @can('primera-comunion.edit')
                    <button wire:click="saveCertificate"
                            class="flex items-center w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Guardar Cambios
                    </button>
                @endcan

                {{-- Toggle Preview --}}
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

                {{-- Editar Registro --}}
                @can('primera-comunion.edit')
                    <a href="{{ route('primera-comunion.edit', $primeraComunion) }}"
                       class="flex items-center w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar Primera Comunion 
                    </a>
                @endcan

                {{-- Volver --}}
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
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">Diócesis</p>
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
                    Certificaci&oacute;n de Primera Comuni&oacute;n
                </span>
            </div>

            <div class="border-t border-[#7D5A1E] my-1"></div>
            <div class="text-center text-[#7D5A1E] text-xs tracking-[12px] my-1">&bull; &bull; &bull;</div>
            <div class="border-t border-[#7D5A1E] mb-6"></div>

            @php
                $placeholderClass = 'text-gray-400 dark:text-gray-500 italic text-sm';
                $lineClass = 'border-b border-gray-500 dark:border-gray-400 inline-block pb-0.5 font-medium';
                $inputClass = 'border-b border-gray-500 dark:border-gray-400 bg-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:border-blue-500 pb-0.5 font-serif';
            @endphp

            {{-- ─── CERTIFICATE BODY ─── --}}
            <div class="font-serif text-gray-800 dark:text-gray-200 leading-loose space-y-5 text-sm md:text-[14px]">

                {{-- Línea 1: infrascrito certifica que --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-2">
                    <span>El infrascrito encargado del archivo de esta parroquia certifica que</span>
                </p>

                {{-- Nombre del comulgante (línea completa subrayada) --}}
                <p class="w-full border-b border-gray-500 dark:border-gray-400 pb-0.5 font-bold uppercase tracking-widest text-center text-base md:text-lg
                           {{ $comulgante?->nombre_completo ? 'text-gray-900 dark:text-white' : $placeholderClass }}">
                    {{ $comulgante?->nombre_completo ?: 'Nombre Completo del Comulgante' }}
                </p>
                @if ($comulgante?->dni)
                    <p class="text-center text-xs text-gray-400 font-mono -mt-3">DNI: {{ $comulgante->dni }}</p>
                @endif

                {{-- Hizo su Primera Comunión --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-2">
                    <span>Hizo su</span>
                    <strong>PRIMERA COMUNIÓN</strong>
                    <span>el día</span>
                    <span class="{{ $lineClass }} min-w-[80px] text-center {{ $diaComunion ? '' : $placeholderClass }}">
                        {{ $diaComunion ?: '' }}
                    </span>
                    <span>del mes</span>
                    <span class="{{ $lineClass }} min-w-[160px] text-center {{ $mesComunion ? '' : $placeholderClass }}">
                        {{ $mesComunion ?: '' }}
                    </span>
                </p>

                {{-- Año --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-2">
                    <span>año</span>
                    <span class="{{ $lineClass }} min-w-[120px] text-center {{ $anoComunion ? '' : $placeholderClass }}">
                        {{ $anoComunion ?: '' }}
                    </span>
                </p>

                {{-- En (lugar de la celebración) --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-2">
                    <span>En</span>
                    @if ($previewMode)
                        <span class="{{ $lineClass }} min-w-[300px] {{ $lugar_celebracion ? '' : $placeholderClass }}">
                            {{ $lugar_celebracion ?: '' }}
                        </span>
                    @else
                        @can('primera-comunion.edit')
                            <input type="text"
                                   wire:model.live="lugar_celebracion"
                                   placeholder="Lugar de la celebración"
                                   class="{{ $inputClass }} min-w-[300px]">
                        @else
                            <span class="{{ $lineClass }} min-w-[300px] {{ $lugar_celebracion ? '' : $placeholderClass }}">
                                {{ $lugar_celebracion ?: '' }}
                            </span>
                        @endcan
                    @endif
                </p>

                {{-- Nota marginal (opcional) --}}
                @if ($nota_marginal || !$previewMode)
                <div class="flex flex-wrap items-start gap-x-2 gap-y-1 pt-2">
                    <span class="font-bold shrink-0">NOTA MARGINAL:</span>
                    @if ($previewMode)
                        <p class="border-b border-gray-400 dark:border-gray-500 flex-1 min-w-[200px] pb-0.5 {{ $nota_marginal ? '' : $placeholderClass }}">
                            {{ $nota_marginal ?: '' }}
                        </p>
                    @else
                        @can('primera-comunion.edit')
                            <input type="text"
                                   wire:model.live="nota_marginal"
                                   placeholder="Notas adicionales..."
                                   class="{{ $inputClass }} flex-1 min-w-[200px]">
                        @else
                            <p class="border-b border-gray-400 dark:border-gray-500 flex-1 pb-0.5">{{ $nota_marginal ?: '—' }}</p>
                        @endcan
                    @endif
                </div>
                @endif

                <div class="pt-4"></div>

                {{-- Dado en --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-2">
                    <span>Dado en</span>
                    @if ($previewMode)
                        <span class="{{ $lineClass }} min-w-[180px] {{ $lugar_expedicion ? '' : $placeholderClass }}">
                            {{ $lugar_expedicion ?: '' }}
                        </span>
                    @else
                        @can('primera-comunion.edit')
                            <input type="text"
                                   wire:model.live="lugar_expedicion"
                                   placeholder="Lugar"
                                   class="{{ $inputClass }} min-w-[180px]">
                        @else
                            <span class="{{ $lineClass }} min-w-[180px] {{ $lugar_expedicion ? '' : $placeholderClass }}">
                                {{ $lugar_expedicion ?: '' }}
                            </span>
                        @endcan
                    @endif
                    <span>a los</span>
                    @if ($previewMode)
                        <span class="{{ $lineClass }} min-w-[60px] text-center {{ $diaExp ? '' : $placeholderClass }}">
                            {{ $diaExp ?: '' }}
                        </span>
                    @else
                        @can('primera-comunion.edit')
                            <input type="number" min="1" max="31"
                                   wire:model.live="exp_dia"
                                   placeholder="Día"
                                   class="{{ $inputClass }} w-14 text-center [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                        @else
                            <span class="{{ $lineClass }} min-w-[60px] text-center {{ $diaExp ? '' : $placeholderClass }}">
                                {{ $diaExp ?: '' }}
                            </span>
                        @endcan
                    @endif
                    <span>del mes de</span>
                </p>

                {{-- Mes y año de expedición --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-2">
                    @if ($previewMode)
                        <span class="{{ $lineClass }} min-w-[160px] text-center {{ $mesExp ? '' : $placeholderClass }}">
                            {{ $mesExp ?: '' }}
                        </span>
                    @else
                        @can('primera-comunion.edit')
                            <select wire:model.live="exp_mes"
                                    class="border-b border-gray-500 dark:border-gray-400 bg-transparent text-gray-900 dark:text-gray-100 focus:outline-none focus:border-blue-500 pb-0.5 text-sm font-serif min-w-[140px]">
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
                            <span class="{{ $lineClass }} min-w-[160px] text-center {{ $mesExp ? '' : $placeholderClass }}">
                                {{ $mesExp ?: '' }}
                            </span>
                        @endcan
                    @endif
                    <span>año</span>
                    @if ($previewMode)
                        <span class="{{ $lineClass }} min-w-[100px] text-center {{ $anoExp ? '' : $placeholderClass }}">
                            {{ $anoExp ? '20' . str_pad($anoExp, 2, '0', STR_PAD_LEFT) : '' }}
                        </span>
                    @else
                        @can('primera-comunion.edit')
                            <input type="number" min="0" max="99"
                                   wire:model.live="exp_ano"
                                   placeholder="Año"
                                   class="{{ $inputClass }} w-16 text-center [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                        @else
                            <span class="{{ $lineClass }} min-w-[100px] text-center {{ $anoExp ? '' : $placeholderClass }}">
                                {{ $anoExp ? '20' . str_pad($anoExp, 2, '0', STR_PAD_LEFT) : '' }}
                            </span>
                        @endcan
                    @endif
                </p>

                {{-- Firma del Párroco --}}
                <div class="flex justify-center pt-10 pb-2">
                    <div class="text-center">
                        {{-- Espacio para sello físico --}}
                        <div class="mb-6 h-16 flex items-center justify-center">
                            <span class="text-xs italic text-gray-300 dark:text-gray-600">(Sello)</span>
                        </div>

                        {{-- Línea de firma con nombre del párroco --}}
                        <div class="w-64 border-t border-gray-600 dark:border-gray-400 pt-2 mx-auto">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ $parroco?->nombre_completo ?? ($primeraComunion->iglesia?->parroco_nombre ?? '') }}
                            </p>
                            <p class="text-xs font-bold uppercase tracking-[3px] mt-0.5">Párroco</p>
                        </div>
                    </div>
                </div>

            </div>
            {{-- end certificate body --}}
        </div>
    </div>

</div>