<div class="space-y-6">

    {{-- HEADER --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600
                dark:from-indigo-700 dark:to-violet-700 shadow-md px-6 py-5">

        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5"></div>

        <div class="relative flex justify-between items-center">

            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-xl font-bold text-white">
                        Editar Inscripción de Curso
                    </h1>

                    <p class="text-indigo-100 text-sm">
                        {{ $inscripcion->feligres->persona->nombre_completo ?? '—' }}
                    </p>
                </div>

            </div>

            <a href="{{ route('inscripcion-curso.index',$inscripcion) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                      bg-white/15 hover:bg-white/25 border border-white/20
                      text-white text-sm font-medium">
                Volver
            </a>

        </div>

    </div>


    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <p class="text-green-700 font-medium">
                {{ session('success') }}
            </p>
        </div>
    @endif


    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow border p-6 space-y-5">


        {{-- FELIGRES --}}
        <div class="p-4 rounded-lg bg-gray-50 border">

            <p class="text-xs text-gray-500 uppercase">
                Feligrés
            </p>

            <p class="text-sm font-semibold">
                {{ $inscripcion->feligres->persona->nombre_completo ?? '—' }}
            </p>

            <p class="text-xs text-gray-500">
                DNI:
                {{ $inscripcion->feligres->persona->dni ?? '—' }}

                · Curso actual:
                {{ $inscripcion->curso->nombre ?? '—' }}
            </p>

        </div>


        {{-- CURSO --}}
        <div>

            <label class="text-xs font-semibold uppercase text-gray-500">
                Curso
            </label>

            <select wire:model.live="curso_id"
                class="w-full border rounded-lg px-3 py-2">

                <option value="">Seleccione curso</option>

                @foreach($cursos as $curso)

                    <option value="{{ $curso->id }}">
                        {{ $curso->nombre }}
                    </option>

                @endforeach

            </select>

            @error('curso_id')
                <p class="text-red-500 text-xs mt-1">
                    {{ $message }}
                </p>
            @enderror

        </div>




        {{-- INSTRUCTOR --}}
        <div>
            <label class="text-xs font-semibold uppercase text-gray-500">
                Instructor
            </label>

            <input type="text"
                value="{{ $nombreInstructor }}"
                readonly
                placeholder="Seleccione un curso"
                class="w-full border rounded-lg px-3 py-2 bg-gray-100">
        </div>







        {{-- APROBADO --}}
        <div class="space-y-1">

            <label class="block text-xs font-semibold uppercase text-gray-500">
                Aprobado
            </label>

            <select wire:model="aprobado"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2">

                <option value="">Seleccione</option>
                <option value="1">Sí</option>
                <option value="0">No</option>

            </select>

            @error('aprobado')
                <p class="text-red-500 text-xs">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- CERTIFICADO EMITIDO --}}
        <div class="space-y-1">

            <label class="block text-xs font-semibold uppercase text-gray-500">
                Certificado emitido
            </label>

            <select
                wire:model="certificado_emitido"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                <option value="">Seleccione</option>
                <option value="1">Sí</option>
                <option value="0">No</option>

            </select>

            @error('certificado_emitido')
                <p class="text-red-500 text-xs">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- FECHA INSCRIPCION --}}
        <div>

            <label class="text-xs font-semibold uppercase text-gray-500">
                Fecha de inscripción
            </label>

            <input type="date"
                   wire:model="fecha_inscripcion"
                   class="w-full border rounded-lg px-3 py-2">

            @error('fecha_inscripcion')
                <p class="text-red-500 text-xs mt-1">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- FECHA CERTIFICADO --}}
        <div>

            <label class="text-xs font-semibold uppercase text-gray-500">
                Fecha certificado
            </label>

            <input type="date"
                   wire:model="fecha_certificado"
                   class="w-full border rounded-lg px-3 py-2">

            @error('fecha_certificado')
                <p class="text-red-500 text-xs mt-1">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- BOTONES --}}
        <div class="flex justify-between pt-5 border-t">

            <a href="{{ route('inscripcion-curso.show',$inscripcion) }}"
               class="px-4 py-2 bg-gray-200 rounded-lg">
                Cancelar
            </a>

            <button
                wire:click="update"
                wire:loading.attr="disabled"
                class="px-6 py-2 bg-emerald-600 text-white rounded-lg">

                Actualizar Inscripción

            </button>

        </div>

    </div>

</div>