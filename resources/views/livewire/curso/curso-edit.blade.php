<div class="space-y-6">

    <div class="flex justify-between">

        <h1 class="text-xl font-bold">
            Editar Curso
        </h1>

        <a href="{{ route('curso.index') }}"
           class="px-4 py-2 border rounded">
            Volver
        </a>

    </div>


    <div class="bg-white p-6 rounded-lg shadow border space-y-4">

        <div class="grid grid-cols-2 gap-4">

            <div>
                <label>Nombre</label>

                <input type="text"
                       wire:model="nombre"
                       class="w-full border rounded px-3 py-2">
            </div>


            <div>
                <label>Estado</label>

                <select wire:model="estado"
                        class="w-full border rounded px-3 py-2">

                    <option value="Activo">Activo</option>
                    <option value="Finalizado">Finalizado</option>
                    <option value="Cancelado">Cancelado</option>

                </select>
            </div>


            <div>
                <label>Fecha inicio</label>

                <input type="date"
                       wire:model="fecha_inicio"
                       class="w-full border rounded px-3 py-2">
            </div>


            <div>
                <label>Fecha fin</label>

                <input type="date"
                       wire:model="fecha_fin"
                       class="w-full border rounded px-3 py-2">
            </div>


            <div>
                <label>Iglesia</label>

                <select wire:model="iglesia_id"
                        class="w-full border rounded px-3 py-2">

                    @foreach($iglesias as $ig)

                        <option value="{{ $ig->id }}">
                            {{ $ig->nombre }}
                        </option>

                    @endforeach

                </select>
            </div>


            <div>
                <label>Tipo Curso</label>

                <select wire:model="tipo_curso_id"
                        class="w-full border rounded px-3 py-2">

                    @foreach($tipos as $t)

                        <option value="{{ $t->id }}">
                            {{ $t->nombre }}
                        </option>

                    @endforeach

                </select>
            </div>


            <div>
                <label>Instructor</label>

                <select wire:model="instructor_id"
                        class="w-full border rounded px-3 py-2">

                    @foreach($instructores as $i)

                        <option value="{{ $i->id }}">
                            {{ $i->feligres?->persona?->nombre_completo }}
                        </option>

                    @endforeach

                </select>

            </div>

        </div>


        <div class="flex justify-end">

            <button wire:click="update"
                    class="px-6 py-2 bg-blue-600 text-white rounded">

                Actualizar

            </button>

        </div>

    </div>

</div>