<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Documentos Generados</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Listado histórico de documentos emitidos para reimpresión exacta.</p>
        </div>
        <a href="{{ route('settings.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
            Volver
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="md:col-span-2">
                <input type="text" wire:model.live.debounce.300ms="buscar"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                       placeholder="Buscar por nombre de archivo, tipo o ID de registro">
            </div>
            <div>
                <select wire:model.live="filtro_tipo"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                    <option value="">Todos los tipos</option>
                    @foreach ($this->tiposDisponibles as $tipo)
                        <option value="{{ $tipo }}">{{ $tipo }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        @if (session('success'))
            <div class="mx-4 mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-800 dark:border-emerald-800/40 dark:bg-emerald-900/20 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mx-4 mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-800 dark:border-red-800/40 dark:bg-red-900/20 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/40">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Emitido</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Tipo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Fuente</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Persona relacionada</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($documentos as $doc)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                {{ $doc->fecha_emision?->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                {{ $doc->tipo_documento }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                {{ $this->nombreFuente($doc->fuente_tipo) }} #{{ $doc->fuente_id }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200 max-w-[320px]">
                                {{ $this->personaRelacionada($doc) }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('configuracion.documentos-generados.pdf', $doc->id) }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md bg-cyan-600 hover:bg-cyan-700 text-white">
                                        Reimprimir copia
                                    </a>
                                    @php($rutaRegistro = $this->rutaRegistro($doc->fuente_tipo, (int) $doc->fuente_id))
                                    @if ($rutaRegistro)
                                        <a href="{{ $rutaRegistro }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200">
                                            Ver registro
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200">
                                            Registro eliminado
                                        </span>
                                    @endif

                                    @if (auth()->user()?->hasRole('root'))
                                        <button
                                            type="button"
                                            x-data="{}"
                                            @click="
                                                const valor = prompt('Esta acción eliminará permanentemente este documento. Escribe ELIMINAR para confirmar.');
                                                if (valor === null) return;
                                                $wire.eliminarPermanentemente({{ $doc->id }}, valor);
                                            "
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md bg-red-600 hover:bg-red-700 text-white"
                                        >
                                            Eliminar permanente
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                No hay documentos generados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $documentos->links() }}
        </div>
    </div>
</div>
