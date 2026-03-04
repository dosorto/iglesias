<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold">Gestión de Cursos</h1>
            <p class="text-gray-600 text-sm">Administrar cursos registrados</p>
        </div>

        <a href="{{ route('curso.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Nuevo Curso
        </a>

    </div>


    {{-- BUSCADOR --}}
    <div class="bg-white shadow rounded-lg p-4 flex gap-3">

        <input type="text"
               wire:model.live.debounce.300ms="search"
               placeholder="Buscar curso..."
               class="flex-1 border rounded-lg px-3 py-2">

        <select wire:model.live="perPage"
                class="border rounded-lg px-3 py-2">

            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>

        </select>

    </div>


    {{-- TABLA --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-4 py-3 text-left text-xs uppercase">Curso</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">Iglesia</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">Tipo Curso</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">Instructor</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">Estado</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">Acciones</th>

                </tr>

            </thead>


            <tbody class="divide-y">

                @forelse($cursos as $curso)

                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3">
                            <strong>{{ $curso->nombre }}</strong>
                        </td>


                        <td class="px-4 py-3">
                            {{ $curso->iglesia?->nombre }}
                        </td>


                        <td class="px-4 py-3">
                            {{ $curso->tipoCurso?->nombre_curso }}
                        </td>


                        <td class="px-4 py-3">
                            {{ $curso->instructor?->feligres?->persona?->nombre_completo }}
                        </td>


                        <td class="px-4 py-3">

                            <span class="px-2 py-1 text-xs rounded bg-gray-200">
                                {{ $curso->estado }}
                            </span>

                        </td>


                        <td class="px-4 py-3 flex gap-2">

                            <a href="{{ route('curso.show',$curso) }}"
                               class="text-gray-600 hover:text-black">
                                Ver
                            </a>

                            <a href="{{ route('curso.edit',$curso) }}"
                               class="text-blue-600">
                                Editar
                            </a>

                            <button
                                wire:click="confirmCursoDeletion({{ $curso->id }},'{{ $curso->nombre }}')"
                                class="text-red-600">
                                Eliminar
                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center py-8 text-gray-500">
                            No hay cursos registrados
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINACION --}}
    <div>
        {{ $cursos->links() }}
    </div>


    {{-- MODAL ELIMINAR --}}
    @if($showDeleteModal)

        <div class="fixed inset-0 bg-black/50 flex items-center justify-center">

            <div class="bg-white p-6 rounded-lg w-96">

                <h2 class="text-lg font-bold mb-3">
                    Eliminar Curso
                </h2>

                <p class="text-sm text-gray-600">
                    ¿Eliminar el curso <strong>{{ $cursoNameBeingDeleted }}</strong>?
                </p>

                <div class="flex justify-end gap-3 mt-5">

                    <button
                        wire:click="$set('showDeleteModal',false)"
                        class="px-4 py-2 border rounded">
                        Cancelar
                    </button>

                    <button
                        wire:click="delete"
                        class="px-4 py-2 bg-red-600 text-white rounded">
                        Eliminar
                    </button>

                </div>

            </div>

        </div>

    @endif

</div>