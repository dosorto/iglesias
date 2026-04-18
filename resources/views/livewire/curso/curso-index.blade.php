<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $isInstructorView ? 'Mis Cursos' : 'Gestión de Cursos' }}
            </h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">
                {{ $isInstructorView ? 'Vista de instructor: solo cursos asignados a tu perfil.' : 'Administra los cursos disponibles en el sistema' }}
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            @can('curso.create')
                @if(! $isInstructorView)
                <a href="{{ route('curso.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nuevo Curso
                </a>
                @endif
            @endcan
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="content-container mx-auto w-full max-w-7xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                    <div class="flex items-center gap-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Lista de Cursos</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                            {{ $cursos->total() }} {{ $cursos->total() === 1 ? 'registro' : 'registros' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3 flex-1 max-w-lg w-full">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                placeholder="Buscar por nombre o tipo..."
                            >
                        </div>

                        <select
                            wire:model.live="perPage"
                            class="block px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white">
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
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Curso</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipo Curso</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Instructor</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</span>
                            </th>
                            <th class="px-6 py-3 text-left w-40">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($cursos as $curso)
                            <tr
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer group"
                                data-href="{{ route('curso.show', $curso->id) }}"
                                onclick="window.location=this.dataset.href"
                            >
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3 shadow-sm group-hover:scale-110 transition-transform">
                                            <span class="text-white text-xs font-bold">
                                                {{ strtoupper(substr($curso->nombre ?? 'C', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $curso->nombre }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                @if($curso->fecha_inicio)
                                                    {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}
                                                    @if($curso->fecha_fin) — {{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}@endif
                                                @else
                                                    ID: {{ $curso->id }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $curso->tipoCurso?->nombre_curso ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $curso->instructor?->feligres?->persona?->nombre_completo ?? 'Sin instructor asignado' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $badgeClass = match(strtolower($curso->estado ?? '')) {
                                            'activo'     => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                                            'finalizado' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                                            'cancelado'  => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
                                            default      => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                        {{ $curso->estado }}
                                    </span>
                                </td>

                                <td class="px-6 py-4" onclick="event.stopPropagation()">
                                    <div class="flex items-center space-x-2">
                                        @can('curso.view')
                                            <a href="{{ route('curso.show', $curso) }}"
                                               class="p-1 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 rounded transition-colors"
                                               title="Ver detalles">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                        @endcan

                                        @can('curso.edit')
                                            <a href="{{ route('curso.edit', $curso) }}"
                                               class="p-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors"
                                               title="Editar curso">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        @endcan

                                        @can('curso.delete')
                                            <button
                                                wire:click="confirmCursoDeletion({{ $curso->id }}, '{{ addslashes($curso->nombre ?? '') }}')"
                                                class="p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors"
                                                title="Eliminar curso">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                            @if($search) No se encontraron resultados
                                            @else No hay cursos registrados
                                            @endif
                                        </h3>
                                        <p class="text-gray-500 dark:text-gray-400 mb-4 max-w-sm">
                                            @if($search)
                                                No hay registros que coincidan con tu búsqueda "{{ $search }}".
                                            @else
                                                Comienza agregando los cursos disponibles en el sistema.
                                            @endif
                                        </p>
                                        @if(!$search && auth()->user()->can('curso.create'))
                                            <a href="{{ route('curso.create') }}"
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Crear primer curso
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                {{ $cursos->links() }}
            </div>
        </div>
    </div>

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
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">¿Eliminar Curso?</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Estás a punto de eliminar <span class="font-semibold text-gray-900 dark:text-white">{{ $cursoNameBeingDeleted }}</span>.
                            Esta acción es permanente y no se podrá recuperar.
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
        .anim-scale-in { animation: scale-in 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes scale-in {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</div>