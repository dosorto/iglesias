<div class="space-y-6">

    {{-- ══ HEADER ════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600
                dark:from-indigo-700 dark:to-violet-700 shadow-md px-6 py-5">
        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
        <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                 m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white leading-tight">Editar Bautismo</h1>
                    <p class="text-indigo-100 text-sm mt-0.5">
                        Modifica los datos del registro bautismal.
                    </p>
                </div>
            </div>

            <a href="{{ route('bautismo.index') }}"
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

    {{-- ══ FORMULARIO ═════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                ring-1 ring-black/5 dark:ring-white/5">

        {{-- Card header --}}
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                         bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300
                         text-xs font-bold ring-2 ring-indigo-200 dark:ring-indigo-700/50">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944
                             a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591
                             3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622
                             0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </span>
            <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                Datos del Bautismo
            </h2>
        </div>

        <div class="p-6 space-y-5">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- ── Iglesia ─────────────────────────────────────────── --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Iglesia <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="iglesia_id"
                            class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                   border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                   focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                   @error('iglesia_id') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror">
                        <option value="">— Selecciona —</option>
                        @foreach ($iglesias as $ig)
                            <option value="{{ $ig->id }}">{{ $ig->nombre }}</option>
                        @endforeach
                    </select>
                    @error('iglesia_id')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ── Fecha de Bautismo ───────────────────────────────── --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Fecha de Bautismo <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           wire:model.live="fecha_bautismo"
                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                  border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                  @error('fecha_bautismo') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                    @error('fecha_bautismo')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ── Encargado ───────────────────────────────────────── --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Encargado <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="encargado_id"
                            class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                   border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                   focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                   @error('encargado_id') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror">
                        <option value="">— Selecciona —</option>
                        @foreach ($encargados as $enc)
                            <option value="{{ $enc->id }}">
                                {{ $enc->feligres?->persona?->nombre_completo ?? 'Encargado #'.$enc->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('encargado_id')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ── Libro de Bautismo ───────────────────────────────── --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Libro de Bautismo
                    </label>
                    <input type="text"
                           wire:model.live.debounce.400ms="libro_bautismo"
                           placeholder="Ej: Libro III"
                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                  border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white placeholder-gray-400
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                  @error('libro_bautismo') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                    @error('libro_bautismo')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ── Folio ───────────────────────────────────────────── --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Folio
                    </label>
                    <input type="text"
                           wire:model.live.debounce.400ms="folio"
                           placeholder="Ej: F-10"
                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                  border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white placeholder-gray-400
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                  @error('folio') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                    @error('folio')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ── Partida N° ──────────────────────────────────────── --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Partida N°
                    </label>
                    <input type="text"
                           wire:model.live.debounce.400ms="partida_numero"
                           placeholder="Ej: P-0010"
                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                  border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white placeholder-gray-400
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                  @error('partida_numero') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                    @error('partida_numero')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- ── Observaciones ───────────────────────────────────────── --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                    Observaciones
                </label>
                <textarea wire:model.live.debounce.400ms="observaciones"
                          rows="3"
                          placeholder="Observaciones adicionales…"
                          class="block w-full px-3 py-2.5 text-sm rounded-lg transition-colors resize-y
                                 border border-gray-300 dark:border-gray-600
                                 bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white placeholder-gray-400
                                 focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                 @error('observaciones') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror"></textarea>
                @error('observaciones')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500 text-right">
                    {{ strlen($observaciones ?? '') }} / 500
                </p>
            </div>

            {{-- ── Barra de acciones ───────────────────────────────────── --}}
            <div class="flex items-center justify-between pt-5 border-t border-gray-100 dark:border-gray-700/50">
                <a href="{{ route('bautismo.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                          bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                          text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancelar
                </a>

                <button type="button"
                        wire:click="guardar"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2.5 px-7 py-2.5 rounded-lg text-sm font-bold
                               shadow-md shadow-indigo-500/30 transition-all duration-150
                               bg-gradient-to-r from-indigo-500 to-indigo-600
                               hover:from-indigo-600 hover:to-indigo-700
                               active:scale-[0.98]
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none
                               text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800">
                    <svg wire:loading wire:target="guardar"
                         class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <svg wire:loading.remove wire:target="guardar"
                         class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
