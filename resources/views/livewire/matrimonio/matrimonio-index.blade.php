<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Matrimonios</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Administra los registros matrimoniales de la parroquia</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('matrimonio.create')
                <a href="{{ route('matrimonio.create') }}"
                   class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nuevo Matrimonio
                </a>
            @endcan
        </div>
    </div>

    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Table Container --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

        {{-- Table Header --}}
        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                <div class="flex items-center gap-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Lista de Matrimonios</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 dark:bg-rose-900 text-rose-800 dark:text-rose-200">
                        {{ $matrimonios->total() }} {{ $matrimonios->total() === 1 ? 'registro' : 'registros' }}
                    </span>
                </div>
                <div class="flex items-center gap-3 flex-1 max-w-lg w-full">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text"
                               class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md
                                      focus:ring-2 focus:ring-rose-500 focus:border-transparent
                                      dark:bg-gray-800 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                               placeholder="Buscar por esposo/esposa, DNI o parroquia…">
                    </div>
                    <select wire:model.live="perPage"
                            class="block px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md
                                   focus:ring-2 focus:ring-rose-500 focus:border-transparent
                                   dark:bg-gray-800 dark:text-white">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left"><span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Esposo</span></th>
                        <th class="px-6 py-3 text-left"><span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Esposa</span></th>
                        <th class="px-6 py-3 text-left"><span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Parroquia</span></th>
                        <th class="px-6 py-3 text-left"><span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</span></th>
                        <th class="px-6 py-3 text-left"><span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Encargado</span></th>
                        <th class="px-6 py-3 text-left"><span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Libro / Partida</span></th>
                        <th class="px-6 py-3 text-left w-32"><span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($matrimonios as $m)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">

                            {{-- Esposo --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3 shadow-sm group-hover:scale-110 transition-transform">
                                        <span class="text-white text-xs font-bold">
                                            {{ strtoupper(substr($m->esposo?->persona?->primer_nombre ?? '?', 0, 1) . substr($m->esposo?->persona?->primer_apellido ?? '', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $m->esposo?->persona?->nombre_completo ?? '—' }}
                                    </div>
                                </div>
                            </td>

                            {{-- Esposa --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-pink-500 rounded-full flex items-center justify-center mr-3 shadow-sm group-hover:scale-110 transition-transform">
                                        <span class="text-white text-xs font-bold">
                                            {{ strtoupper(substr($m->esposa?->persona?->primer_nombre ?? '?', 0, 1) . substr($m->esposa?->persona?->primer_apellido ?? '', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $m->esposa?->persona?->nombre_completo ?? '—' }}
                                    </div>
                                </div>
                            </td>

                            {{-- Parroquia --}}
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $m->iglesia?->nombre ?? '—' }}</span>
                            </td>

                            {{-- Fecha --}}
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $m->fecha_matrimonio?->format('d/m/Y') ?? '—' }}
                                </span>
                            </td>

                            {{-- Encargado --}}
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $m->encargado?->feligres?->persona?->nombre_completo ?? '—' }}
                                </span>
                            </td>

                            {{-- Libro / Partida --}}
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-500 dark:text-gray-400 space-y-0.5">
                                    @if ($m->libro_matrimonio)
                                        <div>Libro: <span class="font-mono font-semibold text-gray-700 dark:text-gray-200">{{ $m->libro_matrimonio }}</span></div>
                                    @endif
                                    @if ($m->partida_numero)
                                        <div>Partida: <span class="font-mono font-semibold text-gray-700 dark:text-gray-200">{{ $m->partida_numero }}</span></div>
                                    @endif
                                    @if (!$m->libro_matrimonio && !$m->partida_numero) —@endif
                                </div>
                            </td>

                            {{-- Acciones --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @can('matrimonio.view')
                                        <a href="{{ route('matrimonio.show', $m) }}"
                                           class="p-1.5 rounded-md text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                                           title="Ver detalle">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('matrimonio.edit')
                                        <a href="{{ route('matrimonio.edit', $m) }}"
                                           class="p-1.5 rounded-md text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors"
                                           title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('matrimonio.delete')
                                        <button wire:click="confirmMatrimonioDeletion({{ $m->id }}, '{{ addslashes($m->esposo?->persona?->nombre_completo . ' & ' . $m->esposa?->persona?->nombre_completo) }}')"
                                                class="p-1.5 rounded-md text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                                title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">No se encontraron registros de matrimonio.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($matrimonios->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                {{ $matrimonios->links() }}
            </div>
        @endif
    </div>

    {{-- Delete Modal --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 max-w-md w-full mx-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Confirmar eliminación</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    ¿Estás seguro de que deseas eliminar el registro de matrimonio de
                    <strong class="text-gray-900 dark:text-white">{{ $matrimonioNameBeingDeleted }}</strong>?
                    Esta acción no se puede deshacer.
                </p>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm font-medium">
                        Cancelar
                    </button>
                    <button wire:click="delete"
                            class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition-colors text-sm font-medium">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
