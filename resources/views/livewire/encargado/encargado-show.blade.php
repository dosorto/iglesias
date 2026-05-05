<div class="space-y-6">

    {{-- HEADER --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600
                dark:from-indigo-700 dark:to-violet-700 shadow-md px-6 py-5">
        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
        <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white leading-tight">Detalle de Encargado</h1>
                    <p class="text-indigo-100 text-sm mt-0.5">
                        {{ $encargado->feligres->persona->nombre_completo ?? '' }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @can('encargado.edit')
                    <a href="{{ route('encargado.edit', $encargado) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                              bg-white/15 hover:bg-white/25 border border-white/20
                              text-white text-sm font-medium transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                @endcan
                <a href="{{ route('encargado.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                          bg-white/15 hover:bg-white/25 border border-white/20
                          text-white text-sm font-medium transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Columna izquierda: info --}}
        <div class="lg:col-span-1 space-y-5">

            {{-- Info principal --}}
            <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                        ring-1 ring-black/5 dark:ring-white/5 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Información General</h2>
                </div>
                <div class="p-6 space-y-5">

                    {{-- Persona --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Persona</p>
                        <p class="text-base font-bold text-gray-900 dark:text-white">
                            {{ $encargado->feligres->persona->nombre_completo ?? '—' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            DNI: {{ $encargado->feligres->persona->dni ?? '—' }}
                        </p>
                    </div>

                    {{-- Parroquia --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Parroquia</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $encargado->feligres->iglesia->nombre ?? '—' }}
                        </p>
                    </div>

                    {{-- Estado feligrés --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Estado Feligrés</p>
                        @php $estado = $encargado->feligres->estado ?? 'Activo'; @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                     {{ $estado === 'Activo'
                                         ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300'
                                         : 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300' }}">
                            {{ $estado }}
                        </span>
                    </div>

                    {{-- Firma --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Firma Principal</p>
                        @if ($encargado->path_firma_principal)
                            <img src="{{ asset('storage/' . $encargado->path_firma_principal) }}"
                                 alt="Firma principal"
                                 class="h-20 object-contain rounded-lg border border-indigo-200 dark:border-indigo-700
                                        bg-white p-1.5 shadow-sm">
                        @else
                            <p class="text-sm text-gray-400 dark:text-gray-500 italic">Sin firma registrada</p>
                        @endif
                    </div>

                </div>
                <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700/50">
                    <div class="text-[10px] text-gray-400 dark:text-gray-500 flex flex-col gap-0.5">
                        <span>Creado: {{ $encargado->created_at->format('d/m/Y H:i') }}
                            — {{ $encargado->creator->name ?? 'Sistema' }}</span>
                        <span>Actualizado: {{ $encargado->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- Link feligrés --}}
            @can('feligres.view')
                <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-indigo-200 dark:border-indigo-700/60
                            ring-1 ring-black/5 dark:ring-white/5 overflow-hidden">
                    <div class="px-6 py-4 border-b border-indigo-100 dark:border-indigo-700/60 bg-indigo-50/50 dark:bg-indigo-900/20">
                        <h2 class="text-xs font-bold text-indigo-700 dark:text-indigo-300 uppercase tracking-widest">Registro de Feligrés</h2>
                    </div>
                    <div class="px-6 py-4">
                        <a href="{{ route('feligres.show', $encargado->feligres) }}"
                           class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                            Ver ficha de feligrés
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endcan
        </div>

        {{-- Columna derecha: historial --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                        ring-1 ring-black/5 dark:ring-white/5">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Historial de Cambios</h2>
                </div>
                <div class="p-6">
                    <ul role="list" class="-mb-8">
                        @forelse ($encargado->auditLogs as $log)
                            <li>
                                <div class="relative pb-8">
                                    @if (!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            @php
                                                $colors = ['created' => 'bg-emerald-500', 'updated' => 'bg-indigo-500', 'deleted' => 'bg-red-500'];
                                            @endphp
                                            <span class="h-8 w-8 rounded-full {{ $colors[$log->event] ?? 'bg-gray-500' }}
                                                         flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                @if ($log->event === 'created')
                                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                @elseif ($log->event === 'updated')
                                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                    </svg>
                                                @else
                                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
                                                        {{ $log->user_name ?? ($log->user->name ?? 'Sistema') }}
                                                    </span>
                                                </p>
                                                @if ($log->event === 'updated' && $log->new_values)
                                                    <div class="mt-2 text-[11px] bg-gray-50 dark:bg-gray-900/60 px-3 py-2 rounded-lg
                                                                border border-gray-200 dark:border-gray-700 space-y-1">
                                                        @foreach ($log->new_values as $key => $value)
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <span class="font-bold text-gray-400 uppercase tracking-tight">
                                                                    {{ str_replace('_', ' ', $key) }}:
                                                                </span>
                                                                <span class="text-red-400 line-through">
                                                                    {{ is_array($log->old_values[$key] ?? '') ? '…' : ($log->old_values[$key] ?? 'N/A') }}
                                                                </span>
                                                                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                                </svg>
                                                                <span class="text-emerald-500 font-bold">
                                                                    {{ is_array($value) ? '…' : $value }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="whitespace-nowrap text-right text-xs text-gray-500 dark:text-gray-400 flex flex-col items-end">
                                                <time>{{ $log->created_at->format('d/m/y H:i') }}</time>
                                                <span class="text-[10px] italic">{{ $log->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="flex flex-col items-center justify-center py-10 text-center">
                                <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-sm text-gray-400 dark:text-gray-500 italic">No se han registrado movimientos.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
