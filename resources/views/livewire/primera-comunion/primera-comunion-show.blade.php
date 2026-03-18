@php
    use Illuminate\Support\Facades\Storage;
    $comulgante      = $primeraComunion->feligres?->persona;
    $catequista      = $primeraComunion->catequista?->persona;
    $ministro        = $primeraComunion->ministro?->persona;
    $parroco         = $primeraComunion->parroco?->persona;
    $encargado       = $primeraComunion->encargado?->feligres?->persona;
    $iglesiaNombre   = $iglesiaConfig?->nombre ?? $primeraComunion->iglesia?->nombre ?? '';
    $logoIglesia     = $iglesiaConfig?->logo_url ?? asset('image/Logo_guest.png');

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
                <a href="{{ route('primera-comunion.certificado.pdf', $primeraComunion) }}" target="_blank"
                   class="flex items-center w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h6M9 13h6M9 9h1"/>
                    </svg>
                    Generar PDF
                </a>

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

            @if ($errors->any())
                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg px-4 py-3 text-sm text-red-700 dark:text-red-400">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ─── CERTIFICATE HEADER con logos ─── --}}
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

            <div class="border-t border-[#7D5A1E] my-1"></div>
            <div class="text-center text-[#7D5A1E] text-xs tracking-[12px] my-1">&bull; &bull; &bull;</div>
            <div class="border-t border-[#7D5A1E] my-1"></div>
            <div class="text-center my-3">
                <span class="inline-block bg-[#7D5A1E] text-white text-sm font-bold uppercase tracking-[4px] px-8 py-2">
                    Certificaci&oacute;n de Primera Comuni&oacute;n
                </span>
            </div>
            <div class="border-t border-[#7D5A1E] my-1"></div>
            <div class="text-center text-[#7D5A1E] text-xs tracking-[12px] my-1">&bull; &bull; &bull;</div>
            <div class="border-t border-[#7D5A1E] mb-6"></div>

            @php $placeholderClass = 'text-gray-400 dark:text-gray-500 italic text-sm'; @endphp

            {{-- ─── CERTIFICATE BODY ─── --}}
            <div class="font-serif text-gray-800 dark:text-gray-200 leading-loose space-y-5 text-sm md:text-[14px]">

                <p>El infrascrito encargado del archivo de esta parroquia certifica que</p>

                <div class="py-1">
                    <span class="block border-b-2 border-gray-500 dark:border-gray-400 pb-1
                                 text-base md:text-lg font-semibold text-center text-gray-900 dark:text-white min-h-[28px]">
                        {{ $comulgante?->nombre_completo ?: '' }}
                    </span>
                </div>
                @if ($comulgante?->dni)
                    <p class="text-center text-xs text-gray-400 font-mono -mt-3">DNI: {{ $comulgante->dni }}</p>
                @endif

                <p class="flex flex-wrap items-end gap-x-1 gap-y-2">
                    <span>Hizo su</span>
                    <strong>PRIMERA COMUNIÓN</strong>
                    <span>el día</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[80px] text-center font-medium {{ $diaComunion ? '' : $placeholderClass }}">
                        {{ $diaComunion ?: '' }}
                    </span>
                    <span>del mes</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[160px] text-center font-medium {{ $mesComunion ? '' : $placeholderClass }}">
                        {{ $mesComunion ?: '' }}
                    </span>
                </p>

                <p class="flex flex-wrap items-end gap-x-1 gap-y-2">
                    <span>año</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[120px] text-center font-medium {{ $anoComunion ? '' : $placeholderClass }}">
                        {{ $anoComunion ?: '' }}
                    </span>
                </p>

                <p class="flex flex-wrap items-end gap-x-1 gap-y-2">
                    <span>En</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 flex-1 min-w-[300px] font-medium {{ $lugar_celebracion ? '' : $placeholderClass }}">
                        {{ $lugar_celebracion ?: '' }}
                    </span>
                </p>

                {{-- NOTA MARGINAL --}}
                <div class="flex flex-wrap items-start gap-x-2 gap-y-1 pt-2">
                    <span class="font-bold shrink-0">NOTA MARGINAL:</span>
                    <p class="border-b border-gray-400 dark:border-gray-500 flex-1 min-w-[200px] pb-0.5 {{ $nota_marginal ? '' : $placeholderClass }}">
                        {{ $nota_marginal ?: '—' }}
                    </p>
                </div>

                {{-- DADO EN — una sola línea --}}
                <p class="flex flex-wrap items-end gap-x-1 gap-y-2 pt-4">
                    <span>Dado en</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[160px] pl-1 font-medium {{ $lugar_expedicion ? '' : $placeholderClass }}">
                        {{ $lugar_expedicion ?: '' }}
                    </span>
                    <span>a los</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[50px] text-center font-medium {{ $diaExp ? '' : $placeholderClass }}">
                        {{ $diaExp ?: '' }}
                    </span>
                    <span>del mes de</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[130px] text-center font-medium {{ $mesExp ? '' : $placeholderClass }}">
                        {{ $mesExp ?: '' }}
                    </span>
                    <span>año</span>
                    <span class="border-b border-gray-400 dark:border-gray-500 inline-block min-w-[80px] text-center font-medium {{ $anoExp ? '' : $placeholderClass }}">
                        {{ $anoExp ? '20' . str_pad($anoExp, 2, '0', STR_PAD_LEFT) : '' }}
                    </span>
                </p>

                <p class="text-xs italic text-gray-400 mt-1">(Sello)</p>

                {{-- FIRMA DEL ENCARGADO — centrada, nombre encargado arriba, "Párroco" abajo --}}
                <div class="flex justify-center pt-8 pb-2">
                    <div class="text-center">
                        @php $firmaPath = $primeraComunion->encargado?->path_firma_principal; @endphp

                        @if ($firmaPath)
                            <div class="mb-1 flex justify-center">
                                <img src="{{ Storage::url($firmaPath) }}"
                                     alt="Firma encargado"
                                     class="max-h-16 max-w-[220px] object-contain">
                            </div>
                            @can('primera-comunion.edit')
                                <div class="mt-1 mb-2">
                                    <label class="text-xs text-gray-400 cursor-pointer hover:text-blue-500 underline">
                                        Cambiar firma
                                        <input type="file" wire:model="firma_nueva" accept="image/*" class="hidden">
                                    </label>
                                    @if ($firma_nueva)
                                        <div class="flex items-center gap-2 mt-1 justify-center">
                                            <img src="{{ $firma_nueva->temporaryUrl() }}" class="max-h-10 max-w-[140px] object-contain rounded border border-gray-300">
                                            <button wire:click="uploadFirma" class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-0.5 rounded">Guardar</button>
                                            <button wire:click="$set('firma_nueva', null)" class="text-xs text-gray-400 hover:text-red-500">✕</button>
                                        </div>
                                    @endif
                                    @error('firma_nueva') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                            @endcan
                        @else
                            @can('primera-comunion.edit')
                                <div class="mb-2 border border-dashed border-gray-400 dark:border-gray-500 rounded p-3 flex flex-col items-center gap-1">
                                    <p class="text-xs text-gray-400">Sin firma. Agregar:</p>
                                    <input type="file" wire:model="firma_nueva" accept="image/*"
                                           class="text-xs text-gray-500 dark:text-gray-400 file:mr-2 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-xs file:bg-gray-100 dark:file:bg-gray-700 file:text-gray-700 dark:file:text-gray-300">
                                    @if ($firma_nueva)
                                        <div class="flex items-center gap-2 mt-1">
                                            <img src="{{ $firma_nueva->temporaryUrl() }}" class="max-h-10 max-w-[140px] object-contain rounded border border-gray-300">
                                            <button wire:click="uploadFirma" class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-0.5 rounded">Guardar</button>
                                            <button wire:click="$set('firma_nueva', null)" class="text-xs text-gray-400 hover:text-red-500">✕</button>
                                        </div>
                                    @endif
                                    @error('firma_nueva') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                            @else
                                <div class="mb-2 h-10"></div>
                            @endcan
                        @endif

                        {{-- Nombre del encargado arriba de la línea, "Párroco" debajo --}}
                        <div class="w-64 border-t border-gray-500 dark:border-gray-400 pt-2 mx-auto">
                            @if ($encargado?->nombre_completo)
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $encargado->nombre_completo }}
                                </p>
                            @endif
                            <p class="text-xs font-bold uppercase tracking-[3px] mt-0.5">Párroco</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>