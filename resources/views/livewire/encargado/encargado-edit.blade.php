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
                    <h1 class="text-xl font-bold text-white leading-tight">Editar Encargado</h1>
                    <p class="text-indigo-100 text-sm mt-0.5">
                        {{ $encargado->feligres->persona->nombre_completo ?? '' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('encargado.show', $encargado) }}"
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

    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                ring-1 ring-black/5 dark:ring-white/5">

        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                         bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300
                         text-xs font-bold ring-2 ring-indigo-200 dark:ring-indigo-700/50">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </span>
            <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">Datos del Encargado</h2>
        </div>

        <div class="p-6 space-y-5">

            {{-- Persona (solo lectura) --}}
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-600">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Persona</p>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $encargado->feligres->persona->nombre_completo ?? '—' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    DNI: {{ $encargado->feligres->persona->dni ?? '—' }}
                    &nbsp;·&nbsp; Iglesia: {{ $encargado->feligres->iglesia->nombre ?? '—' }}
                </p>
            </div>

            {{-- Firma actual --}}
            @if ($encargado->path_firma_principal)
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Firma actual</p>
                    <img src="{{ asset('storage/' . $encargado->path_firma_principal) }}"
                         alt="Firma actual"
                         class="h-20 object-contain rounded-lg border border-indigo-200 dark:border-indigo-700 bg-white p-1.5 shadow-sm">
                </div>
            @endif

            {{-- Nueva firma --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                    {{ $encargado->path_firma_principal ? 'Reemplazar Firma' : 'Subir Firma Principal' }}
                </label>

                <label for="firma"
                       class="group relative flex flex-col items-center justify-center w-full
                              rounded-xl border-2 border-dashed cursor-pointer transition-all duration-200
                              {{ $firma
                                  ? 'border-green-400 bg-green-50 dark:bg-green-900/10'
                                  : 'border-gray-300 bg-gray-50 hover:border-indigo-400 hover:bg-indigo-50 dark:bg-gray-700/40 dark:border-gray-600 dark:hover:border-indigo-500' }}
                              min-h-[180px] p-6">

                    @if ($firma)
                        <div class="flex flex-col items-center gap-3 w-full">
                            <img src="{{ $firma->temporaryUrl() }}"
                                 alt="Vista previa firma"
                                 class="max-h-36 w-auto rounded-lg border border-green-200 object-contain shadow-sm" />
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-sm font-medium text-green-600">Imagen cargada correctamente</span>
                            </div>
                            <span class="text-xs text-gray-400 underline group-hover:text-indigo-500 transition-colors">
                                Haz clic para cambiar la imagen
                            </span>
                        </div>
                    @else
                        <div class="flex flex-col items-center gap-3 text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center
                                        group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/30 transition-colors">
                                <svg class="w-8 h-8 text-gray-400 group-hover:text-indigo-500 transition-colors"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M3 17v2a2 2 0 002 2h14a2 2 0 002-2v-2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-indigo-600 transition-colors">
                                    Haz clic para subir la firmas
                                </p>
                                <p class="text-xs text-gray-400 mt-1">o arrastra y suelta la imagen aquí</p>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap justify-center">
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">PNG</span>
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">JPG</span>
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">JPEG</span>
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">Máx. 2MB</span>
                            </div>
                        </div>
                    @endif

                    <input wire:model="firma" id="firma" type="file" accept="image/*"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                </label>

                {{-- Loading --}}
                <div wire:loading wire:target="firma" class="mt-2 flex items-center gap-2 text-sm text-indigo-600">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    Cargando imagen...
                </div>

                @error('firma')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Barra de acciones --}}
            <div class="flex items-center justify-between pt-5 border-t border-gray-100 dark:border-gray-700/50">
                <a href="{{ route('encargado.show', $encargado) }}"
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
                               shadow-md shadow-emerald-500/30 transition-all duration-150 active:scale-[0.98]
                               bg-gradient-to-r from-emerald-500 to-emerald-600
                               hover:from-emerald-600 hover:to-emerald-700
                               disabled:opacity-60 disabled:cursor-not-allowed
                               text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800">
                    <svg wire:loading wire:target="update" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <svg wire:loading.remove wire:target="update" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    <span wire:loading.remove wire:target="update">Actualizar Encargado</span>
                    <span wire:loading wire:target="update">Guardando…</span>
                </button>
            </div>
        </div>
    </div>

</div>
