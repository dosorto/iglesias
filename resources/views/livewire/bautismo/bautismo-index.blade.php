<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Bautismos</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Administra los registros bautismales de la parroquia</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('bautismo.create')
                <a href="{{ route('bautismo.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nuevo Bautismo
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
    <div class="content-container mx-auto w-full max-w-7xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            {{-- Table Header --}}
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                    <div class="flex items-center gap-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Lista de Bautismos</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                            {{ $bautismos->total() }} {{ $bautismos->total() === 1 ? 'registro' : 'registros' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 flex-1 max-w-lg w-full">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text"
                                   class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md
                                          focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                          dark:bg-gray-800 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                   placeholder="Buscar por bautizado, DNI o iglesia…">
                        </div>
                        <select wire:model.live="perPage"
                                class="block px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md
                                       focus:ring-2 focus:ring-blue-500 focus:border-transparent
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
                            <th class="px-6 py-3 text-left"><span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bautizado</span></th>
                            <th class="px-6 py-3 text-left"><span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Iglesia</span></th>
                            <th class="px-6 py-3 text-left"><span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</span></th>
                            <th class="px-6 py-3 text-left"><span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Encargado</span></th>
                            <th class="px-6 py-3 text-left"><span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Libro / Partida</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($bautismos as $b)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">

                                {{-- Bautizado --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-sky-600 rounded-full flex items-center justify-center mr-3 shadow-sm group-hover:scale-110 transition-transform">
                                            <span class="text-white text-xs font-bold">
                                                {{ strtoupper(substr($b->bautizado?->persona?->primer_nombre ?? '?', 0, 1) . substr($b->bautizado?->persona?->primer_apellido ?? '', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $b->bautizado?->persona?->nombre_completo ?? '—' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                                                {{ $b->bautizado?->persona?->dni ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Iglesia --}}
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $b->iglesia?->nombre ?? '—' }}</span>
                                </td>

                                {{-- Fecha --}}
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $b->fecha_bautismo?->format('d/m/Y') ?? '—' }}
                                    </span>
                                </td>

                                {{-- Encargado --}}
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $b->encargado?->feligres?->persona?->nombre_completo ?? '—' }}
                                    </span>
                                </td>

                                {{-- Libro / Partida --}}
                                <td class="px-6 py-4">
                                    @if ($b->libro_bautismo || $b->partida_numero)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            @if ($b->libro_bautismo) <span>{{ $b->libro_bautismo }}</span> @endif
                                            @if ($b->folio) <span class="ml-1">Fol. {{ $b->folio }}</span> @endif
                                            @if ($b->partida_numero) <span class="ml-1">Ptda. {{ $b->partida_numero }}</span> @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                            @if($search) No se encontraron resultados @else No hay bautismos registrados @endif
                                        </h3>
                                        @if(!$search)
                                            @can('bautismo.create')
                                                <a href="{{ route('bautismo.create') }}"
                                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors mt-2">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                    </svg>
                                                    Registrar primer bautismo
                                                </a>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                {{ $bautismos->links() }}
            </div>

        </div>
    </div>


</div>
