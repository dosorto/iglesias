<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Inscripciones de Curso
            </h1>

            <p class="text-gray-600 dark:text-gray-300 mt-1">
                Administración de inscripciones a cursos
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('inscripcion-curso.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center shadow-sm">
                Nueva Inscripción
            </a>

            <a href="{{ route('inscripcion-curso.certificados-aprobados.pdf') }}"
               target="_blank"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium flex items-center shadow-sm">
                Certificados aprobados (PDF)
            </a>

            <button
                type="button"
                wire:click="aprobarTodos"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium flex items-center shadow-sm">
                Aprobar todos
            </button>
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

    @if (session()->has('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- BUSCADOR --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">

        <div class="flex items-center gap-4">

            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por persona, curso o instructor..."
                class="w-full border-gray-300 dark:border-gray-600 rounded-lg
                       bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white"
            >

            <select
                wire:model.live="perPage"
                class="border-gray-300 dark:border-gray-600 rounded-lg
                       bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white"
            >
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>

        </div>

    </div>

    {{-- TABLA --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">

        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

            {{-- HEADER TABLA --}}
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Persona
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Curso
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Instructor
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Fecha inscripción
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Aprobado
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Certificado
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Fecha certificado
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        Acciones
                    </th>

                </tr>
            </thead>

            {{-- BODY TABLA --}}
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                @forelse ($inscripciones as $inscripcion)

                    <tr>

                        {{-- PERSONA --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $inscripcion->feligres->persona->nombre_completo ?? 'N/A' }}
                        </td>

                        {{-- CURSO --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $inscripcion->curso->nombre ?? 'N/A' }}
                        </td>

                        {{-- INSTRUCTOR --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $inscripcion->curso?->instructor?->feligres?->persona?->nombre_completo ?? '—' }}
                        </td>

                        {{-- FECHA INSCRIPCION --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $inscripcion->fecha_inscripcion }}
                        </td>

                        {{-- APROBADO CLICKEABLE --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button
                                type="button"
                                wire:click="toggleAprobado({{ $inscripcion->id }})"
                                class="px-2.5 py-1 text-xs font-semibold rounded transition-colors
                                       {{ $inscripcion->aprobado
                                            ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                            : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                {{ $inscripcion->aprobado ? 'Sí' : 'No' }}
                            </button>
                        </td>

                        {{-- CERTIFICADO --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if ($inscripcion->certificado_emitido)
                                <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded">
                                    Emitido
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded">
                                    No
                                </span>
                            @endif
                        </td>

                        {{-- FECHA CERTIFICADO --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $inscripcion->fecha_certificado ?? '—' }}
                        </td>

                        {{-- ACCIONES --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('matriculado-curso.show', $inscripcion->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                                    Ver
                                </a>

                                @if ($inscripcion->aprobado)
                                    <a href="{{ route('inscripcion-curso.certificado.pdf', $inscripcion->id) }}"
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition-colors">
                                        Certificado
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded bg-gray-100 text-gray-500">
                                        Certificado
                                    </span>
                                @endif
                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="8"
                            class="px-6 py-6 text-center text-gray-500"
                        >
                            No hay inscripciones registradas
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- PAGINACION --}}
    <div>
        {{ $inscripciones->links() }}
    </div>

</div>