@php
    // Problema #21: Obtener cursos que enseña este instructor
    $cursos = \App\Models\Curso::where('instructor_id', $instructor->id)->get();
    $auditLogs = $instructor->auditLogs ?? collect();

    $formatValue = static function ($value): string {
        if (is_null($value) || $value === '') {
            return 'N/A';
        }
        if (is_bool($value)) {
            return $value ? 'Sí' : 'No';
        }
        if (is_array($value)) {
            return '...';
        }
        return \Illuminate\Support\Str::limit((string) $value, 80);
    };

    $eventColors = [
        'created' => 'bg-emerald-700',
        'updated' => 'bg-amber-700',
        'deleted' => 'bg-slate-400',
    ];
@endphp

<div class="container-fluid max-w-7xl mx-auto py-2">
    {{-- Encabezado --}}
    <section class="mb-8 border-b border-slate-200 dark:border-slate-700 pb-6">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5">
            <div>
                <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-3">
                    <span>Instructores</span>
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    <span class="text-emerald-800 dark:text-emerald-300 font-medium">Perfil de Instructor</span>
                </nav>
                <h1 class="font-serif text-4xl md:text-5xl text-emerald-900 dark:text-emerald-300">
                    {{ $instructor->feligres?->persona?->nombre_completo ?? 'Instructor' }}
                </h1>
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-sm text-slate-700 dark:text-slate-300">
                        DNI: {{ $instructor->feligres?->persona?->dni ?? '—' }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $instructor->estado === 'Activo' ? 'bg-emerald-200 text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-200' : 'bg-red-200 text-red-900 dark:bg-red-900/40 dark:text-red-200' }}">
                        {{ $instructor->estado }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('instructor.index') }}" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-emerald-800 dark:text-emerald-300 font-semibold rounded-lg hover:bg-emerald-50 dark:hover:bg-slate-700 transition-colors duration-200">
                    Volver
                </a>
                @can('instructor.edit')
                    <a href="{{ route('instructor.edit', $instructor) }}" class="px-5 py-2.5 bg-emerald-800 text-white font-semibold rounded-lg shadow-sm hover:bg-emerald-700 transition-colors duration-200">
                        Editar Perfil
                    </a>
                @endcan
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 xl:gap-8 items-start">
        {{-- Columna Izquierda: Datos Personales --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Datos Personales --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 border-l-4 border-l-emerald-800 p-6">
                <h2 class="text-3xl font-serif text-emerald-900 dark:text-emerald-300 mb-6">Datos Personales</h2>
                <dl class="space-y-5">
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Teléfono</dt>
                        <dd class="text-slate-900 dark:text-slate-100 mt-1">{{ $instructor->feligres?->persona?->telefono ?? 'No registrado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Correo electrónico</dt>
                        <dd class="text-slate-900 dark:text-slate-100 mt-1 break-all">{{ $instructor->feligres?->persona?->email ?? 'No registrado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Iglesia / Parroquia</dt>
                        <dd class="text-slate-900 dark:text-slate-100 mt-1">{{ $instructor->feligres?->iglesia?->nombre ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Fecha de ingreso</dt>
                        <dd class="text-slate-900 dark:text-slate-100 mt-1">
                            {{ $instructor->fecha_ingreso ? $instructor->fecha_ingreso->translatedFormat('d \d\e F, Y') : 'No registrado' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Estado del Feligrés</dt>
                        <dd class="text-slate-900 dark:text-slate-100 mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold {{ $instructor->feligres?->estado === 'Activo' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200' }}">
                                {{ $instructor->feligres?->estado ?? '—' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Firma --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 border-l-4 border-l-amber-800 p-6">
                <h2 class="text-lg font-serif text-amber-900 dark:text-amber-300 mb-4">Firma del Instructor</h2>
                @if($instructor->path_firma)
                    <img src="{{ asset('storage/' . $instructor->path_firma) }}"
                         alt="Firma del instructor"
                         class="w-full rounded border border-slate-200 dark:border-slate-700">
                @else
                    <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-6">No tiene firma registrada</p>
                @endif
            </div>

            {{-- Link a Feligrés --}}
            @can('feligres.view')
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-indigo-200 dark:border-indigo-700 p-6">
                    <h3 class="text-sm uppercase tracking-widest font-bold text-indigo-700 dark:text-indigo-300 mb-3">Registro de Feligrés</h3>
                    <a href="{{ route('feligres.show', $instructor->feligres) }}"
                       class="inline-flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        Ver ficha de feligrés →
                    </a>
                </div>
            @endcan
        </div>

        {{-- Columna Derecha: Cursos e Historial --}}
        <div class="lg:col-span-8 space-y-6">
            {{-- Cursos que Enseña --}}
            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <header class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-3xl font-serif text-emerald-900 dark:text-emerald-300">Cursos que Enseña</h2>
                </header>
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[640px]">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-700/60">
                                <th class="px-6 py-3 text-[11px] uppercase tracking-[0.16em] font-bold text-slate-500 dark:text-slate-300">Nombre del Curso</th>
                                <th class="px-6 py-3 text-[11px] uppercase tracking-[0.16em] font-bold text-slate-500 dark:text-slate-300">Tipo</th>
                                <th class="px-6 py-3 text-[11px] uppercase tracking-[0.16em] font-bold text-slate-500 dark:text-slate-300">Estado</th>
                                <th class="px-6 py-3 text-[11px] uppercase tracking-[0.16em] font-bold text-slate-500 dark:text-slate-300">Inscritos</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($cursos as $curso)
                                <tr class="hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-colors duration-200">
                                    <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $curso->nombre ?? 'Curso sin nombre' }}
                                        @can('curso.view')
                                            <a href="{{ route('curso.show', $curso) }}" class="block text-[11px] text-emerald-700 dark:text-emerald-300 hover:underline mt-1">Ver detalle</a>
                                        @endcan
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                        {{ $curso->tipoCurso?->nombre_curso ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold {{ $curso->estado === 'Activo' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200' : 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200' }}">
                                            {{ $curso->estado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300 font-medium">
                                        {{ $curso->inscripcionesCurso()->count() ?? 0 }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400 italic">No hay cursos asignados a este instructor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Historial de Cambios --}}
            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h2 class="text-3xl font-serif text-emerald-900 dark:text-emerald-300 mb-6">Historial de Cambios</h2>

                <div class="relative space-y-6">
                    <span class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-slate-200 dark:bg-slate-700" aria-hidden="true"></span>

                    @forelse($auditLogs as $log)
                        <article class="relative pl-10">
                            <span class="absolute left-0 top-1 w-6 h-6 rounded-full {{ $eventColors[$log->event] ?? 'bg-slate-400' }} border-4 border-white dark:border-slate-800"></span>

                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-1">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $log->event === 'created' ? 'Registro inicial' : ($log->event === 'updated' ? 'Actualización de perfil' : 'Eliminación') }}
                                </h3>
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                            </div>

                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                Modificado por: <span class="italic">{{ $log->user_name ?? ($log->user->name ?? 'Sistema') }}</span>
                            </p>

                            @if($log->event === 'updated' && is_array($log->new_values ?? null) && count($log->new_values))
                                @php
                                    $hiddenFields = ['updated_at', 'created_at', 'id', 'user_id'];
                                @endphp
                                <div class="mt-2 text-[11px] rounded-md bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 px-3 py-2 space-y-1">
                                    @foreach($log->new_values as $key => $value)
                                        @continue(in_array($key, $hiddenFields, true))
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ str_replace('_', ' ', $key) }}:</span>
                                            <span class="text-red-500 line-through">{{ $formatValue($log->old_values[$key] ?? null) }}</span>
                                            <span class="text-slate-400">→</span>
                                            <span class="text-emerald-600 dark:text-emerald-300 font-semibold">{{ $formatValue($value) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    @empty
                        <p class="text-sm text-slate-500 dark:text-slate-400 italic">No se han registrado movimientos.</p>
                    @endforelse
                </div>

                <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-700 text-[11px] text-slate-500 dark:text-slate-400 flex flex-col gap-1">
                    <span>Creado: {{ $instructor->created_at->format('d/m/Y H:i') }} por {{ $instructor->creator->name ?? 'Sistema' }}</span>
                    <span>Actualizado: {{ $instructor->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </section>
        </div>
    </div>
</div>
