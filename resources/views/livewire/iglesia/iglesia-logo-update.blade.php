<div class="content-container space-y-6">

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
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white leading-tight">Logo de la Parroquia</h1>
                    <p class="text-indigo-100 text-sm mt-0.5">
                        {{ $iglesia->nombre ?? 'Mi Parroquia' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('encargado.index') }}"
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

    {{-- Flash guardado --}}
    @if ($guardado)
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3500)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="flex items-center gap-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20
                    border border-emerald-200 dark:border-emerald-700 px-4 py-3">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">
                Logo actualizado correctamente. Ya aparecerá en los certificados PDF.
            </p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

        {{-- ── COLUMNA IZQUIERDA: Logo actual ── --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-5">
                Logo Actual
            </p>

            <div class="flex flex-col items-center gap-5">

                @if ($iglesia->path_logo)
                    {{-- Logo existente --}}
                    <div class="relative">
                        <img src="{{ asset('storage/' . $iglesia->path_logo) }}"
                             alt="Logo de la parroquia"
                             class="w-40 h-40 object-contain rounded-2xl border border-gray-200 dark:border-gray-600
                                    shadow-sm bg-gray-50 dark:bg-gray-700/40" />
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center shadow">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                        Logo cargado · aparece en los certificados PDF
                    </p>
                    <button type="button"
                            wire:click="eliminarLogo"
                            wire:confirm="¿Seguro que deseas eliminar el logo? Esta acción no se puede deshacer."
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium
                                   text-red-600 bg-red-50 dark:bg-red-900/20
                                   border border-red-200 dark:border-red-800
                                   hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar logo
                    </button>
                @else
                    {{-- Sin logo --}}
                    <div class="w-40 h-40 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600
                                bg-gray-50 dark:bg-gray-700/40 flex flex-col items-center justify-center gap-3">
                        <svg class="w-14 h-14 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs text-gray-400 dark:text-gray-500">Sin logo</span>
                    </div>
                    <p class="text-sm text-amber-600 dark:text-amber-400 text-center font-medium">
                        No tienes logo configurado. Sube uno para que aparezca en los certificados PDF.
                    </p>
                @endif

            </div>
        </div>

        {{-- ── COLUMNA DERECHA: Subir/Cambiar logo ── --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-5">
                {{ $iglesia->path_logo ? 'Cambiar Logo' : 'Subir Logo' }}
            </p>

            {{-- Preview del nuevo logo antes de guardar --}}
            @if ($logo_nuevo)
                <div class="flex flex-col items-center gap-3 mb-5 p-4 rounded-xl
                            bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800">
                    <img src="{{ $logo_nuevo->temporaryUrl() }}"
                         alt="Vista previa"
                         class="w-32 h-32 object-contain rounded-xl border border-blue-200 shadow-sm bg-white" />
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                            Vista previa — aún no guardado
                        </span>
                    </div>
                </div>
            @endif

            {{-- Drop-zone --}}
            <label for="logo_nuevo"
                   class="group relative flex flex-col items-center justify-center w-full
                          rounded-xl border-2 border-dashed cursor-pointer transition-all duration-200
                          {{ $logo_nuevo
                              ? 'border-blue-400 bg-blue-50 dark:bg-blue-900/10'
                              : 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/40
                                 hover:border-indigo-400 hover:bg-indigo-50 dark:hover:border-indigo-500 dark:hover:bg-indigo-900/10' }}
                          min-h-[160px] p-6">

                <div class="flex flex-col items-center gap-3 text-center pointer-events-none">
                    <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center
                                group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/30 transition-colors">
                        <svg class="w-7 h-7 text-gray-400 group-hover:text-indigo-500 transition-colors"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-indigo-600 transition-colors">
                            {{ $logo_nuevo ? 'Haz clic para cambiar la imagen' : 'Haz clic para subir el logo' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">o arrastra y suelta aquí</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap justify-center">
                        <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">PNG</span>
                        <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">JPG</span>
                        <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">JPEG</span>
                        <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">Máx. 2MB</span>
                    </div>
                </div>

                <input wire:model="logo_nuevo" id="logo_nuevo" type="file" accept="image/*"
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
            </label>

            {{-- Loading --}}
            <div wire:loading wire:target="logo_nuevo" class="mt-3 flex items-center gap-2 text-sm text-indigo-600">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
                Cargando imagen...
            </div>

            @error('logo_nuevo')
                <p class="mt-2 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror

            {{-- Botón guardar --}}
            <div class="flex justify-end mt-5 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                <button type="button"
                        wire:click="guardarLogo"
                        wire:loading.attr="disabled"
                        @disabled(!$logo_nuevo)
                        class="inline-flex items-center gap-2.5 px-6 py-2.5 rounded-lg text-sm font-bold
                               bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700
                               text-white shadow-md shadow-indigo-500/30 transition-all duration-150 active:scale-[0.98]
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                    <svg wire:loading wire:target="guardarLogo" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <svg wire:loading.remove wire:target="guardarLogo" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    <span wire:loading.remove wire:target="guardarLogo">Guardar Logo</span>
                    <span wire:loading wire:target="guardarLogo">Guardando…</span>
                </button>
            </div>

        </div>
    </div>
</div>