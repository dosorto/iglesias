<div class="space-y-6">

    {{-- HEADER --}}
    <div class="relative overflow-hidden rounded-xl shadow-md px-6 py-5"
         style="background: linear-gradient(to right, var(--color-purpura-sagrado), #7C3AED)">
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
                    <p class="text-purple-100 text-sm mt-0.5">
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

        {{-- Columna izquierda --}}
        <div class="lg:col-span-1 space-y-5">

            {{-- Info principal --}}
            <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60 overflow-hidden">
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
                        @php $estadoFeligres = $encargado->feligres->estado ?? 'Activo'; @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                     {{ $estadoFeligres === 'Activo'
                                         ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300'
                                         : 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300' }}">
                            {{ $estadoFeligres }}
                        </span>
                    </div>

                    {{-- Estado encargado --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Estado Encargado</p>
                        @php $estadoEncargado = $encargado->estado ?? 'Activo'; @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                     {{ $estadoEncargado === 'Activo'
                                         ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300'
                                         : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $estadoEncargado === 'Activo' ? 'bg-purple-500' : 'bg-gray-400' }}"></span>
                            {{ $estadoEncargado }}
                        </span>
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

            {{-- Firma --}}
            <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border overflow-hidden
                        {{ $encargado->path_firma_principal
                            ? 'border-emerald-200 dark:border-emerald-700/60'
                            : 'border-amber-300 dark:border-amber-700/60' }}">
                <div class="px-6 py-4 border-b
                            {{ $encargado->path_firma_principal
                                ? 'border-emerald-100 dark:border-emerald-700/40 bg-emerald-50/50 dark:bg-emerald-900/10'
                                : 'border-amber-100 dark:border-amber-700/40 bg-amber-50/50 dark:bg-amber-900/10' }}">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-bold uppercase tracking-widest
                                   {{ $encargado->path_firma_principal
                                       ? 'text-emerald-700 dark:text-emerald-300'
                                       : 'text-amber-700 dark:text-amber-300' }}">
                            Firma Principal
                        </h2>
                        @if($encargado->path_firma_principal)
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Registrada
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 dark:text-amber-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                                Pendiente
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    @if($encargado->path_firma_principal)
                        <div class="bg-white rounded-lg border border-gray-100 p-3 flex items-center justify-center">
                            <img src="{{ asset('storage/' . $encargado->path_firma_principal) }}"
                                 alt="Firma principal"
                                 class="max-h-24 object-contain">
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center gap-3 py-2 text-center">
                            <svg class="w-10 h-10 text-amber-300 dark:text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            <p class="text-sm text-gray-400 dark:text-gray-500 italic">Sin firma registrada</p>
                            @can('encargado.edit')
                                <a href="{{ route('encargado.edit', $encargado) }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold
                                          bg-amber-500 hover:bg-amber-600 text-white transition-colors duration-150">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Subir firma ahora
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>

            {{-- Link feligrés --}}
            @can('feligres.view')
                <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/50">
                        <h2 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Registro de Feligrés</h2>
                    </div>
                    <div class="px-6 py-4">
                        <a href="{{ route('feligres.show', $encargado->feligres) }}"
                           class="inline-flex items-center gap-1.5 text-sm font-medium hover:underline"
                           style="color: var(--color-purpura-sagrado)">
                            Ver ficha de feligrés
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endcan
        </div>

        {{-- Columna derecha --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Sacramentos gestionados --}}
            <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Sacramentos Gestionados</h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-y sm:divide-y-0 divide-gray-100 dark:divide-gray-700/60">
                    <div class="flex flex-col items-center justify-center p-5 gap-1">
                        <div class="w-9 h-9 rounded-full bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center mb-1">
                            <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $sacramentosCount['bautismos'] }}</span>
                        <span class="text-[11px] text-gray-500 dark:text-gray-400 uppercase tracking-wide">Bautismos</span>
                    </div>
                    <div class="flex flex-col items-center justify-center p-5 gap-1">
                        <div class="w-9 h-9 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center mb-1">
                            <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $sacramentosCount['matrimonios'] }}</span>
                        <span class="text-[11px] text-gray-500 dark:text-gray-400 uppercase tracking-wide">Matrimonios</span>
                    </div>
                    <div class="flex flex-col items-center justify-center p-5 gap-1">
                        <div class="w-9 h-9 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-1">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $sacramentosCount['confirmaciones'] }}</span>
                        <span class="text-[11px] text-gray-500 dark:text-gray-400 uppercase tracking-wide">Confirmaciones</span>
                    </div>
                    <div class="flex flex-col items-center justify-center p-5 gap-1">
                        <div class="w-9 h-9 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mb-1">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-5.747-8.12l11.494 4.373M6.253 14.373l11.494-4.373"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $sacramentosCount['comuniones'] }}</span>
                        <span class="text-[11px] text-gray-500 dark:text-gray-400 uppercase tracking-wide">Comuniones</span>
                    </div>
                </div>
            </div>

            {{-- Historial de cambios --}}
            <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60">
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
                                                $colors = [
                                                    'created' => 'bg-emerald-500',
                                                    'updated' => 'bg-purple-600',
                                                    'deleted' => 'bg-red-500',
                                                ];
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
