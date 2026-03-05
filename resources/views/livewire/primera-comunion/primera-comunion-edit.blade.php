<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Primera Comunión</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Modifica los datos del registro</p>
        </div>
        <a href="{{ route('primera-comunion.index') }}"
           class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg font-medium transition-colors flex items-center text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Cancelar
        </a>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-5">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Iglesia --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Iglesia <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="iglesia_id"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   dark:bg-gray-700 dark:text-white">
                        <option value="">— Selecciona —</option>
                        @foreach ($iglesias as $ig)
                            <option value="{{ $ig->id }}">{{ $ig->nombre }}</option>
                        @endforeach
                    </select>
                    @error('iglesia_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Fecha --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Fecha de Primera Comunión <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="fecha_primera_comunion" type="date"
                           class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-white">
                    @error('fecha_primera_comunion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Libro --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Libro de Comunión</label>
                    <input wire:model="libro_comunion" type="text"
                           class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-white"
                           placeholder="Ej: Libro III">
                    @error('libro_comunion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Folio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Folio</label>
                    <input wire:model="folio" type="text"
                           class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-white"
                           placeholder="Ej: 42">
                    @error('folio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Partida --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Partida N°</label>
                    <input wire:model="partida_numero" type="text"
                           class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-white"
                           placeholder="Ej: 15">
                    @error('partida_numero') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Observaciones --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observaciones</label>
                <textarea wire:model="observaciones" rows="3"
                          class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md
                                 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                 dark:bg-gray-700 dark:text-white"
                          placeholder="Observaciones adicionales..."></textarea>
                @error('observaciones') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar cambios
                </button>
            </div>

        </div>
    </form>

</div>