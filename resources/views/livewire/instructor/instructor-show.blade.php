<div class="space-y-6">

    {{-- ══ HEADER ════════════════════════════════════════════════════════ --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                Detalle del Instructor
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Viendo la información detallada y el historial de actividad.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('instructor.index') }}"
               class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 text-sm font-medium">
                Volver
            </a>

            @can('instructor.edit')
                <a href="{{ route('instructor.edit', $instructor) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                    Editar
                </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- ── Data Column ─────────────────────────────────────────────── --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">
                        Información General
                    </h2>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Persona</label>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $instructor->feligres?->persona?->nombre_completo ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">DNI: {{ $instructor->feligres?->persona?->dni ?? '—' }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Iglesia</label>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $instructor->feligres?->iglesia?->nombre ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Estado Feligrés</label>
                        @php $estadoFel = $instructor->feligres?->estado; @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $estadoFel === 'Activo' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                            {{ $estadoFel ?? '—' }}
                        </span>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Estado Instructor</label>
                        @php $estadoInst = $instructor->estado; @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $estadoInst === 'Activo' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                            {{ $estadoInst ?? '—' }}
                        </span>
                    </div>

                    @if ($instructor->fecha_ingreso)
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Fecha de Ingreso</label>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($instructor->fecha_ingreso)->format('d/m/Y') }}
                            </p>
                        </div>
                    @endif

                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">Firma</label>
                        @if($instructor->path_firma)
                            <img src="{{ asset('storage/' . $instructor->path_firma) }}" 
                                alt="Firma del instructor"
                                style="max-height:150px;">
                        @else
                            <span>No tiene firma</span>
                        @endif
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-[10px] text-gray-400 flex flex-col gap-1">
                        <span>Creado: {{ optional($instructor->created_at)->format('d/m/Y H:i') ?? 'N/A' }}
                            por {{ optional($instructor->creator)->name ?? 'Sistema' }}</span>
                        <span>Actualizado: {{ optional($instructor->updated_at)->format('d/m/Y H:i') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- Link al feligrés --}}
            @can('feligres.view')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-indigo-200 dark:border-indigo-700 overflow-hidden">
                    <div class="px-6 py-4 bg-indigo-50 dark:bg-indigo-900/30 border-b border-indigo-200 dark:border-indigo-700">
                        <h2 class="text-sm font-bold text-indigo-700 dark:text-indigo-300 uppercase tracking-widest">Registro de Feligrés</h2>
                    </div>
                    <div class="px-6 py-4">
                        <a href="{{ route('feligres.show', $instructor->feligres) }}"
                           class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                            Ver ficha de feligrés →
                        </a>
                    </div>
                </div>
            @endcan
        </div>

        {{-- ── Timeline Column ─────────────────────────────────────────── --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">
                        Historial de Cambios
                    </h2>
                </div>

                <div class="p-6">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @forelse($instructor->auditLogs as $log)
                                <li>
                                    <div class="relative pb-8">
                                        @if (!$loop->last)
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
                                                    @if ($log->event === 'created')
                                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                        </svg>
                                                    @elseif ($log->event === 'updated')
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

                                                    @if ($log->event === 'updated' && is_array($newValues) && count($newValues))
                                                        <div class="mt-2 text-[11px] bg-gray-50 dark:bg-gray-900 px-3 py-2 rounded border border-gray-200 dark:border-gray-700">
                                                            @foreach ($newValues as $key => $value)
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
    </div>

</div>
