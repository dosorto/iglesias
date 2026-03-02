<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Bautismos</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Listado de registros bautismales</p>
        </div>
        @can('bautismo.create')
        <a href="{{ route('bautismo.create') }}"
           class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium
                  transition-colors flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Bautismo
        </a>
        @endcan
    </div>

    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Tabla --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bautizado</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Iglesia</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($bautismos as $b)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $b->id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $b->bautizado?->persona?->nombre_completo ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                            {{ $b->iglesia?->nombre ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                            {{ $b->fecha_bautismo?->format('d/m/Y') ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                            No hay bautismos registrados aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($bautismos->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $bautismos->links() }}
            </div>
        @endif
    </div>

</div>
