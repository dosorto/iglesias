    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Catálogo de Religiones</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1">
                    Administración de religiones registradas
                </p>
            </div>

            @can('religion.create')
                <a href="{{ route('religion.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center shadow-sm">
                    Nueva Religión
                </a>
            @endcan
        </div>

        {{-- Tabla --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Listado</h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $religion->total() }} registros
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Religión</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($religion as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $item->id }}
                                </td>

                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->religion }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">


                                        @can('religion.view')
                                            <a href="{{ route('religion.show', $item) }}"
                                            class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200"
                                            title="Ver detalles">
                                                👁️
                                            </a>
                                        @endcan

                                        @can('religion.edit')
                                            <a href="{{ route('religion.edit', $item) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                                ✏️
                                            </a>
                                        @endcan

                                        @can('religion.delete')
                                            <button
                                                wire:click="confirmReligionDeletion('{{ $item->id }}', '{{ $item->religion }}')"
                                                class="text-red-600 hover:text-red-800">
                                                🗑️
                                            </button>
                                        @endcan

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                    No hay religiones registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $religion->links() }}
            </div>
        </div>


        {{-- Modern Delete Modal --}}
            @if($showDeleteModal)
                <div class="fixed inset-0 bg-gray-900/50 dark:bg-black/70 backdrop-blur-sm flex items-center justify-center z-[100] p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-200 dark:border-gray-700 anim-scale-in">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 dark:bg-red-900/30 rounded-full text-red-600 dark:text-red-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            
                            <div class="text-center">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">¿Eliminar Iglesia?</h3>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Estás a punto de eliminar la iglesia <span class="font-semibold text-gray-900 dark:text-white">{{ $religionNameBeingDeleted }}</span>. 

                                    Esta acción es permanente y afectará a los registros vinculados.
                                </p>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-end space-x-3">
                            <button 
                                wire:click="$set('showDeleteModal', false)"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors"
                            >
                                Cancelar
                            </button>
                            
                            <button 
                                wire:click="delete"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-all duration-200 flex items-center shadow-lg active:scale-95"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Sí, eliminar
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <style>
                .anim-scale-in {
                    animation: scale-in 0.2s cubic-bezier(0.16, 1, 0.3, 1);
                }
                @keyframes scale-in {
                    from { opacity: 0; transform: scale(0.95); }
                    to { opacity: 1; transform: scale(1); }
                }
            </style>
        </div>

    </div>
        
