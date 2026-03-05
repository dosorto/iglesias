<div class="space-y-6">

    {{-- HEADER --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-sky-600 to-blue-600 shadow-md px-6 py-5">
        <div class="relative flex justify-between items-center">

            <div>
                <h1 class="text-xl font-bold text-white">Registrar Curso</h1>
                <p class="text-sky-100 text-sm">Completa los pasos para registrar un curso</p>
            </div>

            <a href="{{ route('curso.index') }}"
               class="px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 text-white text-sm">
                Volver
            </a>

        </div>
    </div>


    {{-- INDICADOR PASOS (igual a Bautismo) --}}
    <div class="bg-white rounded-xl shadow border p-6">

        <div class="flex items-center justify-between relative">

            {{-- linea --}}
            <div class="absolute top-4 left-0 w-full h-[2px] bg-gray-200"></div>

            @foreach ([1 => 'Curso', 2 => 'Tipo Curso', 3 => 'Instructor'] as $n => $label)

                <div class="relative flex flex-col items-center w-full">

                    {{-- circulo --}}
                    <div class="
                        @if($paso == $n) bg-blue-600 text-white
                        @elseif($paso > $n) bg-blue-500 text-white
                        @else bg-gray-200 text-gray-500
                        @endif
                        w-8 h-8 rounded-full flex items-center justify-center font-semibold z-10">

                        {{ $n }}

                    </div>

                    {{-- texto --}}
                    <span class="text-xs mt-2 text-gray-600">
                        {{ $label }}
                    </span>

                </div>

            @endforeach

        </div>

    </div>


    {{-- PASO 1 DATOS CURSO --}}
    @if($paso === 1)

        <div class="bg-white rounded-xl shadow border p-6 space-y-4">

            <h2 class="font-semibold text-gray-700">Datos del Curso</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="text-sm font-medium">Nombre del Curso</label>

                    <input type="text"
                           wire:model="nombre"
                           class="w-full border rounded-lg px-3 py-2">

                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label class="text-sm font-medium">Estado</label>

                    <select wire:model="estado"
                            class="w-full border rounded-lg px-3 py-2">

                        <option value="Activo">Activo</option>
                        <option value="Finalizado">Finalizado</option>
                        <option value="Cancelado">Cancelado</option>

                    </select>
                </div>


                <div>
                    <label class="text-sm font-medium">Fecha Inicio</label>

                    <input type="date"
                           wire:model="fecha_inicio"
                           class="w-full border rounded-lg px-3 py-2">
                </div>


                <div>
                    <label class="text-sm font-medium">Fecha Fin</label>

                    <input type="date"
                           wire:model="fecha_fin"
                           class="w-full border rounded-lg px-3 py-2">
                </div>


                <div class="md:col-span-2">

                    <label class="text-sm font-medium">Iglesia</label>

                    <select wire:model="iglesia_id"
                            class="w-full border rounded-lg px-3 py-2">

                        <option value="">Selecciona Iglesia</option>

                        @foreach($iglesias as $ig)
                            <option value="{{ $ig->id }}">
                                {{ $ig->nombre }}
                            </option>
                        @endforeach

                    </select>

                    @error('iglesia_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                </div>


                <div class="md:col-span-2">

                    <label class="text-sm font-medium">Encargado</label>

                    <input type="text"
                           value="{{ optional($encargados->firstWhere('id',$encargado_id))->feligres?->persona?->nombre_completo }}"
                           disabled
                           class="w-full border rounded-lg px-3 py-2 bg-gray-100">

                </div>

            </div>

        </div>

    @endif



    {{-- ======================= --}}
    {{-- PASO 2 TIPO CURSO --}}
    {{-- ======================= --}}

    @if($paso === 2)

        <div class="bg-white rounded-xl shadow border p-6 space-y-4">   
            

            <h2 class="font-semibold text-gray-700">
                Tipo de Curso
            </h2>

            <div class="relative">

                <input
                    type="text"
                    wire:model.live="buscar_tipo_curso"
                    placeholder="Buscar tipo de curso..."
                    class="w-full border rounded-lg px-3 py-2"
                >

                @if(count($tipoCursoResultados))

                    <div class="absolute bg-white border w-full rounded-lg shadow mt-1 z-10">

                        @foreach($tipoCursoResultados as $tipo)

                            <div
                                wire:click="seleccionarTipoCurso({{ $tipo->id }})"
                                class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                            >

                                {{ $tipo->nombre_curso }}

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>


            @if($tipo_curso_id)

                <div class="p-3 bg-green-50 border border-green-200 rounded-lg">

                    Tipo de curso seleccionado correctamente

                </div>

            @endif


            @error('buscar_tipo_curso')

                <p class="text-red-500 text-xs mt-2">

                    {{ $message }}

                </p>

            @enderror

        </div>

    @endif



    {{-- PASO 3 INSTRUCTOR --}}
    @if($paso === 3)

        <div class="bg-white rounded-xl shadow border p-6 space-y-4">

            <h2 class="font-semibold text-gray-700">Instructor</h2>

            <div class="relative">

                <input type="text"
                       wire:model.live="buscar_instructor"
                       placeholder="Buscar instructor..."
                       class="w-full border rounded-lg px-3 py-2">


                @if(count($instructorResultados))

                    <div class="absolute bg-white border w-full rounded-lg shadow mt-1 z-10">

                        @foreach($instructorResultados as $inst)

                            <div wire:click="seleccionarInstructor({{ $inst->id }})"
                                 class="px-3 py-2 hover:bg-gray-100 cursor-pointer">

                                {{ $inst->feligres?->persona?->nombre_completo }}

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>


            @if($instructor_id)

                <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                    Instructor seleccionado correctamente
                </div>

            @endif


            @error('instructor_id')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror

        </div>

    @endif



    {{-- BOTONES --}}
    <div class="flex justify-between">

        @if($paso > 1)

            <button wire:click="anteriorPaso"
                    class="px-4 py-2 border rounded-lg">
                Anterior
            </button>

        @endif


        @if($paso < 3)

            <button wire:click="siguientePaso"
                    class="px-6 py-2 bg-sky-600 text-white rounded-lg">
                Siguiente
            </button>

        @else

            <button wire:click="guardar"
                    class="px-6 py-2 bg-emerald-600 text-white rounded-lg">
                Guardar Curso
            </button>

        @endif

    </div>


</div>