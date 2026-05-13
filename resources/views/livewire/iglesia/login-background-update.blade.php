<div class="space-y-4">

    @if ($guardado)
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 rounded-lg text-sm">
            Imagen de fondo actualizada correctamente.
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Vista previa actual --}}
        <div class="flex flex-col items-center gap-3">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Fondo actual</p>

            @if ($iglesia?->path_login_background)
                <div class="relative w-full">
                    <img src="{{ asset('storage/' . $iglesia->path_login_background) }}"
                         alt="Fondo del login"
                         class="w-full h-40 object-cover rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm">
                    <div class="absolute inset-0 bg-[#0F6E46]/60 rounded-lg flex items-center justify-center">
                        <span class="text-white text-xs font-medium">Vista previa con overlay verde</span>
                    </div>
                </div>
                <button type="button"
                        wire:click="eliminar"
                        wire:confirm="¿Eliminar la imagen de fondo?"
                        class="text-sm text-red-600 dark:text-red-400 hover:underline">
                    Eliminar imagen
                </button>
            @else
                <div class="w-full h-40 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 bg-[#0F6E46]/10 flex items-center justify-center">
                    <span class="text-xs text-gray-400">Sin imagen — se muestra fondo verde sólido</span>
                </div>
            @endif
        </div>

        {{-- Upload --}}
        <div class="flex flex-col gap-3">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                {{ $iglesia?->path_login_background ? 'Cambiar imagen' : 'Subir imagen' }}
            </p>

            @if ($imagen_nueva)
                <img src="{{ $imagen_nueva->temporaryUrl() }}"
                     alt="Vista previa"
                     class="w-full h-28 object-cover rounded-lg border border-blue-200 shadow-sm">
            @endif

            <label class="flex flex-col items-center justify-center w-full h-28 rounded-lg border-2 border-dashed cursor-pointer transition-colors
                          {{ $imagen_nueva ? 'border-blue-400 bg-blue-50 dark:bg-blue-900/10' : 'border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/40 hover:border-[#0F6E46] hover:bg-green-50' }}">
                <svg class="w-7 h-7 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-xs text-gray-500">PNG, JPG · Máx 4MB</span>
                <input wire:model="imagen_nueva" type="file" accept="image/*" class="hidden">
            </label>

            <div wire:loading wire:target="imagen_nueva" class="text-xs text-indigo-600 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
                Cargando…
            </div>

            @error('imagen_nueva')
                <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

            <button type="button"
                    wire:click="guardar"
                    wire:loading.attr="disabled"
                    @disabled(!$imagen_nueva)
                    class="w-full py-2 rounded-lg text-sm font-semibold text-white transition-colors
                           bg-[#0F6E46] hover:bg-[#0a5535] disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="guardar">Guardar imagen</span>
                <span wire:loading wire:target="guardar">Guardando…</span>
            </button>
        </div>
    </div>
</div>
