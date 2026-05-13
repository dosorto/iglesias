<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Documentos Generados</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Configura datos de certificado/constancia, payload, firma y logos opcionales.</p>
        </div>
        <a href="{{ route('settings.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
            Volver
        </a>
    </div>

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

    <form wire:submit.prevent="guardar" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-4">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">1. Datos de certificado o constancia</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de documento</label>
                    <select wire:model="tipo_documento" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                        <option value="certificado">Certificado</option>
                        <option value="constancia">Constancia</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre del documento</label>
                    <input type="text" wire:model="nombre_documento" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100" placeholder="Ej. Constancia de Bautismo">
                    @error('nombre_documento') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                    <textarea wire:model="descripcion_documento" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100" placeholder="Uso interno del documento"></textarea>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-4">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">4. Firma</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Título de firma</label>
                    <input type="text" wire:model="firma_titulo" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100" placeholder="Ej. Párroco">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre para firma</label>
                    <input type="text" wire:model="firma_nombre" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100" placeholder="Ej. Pbro. Juan Pérez">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Imagen de firma (opcional)</label>
                    <input type="file" wire:model="firma_nueva" accept="image/jpeg,image/png" class="w-full text-sm text-gray-600 dark:text-gray-300">
                    @error('firma_nueva') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                @if ($firma_path)
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                        <span class="text-sm text-gray-600 dark:text-gray-300">Firma cargada</span>
                        <button type="button" wire:click="eliminarFirma" class="text-xs text-red-600 dark:text-red-400 hover:underline">Eliminar</button>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-4">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">3. Payload</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ingresa el payload JSON que deseas guardar con este documento generado.</p>
            <textarea wire:model="payload_json" rows="12" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 font-mono text-sm"></textarea>
            @error('payload_json') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-4">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">5. Logos (si aplica)</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo izquierdo</label>
                    <input type="file" wire:model="logo_izquierdo_nuevo" accept="image/jpeg,image/png" class="w-full text-sm text-gray-600 dark:text-gray-300">
                    @error('logo_izquierdo_nuevo') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    @if ($logo_izquierdo_path)
                        <div class="mt-2 flex items-center justify-between bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-600 rounded-lg p-2">
                            <span class="text-xs text-gray-600 dark:text-gray-300">Logo izquierdo cargado</span>
                            <button type="button" wire:click="eliminarLogoIzquierdo" class="text-xs text-red-600 dark:text-red-400 hover:underline">Eliminar</button>
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo derecho</label>
                    <input type="file" wire:model="logo_derecho_nuevo" accept="image/jpeg,image/png" class="w-full text-sm text-gray-600 dark:text-gray-300">
                    @error('logo_derecho_nuevo') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    @if ($logo_derecho_path)
                        <div class="mt-2 flex items-center justify-between bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-600 rounded-lg p-2">
                            <span class="text-xs text-gray-600 dark:text-gray-300">Logo derecho cargado</span>
                            <button type="button" wire:click="eliminarLogoDerecho" class="text-xs text-red-600 dark:text-red-400 hover:underline">Eliminar</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg px-4 py-3 text-sm text-blue-700 dark:text-blue-300">
            <p><strong>2. Guardado en Archivo Json:</strong> esta configuración se guarda automáticamente en <span class="font-mono">{{ $this->rutaJson }}</span>.</p>
        </div>

        <div>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Guardar configuración
            </button>
        </div>
    </form>
</div>
