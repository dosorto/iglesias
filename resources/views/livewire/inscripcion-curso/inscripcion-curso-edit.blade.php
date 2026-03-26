<div class="space-y-6">

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
                        {{ $nombreFeligres ?? ($inscripcion->feligres->persona->nombre_completo ?? '—') }}
                    </p>
                </div>
            </div>

            <a href="{{ route('curso.show', $inscripcion->curso_id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                      bg-white/15 hover:bg-white/25 border border-white/20
                      text-white text-sm font-medium">
                Volver
            </a>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <p class="text-green-700 font-medium">
                {{ session('success') }}
            </p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <ul class="list-disc pl-5 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-6 space-y-6">

        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">
                Feligrés
            </p>

            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ $nombreFeligres ?? '—' }}
            </p>

            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                DNI: {{ $dniFeligres ?? '—' }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">
                    Curso
                </label>

                <input type="text"
                       value="{{ $inscripcion->curso->nombre ?? '—' }}"
                       readonly
                       class="w-full mt-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div>
                <label class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">
                    Instructor
                </label>

                <input type="text"
                       value="{{ $nombreInstructor }}"
                       readonly
                       class="w-full mt-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div>
                <label class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">
                    Fecha de inscripción
                </label>

                <input type="date"
                       wire:model="fecha_inscripcion"
                       readonly
                       class="w-full mt-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">

                @error('fecha_inscripcion')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">
                    Fecha certificado
                </label>

                <input type="text"
                       value="{{ $fecha_certificado ? \Carbon\Carbon::parse($fecha_certificado)->format('d/m/Y') : 'N/A' }}"
                       readonly
                       class="w-full mt-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">
                    Aprobado
                </label>

                <select wire:model.live="aprobado"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2
                               bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>

                @error('aprobado')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">
                    Certificado emitido
                </label>

                <select wire:model.live="certificado_emitido"
                        {{ $aprobado === '0' ? 'disabled' : '' }}
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2
                               bg-white dark:bg-gray-700 text-gray-900 dark:text-white disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>

                @error('certificado_emitido')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror

                @if($aprobado === '0')
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Debes aprobar la inscripción antes de emitir certificado.
                    </p>
                @endif
            </div>
        </div>

        

        <div class="flex justify-between pt-5 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('curso.show', $inscripcion->curso_id) }}"
               class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg">
                Cancelar
            </a>     

              

            <button wire:click="update"
                    wire:loading.attr="disabled"
                    class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">
                Actualizar Inscripción
            </button>
        </div>
    </div>
</div>