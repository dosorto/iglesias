<div class="space-y-6">

    {{-- ══ HEADER ════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-amber-600 to-orange-600
                dark:from-amber-700 dark:to-orange-700 shadow-md px-6 py-5">
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
                    <h1 class="text-xl font-bold text-white leading-tight">Editar Instructor</h1>
                    <p class="text-amber-100 text-sm mt-0.5">
                        Modifica los datos del instructor
                        <strong>{{ $instructor->feligres?->persona?->nombre_completo }}</strong>.
                    </p>
                </div>
            </div>

            <a href="{{ route('instructor.index') }}"
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

        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                         bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300
                         text-xs font-bold ring-2 ring-amber-200 dark:ring-amber-700/50">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </span>
            <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                Datos del Instructor
            </h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- ── Estado ──────────────────────────────────────────── --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="estado"
                            class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                   border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700/60
                                   text-gray-900 dark:text-white
                                   focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                   @error('estado') border-red-400 @enderror">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ── Vista previa badge ──────────────────────────────── --}}
                <div class="flex items-end pb-0.5">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1.5">
                            Vista previa
                        </p>
                        @php
                            $badgeClass = $estado === 'Activo'
                                ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300'
                                : 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                            {{ $estado ?? '—' }}
                        </span>
                    </div>
                </div>

                {{-- ── Fecha Ingreso ───────────────────────────────────── --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Fecha de Ingreso
                    </label>
                    <input type="date"
                           wire:model="fecha_ingreso"
                           class="block w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                  border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60
                                  text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-amber-500 focus:border-transparent" />
                    @error('fecha_ingreso')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ── Firma (reemplazar) ──────────────────────────────── --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Firma
                        <span class="ml-1 text-[10px] font-normal text-gray-400 normal-case">(dejar vacío para mantener la actual)</span>
                    </label>

                    {{-- Firma actual --}}
                    @if($instructor->path_firma)
                        <img src="{{ asset('storage/' . $instructor->path_firma) }}" 
                            alt="Firma actual"
                            style="max-height:150px;">
                    @endif

                    <label class="relative mt-3 block cursor-pointer rounded-xl border-2 border-dashed border-amber-300 dark:border-amber-700/60
                                 bg-amber-50/60 dark:bg-amber-900/10 p-4 transition-colors
                                 hover:border-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20">
                        <input type="file"
                               wire:model="firma"
                               accept="image/*"
                               class="absolute inset-0 h-full w-full cursor-pointer opacity-0" />

                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>

                            <div>
                                <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">
                                    {{ $firma ? 'Imagen seleccionada para reemplazar firma' : 'Haz clic para subir una nueva firma' }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    Formatos permitidos: JPG, PNG, WEBP. Tamaño máximo: 2 MB.
                                </p>
                            </div>
                        </div>
                    </label>

                    @error('firma')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror

                    @if ($firma)
                        <div class="mt-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Vista previa nueva firma:</p>
                            <img src="{{ $firma->temporaryUrl() }}"
                                 alt="Nueva firma"
                                 class="h-16 object-contain rounded border border-amber-200 dark:border-amber-700 bg-white p-1">
                        </div>
                    @endif
                </div>

            </div>

            {{-- ── Barra de acciones ───────────────────────────────────── --}}
            <div class="flex items-center justify-between mt-8 pt-5 border-t border-gray-100 dark:border-gray-700/50">
                <a href="{{ route('instructor.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                          bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                          text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancelar
                </a>

                <button type="button"
                        wire:click="update"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2.5 px-7 py-2.5 rounded-lg text-sm font-bold
                               shadow-md shadow-amber-500/30 transition-all duration-150
                               bg-gradient-to-r from-amber-500 to-amber-600
                               hover:from-amber-600 hover:to-amber-700
                               active:scale-[0.98]
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none
                               text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800">
                    <svg wire:loading wire:target="update"
                         class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <svg wire:loading.remove wire:target="update"
                         class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    <span wire:loading.remove wire:target="update">Actualizar Instructor</span>
                    <span wire:loading wire:target="update">Guardando…</span>
                </button>
            </div>
        </div>
    </div>

</div>
