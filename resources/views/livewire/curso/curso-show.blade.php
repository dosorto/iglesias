<div class="space-y-6">

    <div class="flex justify-between items-center">

        <h1 class="text-2xl font-bold">
            Detalle del Curso
        </h1>

        <a href="{{ route('curso.index') }}"
           class="px-4 py-2 bg-gray-200 rounded">
            Volver
        </a>

    </div>


    <div class="bg-white rounded-lg shadow border p-6 space-y-4">

        <div class="grid grid-cols-2 gap-4">

            <div>
                <label class="text-sm text-gray-500">Nombre</label>
                <p class="font-semibold">{{ $curso->nombre }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Estado</label>
                <p>{{ $curso->estado }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Fecha inicio</label>
                <p>{{ $curso->fecha_inicio }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Fecha fin</label>
                <p>{{ $curso->fecha_fin }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Iglesia</label>
                <p>{{ $curso->iglesia?->nombre }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Tipo Curso</label>
                <p>{{ $curso->tipoCurso?->nombre }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Instructor</label>
                <p>{{ $curso->instructor?->feligres?->persona?->nombre_completo }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Encargado</label>
                <p>{{ $curso->encargado?->feligres?->persona?->nombre_completo }}</p>
            </div>

        </div>

    </div>

</div>