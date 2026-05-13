@extends('layouts.app')

@section('title', 'Detalle de Feligrés')

@section('content')
<div class="container-fluid max-w-7xl mx-auto py-2">
    @php
        $sacramentosCollection = collect($sacramentos ?? []);
        $cursosCollection = collect($cursos ?? []);
        $auditLogs = $feligre->auditLogs ?? collect();

        $registeredSacraments = $sacramentosCollection
            ->pluck('tipo')
            ->filter()
            ->map(fn ($tipo) => mb_strtolower(trim($tipo)));

        $pendingSacraments = collect(['Bautismo', 'Primera Comunión', 'Confirmación', 'Matrimonio'])
            ->filter(fn ($tipo) => ! $registeredSacraments->contains(mb_strtolower($tipo)))
            ->values();

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

    <section class="mb-8 border-b border-slate-200 dark:border-slate-700 pb-6">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5">
            <div>
                <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-3">
                    <span>Feligreses</span>
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    <span class="text-emerald-800 dark:text-emerald-300 font-medium">Perfil de Feligrés</span>
                </nav>
                <h1 class="font-serif text-4xl md:text-5xl text-emerald-900 dark:text-emerald-300">
                    {{ $feligre->persona->nombre_completo }}
                </h1>
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-sm text-slate-700 dark:text-slate-300">DNI: {{ $feligre->persona->dni }}</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $feligre->estado === 'Activo' ? 'bg-amber-200 text-amber-900 dark:bg-amber-900/40 dark:text-amber-200' : 'bg-red-200 text-red-900 dark:bg-red-900/40 dark:text-red-200' }}">
                        {{ $feligre->estado }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('feligres.index') }}" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-emerald-800 dark:text-emerald-300 font-semibold rounded-lg hover:bg-emerald-50 dark:hover:bg-slate-700 transition-colors duration-200">
                    Volver
                </a>
                @can('feligres.edit')
                    <a href="{{ route('feligres.edit', $feligre) }}" class="px-5 py-2.5 bg-emerald-800 text-white font-semibold rounded-lg shadow-sm hover:bg-emerald-700 transition-colors duration-200">
                        Editar Perfil
                    </a>
                @endcan
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 xl:gap-8 items-start">
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 border-l-4 border-l-emerald-800 p-6">
                <h2 class="text-3xl font-serif text-emerald-900 dark:text-emerald-300 mb-6">Datos Personales</h2>
                <dl class="space-y-5">
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Fecha de nacimiento</dt>
                        <dd class="text-slate-900 dark:text-slate-100 mt-1">
                            {{ $feligre->persona->fecha_nacimiento ? $feligre->persona->fecha_nacimiento->translatedFormat('d \d\e F, Y') : 'No registrado' }}
                            @if($feligre->persona->fecha_nacimiento)
                                <span class="text-slate-500 dark:text-slate-400">({{ \Carbon\Carbon::parse($feligre->persona->fecha_nacimiento)->age }} años)</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Dirección</dt>
                        <dd class="text-slate-900 dark:text-slate-100 mt-1">{{ $feligre->persona->direccion ?? 'No registrado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Teléfono</dt>
                        <dd class="text-slate-900 dark:text-slate-100 mt-1">{{ $feligre->persona->telefono ?? 'No registrado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Correo electrónico</dt>
                        <dd class="text-slate-900 dark:text-slate-100 mt-1 break-all">{{ $feligre->persona->email ?? 'No registrado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Iglesia / Parroquia</dt>
                        <dd class="text-slate-900 dark:text-slate-100 mt-1">{{ $feligre->iglesia->nombre }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Fecha de ingreso</dt>
                        <dd class="text-slate-900 dark:text-slate-100 mt-1">{{ $feligre->fecha_ingreso ? $feligre->fecha_ingreso->translatedFormat('d \d\e F, Y') : 'No registrado' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-emerald-900 text-emerald-50 rounded-xl p-6 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-28 h-28 rounded-full bg-emerald-700/40"></div>
                <h3 class="text-2xl font-serif mb-4 relative">Sacramentos Pendientes</h3>
                <div class="space-y-3 mb-5 relative">
                    @forelse($pendingSacraments as $pending)
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <span>{{ $pending }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] uppercase tracking-wider font-semibold bg-white/20">No registrado</span>
                        </div>
                    @empty
                        <p class="text-sm text-emerald-100/90">No hay sacramentos pendientes.</p>
                    @endforelse
                </div>
                @can('feligres.edit')
                    <a href="{{ route('feligres.edit', $feligre) }}" class="relative block text-center w-full py-2.5 rounded-lg bg-amber-300 text-amber-950 font-semibold hover:bg-amber-200 transition-colors duration-200">
                        Ver requisitos
                    </a>
                @endcan
            </div>

            @if($feligre->encargado)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-amber-200 dark:border-amber-700 p-5">
                    <h3 class="text-sm uppercase tracking-widest font-bold text-amber-700 dark:text-amber-300 mb-3">Encargado</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Firma principal</p>
                    <p class="text-xs font-mono text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 p-2 rounded mt-1 break-all">
                        {{ $feligre->encargado->path_firma_principal ?? 'Sin firma registrada' }}
                    </p>
                    @can('encargado.view')
                        <a href="{{ route('encargado.show', $feligre->encargado) }}" class="mt-3 inline-block text-xs font-semibold text-amber-700 dark:text-amber-300 hover:underline">
                            Ver detalles del encargado
                        </a>
                    @endcan
                </div>
            @endif
        </div>

        <div class="lg:col-span-8 space-y-6">
            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <header class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-3xl font-serif text-emerald-900 dark:text-emerald-300">Registro de Sacramentos</h2>
                </header>
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[640px]">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-700/60">
                                <th class="px-6 py-3 text-[11px] uppercase tracking-[0.16em] font-bold text-slate-500 dark:text-slate-300">Sacramento</th>
                                <th class="px-6 py-3 text-[11px] uppercase tracking-[0.16em] font-bold text-slate-500 dark:text-slate-300">Fecha</th>
                                <th class="px-6 py-3 text-[11px] uppercase tracking-[0.16em] font-bold text-slate-500 dark:text-slate-300">Lugar</th>
                                <th class="px-6 py-3 text-[11px] uppercase tracking-[0.16em] font-bold text-slate-500 dark:text-slate-300">Libro/Folio</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($sacramentosCollection as $sacramento)
                                @php
                                    $record = $sacramento['model'] ?? null;
                                    $lugar = data_get($record, 'iglesia.nombre')
                                        ?? data_get($record, 'parroquia.nombre')
                                        ?? $feligre->iglesia->nombre
                                        ?? 'No registrado';
                                    $libro = data_get($record, 'libro')
                                        ?? data_get($record, 'numero_libro')
                                        ?? data_get($record, 'libro_numero');
                                    $folio = data_get($record, 'folio')
                                        ?? data_get($record, 'numero_folio')
                                        ?? data_get($record, 'folio_numero');
                                @endphp
                                <tr class="hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="w-1.5 h-8 rounded-full bg-amber-700"></span>
                                            <div>
                                                <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $sacramento['tipo'] }}</p>
                                                @can($sacramento['permission'])
                                                    <a href="{{ route($sacramento['route'], $sacramento['model']) }}" class="text-[11px] text-emerald-700 dark:text-emerald-300 hover:underline">Ver detalle</a>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $sacramento['fecha'] ? $sacramento['fecha']->format('d M, Y') : 'No registrada' }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $lugar }}</td>
                                    <td class="px-6 py-4 text-slate-700 dark:text-slate-200 font-mono text-sm">{{ $libro || $folio ? 'L-' . ($libro ?? 'N/A') . ' / F-' . ($folio ?? 'N/A') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400 italic">No hay sacramentos registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <header class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-3xl font-serif text-emerald-900 dark:text-emerald-300">Cursos y Formación</h2>
                </header>
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[640px]">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-700/60">
                                <th class="px-6 py-3 text-[11px] uppercase tracking-[0.16em] font-bold text-slate-500 dark:text-slate-300">Nombre del curso</th>
                                <th class="px-6 py-3 text-[11px] uppercase tracking-[0.16em] font-bold text-slate-500 dark:text-slate-300">Fecha inicio</th>
                                <th class="px-6 py-3 text-[11px] uppercase tracking-[0.16em] font-bold text-slate-500 dark:text-slate-300">Instructor</th>
                                <th class="px-6 py-3 text-[11px] uppercase tracking-[0.16em] font-bold text-slate-500 dark:text-slate-300">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($cursosCollection as $inscripcion)
                                @php
                                    $instructor = data_get($inscripcion, 'curso.instructor.feligres.persona.nombre_completo')
                                        ?? data_get($inscripcion, 'instructor.feligres.persona.nombre_completo')
                                        ?? 'No asignado';
                                @endphp
                                <tr class="hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-colors duration-200">
                                    <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $inscripcion->curso->nombre ?? 'Curso sin nombre' }}
                                        @can('inscripcion-curso.view')
                                            <a href="{{ route('inscripcion-curso.show', $inscripcion) }}" class="block text-[11px] text-emerald-700 dark:text-emerald-300 hover:underline mt-1">Ver detalle</a>
                                        @endcan
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                        {{ $inscripcion->fecha_inscripcion ? $inscripcion->fecha_inscripcion->format('d M, Y') : ($inscripcion->fecha_certificado ? $inscripcion->fecha_certificado->format('d M, Y') : 'No registrada') }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $instructor }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold {{ $inscripcion->aprobado ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200' }}">
                                            {{ $inscripcion->aprobado ? 'Completado' : 'En curso' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400 italic">No hay cursos registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

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
                    <span>Creado: {{ $feligre->created_at->format('d/m/Y H:i') }} por {{ $feligre->creator->name ?? 'Sistema' }}</span>
                    <span>Actualizado: {{ $feligre->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
