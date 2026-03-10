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

        <a href="{{ route('inscripcion-curso.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center shadow-sm">

            Nueva Inscripción

        </a>

    </div>



    {{-- BUSCADOR --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">

        <div class="flex items-center gap-4">

            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar..."
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

                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
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


                        {{-- FECHA INSCRIPCION --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">

                            {{ $inscripcion->fecha_inscripcion }}

                        </td>


                        {{-- APROBADO --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm">

                            @if ($inscripcion->aprobado)

                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                                    Sí
                                </span>

                            @else

                                <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">
                                    No
                                </span>

                            @endif

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
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">

                            <div class="flex items-center justify-end gap-4">

                                {{-- VER --}}
                                <a href="{{ route('inscripcion-curso.show',$inscripcion) }}"
                                class="text-gray-500 hover:text-gray-700">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0
                                                3 3 0 016 0z"/>

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5
                                                12 5c4.477 0 8.268 2.943
                                                9.542 7-1.274 4.057-5.065
                                                7-9.542 7-4.477 0-8.268
                                                -2.943-9.542-7z"/>

                                    </svg>

                                </a>



                                {{-- EDITAR --}}
                                <a href="{{ route('inscripcion-curso.edit',$inscripcion) }}"
                                class="text-blue-500 hover:text-blue-700">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11
                                                a2 2 0 002 2h11a2 2 0 002-2v-5
                                                m-1.414-9.414a2 2 0 112.828
                                                2.828L11.828 15H9v-2.828
                                                l8.586-8.586z"/>

                                    </svg>

                                </a>



                                {{-- ELIMINAR --}}
                                <button wire:click="confirmDeletion({{ $inscripcion->id }})"
                                        class="text-red-500 hover:text-red-700">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0
                                                0116.138 21H7.862a2 2 0
                                                01-1.995-1.858L5 7m5
                                                4v6m4-6v6M1 7h22
                                                M8 7V4a1 1 0 011-1h6a1
                                                1 0 011 1v3"/>

                                    </svg>

                                </button>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
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



    {{-- MODAL ELIMINAR --}}
    @if ($showDeleteModal)

        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96">

                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                    Confirmar eliminación
                </h2>

                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    ¿Seguro que deseas eliminar esta inscripción?
                </p>

                <div class="flex justify-end gap-3">

                    <button
                        wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2 bg-gray-200 rounded-lg"
                    >
                        Cancelar
                    </button>

                    <button
                        wire:click="delete"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg"
                    >
                        Eliminar
                    </button>

                </div>

            </div>

        </div>

    @endif

</div>