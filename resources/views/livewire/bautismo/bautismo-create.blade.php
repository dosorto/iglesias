<div class="space-y-6">

    {{-- ══ HEADER ══════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Registrar Bautismo</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Completa los tres pasos para registrar el acto bautismal</p>
        </div>
        <a href="{{ route('bautismo.index') }}"
           class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600
                  text-gray-700 dark:text-gray-200 font-medium transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver
        </a>
    </div>

    {{-- ══ INDICADOR DE PASOS ══════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 px-6 py-4">
        <div class="flex items-center gap-0">

            @php $pasos = [['n'=>1,'label'=>'Acto'], ['n'=>2,'label'=>'Personas'], ['n'=>3,'label'=>'Registro']]; @endphp

            @foreach ($pasos as $i => $p)
                {{-- Círculo del paso --}}
                <div class="flex flex-col items-center flex-shrink-0">
                    <div @class([
                        'w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm transition-colors',
                        'bg-blue-600 text-white'                                        => $paso === $p['n'],
                        'bg-green-500 text-white'                                       => $paso > $p['n'],
                        'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' => $paso < $p['n'],
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
                        'mt-1 text-xs font-medium',
                        'text-blue-600 dark:text-blue-400'           => $paso === $p['n'],
                        'text-green-600 dark:text-green-400'         => $paso > $p['n'],
                        'text-gray-400 dark:text-gray-500'           => $paso < $p['n'],
                    ])>{{ $p['label'] }}</span>
                </div>

                {{-- Línea connettore (excepto después del último) --}}
                @if ($i < count($pasos) - 1)
                    <div @class([
                        'h-0.5 flex-1 mx-2 mb-4 transition-colors',
                        'bg-green-400' => $paso > $p['n'],
                        'bg-gray-200 dark:bg-gray-600' => $paso <= $p['n'],
                    ])></div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- ══ PASO 1: DATOS DEL ACTO ══════════════════════════════════════ --}}
    @if ($paso === 1)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">

        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full
                             bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-bold">1</span>
                Datos del Acto Bautismal
            </h2>
        </div>

        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">

            {{-- Iglesia --}}
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Iglesia <span class="text-red-500">*</span>
                </label>
                <select wire:model="iglesia_id"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent
                               dark:bg-gray-700 dark:text-white
                               @error('iglesia_id') border-red-500 @enderror">
                    <option value="">— Selecciona una iglesia —</option>
                    @foreach ($iglesias as $ig)
                        <option value="{{ $ig->id }}">{{ $ig->nombre }}</option>
                    @endforeach
                </select>
                @error('iglesia_id')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fecha de bautismo --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Fecha de Bautismo <span class="text-red-500">*</span>
                </label>
                <input type="date"
                       wire:model="fecha_bautismo"
                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                              focus:ring-2 focus:ring-blue-500 focus:border-transparent
                              dark:bg-gray-700 dark:text-white
                              @error('fecha_bautismo') border-red-500 @enderror" />
                @error('fecha_bautismo')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Encargado (opcional) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Encargado <span class="text-xs text-gray-400">(opcional)</span>
                </label>
                <select wire:model="encargado_id"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent
                               dark:bg-gray-700 dark:text-white">
                    <option value="">— Sin encargado —</option>
                    @foreach ($encargados as $enc)
                        @if ($enc->feligres && $enc->feligres->persona)
                            <option value="{{ $enc->id }}">{{ $enc->feligres->persona->nombre_completo }}</option>
                        @endif
                    @endforeach
                </select>
                @error('encargado_id')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>
    @endif

    {{-- ══ PASO 2: PERSONAS ═════════════════════════════════════════════ --}}
    @if ($paso === 2)
    <div class="space-y-4">

        @php
            $rolesConfig = [
                ['key' => 'bautizado', 'label' => 'Bautizado',  'required' => true,  'color' => 'blue'],
                ['key' => 'padre',     'label' => 'Padre',      'required' => false, 'color' => 'purple'],
                ['key' => 'madre',     'label' => 'Madre',      'required' => false, 'color' => 'pink'],
                ['key' => 'padrino',   'label' => 'Padrino',    'required' => false, 'color' => 'indigo'],
                ['key' => 'madrina',   'label' => 'Madrina',    'required' => false, 'color' => 'teal'],
            ];
        @endphp

        @foreach ($rolesConfig as $rc)
            @php
                $key       = $rc['key'];
                $rolDni    = $this->{"{$key}_dni"};
                $rolPersona= $this->{"{$key}_persona"};
                $rolFelId  = $this->{"{$key}_feligres_id"};
                $rolEstado = $this->{"{$key}_estado"};
                $isMiniOpen = ($mini_rol === $key);
            @endphp

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">

                {{-- Header rol --}}
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600
                            flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        {{ $rc['label'] }}
                        @if ($rc['required'])
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">
                                Obligatorio
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                Opcional
                            </span>
                        @endif
                    </h3>
                    @if ($rolEstado === 'found' || $rolEstado === 'sin_feligres' || $rolEstado === 'sin_persona')
                        <button type="button"
                                wire:click="limpiarRol('{{ $key }}')"
                                class="text-xs text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Limpiar
                        </button>
                    @endif
                </div>

                <div class="p-5 space-y-4">

                    {{-- ── Estado: IDLE o buscador ── --}}
                    @if ($rolEstado === 'idle' && ! $isMiniOpen)
                        <div class="flex gap-3">
                            <input type="text"
                                   wire:model="{{$key}}_dni"
                                   placeholder="Ingresa el DNI del {{ strtolower($rc['label']) }}…"
                                   class="flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                          focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                          dark:bg-gray-700 dark:text-white dark:placeholder-gray-400" />
                            <button type="button"
                                    wire:click="buscarPersona('{{ $key }}')"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium
                                           rounded-lg transition-colors flex items-center gap-2 disabled:opacity-60">
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
                            <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    @endif

                    {{-- ── Estado: ENCONTRADO como feligrés ── --}}
                    @if ($rolEstado === 'found')
                        <div class="flex items-center gap-3 p-4 rounded-lg
                                    bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $rolPersona['nombre_completo'] }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    DNI: {{ $rolPersona['dni'] }}
                                    @if ($rolPersona['telefono']) &nbsp;·&nbsp; {{ $rolPersona['telefono'] }} @endif
                                </p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-medium
                                             bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300">
                                    ✓ Registrado como feligrés
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- ── Estado: persona existe pero NO es feligrés ── --}}
                    @if ($rolEstado === 'sin_feligres')
                        <div class="p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-bold text-sm">
                                        {{ strtoupper(substr($rolPersona['nombre_completo'], 0, 1)) }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $rolPersona['nombre_completo'] }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        DNI: {{ $rolPersona['dni'] }}
                                    </p>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-medium
                                                 bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">
                                        Persona encontrada — no está registrada como feligrés
                                    </span>
                                </div>
                            </div>
                            @if (! $isMiniOpen)
                                <button type="button"
                                        wire:click="abrirRegistrarFeligres('{{ $key }}')"
                                        class="w-full py-2 text-sm font-semibold text-amber-700 dark:text-amber-300
                                               border border-amber-300 dark:border-amber-600 rounded-lg
                                               hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-colors">
                                    + Registrar como Feligrés
                                </button>
                            @endif
                        </div>
                    @endif

                    {{-- ── Estado: persona NO encontrada ── --}}
                    @if ($rolEstado === 'sin_persona')
                        <div class="p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 space-y-3">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    No se encontró ninguna persona con DNI <strong>"{{ $rolDni }}"</strong>.
                                </p>
                            </div>
                            {{-- Mostrar input DNI también para re-buscar --}}
                            <div class="flex gap-3">
                                <input type="text"
                                       wire:model="{{$key}}_dni"
                                       class="flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                              focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                                <button type="button"
                                        wire:click="buscarPersona('{{ $key }}')"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    Buscar
                                </button>
                            </div>
                            @if (! $isMiniOpen)
                                <button type="button"
                                        wire:click="abrirCrearPersona('{{ $key }}')"
                                        class="w-full py-2 text-sm font-semibold text-emerald-700 dark:text-emerald-300
                                               border border-emerald-300 dark:border-emerald-600 rounded-lg
                                               hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                    + Crear Nueva Persona
                                </button>
                            @endif
                        </div>
                    @endif

                    {{-- ════ MINI-FORM: CREAR PERSONA ══════════════════════════ --}}
                    @if ($isMiniOpen && $mini_tipo === 'persona')
                        <div class="p-5 rounded-lg border border-emerald-200 dark:border-emerald-800
                                    bg-emerald-50/50 dark:bg-emerald-900/10 space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    Nueva Persona — {{ $rc['label'] }}
                                </h4>
                                <button type="button" wire:click="cancelarMini"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Número de Identidad <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model.defer="mini_p_dni" placeholder="Ej: 0801199912345"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                                  focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white
                                                  @error('mini_p_dni') border-red-500 @enderror" />
                                    @error('mini_p_dni')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Primer Nombre <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model.defer="mini_p_primer_nombre"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                                  focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white
                                                  @error('mini_p_primer_nombre') border-red-500 @enderror" />
                                    @error('mini_p_primer_nombre')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Segundo Nombre
                                    </label>
                                    <input type="text" wire:model.defer="mini_p_segundo_nombre"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                                  focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Primer Apellido <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model.defer="mini_p_primer_apellido"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                                  focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white
                                                  @error('mini_p_primer_apellido') border-red-500 @enderror" />
                                    @error('mini_p_primer_apellido')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Segundo Apellido
                                    </label>
                                    <input type="text" wire:model.defer="mini_p_segundo_apellido"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                                  focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Teléfono
                                    </label>
                                    <input type="text" wire:model.defer="mini_p_telefono"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                                  focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Correo Electrónico
                                    </label>
                                    <input type="email" wire:model.defer="mini_p_email"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                                  focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white
                                                  @error('mini_p_email') border-red-500 @enderror" />
                                    @error('mini_p_email')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>

                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="button" wire:click="cancelarMini"
                                        class="flex-1 py-2 text-sm font-medium text-gray-600 dark:text-gray-300
                                               border border-gray-300 dark:border-gray-600 rounded-lg
                                               hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Cancelar
                                </button>
                                <button type="button" wire:click="guardarMiniPersona"
                                        wire:loading.attr="disabled"
                                        class="flex-1 py-2 text-sm font-semibold text-white bg-emerald-600
                                               hover:bg-emerald-700 rounded-lg transition-colors disabled:opacity-60">
                                    <span wire:loading.remove wire:target="guardarMiniPersona">Guardar Persona</span>
                                    <span wire:loading wire:target="guardarMiniPersona">Guardando…</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- ════ MINI-FORM: REGISTRAR COMO FELIGRÉS ════════════════ --}}
                    @if ($isMiniOpen && $mini_tipo === 'feligres')
                        <div class="p-5 rounded-lg border border-blue-200 dark:border-blue-800
                                    bg-blue-50/50 dark:bg-blue-900/10 space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-300 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Registrar como Feligrés — {{ $rolPersona['nombre_completo'] ?? '' }}
                                </h4>
                                <button type="button" wire:click="cancelarMini"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            @error('iglesia_id')
                                <div class="p-3 rounded bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700">
                                    <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                </div>
                            @enderror

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Fecha de Ingreso
                                    </label>
                                    <input type="date" wire:model.defer="mini_f_fecha_ingreso"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                                  focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Estado <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model.defer="mini_f_estado"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                                   focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>

                            <p class="text-xs text-blue-600 dark:text-blue-400">
                                Se registrará en la iglesia seleccionada en el Paso 1.
                            </p>

                            <div class="flex gap-3 pt-1">
                                <button type="button" wire:click="cancelarMini"
                                        class="flex-1 py-2 text-sm font-medium text-gray-600 dark:text-gray-300
                                               border border-gray-300 dark:border-gray-600 rounded-lg
                                               hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Cancelar
                                </button>
                                <button type="button" wire:click="guardarMiniFeligres"
                                        wire:loading.attr="disabled"
                                        class="flex-1 py-2 text-sm font-semibold text-white bg-blue-600
                                               hover:bg-blue-700 rounded-lg transition-colors disabled:opacity-60">
                                    <span wire:loading.remove wire:target="guardarMiniFeligres">Registrar Feligrés</span>
                                    <span wire:loading wire:target="guardarMiniFeligres">Guardando…</span>
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endforeach

        {{-- Error global bautizado --}}
        @error('bautizado_dni')
            <div class="flex items-center gap-2 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/>
                </svg>
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            </div>
        @enderror
    </div>
    @endif

    {{-- ══ PASO 3: REGISTRO + RESUMEN ══════════════════════════════════ --}}
    @if ($paso === 3)
    <div class="space-y-5">

        {{-- Resumen --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">

            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full
                                 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs font-bold">✓</span>
                    Resumen del Bautismo
                </h2>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                @php
                    $igObj = $iglesias->firstWhere('id', $iglesia_id);
                    $encObj = $encargados->firstWhere('id', $encargado_id);
                @endphp

                <div>
                    <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Iglesia</span>
                    <p class="font-medium text-gray-900 dark:text-white mt-0.5">
                        {{ $igObj?->nombre ?? '—' }}
                    </p>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Fecha de Bautismo</span>
                    <p class="font-medium text-gray-900 dark:text-white mt-0.5">
                        {{ $fecha_bautismo ? \Carbon\Carbon::parse($fecha_bautismo)->format('d/m/Y') : '—' }}
                    </p>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Encargado</span>
                    <p class="font-medium text-gray-900 dark:text-white mt-0.5">
                        @if ($encObj && $encObj->feligres && $encObj->feligres->persona)
                            {{ $encObj->feligres->persona->nombre_completo }}
                        @else
                            <span class="text-gray-400">No especificado</span>
                        @endif
                    </p>
                </div>

                @php
                    $sumRoles = [
                        ['key'=>'bautizado','label'=>'Bautizado'],
                        ['key'=>'padre',    'label'=>'Padre'],
                        ['key'=>'madre',    'label'=>'Madre'],
                        ['key'=>'padrino',  'label'=>'Padrino'],
                        ['key'=>'madrina',  'label'=>'Madrina'],
                    ];
                @endphp

                @foreach ($sumRoles as $sr)
                    @php $sp = $this->{"{$sr['key']}_persona"}; @endphp
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">{{ $sr['label'] }}</span>
                        <p class="font-medium text-gray-900 dark:text-white mt-0.5">
                            @if ($sp)
                                {{ $sp['nombre_completo'] }}
                                <span class="text-xs text-gray-400 ml-1">DNI: {{ $sp['dni'] }}</span>
                            @else
                                <span class="text-gray-400">No especificado</span>
                            @endif
                        </p>
                    </div>
                @endforeach

            </div>
        </div>

        {{-- Campos del libro parroquial --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">

            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full
                                 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-bold">3</span>
                    Libro Parroquial
                    <span class="text-xs text-gray-400 font-normal">(opcional)</span>
                </h2>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Libro de Bautismo
                    </label>
                    <input type="text" wire:model.defer="libro_bautismo"
                           placeholder="Ej: Tomo III"
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Folio
                    </label>
                    <input type="text" wire:model.defer="folio"
                           placeholder="Ej: 42"
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Partida Número
                    </label>
                    <input type="text" wire:model.defer="partida_numero"
                           placeholder="Ej: 0125"
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" />
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Observaciones
                    </label>
                    <textarea wire:model.defer="observaciones"
                              rows="3"
                              placeholder="Notas adicionales sobre el bautismo…"
                              class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                     focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-none"></textarea>
                </div>

            </div>
        </div>
    </div>
    @endif

    {{-- ══ BOTONES DE NAVEGACIÓN ════════════════════════════════════════ --}}
    <div class="flex justify-between items-center gap-4">

        {{-- Anterior --}}
        @if ($paso > 1)
            <button type="button"
                    wire:click="anteriorPaso"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200
                           border border-gray-300 dark:border-gray-600 rounded-lg
                           hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Anterior
            </button>
        @else
            <div></div>
        @endif

        {{-- Siguiente / Guardar --}}
        @if ($paso < 3)
            <button type="button"
                    wire:click="siguientePaso"
                    wire:loading.attr="disabled"
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600
                           hover:bg-blue-700 rounded-lg transition-colors flex items-center gap-2 disabled:opacity-60">
                <span wire:loading.remove wire:target="siguientePaso">
                    Siguiente
                    <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
                <span wire:loading wire:target="siguientePaso">Validando…</span>
            </button>
        @else
            <button type="button"
                    wire:click="guardar"
                    wire:loading.attr="disabled"
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-green-600
                           hover:bg-green-700 rounded-lg transition-colors flex items-center gap-2 disabled:opacity-60">
                <svg wire:loading.remove wire:target="guardar"
                     class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <svg wire:loading wire:target="guardar"
                     class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
                <span wire:loading.remove wire:target="guardar">Registrar Bautismo</span>
                <span wire:loading wire:target="guardar">Guardando…</span>
            </button>
        @endif

    </div>

</div>
