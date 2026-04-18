<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Configuración de Certificados</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Administra el logo y el formato de fondo de los certificados</p>
        </div>
        <a href="{{ route('settings.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600
                  text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver
        </a>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-600 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-600 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- ===== LOGO SECTION ===== --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Logo izquierdo de la Iglesia</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Este logo aparece en el lado izquierdo de la cabecera de todos los certificados.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Upload logo card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Subir nuevo logo</h2>

            <form wire:submit.prevent="subirLogo" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Imagen del logo (JPG / PNG, máx. 2 MB)
                    </label>

                    <label for="logo-input"
                           class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-xl cursor-pointer
                                  border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/40
                                  hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        @if ($logo_nuevo)
                            <img src="{{ $logo_nuevo->temporaryUrl() }}" alt="Vista previa"
                                 class="h-full w-full object-contain rounded-xl p-1">
                        @else
                            <div class="flex flex-col items-center gap-2 text-gray-400 dark:text-gray-500">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm">Haz clic para seleccionar una imagen</span>
                            </div>
                        @endif
                        <input id="logo-input" type="file" wire:model="logo_nuevo" accept="image/jpeg,image/png" class="hidden">
                    </label>

                    @error('logo_nuevo')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Guardar Logo
                </button>
            </form>
        </div>

        {{-- Current logo card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Logo actual</h2>

            @if ($iglesia?->logo_url)
                <div class="space-y-4">
                    <img src="{{ $iglesia->logo_url }}" alt="Logo actual"
                         class="w-full rounded-lg border border-gray-200 dark:border-gray-600 object-contain max-h-48 bg-gray-50 dark:bg-gray-700/40 p-2">

                    @if (! $confirmandoEliminarLogo)
                        <button wire:click="$set('confirmandoEliminarLogo', true)"
                                class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40
                                       text-red-700 dark:text-red-400 text-sm font-medium rounded-lg border border-red-200 dark:border-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar logo
                        </button>
                    @else
                        <div class="flex items-center gap-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg px-4 py-3">
                            <span class="text-sm text-red-700 dark:text-red-400 flex-1">¿Confirmar eliminación?</span>
                            <button wire:click="eliminarLogo"
                                    class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                Sí, eliminar
                            </button>
                            <button wire:click="$set('confirmandoEliminarLogo', false)"
                                    class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600
                                           text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
                                Cancelar
                            </button>
                        </div>
                    @endif
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-48 text-gray-400 dark:text-gray-500 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm">No hay logo configurado</p>
                    <p class="text-xs mt-1">Se usará el escudo por defecto</p>
                </div>
            @endif
        </div>

    </div>

    <div class="mt-2">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Logo derecho de la Iglesia</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Este logo aparece en el lado derecho de la cabecera. Si no se configura, se usará el logo izquierdo.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Subir logo derecho</h2>

            <form wire:submit.prevent="subirLogoDerecha" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Imagen del logo derecho (JPG / PNG, máx. 2 MB)
                    </label>

                    <label for="logo-derecha-input"
                           class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-xl cursor-pointer
                                  border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/40
                                  hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        @if ($logo_derecha_nuevo)
                            <img src="{{ $logo_derecha_nuevo->temporaryUrl() }}" alt="Vista previa"
                                 class="h-full w-full object-contain rounded-xl p-1">
                        @else
                            <div class="flex flex-col items-center gap-2 text-gray-400 dark:text-gray-500">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm">Haz clic para seleccionar una imagen</span>
                            </div>
                        @endif
                        <input id="logo-derecha-input" type="file" wire:model="logo_derecha_nuevo" accept="image/jpeg,image/png" class="hidden">
                    </label>

                    @error('logo_derecha_nuevo')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Guardar Logo Derecho
                </button>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Logo derecho actual</h2>

            @if ($iglesia?->logo_derecha_url)
                <div class="space-y-4">
                    <img src="{{ $iglesia->logo_derecha_url }}" alt="Logo derecho actual"
                         class="w-full rounded-lg border border-gray-200 dark:border-gray-600 object-contain max-h-48 bg-gray-50 dark:bg-gray-700/40 p-2">

                    @if (! $confirmandoEliminarLogoDerecha)
                        <button wire:click="$set('confirmandoEliminarLogoDerecha', true)"
                                class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40
                                       text-red-700 dark:text-red-400 text-sm font-medium rounded-lg border border-red-200 dark:border-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar logo derecho
                        </button>
                    @else
                        <div class="flex items-center gap-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg px-4 py-3">
                            <span class="text-sm text-red-700 dark:text-red-400 flex-1">¿Confirmar eliminación?</span>
                            <button wire:click="eliminarLogoDerecha"
                                    class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                Sí, eliminar
                            </button>
                            <button wire:click="$set('confirmandoEliminarLogoDerecha', false)"
                                    class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600
                                           text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
                                Cancelar
                            </button>
                        </div>
                    @endif
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-48 text-gray-400 dark:text-gray-500 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm">No hay logo derecho configurado</p>
                    <p class="text-xs mt-1">Se usará el logo izquierdo</p>
                </div>
            @endif
        </div>

    </div>

    {{-- ===== CERTIFICATE BACKGROUND SECTION ===== --}}
    <div class="mt-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Formatos por Documento</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Configura formato y orientación para cada sacramento y certificado.</p>
    </div>

    @foreach ($formatos as $formato)
        @php
            $tipo = $formato['tipo'];
            $orientacionesPlantilla = [
                'portrait' => 'Vertical',
                'landscape' => 'Horizontal',
            ];
        @endphp

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-1">{{ $formato['titulo'] }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $formato['descripcion'] }}</p>

            <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-5">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="radio" wire:model="orientaciones.{{ $tipo }}" value="portrait" class="text-teal-600 border-gray-300 focus:ring-teal-500">
                    Vertical
                </label>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="radio" wire:model="orientaciones.{{ $tipo }}" value="landscape" class="text-teal-600 border-gray-300 focus:ring-teal-500">
                    Horizontal
                </label>

                <button type="button" wire:click="guardarOrientacionTipo('{{ $tipo }}')"
                        class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    Guardar orientación
                </button>
            </div>

            @error('orientaciones.'.$tipo)
                <p class="mt-2 mb-3 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

            <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-5">
                <label for="paper-size-{{ $tipo }}" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Tamaño de hoja
                </label>

                <select id="paper-size-{{ $tipo }}"
                        wire:model="paperSizes.{{ $tipo }}"
                        class="w-full sm:w-64 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500 text-sm">
                    @foreach ($paperSizeOptions as $paperKey => $paperLabel)
                        <option value="{{ $paperKey }}">{{ $paperLabel }}</option>
                    @endforeach
                </select>

                <button type="button" wire:click="guardarTamanoTipo('{{ $tipo }}')"
                        class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    Guardar tamaño
                </button>
            </div>

            @error('paperSizes.'.$tipo)
                <p class="mt-2 mb-3 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                @foreach ($orientacionesPlantilla as $claveOrientacion => $etiquetaOrientacion)
                    @php
                        $archivoTemporal = data_get($formatos_nuevos, $tipo . '.' . $claveOrientacion);
                        $urlActual = $formato['url_' . $claveOrientacion];
                        $confirmando = (bool) data_get($confirmandoEliminar, $tipo . '.' . $claveOrientacion, false);
                    @endphp

                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Plantilla {{ strtolower($etiquetaOrientacion) }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Se usará cuando la orientación seleccionada sea {{ strtolower($etiquetaOrientacion) }}.</p>

                        <form wire:submit.prevent="subirFormato('{{ $tipo }}', '{{ $claveOrientacion }}')" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Imagen del formato (JPG / PNG, máx. 5 MB)
                                </label>

                                <label for="formato-input-{{ $tipo }}-{{ $claveOrientacion }}"
                                       class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-xl cursor-pointer
                                              border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/40
                                              hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    @if ($archivoTemporal)
                                        <img src="{{ $archivoTemporal->temporaryUrl() }}" alt="Vista previa"
                                             class="h-full w-full object-contain rounded-xl p-1">
                                    @else
                                        <div class="flex flex-col items-center gap-2 text-gray-400 dark:text-gray-500">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-sm">Haz clic para seleccionar una imagen</span>
                                        </div>
                                    @endif
                                    <input id="formato-input-{{ $tipo }}-{{ $claveOrientacion }}" type="file" wire:model="formatos_nuevos.{{ $tipo }}.{{ $claveOrientacion }}" accept="image/jpeg,image/png" class="hidden">
                                </label>

                                @error('formatos_nuevos.'.$tipo.'.'.$claveOrientacion)
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                    class="inline-flex items-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Guardar plantilla {{ strtolower($etiquetaOrientacion) }}
                            </button>
                        </form>

                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Plantilla actual</h4>

                            @if ($urlActual)
                                <div class="space-y-4">
                                    <img src="{{ $urlActual }}" alt="Plantilla {{ strtolower($etiquetaOrientacion) }} de {{ $formato['titulo'] }}"
                                         class="w-full rounded-lg border border-gray-200 dark:border-gray-600 object-contain max-h-72">

                                    @if (! $confirmando)
                                        <button wire:click="$set('confirmandoEliminar.{{ $tipo }}.{{ $claveOrientacion }}', true)"
                                                class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40
                                                       text-red-700 dark:text-red-400 text-sm font-medium rounded-lg border border-red-200 dark:border-red-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Eliminar plantilla {{ strtolower($etiquetaOrientacion) }}
                                        </button>
                                    @else
                                        <div class="flex items-center gap-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg px-4 py-3">
                                            <span class="text-sm text-red-700 dark:text-red-400 flex-1">¿Confirmar eliminación?</span>
                                            <button wire:click="eliminarFormato('{{ $tipo }}', '{{ $claveOrientacion }}')"
                                                    class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                Sí, eliminar
                                            </button>
                                            <button wire:click="$set('confirmandoEliminar.{{ $tipo }}.{{ $claveOrientacion }}', false)"
                                                    class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600
                                                           text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
                                                Cancelar
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center h-48 text-gray-400 dark:text-gray-500 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm">No hay plantilla {{ strtolower($etiquetaOrientacion) }} configurada</p>
                                    <p class="text-xs mt-1">Se usará la plantilla disponible o el diseño por defecto</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- Info note --}}
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg px-4 py-3 text-sm text-blue-700 dark:text-blue-300 space-y-1">
        <p><strong>Logos:</strong> Se recomienda una imagen cuadrada (mín. 200 × 200 px) en PNG con fondo transparente. El logo izquierdo y el derecho aparecerán en la cabecera de los certificados.</p>
        <p><strong>Formatos de fondo:</strong> Se recomienda usar imágenes de alta resolución en PNG o JPG, con la orientación correcta para cada documento.</p>
    </div>

</div>
