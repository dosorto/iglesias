<div class="space-y-6 sacramento-create-form">

    @once
        <style>
            .sacramento-create-form input::placeholder,
            .sacramento-create-form textarea::placeholder {
                color: rgb(156 163 175 / 0.55);
            }

            .dark .sacramento-create-form input::placeholder,
            .dark .sacramento-create-form textarea::placeholder {
                color: rgb(156 163 175 / 0.45);
            }
        </style>
    @endonce

    {{-- HEADER --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-sky-600 to-blue-600
                dark:from-sky-700 dark:to-blue-700 shadow-md px-6 py-5">
        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
        <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white leading-tight">Registrar Primera Comunión</h1>
                    <p class="text-sky-100 text-sm mt-0.5">Completa los dos pasos para registrar el acto</p>
                </div>
            </div>
            <a href="{{ route('primera-comunion.index') }}"
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
                        'bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-sky-500/30'         => $paso === $p['n'],
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
                        'text-sky-600 dark:text-sky-400'         => $paso === $p['n'],
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
                ['key' => 'feligres',   'label' => 'Comulgante', 'required' => true,  'accent' => 'sky'],
                ['key' => 'catequista', 'label' => 'Catequista', 'required' => false, 'accent' => 'violet'],
                ['key' => 'ministro',   'label' => 'Ministro',   'required' => false, 'accent' => 'indigo'],
            ];
        @endphp

        @foreach ($rolesConfig as $rc)
            @php
                $key        = $rc['key'];
                $rolDni     = $this->{"{$key}_dni"};
                $rolPersona = $this->{"{$key}_persona"};
                $rolFelId   = $this->{"{$key}_feligres_id"};
                $rolEstado  = $this->{"{$key}_estado"};
                $isMiniOpen = ($mini_rol === $key);
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
                                       oninput="if(this.value && /^[0-9]+$/.test(this.value) === false) { const letters = this.value.match(/[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/g); if(letters) this.value = letters.join(''); }"
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
                                    &#x2713; Registrado como feligrés
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
                                        Persona encontrada &mdash; no está registrada como feligrés
                                    </span>
                                </div>
                            </div>
                            @if (! $isMiniOpen)
                                <button type="button"
                                        wire:click="abrirRegistrarFeligres('{{ $key }}')"
                                        class="w-full py-2.5 text-sm font-semibold
                                               text-amber-700 dark:text-amber-300
                                               border border-amber-300 dark:border-amber-600 rounded-xl
                                               hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-all">
                                    + Registrar como Feligrés
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
                                    <p class="text-sm font-medium text-red-700 dark:text-red-300">No se encontró ninguna persona</p>
                                    <p class="text-xs text-red-600 dark:text-red-400 mt-0.5">
                                        <strong>"{{ $rolDni }}"</strong> no existe en la base de datos.
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <input type="text"
                                       wire:model="{{ $key }}_dni"
                                       autocomplete="off"
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
                                        wire:click="abrirCrearPersona('{{ $key }}')"
                                        class="w-full py-2.5 text-sm font-semibold
                                               text-emerald-700 dark:text-emerald-300
                                               border border-emerald-300 dark:border-emerald-600 rounded-xl
                                               hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all">
                                    + Crear Nueva Persona
                                </button>
                            @endif
                        </div>
                    @endif

                    {{-- MINI-FORM: CREAR PERSONA + FELIGRES --}}
                    @if ($isMiniOpen && $mini_tipo === 'persona')
                        <div class="rounded-xl border border-emerald-200 dark:border-emerald-700/50
                                    bg-gradient-to-b from-emerald-50/80 to-transparent
                                    dark:from-emerald-900/15 dark:to-transparent overflow-hidden">

                            <div class="flex items-center justify-between px-5 py-3
                                        border-b border-emerald-100 dark:border-emerald-800/40
                                        bg-emerald-50 dark:bg-emerald-900/20">
                                <h4 class="text-sm font-semibold text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    Nueva Persona + Feligrés &mdash; <span class="font-bold ml-1">{{ $rc['label'] }}</span>
                                </h4>
                                <button type="button" wire:click="cancelarMini"
                                        class="p-1 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-200
                                               hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="p-5 space-y-5">
                                <div>
                                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3
                                               flex items-center gap-2
                                               before:content-[''] before:flex-1 before:h-px before:bg-gray-200 dark:before:bg-gray-700
                                               after:content-['']  after:flex-1  after:h-px  after:bg-gray-200 dark:after:bg-gray-700">
                                        Datos Personales
                                    </p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                        <div class="sm:col-span-2">
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Número de Identidad <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" wire:model="mini_p_dni" placeholder="Ej: 0801199912345"
                                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                          @error('mini_p_dni') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                            @error('mini_p_dni')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Primer Nombre <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" wire:model="mini_p_primer_nombre"
                                                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/gu, '')"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                          @error('mini_p_primer_nombre') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                            @error('mini_p_primer_nombre')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Segundo Nombre
                                            </label>
                                            <input type="text" wire:model="mini_p_segundo_nombre"
                                                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/gu, '')"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Primer Apellido <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" wire:model="mini_p_primer_apellido"
                                                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/gu, '')"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                          @error('mini_p_primer_apellido') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                            @error('mini_p_primer_apellido')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Segundo Apellido
                                            </label>
                                            <input type="text" wire:model="mini_p_segundo_apellido"
                                                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/gu, '')"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Fecha de Nacimiento <span class="text-red-500">*</span>
                                            </label>
                                            <input type="date" wire:model="mini_p_fecha_nacimiento"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                          @error('mini_p_fecha_nacimiento') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                            @error('mini_p_fecha_nacimiento')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Sexo <span class="text-red-500">*</span>
                                            </label>
                                            <select wire:model="mini_p_sexo"
                                                    class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                           border border-gray-300 dark:border-gray-600
                                                           bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                           focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                                <option value="">Seleccionar...</option>
                                                <option value="M">Masculino</option>
                                                <option value="F">Femenino</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Teléfono <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" wire:model="mini_p_telefono" placeholder="+504 0000-0000"
                                                   oninput="this.value = this.value.replace(/[^0-9+\-]/g, '')"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                                Correo Electrónico
                                            </label>
                                            <input type="email" wire:model="mini_p_email" placeholder="ejemplo@correo.com"
                                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                          border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                          @error('mini_p_email') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                            @error('mini_p_email')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 pt-2 border-t border-emerald-100 dark:border-emerald-800/40">
                                    <button type="button" wire:click="cancelarMini"
                                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                                                   bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                                                   text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                        Cancelar
                                    </button>
                                    <button type="button"
                                            wire:click="guardarMiniPersona"
                                            wire:loading.attr="disabled"
                                            wire:target="guardarMiniPersona"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold
                                                   shadow-md shadow-emerald-500/30 transition-all
                                                   bg-gradient-to-r from-emerald-500 to-emerald-600
                                                   hover:from-emerald-600 hover:to-emerald-700
                                                   text-white disabled:opacity-60">
                                        <svg wire:loading wire:target="guardarMiniPersona"
                                             class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                        </svg>
                                        <span wire:loading.remove wire:target="guardarMiniPersona">Guardar</span>
                                        <span wire:loading wire:target="guardarMiniPersona">Guardando...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- MINI-FORM: REGISTRAR COMO FELIGRES --}}
                    @if ($isMiniOpen && $mini_tipo === 'feligres')
                        <div class="rounded-xl border border-sky-200 dark:border-sky-700/50
                                    bg-gradient-to-b from-sky-50/80 to-transparent
                                    dark:from-sky-900/15 dark:to-transparent overflow-hidden">

                            <div class="flex items-center justify-between px-5 py-3
                                        border-b border-sky-100 dark:border-sky-800/40
                                        bg-sky-50 dark:bg-sky-900/20">
                                <h4 class="text-sm font-semibold text-sky-800 dark:text-sky-300 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Registrar como Feligrés &mdash; {{ $rolPersona['nombre_completo'] ?? '' }}
                                </h4>
                                <button type="button" wire:click="cancelarMini"
                                        class="p-1 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-200
                                               hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="p-5 space-y-4">
                                @error('iglesia_id')
                                    <div class="p-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700">
                                        <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    </div>
                                @enderror

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                            Fecha de Ingreso
                                        </label>
                                        <input type="date" wire:model="mini_f_fecha_ingreso"
                                               class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                      border border-gray-300 dark:border-gray-600
                                                      bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                      focus:ring-2 focus:ring-sky-500 focus:border-transparent" />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                            Estado <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model="mini_f_estado"
                                                class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                       border border-gray-300 dark:border-gray-600
                                                       bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                       focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                                            <option value="Activo">Activo</option>
                                            <option value="Inactivo">Inactivo</option>
                                        </select>
                                    </div>
                                </div>

                                <p class="text-xs text-sky-600 dark:text-sky-400 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Se registrará en la iglesia local automáticamente.
                                </p>

                                <div class="flex justify-end gap-3 pt-2 border-t border-sky-100 dark:border-sky-800/40">
                                    <button type="button" wire:click="cancelarMini"
                                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                                                   bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                                                   text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                        Cancelar
                                    </button>
                                    <button type="button"
                                            wire:click="guardarMiniFeligres"
                                            wire:loading.attr="disabled"
                                            wire:target="guardarMiniFeligres"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold
                                                   shadow-md shadow-sky-500/30 transition-all
                                                   bg-gradient-to-r from-sky-500 to-sky-600
                                                   hover:from-sky-600 hover:to-sky-700
                                                   text-white disabled:opacity-60">
                                        <svg wire:loading wire:target="guardarMiniFeligres"
                                             class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                        </svg>
                                        <span wire:loading.remove wire:target="guardarMiniFeligres">Registrar Feligrés</span>
                                        <span wire:loading wire:target="guardarMiniFeligres">Guardando...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endforeach

        {{-- PÁRROCO AUTOMÁTICO (encargado activo) --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border overflow-hidden
                    ring-1 ring-black/5 dark:ring-white/5
                    {{ $encargado_info ? 'border-teal-200 dark:border-teal-700/50' : 'border-gray-200 dark:border-gray-700/60' }}">

            <div class="flex items-center gap-2.5 px-6 py-3.5 border-b border-gray-100 dark:border-gray-700/60
                        {{ $encargado_info ? 'bg-teal-50/60 dark:bg-teal-900/10' : 'bg-gray-50/80 dark:bg-gray-800/80' }}">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Párroco</h3>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                             bg-teal-100 dark:bg-teal-900/40 text-teal-600 dark:text-teal-400">
                    Automático
                </span>
                @if ($encargado_info)
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">
                        &#x2713; Asignado
                    </span>
                @endif
            </div>

            <div class="p-5">
                @if ($encargado_info)
                    <div class="flex items-center gap-3 p-4 rounded-xl
                                bg-teal-50 dark:bg-teal-900/20
                                border border-teal-200 dark:border-teal-700/50">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-cyan-600
                                    flex items-center justify-center flex-shrink-0 shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white truncate text-sm">
                                {{ $encargado_info['nombre_completo'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                DNI: {{ $encargado_info['dni'] }}
                            </p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-semibold
                                         bg-teal-100 dark:bg-teal-900/40 text-teal-700 dark:text-teal-300">
                                Encargado activo del sistema
                            </span>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2.5 p-4 rounded-xl
                                bg-amber-50 dark:bg-amber-900/20
                                border border-amber-200 dark:border-amber-700/50">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-amber-700 dark:text-amber-300">
                            No hay ningún encargado activo configurado en el sistema.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Error global comulgante --}}
        @error('feligres_dni')
            <div class="flex items-center gap-2 p-4 rounded-xl bg-red-50 dark:bg-red-900/20
                        border border-red-200 dark:border-red-700/50">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/>
                </svg>
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            </div>
        @enderror

    </div>
    @endif

    {{-- PASO 2: REGISTRO + RESUMEN --}}
    @if ($paso === 2)
    <div class="space-y-5">

        {{-- Resumen --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                    ring-1 ring-black/5 dark:ring-white/5">

            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                             bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300
                             text-xs font-bold ring-2 ring-emerald-200 dark:ring-emerald-700/50">&#x2713;</span>
                <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                    Resumen de la Primera Comunión
                </h2>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                @php
                    $sumRoles = [
                        ['key' => 'feligres',   'label' => 'Comulgante', 'persona' => $feligres_persona],
                        ['key' => 'catequista', 'label' => 'Catequista', 'persona' => $catequista_persona],
                        ['key' => 'ministro',   'label' => 'Ministro',   'persona' => $ministro_persona],
                    ];
                @endphp

                @foreach ($sumRoles as $sr)
                    @php $sp = $sr['persona']; @endphp
                    <div class="p-3 rounded-lg {{ $sp ? 'bg-emerald-50 dark:bg-emerald-900/15' : 'bg-gray-50 dark:bg-gray-700/40' }}">
                        <span class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wide">{{ $sr['label'] }}</span>
                        <p class="font-semibold text-gray-900 dark:text-white mt-0.5">
                            @if ($sp)
                                {{ $sp['nombre_completo'] }}
                                <span class="text-xs text-gray-400 font-normal ml-1">DNI: {{ $sp['dni'] }}</span>
                            @else
                                <span class="text-gray-400 font-normal">No especificado</span>
                            @endif
                        </p>
                    </div>
                @endforeach

                {{-- Párroco (encargado) en el resumen --}}
                <div class="p-3 rounded-lg {{ $encargado_info ? 'bg-teal-50 dark:bg-teal-900/15' : 'bg-gray-50 dark:bg-gray-700/40' }}">
                    <span class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wide">Párroco</span>
                    <p class="font-semibold text-gray-900 dark:text-white mt-0.5">
                        @if ($encargado_info)
                            {{ $encargado_info['nombre_completo'] }}
                            <span class="text-xs text-teal-600 dark:text-teal-400 font-normal ml-1">(encargado)</span>
                        @else
                            <span class="text-gray-400 font-normal">Sin encargado activo</span>
                        @endif
                    </p>
                </div>

            </div>
        </div>

        {{-- Libro parroquial --}}
        <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                    ring-1 ring-black/5 dark:ring-white/5">

            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                             bg-sky-100 dark:bg-sky-900/60 text-sky-700 dark:text-sky-300
                             text-xs font-bold ring-2 ring-sky-200 dark:ring-sky-700/50">2</span>
                <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                    Libro Parroquial
                </h2>
                <span class="text-xs text-gray-400 font-normal normal-case tracking-normal">(opcional)</span>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-5">

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Fecha de Primera Comunión <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           wire:model.defer="fecha_primera_comunion"
                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                  border border-gray-300 dark:border-gray-600
                                  bg-gray-50 dark:bg-gray-700/60
                                  text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-sky-500 focus:border-transparent
                                  @error('fecha_primera_comunion') border-red-400 @enderror" />
                    @error('fecha_primera_comunion')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Libro de Comunión
                    </label>
                    <input type="text" wire:model="libro_comunion"
                           placeholder="Ej: Tomo III"
                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                  border border-gray-300 dark:border-gray-600
                                  bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-sky-500 focus:border-transparent" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Folio
                    </label>
                    <input type="text" wire:model="folio"
                           placeholder="Ej: 42"
                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                  border border-gray-300 dark:border-gray-600
                                  bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-sky-500 focus:border-transparent" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Partida Número
                    </label>
                    <input type="text" wire:model="partida_numero"
                           placeholder="Ej: 0125"
                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                  border border-gray-300 dark:border-gray-600
                                  bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-sky-500 focus:border-transparent" />
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Observaciones
                    </label>
                    <textarea wire:model="observaciones"
                              rows="3"
                              placeholder="Notas adicionales sobre la primera comunión..."
                              class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                     border border-gray-300 dark:border-gray-600
                                     bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white
                                     focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none"></textarea>
                </div>

            </div>
        </div>
    </div>
    @endif

    {{-- BOTONES DE NAVEGACION --}}
    <div class="flex justify-between items-center gap-4">

        @if ($paso > 1)
            <button type="button"
                    wire:click="anteriorPaso"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium
                           text-gray-700 dark:text-gray-200 rounded-lg transition-all
                           border border-gray-300 dark:border-gray-600
                           hover:bg-gray-50 dark:hover:bg-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Anterior
            </button>
        @else
            <div></div>
        @endif

        @if ($paso < 2)
            <button type="button"
                    wire:click="siguientePaso"
                    wire:loading.attr="disabled"
                    wire:target="siguientePaso"
                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white rounded-lg
                           shadow-md shadow-sky-500/30 transition-all
                           bg-gradient-to-r from-sky-500 to-blue-600
                           hover:from-sky-600 hover:to-blue-700
                           disabled:opacity-60 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="siguientePaso" class="flex items-center gap-2">
                    Siguiente
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
                <span wire:loading wire:target="siguientePaso">Validando...</span>
            </button>
        @else
            <button type="button"
                    wire:click="guardar"
                    wire:loading.attr="disabled"
                    wire:target="guardar"
                    class="inline-flex items-center gap-2.5 px-7 py-2.5 text-sm font-bold text-white rounded-lg
                           shadow-md shadow-emerald-500/30 transition-all active:scale-[0.98]
                           bg-gradient-to-r from-emerald-500 to-emerald-600
                           hover:from-emerald-600 hover:to-emerald-700
                           disabled:opacity-50 disabled:cursor-not-allowed
                           focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2
                           dark:focus:ring-offset-gray-800">
                <svg wire:loading wire:target="guardar" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
                <span wire:loading.remove wire:target="guardar">Registrar Primera Comunión</span>
                <span wire:loading wire:target="guardar">Guardando...</span>
            </button>
        @endif

    </div>

</div>