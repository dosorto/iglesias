<div class="space-y-6">

    {{-- HEADER --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-rose-600 to-pink-600
                dark:from-rose-700 dark:to-pink-700 shadow-md px-6 py-5">
        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
        <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white leading-tight">Registrar Matrimonio</h1>
                    <p class="text-rose-100 text-sm mt-0.5">Completa los dos pasos para registrar la constancia matrimonial</p>
                </div>
            </div>
            <a href="{{ route('matrimonio.index') }}"
               class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-lg
                      bg-white/15 hover:bg-white/25 border border-white/20
                      text-white text-sm font-medium transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    {{-- INDICADOR DE PASOS --}}
    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                ring-1 ring-black/5 dark:ring-white/5 px-6 py-4">
        <div class="flex items-center gap-0">
            @php $pasos = [['n'=>1,'label'=>'Personas'], ['n'=>2,'label'=>'Registro']]; @endphp
            @foreach ($pasos as $i => $p)
                <div class="flex flex-col items-center flex-shrink-0">
                    <div @class([
                        'w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm transition-all shadow-sm',
                        'bg-gradient-to-br from-rose-500 to-pink-600 text-white shadow-rose-500/30'       => $paso === $p['n'],
                        'bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-emerald-500/30' => $paso > $p['n'],
                        'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500'                  => $paso < $p['n'],
                    ])>
                        @if ($paso > $p['n'])
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            {{ $p['n'] }}
                        @endif
                    </div>
                    <span @class([
                        'mt-1 text-xs font-semibold',
                        'text-rose-600 dark:text-rose-400'       => $paso === $p['n'],
                        'text-emerald-600 dark:text-emerald-400' => $paso > $p['n'],
                        'text-gray-400 dark:text-gray-500'       => $paso < $p['n'],
                    ])>{{ $p['label'] }}</span>
                </div>
                @if ($i < count($pasos) - 1)
                    <div @class([
                        'h-0.5 flex-1 mx-2 mb-4 rounded-full transition-colors',
                        'bg-gradient-to-r from-emerald-400 to-emerald-500' => $paso > $p['n'],
                        'bg-gray-200 dark:bg-gray-600'                     => $paso <= $p['n'],
                    ])></div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- PASO 1: PERSONAS --}}
    @if ($paso === 1)
    <div class="space-y-4">

        @php
            $rolesConfig = [
                ['key' => 'esposo',   'label' => 'Esposo',    'required' => true,  'accent' => 'blue'],
                ['key' => 'esposa',   'label' => 'Esposa',    'required' => true,  'accent' => 'pink'],
                ['key' => 'testigo1', 'label' => 'Testigo 1', 'required' => false, 'accent' => 'violet'],
                ['key' => 'testigo2', 'label' => 'Testigo 2', 'required' => false, 'accent' => 'teal'],
            ];
        @endphp

        @foreach ($rolesConfig as $rc)
            @php
                $key       = $rc['key'];
                $rolDni    = $this->{"{$key}_dni"};
                $rolPersona= $this->{"{$key}_persona"};
                $rolFelId  = $this->{"{$key}_feligres_id"};
                $rolEstado = $this->{"{$key}_estado"};
                $isMiniOpen= ($mini_rol === $key);
            @endphp

            <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border overflow-hidden
                        ring-1 ring-black/5 dark:ring-white/5
                        {{ $rolEstado === 'found' ? 'border-emerald-200 dark:border-emerald-700/50' : 'border-gray-200 dark:border-gray-700/60' }}">

                {{-- Header rol --}}
                <div class="flex items-center justify-between px-6 py-3.5
                            border-b border-gray-100 dark:border-gray-700/60
                            {{ $rolEstado === 'found' ? 'bg-emerald-50/60 dark:bg-emerald-900/10' : 'bg-gray-50/80 dark:bg-gray-800/80' }}">
                    <div class="flex items-center gap-2.5">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $rc['label'] }}</h3>
                        @if ($rc['required'])
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                         bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-300">
                                Obligatorio
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                         bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                Opcional
                            </span>
                        @endif
                        @if ($rolEstado === 'found')
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                         bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">
                                &#x2713; Registrado
                            </span>
                        @endif
                    </div>
                    @if ($rolEstado !== 'idle' || $isMiniOpen)
                        <button type="button"
                                wire:click="limpiarRol('{{ $key }}')"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium
                                       text-red-500 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30
                                       border border-red-200 dark:border-red-800/40 transition-all">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Limpiar
                        </button>
                    @endif
                </div>

                <div class="p-5 space-y-4">

                    {{-- Estado: IDLE --}}
                    @if ($rolEstado === 'idle' && ! $isMiniOpen)
                        <div class="flex gap-3">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       wire:model="{{ $key }}_dni"
                                       placeholder="DNI o nombre del {{ strtolower($rc['label']) }}..."
                                       autocomplete="off"
                                       wire:keydown.enter="buscarPersona('{{ $key }}')"
                                       class="block w-full pl-10 pr-4 py-2.5 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-gray-50 dark:bg-gray-700/60
                                              text-gray-900 dark:text-white dark:placeholder-gray-400
                                              focus:ring-2 focus:ring-sky-500 focus:border-transparent" />
                            </div>
                            <button type="button"
                                    wire:click="buscarPersona('{{ $key }}')"
                                    wire:loading.attr="disabled"
                                    wire:target="buscarPersona('{{ $key }}')"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold
                                           bg-sky-600 hover:bg-sky-700 text-white shadow-sm transition-all disabled:opacity-60">
                                <svg wire:loading.remove wire:target="buscarPersona('{{ $key }}')"
                                     class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <svg wire:loading wire:target="buscarPersona('{{ $key }}')"
                                     class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                </svg>
                                Buscar
                            </button>
                        </div>
                        @error("{$key}_dni")
                            <p class="text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    @endif

                    {{-- Estado: MULTIPLES RESULTADOS --}}
                    @if ($rolEstado === 'multiples' && $busqueda_rol === $key)
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Se encontraron {{ count($busqueda_resultados) }} personas. Selecciona una:
                        </p>
                        <div class="space-y-1.5 max-h-64 overflow-y-auto pr-1">
                            @foreach ($busqueda_resultados as $res)
                                <button type="button"
                                        wire:click="seleccionarResultado({{ $res['id'] }})"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-left
                                               border border-gray-200 dark:border-gray-600
                                               bg-white dark:bg-gray-700/40
                                               hover:bg-sky-50 dark:hover:bg-sky-900/20
                                               hover:border-sky-300 dark:hover:border-sky-600
                                               transition-all group">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-600
                                                flex items-center justify-center flex-shrink-0
                                                group-hover:bg-sky-100 dark:group-hover:bg-sky-900/40">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $res['nombre_completo'] }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            DNI: {{ $res['dni'] }}
                                            @if ($res['telefono'])
                                                &middot; {{ $res['telefono'] }}
                                            @endif
                                        </p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                        @error("{$key}_dni")
                            <p class="text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    @endif

                    {{-- Estado: ENCONTRADO como feligres --}}
                    @if ($rolEstado === 'found')
                        <div class="flex items-center gap-3 p-4 rounded-xl
                                    bg-emerald-50 dark:bg-emerald-900/20
                                    border border-emerald-200 dark:border-emerald-700/50">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600
                                        flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white truncate text-sm">
                                    {{ $rolPersona['nombre_completo'] }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    DNI: {{ $rolPersona['dni'] }}
                                    @if ($rolPersona['telefono'])
                                        &nbsp;&middot;&nbsp;{{ $rolPersona['telefono'] }}
                                    @endif
                                </p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-semibold
                                             bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">
                                    &#x2713; Registrado como feligres
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Estado: persona existe pero NO es feligres --}}
                    @if ($rolEstado === 'sin_feligres')
                        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20
                                    border border-amber-200 dark:border-amber-700/50 space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-600
                                            flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <span class="text-white font-bold text-sm">
                                        {{ strtoupper(substr($rolPersona['nombre_completo'], 0, 1)) }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 dark:text-white truncate text-sm">
                                        {{ $rolPersona['nombre_completo'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">DNI: {{ $rolPersona['dni'] }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-semibold
                                                 bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">
                                        Persona encontrada &mdash; no esta registrada como feligres
                                    </span>
                                </div>
                            </div>
                            @if (! $isMiniOpen)
                                <button type="button"
                                        wire:click="abrirMini('{{ $key }}', 'feligres')"
                                        class="w-full py-2.5 text-sm font-semibold
                                               text-amber-700 dark:text-amber-300
                                               border border-amber-300 dark:border-amber-600 rounded-xl
                                               hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-all">
                                    + Registrar como Feligres
                                </button>
                            @endif
                        </div>
                    @endif

                    {{-- Estado: persona NO encontrada --}}
                    @if ($rolEstado === 'sin_persona')
                        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20
                                    border border-red-200 dark:border-red-700/50 space-y-3">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-red-700 dark:text-red-300">No se encontro ninguna persona</p>
                                    <p class="text-xs text-red-600 dark:text-red-400 mt-0.5">
                                        DNI <strong>"{{ $rolDni }}"</strong> no existe en la base de datos.
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <input type="text"
                                       wire:model="{{ $key }}_dni"
                                       inputmode="numeric"
                                       autocomplete="off"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                       class="flex-1 px-3 py-2 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-sky-500 focus:border-transparent" />
                                <button type="button"
                                        wire:click="buscarPersona('{{ $key }}')"
                                        class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                    Buscar
                                </button>
                            </div>
                            @if (! $isMiniOpen)
                                <button type="button"
                                        wire:click="abrirMini('{{ $key }}', 'persona')"
                                        class="w-full py-2.5 text-sm font-semibold
                                               text-emerald-700 dark:text-emerald-300
                                               border border-emerald-300 dark:border-emerald-600 rounded-xl
                                               hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all">
                                    + Registrar nuevo feligres
                                </button>
                            @endif
                        </div>
                    @endif

                    {{-- Mini-form --}}
                    @if ($isMiniOpen)
                        <div class="rounded-xl border border-{{ $rc['accent'] }}-200 dark:border-{{ $rc['accent'] }}-700/50
                                    bg-gradient-to-b from-{{ $rc['accent'] }}-50/80 to-transparent
                                    dark:from-{{ $rc['accent'] }}-900/15 dark:to-transparent overflow-hidden">

                            {{-- Sub-header --}}
                            <div class="flex items-center justify-between px-5 py-3
                                        border-b border-{{ $rc['accent'] }}-100 dark:border-{{ $rc['accent'] }}-800/40
                                        bg-{{ $rc['accent'] }}-50 dark:bg-{{ $rc['accent'] }}-900/20">
                                <h4 class="text-sm font-semibold text-{{ $rc['accent'] }}-800 dark:text-{{ $rc['accent'] }}-300 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    {{ $mini_tipo === 'persona' ? 'Nuevo Feligrés' : 'Registrar como Feligrés' }}
                                    &ndash; {{ $rc['label'] }}
                                </h4>
                                <button type="button"
                                        wire:click="cancelarMini"
                                        class="p-1 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-200
                                               hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="p-5 space-y-4">
                                @if ($mini_tipo === 'persona')
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                        {{-- N° Identidad --}}
                                        <div class="sm:col-span-2">
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Número de Identidad <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text"
                                                   wire:model.defer="mini_p_dni"
                                                   placeholder="Ej: 0801199912345"
                                                   inputmode="numeric"
                                                   autocomplete="off"
                                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60
                                                          text-gray-900 dark:text-white placeholder-gray-400
                                                          focus:ring-2 focus:ring-{{ $rc['accent'] }}-500 focus:border-transparent
                                                          @error('mini_p_dni') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                            @error('mini_p_dni')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Primer Nombre --}}
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Primer Nombre <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text"
                                                   wire:model.defer="mini_p_primer_nombre"
                                                   oninput="this.value = this.value.replace(/[^a-zA-Z\u00C0-\u024FñÑ\s]/g, '').replace(/\b\w/g, c => c.toUpperCase())"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60
                                                          text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-{{ $rc['accent'] }}-500 focus:border-transparent
                                                          @error('mini_p_primer_nombre') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                            @error('mini_p_primer_nombre')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Segundo Nombre --}}
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Segundo Nombre
                                            </label>
                                            <input type="text"
                                                   wire:model.defer="mini_p_segundo_nombre"
                                                   oninput="this.value = this.value.replace(/[^a-zA-Z\u00C0-\u024FñÑ\s]/g, '').replace(/\b\w/g, c => c.toUpperCase())"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60
                                                          text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-{{ $rc['accent'] }}-500 focus:border-transparent" />
                                        </div>

                                        {{-- Primer Apellido --}}
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Primer Apellido <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text"
                                                   wire:model.defer="mini_p_primer_apellido"
                                                   oninput="this.value = this.value.replace(/[^a-zA-Z\u00C0-\u024FñÑ\s]/g, '').replace(/\b\w/g, c => c.toUpperCase())"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60
                                                          text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-{{ $rc['accent'] }}-500 focus:border-transparent
                                                          @error('mini_p_primer_apellido') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                            @error('mini_p_primer_apellido')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Segundo Apellido --}}
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Segundo Apellido
                                            </label>
                                            <input type="text"
                                                   wire:model.defer="mini_p_segundo_apellido"
                                                   oninput="this.value = this.value.replace(/[^a-zA-Z\u00C0-\u024FñÑ\s]/g, '').replace(/\b\w/g, c => c.toUpperCase())"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60
                                                          text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-{{ $rc['accent'] }}-500 focus:border-transparent" />
                                        </div>

                                        {{-- Sexo --}}
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Sexo <span class="text-red-500">*</span>
                                            </label>
                                            <select wire:model.defer="mini_p_sexo"
                                                    class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                           border border-gray-300 dark:border-gray-600
                                                           bg-white dark:bg-gray-700/60
                                                           text-gray-900 dark:text-white
                                                           focus:ring-2 focus:ring-{{ $rc['accent'] }}-500 focus:border-transparent
                                                           @error('mini_p_sexo') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror">
                                                <option value="">-- Seleccionar --</option>
                                                <option value="M">Masculino</option>
                                                <option value="F">Femenino</option>
                                            </select>
                                            @error('mini_p_sexo')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Fecha de Nacimiento --}}
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Fecha de Nacimiento <span class="text-red-500">*</span>
                                            </label>
                                            <input type="date"
                                                   wire:model.defer="mini_p_fecha_nacimiento"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60
                                                          text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-{{ $rc['accent'] }}-500 focus:border-transparent
                                                          @error('mini_p_fecha_nacimiento') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                            @error('mini_p_fecha_nacimiento')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Teléfono --}}
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Teléfono <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text"
                                                   wire:model.defer="mini_p_telefono"
                                                   placeholder="+504 0000-0000"
                                                   inputmode="tel"
                                                   oninput="this.value = this.value.replace(/[^0-9+\-\s]/g, '')"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60
                                                          text-gray-900 dark:text-white placeholder-gray-400
                                                          focus:ring-2 focus:ring-{{ $rc['accent'] }}-500 focus:border-transparent
                                                          @error('mini_p_telefono') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                            @error('mini_p_telefono')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Email
                                            </label>
                                            <input type="email"
                                                   wire:model.defer="mini_p_email"
                                                   placeholder="ejemplo@correo.com"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60
                                                          text-gray-900 dark:text-white placeholder-gray-400
                                                          focus:ring-2 focus:ring-{{ $rc['accent'] }}-500 focus:border-transparent
                                                          @error('mini_p_email') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                            @error('mini_p_email')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>
                                @endif

                                {{-- Fecha de ingreso y estado se asignan automaticamente --}}

                                <div class="flex gap-2 justify-end pt-2 border-t border-{{ $rc['accent'] }}-100 dark:border-{{ $rc['accent'] }}-800/40">
                                    <button type="button"
                                            wire:click="cancelarMini"
                                            class="px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                                   text-gray-700 dark:text-gray-300
                                                   hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        Cancelar
                                    </button>
                                    <button type="button"
                                            wire:click="{{ $mini_tipo === 'persona' ? 'guardarMiniPersona' : 'guardarMiniFeligres' }}"
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg
                                                   bg-{{ $rc['accent'] }}-600 hover:bg-{{ $rc['accent'] }}-700
                                                   disabled:opacity-60 disabled:cursor-not-allowed
                                                   text-white text-sm font-semibold shadow-sm transition-all">
                                        <svg wire:loading wire:target="guardarMiniPersona,guardarMiniFeligres"
                                             class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                        </svg>
                                        <svg wire:loading.remove wire:target="guardarMiniPersona,guardarMiniFeligres"
                                             class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                        </svg>
                                        <span wire:loading.remove wire:target="guardarMiniPersona,guardarMiniFeligres">Guardar Persona</span>
                                        <span wire:loading wire:target="guardarMiniPersona,guardarMiniFeligres">Guardando…</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- Fecha de matrimonio y celebrante se cargan automaticamente --}}

        {{-- Nav --}}
        <div class="flex justify-end">
            <button wire:click="siguientePaso"
                    class="px-6 py-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-medium text-sm transition-colors shadow-sm">
                Continuar →
            </button>
        </div>
    </div>
    @endif

    {{-- PASO 2: REGISTRO --}}
    @if ($paso === 2)
    <div class="space-y-4">

        <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                    ring-1 ring-black/5 dark:ring-white/5 p-6 space-y-5">

            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 uppercase tracking-wide">Datos del Registro</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Libro</label>
                    <input wire:model="libro_matrimonio" type="text" placeholder="Ej: Libro 1"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Folio</label>
                    <input wire:model="folio" type="text" placeholder="Ej: F-01"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Partida N°</label>
                    <input wire:model="partida_numero" type="text" placeholder="Ej: P-0001"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Observaciones</label>
                <textarea wire:model="observaciones" rows="2"
                          class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                 bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                 focus:ring-2 focus:ring-rose-500 focus:border-transparent resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Nota Marginal</label>
                <textarea wire:model="nota_marginal" rows="2"
                          class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                 bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                 focus:ring-2 focus:ring-rose-500 focus:border-transparent resize-none"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Fecha de Expedición</label>
                    <div class="grid grid-cols-3 gap-2">
                        <input wire:model="exp_dia" type="number" min="1" max="31" placeholder="Día"
                               class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                      focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                        <input wire:model="exp_mes" type="number" min="1" max="12" placeholder="Mes"
                               class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                      focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                        <input wire:model="exp_ano" type="number" min="0" max="99" placeholder="Año"
                               class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                      focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                    </div>
                    <p class="mt-1 text-xs text-gray-400">Día, Mes, Año (2 dígitos, ej: 26)</p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <div class="flex justify-between">
            <button wire:click="anteriorPaso"
                    class="px-6 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300
                           hover:bg-gray-50 dark:hover:bg-gray-700 font-medium text-sm transition-colors">
                ← Atrás
            </button>
            <button wire:click="guardar"
                    class="px-8 py-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold text-sm transition-colors shadow-sm">
                Guardar Matrimonio
            </button>
        </div>
    </div>
    @endif

</div>
