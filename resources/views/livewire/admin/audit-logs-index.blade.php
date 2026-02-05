<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Auditoría del Sistema</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Historial detallado de todas las operaciones realizadas en la plataforma</p>
        </div>
        <div class="flex gap-2">
            @can('audit.export')
                <button wire:click="export" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-xs uppercase tracking-tighter transition-all shadow-lg active:scale-95 border border-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Exportar a Excel
                </button>
            @endcan
        </div>
    </div>

    {{-- Unified Table & Filters - Centered and Reduced Width --}}
    <div class="mx-auto w-full max-w-6xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            {{-- Toolbar / Filters --}}
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                    <div class="flex items-center gap-4 w-full lg:max-w-2xl">
                        <div class="relative flex-grow">
                            <input
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                class="block w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                placeholder="Buscar por usuario, ID o descripción..."
                            >
                        </div>

                        <select
                            wire:model.live="event"
                            class="block w-40 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white mt-2 lg:mt-0"
                        >
                            <option value="">Todos los eventos</option>
                            <option value="created">Creación</option>
                            <option value="updated">Actualización</option>
                            <option value="deleted">Eliminación</option>
                            <option value="restored">Restauración</option>
                        </select>

                        <input 
                            wire:model.live="date" 
                            type="date" 
                            class="block w-40 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white mt-2 lg:mt-0"
                        >

                        <select wire:model.live="perPage" class="block w-24 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white mt-2 lg:mt-0">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-900 px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-800">
                        {{ $logs->total() }} registros encontrados
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider text-xs">Fecha y Hora</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider text-xs">Usuario</th>
                            <th class="px-6 py-3 text-center font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider text-xs">Evento</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider text-xs">Módulo</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider text-xs w-1/3">Cambios Realizados</th>
                            <th class="px-6 py-3 text-right font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider text-xs w-32">Conexión</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $log->created_at->format('d/m/Y H:i:s') }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500 capitalize">{{ $log->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $log->user_name ?? 'Sistema' }}</span>
                                        <span class="text-[10px] text-gray-500 dark:text-gray-400 font-mono">ID: {{ $log->user_id ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $badgeStyle = [
                                            'created' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 border-green-200 dark:border-green-800',
                                            'updated' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 border-blue-200 dark:border-blue-800',
                                            'deleted' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 border-red-200 dark:border-red-800',
                                            'restored' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 border-purple-200 dark:border-purple-800',
                                        ][$log->event] ?? 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-400 border-gray-200 dark:border-gray-700';
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $badgeStyle }}">
                                        {{ strtoupper($log->event) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ class_basename($log->auditable_type) }}</span>
                                        <span class="text-xs text-blue-600 dark:text-blue-400 font-bold">#{{ $log->auditable_id }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    @if($log->event === 'updated' && $log->new_values)
                                        <div class="space-y-1.5 overflow-hidden">
                                            @foreach($log->new_values as $key => $value)
                                                @php
                                                    $oldV = is_array($log->old_values[$key] ?? '') ? json_encode($log->old_values[$key]) : ($log->old_values[$key] ?? 'N/A');
                                                    $newV = is_array($value) ? json_encode($value) : $value;
                                                @endphp
                                                <div class="text-[11px] leading-tight truncate" title="{{ str_replace('_', ' ', $key) }}: {{ $oldV }} -> {{ $newV }}">
                                                    <span class="font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tighter">{{ str_replace('_', ' ', $key) }}:</span>
                                                    <span class="text-red-600 dark:text-red-400 line-through decoration-1">{{ $oldV }}</span>
                                                    <span class="text-emerald-700 dark:text-emerald-400 font-bold underline decoration-emerald-500/30">{{ $newV }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($log->event === 'created')
                                        <span class="text-xs italic text-gray-500 dark:text-gray-400">Creación inicial de registro</span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-600">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="text-xs font-mono text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-900 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-800">{{ $log->ip_address }}</span>
                                        <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate max-w-[100px] mt-1 cursor-help" title="{{ $log->user_agent }}">{{ $log->user_agent }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-200 dark:text-gray-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-lg font-medium text-gray-400 dark:text-gray-500">No se encontraron resultados</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if($logs->hasPages())
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Mostrando {{ $logs->firstItem() }} a {{ $logs->lastItem() }} de {{ $logs->total() }} resultados
                        </div>
                        <div class="flex space-x-1">
                            {{-- Previous Page --}}
                            @if ($logs->onFirstPage())
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-200 dark:bg-gray-800 rounded-lg cursor-not-allowed border border-gray-200 dark:border-gray-700">
                                    Anterior
                                </span>
                            @else
                                <button
                                    wire:click="previousPage"
                                    class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Anterior
                                </button>
                            @endif

                            {{-- Page Numbers (Shortened map) --}}
                            @foreach ($logs->getUrlRange(max(1, $logs->currentPage() - 2), min($logs->lastPage(), $logs->currentPage() + 2)) as $page => $url)
                                @if ($page == $logs->currentPage())
                                    <span class="px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-lg">
                                        {{ $page }}
                                    </span>
                                @else
                                    <button
                                        wire:click="gotoPage({{ $page }})"
                                        class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach

                            {{-- Next Page --}}
                            @if ($logs->hasMorePages())
                                <button
                                    wire:click="nextPage"
                                    class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Siguiente
                                </button>
                            @else
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-200 dark:bg-gray-800 rounded-lg cursor-not-allowed border border-gray-200 dark:border-gray-700">
                                    Siguiente
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
