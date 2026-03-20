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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Iglesia bloqueada --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Iglesia</label>
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
                    @error('iglesia_id')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Fecha de Matrimonio <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model.live="fecha_matrimonio"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                  @error('fecha_matrimonio') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror">
                    @error('fecha_matrimonio')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Encargado --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Padre / Celebrante <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="encargado_id"
                            class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                   focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                   @error('encargado_id') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror">
                        <option value="">— Selecciona —</option>
                        @foreach ($encargados as $enc)
                            <option value="{{ $enc->id }}">{{ $enc->feligres?->persona?->nombre_completo ?? 'Encargado #'.$enc->id }}</option>
                        @endforeach
                    </select>
                    @error('encargado_id')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Roles del matrimonio (editable) --}}
                <div class="sm:col-span-2 border border-gray-200 dark:border-gray-700/60 rounded-xl overflow-hidden">
                    @php
                        $rolesConfig = [
                            ['key' => 'esposo',   'label' => 'Esposo',    'required' => true],
                            ['key' => 'esposa',   'label' => 'Esposa',    'required' => true],
                            ['key' => 'testigo1', 'label' => 'Testigo 1', 'required' => false],
                            ['key' => 'testigo2', 'label' => 'Testigo 2', 'required' => false],
                        ];
                    @endphp

                    <div class="px-5 py-3 bg-gray-50 dark:bg-gray-800/70 border-b border-gray-200 dark:border-gray-700/60">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Esposos y Testigos</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Puedes cambiar las personas asignadas igual que en confirmación.</p>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @foreach ($rolesConfig as $rc)
                            @php
                                $key       = $rc['key'];
                                $rolDni    = $this->{"{$key}_dni"};
                                $rolPersona= $this->{"{$key}_persona"};
                                $rolEstado = $this->{"{$key}_estado"};
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

                                    @if ($rolEstado !== 'idle')
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
                                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-semibold
                                                       bg-indigo-600 hover:bg-indigo-700 text-white transition-all">
                                            Buscar
                                        </button>
                                    </div>
                                    @error("{$key}_dni")
                                        <p class="ml-11 mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                @endif

                                @if ($rolEstado === 'multiples' && $busqueda_rol === $key)
                                    <div class="ml-11 space-y-1.5 max-h-48 overflow-y-auto mt-2">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ count($busqueda_resultados) }} resultados — selecciona uno:</p>
                                        @foreach ($busqueda_resultados as $res)
                                            <button type="button"
                                                    wire:click="seleccionarResultado({{ $res['id'] }})"
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
                                            @if ($rolPersona['telefono'])
                                                &nbsp;&middot;&nbsp;{{ $rolPersona['telefono'] }}
                                            @endif
                                        </p>
                                    </div>
                                @endif

                                @if ($rolEstado === 'sin_feligres')
                                    <div class="ml-11 mt-2 p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50">
                                        <p class="text-xs font-medium text-amber-700 dark:text-amber-300">
                                            <strong>{{ $rolPersona['nombre_completo'] }}</strong> existe pero no está registrada como feligrés.
                                        </p>
                                    </div>
                                @endif

                                @if ($rolEstado === 'sin_persona')
                                    <div class="ml-11 mt-2 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700/50">
                                        <p class="text-xs font-medium text-red-700 dark:text-red-300">No se encontró ninguna persona para "{{ $rolDni }}".</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @error('esposo_feligres_id')
                        <p class="px-5 py-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    @error('esposa_feligres_id')
                        <p class="px-5 pb-3 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Libro --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Libro</label>
                    <input wire:model.lazy="libro_matrimonio" type="text"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                {{-- Folio --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Folio</label>
                    <input wire:model.lazy="folio" type="text"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                {{-- Partida --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Partida N°</label>
                    <input wire:model.lazy="partida_numero" type="text"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                {{-- Lugar de Expedición --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Lugar de Expedición</label>
                    <input wire:model.lazy="lugar_expedicion" type="text"
                           class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('lugar_expedicion')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Observaciones --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Observaciones</label>
                    <textarea wire:model.lazy="observaciones" rows="2"
                              class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                     bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                     focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"></textarea>
                </div>

                {{-- Nota Marginal --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Nota Marginal</label>
                    <textarea wire:model.lazy="nota_marginal" rows="2"
                              class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                     bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                     focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"></textarea>
                    @error('nota_marginal')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha Expedición --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Fecha de Expedición</label>
                    <div class="flex gap-3">
                        <input wire:model.lazy="exp_dia" type="number" min="1" max="31" placeholder="Día"
                               class="w-24 px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                      focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <input wire:model.lazy="exp_mes" type="number" min="1" max="12" placeholder="Mes"
                               class="w-24 px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                      focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <input wire:model.lazy="exp_ano" type="number" min="0" max="99" placeholder="Año (2 dígitos)"
                               class="w-36 px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                      focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 dark:border-gray-700/50">
                <a href="{{ route('matrimonio.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300
                          hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium transition-colors">
                    Cancelar
                </a>
                <button wire:click="guardar"
                        class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition-colors shadow-sm">
                    Guardar Cambios
                </button>
            </div>
        </div>
    </div>

</div>
