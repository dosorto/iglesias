<div class="space-y-6 max-w-6xl mx-auto w-full">

    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                Detalle del Curso
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Viendo la información detallada y el historial de actividad.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('curso.index') }}"
               class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 text-sm font-medium">
                Volver
            </a>

            @can('curso.edit')
                <a href="{{ route('curso.edit', $curso) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                    Editar
                </a>
            @endcan
        </div>
    </div>

    @if (session()->has('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @php
    $badgeClass = match(strtolower($curso->estado ?? '')) {
        'activo'     => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
        'finalizado' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
        'cancelado'  => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
        default      => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200',
    };

    $firmaPath = $curso->instructor?->path_firma;
    $firmaUrl = null;

    if ($firmaPath) {
        if (\Illuminate\Support\Str::startsWith($firmaPath, ['http://', 'https://', '/storage/'])) {
            $firmaUrl = $firmaPath;
        } elseif (\Illuminate\Support\Str::startsWith($firmaPath, ['storage/'])) {
            $firmaUrl = asset($firmaPath);
        } else {
            $firmaUrl = asset('storage/' . ltrim($firmaPath, '/'));
        }
    }
@endphp

    {{-- INFORMACIÓN GENERAL --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">
                Información General
            </h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">

                {{-- IZQUIERDA: DATOS --}}
                <div class="xl:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">
                                Nombre
                            </label>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">
                                {{ $curso->nombre }}
                            </p>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">
                                Estado
                            </label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                {{ $curso->estado }}
                            </span>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">
                                Tipo de Curso
                            </label>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $curso->tipoCurso?->nombre_curso ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">
                                Instructor
                            </label>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $curso->instructor?->feligres?->persona?->nombre_completo ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">
                                Encargado
                            </label>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $curso->encargado?->feligres?->persona?->nombre_completo ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">
                                Fechas
                            </label>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                @if($curso->fecha_inicio)
                                    {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif

                                @if($curso->fecha_fin)
                                    — {{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>

                    </div>
                </div>

                {{-- DERECHA: FIRMA --}}
<div class="xl:col-span-1 flex justify-end">
    <div class="w-full max-w-[260px] border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900/30 p-4 flex flex-col items-center justify-center text-center min-h-[180px]">
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-4">
            Firma del instructor
        </p>

        @if($firmaUrl)
            <img
                src="{{ $firmaUrl }}"
                alt="Firma del instructor"
                class="max-h-28 w-auto object-contain mb-4"
            >

            <div class="w-48 border-t border-gray-400 dark:border-gray-500 pt-2">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $curso->instructor?->feligres?->persona?->nombre_completo ?? 'Instructor' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Instructor
                </p>
            </div>
        @else
            <div class="flex flex-col items-center justify-center text-center">
                <div class="w-48 border-t border-dashed border-gray-400 dark:border-gray-500 mb-3"></div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    No hay firma registrada
                </p>
            </div>
        @endif
    </div>
</div>

            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-[10px] text-gray-400">
                <span>
                    Creado: {{ optional($curso->created_at)->format('d/m/Y H:i') ?? 'N/A' }}
                    por {{ optional($curso->creator)->name ?? 'Sistema' }}
                </span>
                <span class="md:text-right">
                    Actualizado: {{ optional($curso->updated_at)->format('d/m/Y H:i') ?? 'N/A' }}
                </span>
            </div>
        </div>
    </div>

    {{-- MATRICULADOS --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">
                Matriculados
            </h2>

            @can('inscripcion-curso.create')
                <a href="{{ route('inscripcion-curso.create', ['curso_id' => $curso->id]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors duration-200 text-sm font-medium">
                    Agregar matriculado
                </a>
            @endcan
        </div>

        <div class="p-6">
            @if($curso->inscripcionesCurso->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/40">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Nombre
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    DNI
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Fecha inscripción
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($curso->inscripcionesCurso as $inscripcion)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white font-medium">
                                        {{ $inscripcion->feligres?->persona?->nombre_completo ?? 'N/A' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $inscripcion->feligres?->persona?->dni ?? 'N/A' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                        {{ optional($inscripcion->fecha_inscripcion)->format('d/m/Y') ?? 'N/A' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('matriculado-curso.show', $inscripcion->id) }}"
                                               class="px-3 py-1.5 text-xs font-medium rounded-lg bg-sky-600 text-white hover:bg-sky-700 transition-colors inline-block">
                                                Ver
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-sm text-gray-500 dark:text-gray-400 italic text-center py-6">
                    Este curso no tiene matriculados registrados.
                </div>
            @endif
        </div>
    </div>

    {{-- HISTORIAL --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">
                Historial de Cambios
            </h2>
        </div>

        <div class="p-6">
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @forelse($curso->auditLogs as $log)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                @endif

                                <div class="relative flex space-x-3">
                                    <div>
                                        @php
                                            $colors = [
                                                'created' => 'bg-green-500',
                                                'updated' => 'bg-blue-500',
                                                'deleted' => 'bg-red-500',
                                            ];
                                        @endphp

                                        <span class="h-8 w-8 rounded-full {{ $colors[$log->event] ?? 'bg-gray-500' }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                            @if($log->event === 'created')
                                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            @elseif($log->event === 'updated')
                                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                            @else
                                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            @endif
                                        </span>
                                    </div>

                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-bold text-gray-900 dark:text-white uppercase text-xs">
                                                    {{ $log->event === 'created' ? 'Registro inicial' : ($log->event === 'updated' ? 'Actualización' : 'Eliminación') }}
                                                </span>
                                                por
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ $log->user_name ?? optional($log->user)->name ?? 'Sistema' }}
                                                </span>
                                            </p>

                                            @php
                                                $newValues = is_array($log->new_values ?? null) ? $log->new_values : null;
                                                $oldValues = is_array($log->old_values ?? null) ? $log->old_values : [];
                                            @endphp

                                            @if($log->event === 'updated' && is_array($newValues) && count($newValues))
                                                <div class="mt-2 text-[11px] bg-gray-50 dark:bg-gray-900 px-3 py-2 rounded border border-gray-200 dark:border-gray-700">
                                                    @foreach($newValues as $key => $value)
                                                        @if($key !== 'iglesia_id')
                                                            <div class="flex items-center gap-2">
                                                                <span class="font-bold text-gray-400 uppercase tracking-tighter">
                                                                    {{ str_replace('_', ' ', $key) }}:
                                                                </span>
                                                                <span class="text-red-400 line-through">
                                                                    @php $old = $oldValues[$key] ?? 'N/A'; @endphp
                                                                    {{ is_array($old) ? '...' : $old }}
                                                                </span>
                                                                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                                </svg>
                                                                <span class="text-green-500 font-bold">
                                                                    {{ is_array($value) ? '...' : $value }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div class="whitespace-nowrap text-right text-xs text-gray-500 dark:text-gray-400 flex flex-col items-end">
                                            <time>{{ optional($log->created_at)->format('d/m/y H:i') ?? '' }}</time>
                                            <span class="text-[10px] italic">{{ optional($log->created_at)->diffForHumans() ?? '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500 dark:text-gray-400 italic py-4 text-center">
                            No se han registrado movimientos.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

</div>