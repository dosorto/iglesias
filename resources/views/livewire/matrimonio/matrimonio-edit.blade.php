<div class="space-y-6">

    {{-- HEADER --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600
                dark:from-indigo-700 dark:to-violet-700 shadow-md px-6 py-5">
        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
        <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white leading-tight">Editar Matrimonio</h1>
                    <p class="text-indigo-100 text-sm mt-0.5">Modifica los datos del registro matrimonial.</p>
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

    {{-- FORMULARIO --}}
    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                ring-1 ring-black/5 dark:ring-white/5">

        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                         bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300
                         text-xs font-bold ring-2 ring-indigo-200 dark:ring-indigo-700/50">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </span>
            <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                Datos del Matrimonio
            </h2>
        </div>

        <div class="p-6 space-y-5">

            {{-- ── Parroquia, Fecha, Encargado ── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Parroquia</label>
                    <div class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-gray-700
                                bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300
                                flex items-center gap-2 select-none">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span class="font-medium flex-1">{{ $iglesiaActual?->nombre ?? '—' }}</span>
                        <span class="text-[11px] text-gray-400 italic bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">No modificable</span>
                    </div>
                    <input type="hidden" wire:model="iglesia_id">
                    @error('iglesia_id') <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Fecha de Matrimonio <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model.live="fecha_matrimonio"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                  @error('fecha_matrimonio') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror">
                    @error('fecha_matrimonio') <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

            </div>

            {{-- ══ ACORDEÓN: Esposos y Testigos — VA ANTES DEL LIBRO ══ --}}
            @php
                $rolesMatrimonio = [
                    ['key' => 'esposo',   'label' => 'Esposo',    'short' => 'Esp', 'required' => true],
                    ['key' => 'esposa',   'label' => 'Esposa',    'short' => 'Esa', 'required' => true],
                    ['key' => 'testigo1', 'label' => 'Testigo 1', 'short' => 'T1',  'required' => false],
                    ['key' => 'testigo2', 'label' => 'Testigo 2', 'short' => 'T2',  'required' => false],
                ];
                $asignadosMat = collect($rolesMatrimonio)->filter(fn($r) => $this->{"{$r['key']}_estado"} === 'found');
            @endphp

            <div x-data="{ open: {{ $asignadosMat->isNotEmpty() ? 'true' : 'false' }} }"
                 class="border border-gray-200 dark:border-gray-700/60 rounded-xl overflow-hidden">

                <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-between px-5 py-4
                               bg-gray-50 dark:bg-gray-800/80 hover:bg-gray-100 dark:hover:bg-gray-700/60
                               transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Esposos y Testigos</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                Esposo, esposa, testigo 1 y testigo 2
                                @if ($asignadosMat->isNotEmpty())
                                    — <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ $asignadosMat->count() }} asignado(s)</span>
                                @else
                                    — <span class="italic">sin asignar</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @foreach ($rolesMatrimonio as $r)
                            @if ($this->{"{$r['key']}_estado"} === 'found')
                                <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold
                                             bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">
                                    ✓ {{ $r['short'] }}
                                </span>
                            @endif
                        @endforeach
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200"
                             :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="border-t border-gray-200 dark:border-gray-700/60">

                    <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @foreach ($rolesMatrimonio as $rc)
                            @php
                                $key        = $rc['key'];
                                $rolDni     = $this->{"{$key}_dni"};
                                $rolPersona = $this->{"{$key}_persona"};
                                $rolEstado  = $this->{"{$key}_estado"};
                                $isMiniOpen = ($mini_rol === $key);
                            @endphp

                            <div class="px-5 py-4 {{ $rolEstado === 'found' ? 'bg-emerald-50/40 dark:bg-emerald-900/5' : '' }}">
                                <div class="flex items-center gap-3 mb-3">
                                    <div @class([
                                        'w-8 h-8 rounded-full flex items-center justify-center shrink-0 text-xs font-bold',
                                        'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' => $rolEstado === 'found',
                                        'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400'               => $rolEstado !== 'found',
                                    ])>
                                        @if ($rolEstado === 'found')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @else
                                            {{ strtoupper(substr($rc['label'], 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $rc['label'] }}</span>
                                            @if ($rc['required'])
                                                <span class="text-[10px] px-1.5 py-0.5 rounded-full font-semibold bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-300">Obligatorio</span>
                                            @else
                                                <span class="text-[10px] px-1.5 py-0.5 rounded-full font-medium bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500">Opcional</span>
                                            @endif
                                            @if ($rolEstado === 'found')
                                                <span class="text-[10px] px-1.5 py-0.5 rounded-full font-semibold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">
                                                    ✓ {{ $rolPersona['nombre_completo'] }}
                                                </span>
                                            @elseif ($rolEstado === 'idle')
                                                <span class="text-[10px] text-gray-400 dark:text-gray-500 italic">Sin asignar</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($rolEstado !== 'idle' || $isMiniOpen)
                                        <button type="button" wire:click="limpiarRol('{{ $key }}')"
                                                class="shrink-0 inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium
                                                       text-red-500 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30
                                                       border border-red-200 dark:border-red-800/40 transition-all">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Quitar
                                        </button>
                                    @endif
                                </div>

                                @if ($rolEstado === 'idle')
                                    <div class="flex gap-2 ml-11">
                                        <div class="relative flex-1">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                                </svg>
                                            </div>
                                            <input type="text"
                                                   wire:model="{{ $key }}_dni"
                                                   wire:keydown.enter="buscarPersona('{{ $key }}')"
                                                   placeholder="DNI o nombre del {{ strtolower($rc['label']) }}..."
                                                   class="block w-full pl-8 pr-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white dark:placeholder-gray-400
                                                          focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                                        </div>
                                        <button type="button"
                                                wire:click="buscarPersona('{{ $key }}')"
                                                wire:loading.attr="disabled" wire:target="buscarPersona('{{ $key }}')"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-semibold
                                                       bg-indigo-600 hover:bg-indigo-700 text-white transition-all disabled:opacity-60">
                                            <svg wire:loading.remove wire:target="buscarPersona('{{ $key }}')" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                            <svg wire:loading wire:target="buscarPersona('{{ $key }}')" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                            </svg>
                                            Buscar
                                        </button>
                                    </div>
                                    @error("{$key}_dni") <p class="ml-11 mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                @endif

                                @if ($rolEstado === 'multiples' && $busqueda_rol === $key)
                                    <div class="ml-11 space-y-1.5 max-h-48 overflow-y-auto mt-2">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ count($busqueda_resultados) }} resultados — selecciona uno:</p>
                                        @foreach ($busqueda_resultados as $res)
                                            <button type="button" wire:click="seleccionarResultado({{ $res['id'] }})"
                                                    class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-left
                                                           border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700/40
                                                           hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:border-indigo-300
                                                           transition-all group text-sm">
                                                <span class="font-medium text-gray-900 dark:text-white flex-1 truncate">{{ $res['nombre_completo'] }}</span>
                                                <span class="text-xs text-gray-400 font-mono shrink-0">{{ $res['dni'] }}</span>
                                                <svg class="w-3.5 h-3.5 text-gray-300 group-hover:text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($rolEstado === 'found')
                                    <div class="ml-11 mt-2 p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700/50">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $rolPersona['nombre_completo'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            DNI: {{ $rolPersona['dni'] }}
                                            @if ($rolPersona['telefono'] ?? null) &nbsp;&middot;&nbsp;{{ $rolPersona['telefono'] }} @endif
                                        </p>
                                    </div>
                                @endif

                                @if ($rolEstado === 'sin_feligres')
                                    <div class="ml-11 mt-2 p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50">
                                        <p class="text-xs font-medium text-amber-700 dark:text-amber-300">
                                            <strong>{{ $rolPersona['nombre_completo'] }}</strong> existe pero no está registrada como feligrés.
                                        </p>
                                        @if (! $isMiniOpen)
                                            <button type="button" wire:click="abrirRegistrarFeligres('{{ $key }}')"
                                                    class="mt-2 text-xs font-semibold text-amber-700 dark:text-amber-300 border border-amber-300 dark:border-amber-600 rounded-lg px-3 py-1.5 hover:bg-amber-100 transition-all">
                                                + Registrar como feligrés
                                            </button>
                                        @endif
                                    </div>
                                @endif

                                @if ($rolEstado === 'sin_persona')
                                    <div class="ml-11 mt-2 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700/50 space-y-2">
                                        <p class="text-xs font-medium text-red-700 dark:text-red-300">No se encontró ninguna persona para "{{ $rolDni }}".</p>
                                        <div class="flex gap-2">
                                            <input type="text" wire:model="{{ $key }}_dni" autocomplete="off"
                                                   class="flex-1 px-3 py-1.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                                            <button type="button" wire:click="buscarPersona('{{ $key }}')"
                                                    class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg">Buscar</button>
                                        </div>
                                        @if (! $isMiniOpen)
                                            <button type="button" wire:click="abrirCrearPersona('{{ $key }}')"
                                                    class="w-full py-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-600 rounded-lg hover:bg-emerald-50 transition-all">
                                                + Registrar nuevo feligrés
                                            </button>
                                        @endif
                                    </div>
                                @endif

                                @if ($isMiniOpen && $mini_tipo === 'persona')
                                    <div class="ml-11 mt-3 rounded-xl border border-emerald-200 dark:border-emerald-700/50 overflow-hidden">
                                        <div class="flex items-center justify-between px-4 py-2.5 bg-emerald-50 dark:bg-emerald-900/20 border-b border-emerald-100 dark:border-emerald-800/40">
                                            <span class="text-xs font-bold text-emerald-800 dark:text-emerald-300">Nuevo feligrés — {{ $rc['label'] }}</span>
                                            <button type="button" wire:click="cancelarMini" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div class="sm:col-span-2">
                                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Número de Identidad <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model="mini_p_dni" placeholder="Ej: 0801199912345"
                                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 @error('mini_p_dni') border-red-400 @enderror" />
                                                @error('mini_p_dni') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Primer Nombre <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model="mini_p_primer_nombre" oninput="this.value=this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/g,'').replace(/\b\w/g,c=>c.toUpperCase())"
                                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 @error('mini_p_primer_nombre') border-red-400 @enderror" />
                                                @error('mini_p_primer_nombre') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Segundo Nombre</label>
                                                <input type="text" wire:model="mini_p_segundo_nombre" oninput="this.value=this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/g,'').replace(/\b\w/g,c=>c.toUpperCase())"
                                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500" />
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Primer Apellido <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model="mini_p_primer_apellido" oninput="this.value=this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/g,'').replace(/\b\w/g,c=>c.toUpperCase())"
                                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 @error('mini_p_primer_apellido') border-red-400 @enderror" />
                                                @error('mini_p_primer_apellido') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Segundo Apellido</label>
                                                <input type="text" wire:model="mini_p_segundo_apellido" oninput="this.value=this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/g,'').replace(/\b\w/g,c=>c.toUpperCase())"
                                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500" />
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Fecha de Nacimiento <span class="text-red-500">*</span></label>
                                                <input type="date" wire:model="mini_p_fecha_nacimiento"
                                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 @error('mini_p_fecha_nacimiento') border-red-400 @enderror" />
                                                @error('mini_p_fecha_nacimiento') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Sexo <span class="text-red-500">*</span></label>
                                                <select wire:model="mini_p_sexo"
                                                        class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 @error('mini_p_sexo') border-red-400 @enderror">
                                                    <option value="">Seleccionar...</option>
                                                    <option value="M">Masculino</option>
                                                    <option value="F">Femenino</option>
                                                </select>
                                                @error('mini_p_sexo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Teléfono <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model="mini_p_telefono" oninput="this.value=this.value.replace(/[^0-9+\-]/g,'')" placeholder="+504 0000-0000"
                                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 @error('mini_p_telefono') border-red-400 @enderror" />
                                                @error('mini_p_telefono') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Correo Electrónico</label>
                                                <input type="email" wire:model="mini_p_email" placeholder="ejemplo@correo.com"
                                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500" />
                                            </div>
                                            @if ($advertenciaDuplicado)
                                                <div class="sm:col-span-2 mb-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 p-3">
                                                    <div class="flex items-start gap-2">
                                                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" /></svg>
                                                        <div class="flex-1">
                                                            <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Posible duplicado</p>
                                                            <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5">
                                                                Ya existe <strong>{{ $advertenciaDuplicado }}</strong> sin número de identidad.
                                                            </p>
                                                            <div class="flex items-center gap-3 mt-2">
                                                                <button type="button" wire:click="confirmarYGuardarMiniPersona"
                                                                        class="text-xs font-semibold text-white bg-amber-600 hover:bg-amber-700 px-3 py-1 rounded-md transition-colors">
                                                                    Sí, registrar de todas formas
                                                                </button>
                                                                <button type="button" wire:click="$set('advertenciaDuplicado', '')"
                                                                        class="text-xs text-amber-700 dark:text-amber-400 underline hover:no-underline">
                                                                    Cancelar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="sm:col-span-2 flex justify-end gap-2 pt-2 border-t border-emerald-100 dark:border-emerald-800/40">
                                                <button type="button" wire:click="cancelarMini"
                                                        class="px-4 py-2 text-xs font-medium rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 transition-all">Cancelar</button>
                                                <button type="button" wire:click="guardarMiniPersona" wire:loading.attr="disabled" wire:target="guardarMiniPersona"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white transition-all disabled:opacity-60">
                                                    <svg wire:loading wire:target="guardarMiniPersona" class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                                                    <span wire:loading.remove wire:target="guardarMiniPersona">Guardar</span>
                                                    <span wire:loading wire:target="guardarMiniPersona">Guardando...</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($isMiniOpen && $mini_tipo === 'feligres')
                                    <div class="ml-11 mt-3 rounded-xl border border-blue-200 dark:border-blue-700/50 overflow-hidden">
                                        <div class="flex items-center justify-between px-4 py-2.5 bg-blue-50 dark:bg-blue-900/20 border-b border-blue-100 dark:border-blue-800/40">
                                            <span class="text-xs font-bold text-blue-800 dark:text-blue-300">Registrar como Feligrés — {{ $rolPersona['nombre_completo'] ?? '' }}</span>
                                            <button type="button" wire:click="cancelarMini" class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                        <div class="p-4">
                                            <p class="text-xs text-blue-600 dark:text-blue-400 mb-3">Se registrará en la parroquia activa del sistema.</p>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" wire:click="cancelarMini"
                                                        class="px-4 py-2 text-xs font-medium rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 transition-all">Cancelar</button>
                                                <button type="button" wire:click="guardarMiniFeligres" wire:loading.attr="disabled" wire:target="guardarMiniFeligres"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white transition-all disabled:opacity-60">
                                                    <svg wire:loading wire:target="guardarMiniFeligres" class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                                                    <span wire:loading.remove wire:target="guardarMiniFeligres">Registrar Feligrés</span>
                                                    <span wire:loading wire:target="guardarMiniFeligres">Guardando...</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @error('esposo_feligres_id') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            @error('esposa_feligres_id') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

            {{-- ══ PÁRROCO AUTOMÁTICO (encargado activo) ══ --}}
            <input type="hidden" wire:model="encargado_id">
            <div class="border {{ $encargado_info ? 'border-teal-200 dark:border-teal-700/50' : 'border-gray-200 dark:border-gray-700/60' }} rounded-xl overflow-hidden">
                <div class="flex items-center gap-3 px-5 py-3.5
                            {{ $encargado_info ? 'bg-teal-50 dark:bg-teal-900/10' : 'bg-gray-50 dark:bg-gray-800/80' }}
                            border-b {{ $encargado_info ? 'border-teal-100 dark:border-teal-800/40' : 'border-gray-200 dark:border-gray-700/60' }}">
                    <div class="w-8 h-8 rounded-full {{ $encargado_info ? 'bg-teal-100 dark:bg-teal-900/40' : 'bg-gray-100 dark:bg-gray-700' }} flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 {{ $encargado_info ? 'text-teal-600 dark:text-teal-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Párroco</p>
                    </div>
                    @if ($encargado_info)
                        <span class="ml-auto text-[10px] px-2 py-0.5 rounded-full font-semibold bg-teal-100 dark:bg-teal-900/40 text-teal-700 dark:text-teal-300">
                            ✓ Asignado
                        </span>
                    @endif
                </div>

                <div class="px-5 py-4">
                    @if ($encargado_info)
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-700/40">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center shrink-0 shadow-sm">
                                <span class="text-white text-sm font-bold">{{ strtoupper(substr($encargado_info['nombre_completo'], 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $encargado_info['nombre_completo'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">DNI: {{ $encargado_info['dni'] }}</p>
                                <span class="inline-block mt-0.5 text-[10px] px-1.5 py-0.5 rounded-full font-semibold bg-teal-100 dark:bg-teal-900/40 text-teal-700 dark:text-teal-300">
                                    Encargado activo
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2.5 p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-amber-700 dark:text-amber-300">No hay ningún encargado activo configurado.</p>
                        </div>
                    @endif
                </div>
            </div>

            @error('encargado_id') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

            {{-- ── Libro, Folio, Partida, Lugar, Observaciones, Nota Marginal, Fecha Exp. ── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2 border-t border-gray-100 dark:border-gray-700/60">

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Libro</label>
                    <input wire:model.lazy="libro_matrimonio" type="text" placeholder="Ej: Libro III"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white placeholder-gray-400
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Folio</label>
                    <input wire:model.lazy="folio" type="text" placeholder="Ej: F-10"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white placeholder-gray-400
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Partida N°</label>
                    <input wire:model.lazy="partida_numero" type="text" placeholder="Ej: P-0010"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white placeholder-gray-400
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Observaciones</label>
                    <textarea wire:model.lazy="observaciones" rows="2" placeholder="Observaciones adicionales…"
                              class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                     bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white placeholder-gray-400
                                     focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"></textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Nota Marginal</label>
                    <textarea wire:model.lazy="nota_marginal" rows="2" placeholder="Notas adicionales o sacramentos posteriores"
                              class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                     bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white placeholder-gray-400
                                     focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"></textarea>
                    @error('nota_marginal') <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Fecha de Expedición</label>
                    <div class="flex gap-3">
                        <input wire:model.lazy="exp_dia" type="number" min="1" max="31" placeholder="Día"
                               class="w-24 px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                      focus:ring-2 focus:ring-indigo-500 focus:border-transparent [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                        <input wire:model.lazy="exp_mes" type="number" min="1" max="12" placeholder="Mes"
                               class="w-24 px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                      focus:ring-2 focus:ring-indigo-500 focus:border-transparent [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                        <input wire:model.lazy="exp_ano" type="number" min="0" max="99" placeholder="Año (2 dígitos)"
                               class="w-36 px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                      focus:ring-2 focus:ring-indigo-500 focus:border-transparent [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                    </div>
                </div>

            </div>

            {{-- ── Botones ── --}}
            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 dark:border-gray-700/50">
                <a href="{{ route('matrimonio.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300
                          hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium transition-colors">
                    Cancelar
                </a>
                <button wire:click="guardar" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2.5 px-6 py-2.5 rounded-lg text-sm font-bold
                               shadow-md shadow-indigo-500/30 transition-all
                               bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700
                               text-white disabled:opacity-50 active:scale-[0.98]">
                    <svg wire:loading wire:target="guardar" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <svg wire:loading.remove wire:target="guardar" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    <span wire:loading.remove wire:target="guardar">Guardar Cambios</span>
                    <span wire:loading wire:target="guardar">Guardando…</span>
                </button>
            </div>

        </div>
    </div>

</div>