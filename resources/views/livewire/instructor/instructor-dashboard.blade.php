<div class="space-y-8">
    <section class="relative overflow-hidden rounded-2xl bg-emerald-900 p-6 md:p-10 text-white shadow-xl">
        <div class="absolute -right-16 -top-16 h-52 w-52 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-emerald-300/20 blur-2xl"></div>

        <div class="relative grid gap-6 md:grid-cols-3 md:items-center">
            <div class="md:col-span-2">
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-100">Panel del Instructor</p>
                <h1 class="mt-2 text-3xl font-black leading-tight md:text-5xl">{{ $saludoInstructor }}, {{ $nombreInstructor }}</h1>
                <p class="mt-4 max-w-2xl text-sm text-emerald-100 md:text-base">
                    Gestiona tus cursos, revisa el progreso de tus alumnos y mantente al dia con la actividad reciente.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('curso.index') }}"
                       class="rounded-xl bg-amber-300 px-5 py-2.5 text-sm font-bold text-amber-950 shadow hover:bg-amber-200 transition-colors">
                        Ver mis cursos
                    </a>
                    <a href="{{ route('instructor.index') }}"
                       class="rounded-xl border border-white/30 bg-white/10 px-5 py-2.5 text-sm font-bold text-white hover:bg-white/20 transition-colors">
                        Mi perfil de instructor
                    </a>
                </div>
            </div>

            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-100">Estado de firma</p>
                @if($firmaConfigurada)
                    <p class="mt-2 text-xl font-extrabold text-emerald-100">Firma configurada</p>
                    <p class="mt-1 text-sm text-emerald-100">Fecha de hoy: {{ now()->format('d/m/Y') }}</p>
                @else
                    <p class="mt-2 text-xl font-extrabold text-amber-200">Firma no configurada</p>
                @endif

                @if($instructorId)
                    <a href="{{ route('instructor.edit', $instructorId) }}"
                       class="mt-4 inline-flex rounded-lg border border-white/30 px-3 py-2 text-xs font-bold text-white hover:bg-white/20 transition-colors">
                        {{ $firmaConfigurada ? 'Actualizar firma' : 'Configurar firma' }}
                    </a>
                @endif
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total alumnos</p>
            <p class="mt-3 text-4xl font-black text-emerald-700 dark:text-emerald-400">{{ number_format($stats['total_alumnos']) }}</p>
        </article>

        <article class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Cursos activos</p>
            <p class="mt-3 text-4xl font-black text-blue-700 dark:text-blue-400">{{ number_format($stats['cursos_activos']) }}</p>
        </article>

        <article class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Tasa aprobacion</p>
            <p class="mt-3 text-4xl font-black text-amber-700 dark:text-amber-400">{{ number_format($stats['tasa_aprobacion'], 1) }}%</p>
        </article>

        <article class="rounded-2xl border border-purple-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Mi panel</p>
            <p class="mt-3 text-lg font-bold text-purple-700 dark:text-purple-300">Instructor autenticado</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Acceso restringido a tus cursos y registros.</p>
        </article>
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="space-y-4 xl:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-black text-emerald-900 dark:text-emerald-300">Cursos en curso</h2>
                <a href="{{ route('curso.index') }}" class="text-sm font-bold text-emerald-700 hover:underline dark:text-emerald-400">
                    Ver todos
                </a>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @forelse($cursosEnCurso as $curso)
                    <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-lg font-extrabold text-gray-900 dark:text-white">{{ $curso['nombre'] }}</h3>
                            <span class="rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-black uppercase tracking-wider text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                {{ $curso['tipo'] }}
                            </span>
                        </div>

                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                            {{ $curso['inscritos'] }} inscritos · {{ $curso['aprobados'] }} aprobados
                        </p>

                        <div class="mt-4">
                            <div class="mb-1 flex justify-between text-xs font-bold text-gray-600 dark:text-gray-300">
                                <span>Progreso del curso</span>
                                <span>{{ $curso['progreso'] }}%</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-600 {{ $curso['progreso_width_class'] }}"></div>
                            </div>
                        </div>

                        <div class="mt-5 flex gap-2">
                            <a href="{{ route('curso.show', $curso['id']) }}"
                               class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-center text-xs font-bold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                Ver detalle
                            </a>
                            <a href="{{ route('curso.show', $curso['id']) }}"
                               class="flex-1 rounded-lg bg-emerald-700 px-3 py-2 text-center text-xs font-bold text-white hover:bg-emerald-800">
                                Gestionar alumnos
                            </a>
                        </div>
                    </article>
                @empty
                    <article class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center dark:border-gray-600 dark:bg-gray-800 md:col-span-2">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">No hay cursos activos asignados.</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Cuando tengas cursos en estado Activo apareceran aqui.</p>
                    </article>
                @endforelse
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-black text-emerald-900 dark:text-emerald-300">Actividad reciente</h2>
            <div class="mt-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <ol class="space-y-4">
                    @forelse($actividadReciente as $item)
                        <li class="relative pl-5">
                            <span class="absolute left-0 top-1.5 h-2 w-2 rounded-full {{ $item['aprobado'] ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                            <p class="text-[11px] font-black uppercase tracking-wider text-gray-400">{{ $item['momento'] }}</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $item['mensaje'] }}</p>
                        </li>
                    @empty
                        <li>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Sin actividad reciente.</p>
                        </li>
                    @endforelse
                </ol>
            </div>
        </div>
    </section>
</div>
