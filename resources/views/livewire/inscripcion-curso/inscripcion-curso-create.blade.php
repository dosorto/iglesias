<div class="space-y-6">

    @php
        $vieneDeCurso = filled(request()->query('curso_id'));
    @endphp

    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-emerald-100 px-4 py-3 text-emerald-700 border border-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-red-700 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-red-700 border border-red-200">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600
                dark:from-indigo-700 dark:to-violet-700 shadow-md px-6 py-5">

        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
        <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-xl font-bold text-white leading-tight">
                        Registrar Nueva Inscripción
                    </h1>

                    <p class="text-indigo-100 text-sm mt-0.5">
                        Busca una persona por nombre para inscribirla en un curso
                    </p>
                </div>
            </div>

            <a href="{{ $vieneDeCurso ? route('curso.show', $curso_id) : route('inscripcion-curso.index') }}"
               class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-lg
                      bg-white/15 hover:bg-white/25 border border-white/20
                      text-white text-sm font-medium transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                ring-1 ring-black/5 dark:ring-white/5">

        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                         bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300
                         text-xs font-bold ring-2 ring-indigo-200 dark:ring-indigo-700/50">
                1
            </span>

            <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                Persona
            </h2>
        </div>

        <div class="p-6">
            @if ($personaSeleccionada)
                <div class="flex items-center justify-between p-4 rounded-xl
                            bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700/50">

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600
                                    flex items-center justify-center flex-shrink-0 shadow-sm">
                            <span class="text-white font-bold text-sm">
                                {{ strtoupper(substr($personaSeleccionada['nombre'], 0, 1)) }}
                            </span>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">
                                {{ $personaSeleccionada['nombre'] }}
                            </p>

                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                DNI: {{ $personaSeleccionada['dni'] }}
                            </p>

                            <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-semibold
                                         bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">
                                ✓ Seleccionada
                            </span>
                        </div>
                    </div>

                    <button type="button"
                            wire:click="limpiarPersonaSeleccionada"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                                   bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600
                                   text-gray-700 dark:text-gray-200 text-sm font-medium transition">
                        Cambiar
                    </button>
                </div>
            @else
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-1 relative">
                            <input type="text"
                                   wire:model="nombreBusqueda"
                                   wire:keydown.enter="buscarPersona"
                                   placeholder="Ingresa el nombre de la persona..."
                                   class="block w-full pl-10 pr-3 py-2.5 text-sm rounded-lg
                                          border border-gray-300 dark:border-gray-600
                                          bg-gray-50 dark:bg-gray-700
                                          text-gray-900 dark:text-white
                                          focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0
                                             7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        <button type="button"
                                wire:click="buscarPersona"
                                class="inline-flex items-center gap-2 px-4 py-2.5
                                       bg-orange-500 hover:bg-orange-600
                                       text-white text-sm font-medium
                                       rounded-lg shadow-sm transition">
                            <svg class="w-4 h-4"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0
                                         7 7 0 0114 0z"/>
                            </svg>
                            Buscar
                        </button>
                    </div>

                    @if (!empty($resultadosBusqueda))
                        <div class="rounded-xl border border-indigo-200 dark:border-indigo-700/50
                                    bg-indigo-50/60 dark:bg-indigo-900/10 p-4">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-3">
                                Coincidencias encontradas
                            </p>

                            <div class="space-y-2">
                                @foreach($resultadosBusqueda as $resultado)
                                    <button type="button"
                                            wire:click="seleccionarPersona({{ $resultado['id'] }})"
                                            class="w-full flex items-center justify-between p-3 rounded-lg
                                                   bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                   hover:border-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition">
                                        <div class="text-left">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $resultado['nombre'] }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                DNI: {{ $resultado['dni'] }}
                                            </p>
                                        </div>

                                        <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                                            Seleccionar
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border ring-1 ring-black/5 dark:ring-white/5
                {{ $feligres_id ? '' : 'opacity-60 pointer-events-none' }}">

        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                         bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300
                         text-xs font-bold">
                2
            </span>

            <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                Datos de Inscripción
            </h2>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Curso
                </label>

                @if($vieneDeCurso)
                    <input type="hidden" wire:model="curso_id">

                    <input type="text"
                           value="{{ optional($cursos->firstWhere('id', $curso_id))->nombre ?? 'Curso seleccionado' }}"
                           readonly
                           class="w-full mt-1 border-gray-300 dark:border-gray-600 rounded-lg
                                  bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
                @else
                    <select wire:model.live="curso_id"
                            class="w-full mt-1 border-gray-300 dark:border-gray-600 rounded-lg
                                   bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Seleccionar curso</option>

                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}">
                                {{ $curso->nombre }}
                            </option>
                        @endforeach
                    </select>
                @endif

                @error('curso_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Instructor
                </label>

                <input type="text"
                       value="{{ $nombreInstructor }}"
                       readonly
                       class="w-full mt-1 border-gray-300 dark:border-gray-600 rounded-lg
                              bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white"
                       placeholder="Seleccione un curso">
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Fecha de inscripción
                </label>

                <input type="date"
                       wire:model="fecha_inscripcion"
                       class="w-full mt-1 border-gray-300 dark:border-gray-600 rounded-lg
                              bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">

                @error('fecha_inscripcion')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Estado inicial
                </label>

                <div class="mt-1 rounded-lg border border-dashed border-gray-300 dark:border-gray-600
                            bg-gray-50 dark:bg-gray-700/50 px-4 py-3">
                    <p class="text-sm text-gray-700 dark:text-gray-200">
                        La inscripción se guardará automáticamente como:
                    </p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                            Aprobado: No
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                            Certificado: No
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                            Fecha certificado: N/A
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-8 pt-5 border-t border-gray-100 dark:border-gray-700/50 px-6 pb-6">
            <a href="{{ $vieneDeCurso ? route('curso.show', $curso_id) : route('inscripcion-curso.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium
                      bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                Cancelar
            </a>

            <button type="button"
                    wire:click="guardar"
                    wire:loading.attr="disabled"
                    @disabled(!$feligres_id)
                    class="inline-flex items-center gap-2.5 px-7 py-2.5 rounded-lg text-sm font-bold
                           bg-gradient-to-r from-emerald-500 to-emerald-600
                           hover:from-emerald-600 hover:to-emerald-700
                           text-white">
                Guardar Inscripción
            </button>
        </div>
    </div>
</div>